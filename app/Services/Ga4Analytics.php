<?php

namespace App\Services;

use App\Support\IntegrationLogger;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Visitor numbers from Google Analytics 4.
 *
 * GA already counts a person once per day however many times they come back,
 * so the dashboard reads those numbers instead of keeping its own log. Talks
 * to the Data API directly: the service account JWT is signed here, which
 * saves pulling in the Google client library for two endpoints.
 */
class Ga4Analytics
{
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';
    private const CACHE_TTL_MINUTES = 30;
    private const DAYS_IN_TREND = 30;

    /** Whether the property id and the service account file are both in place. */
    public function configured(): bool
    {
        return $this->propertyId() !== '' && $this->credentials() !== null;
    }

    /**
     * Everything the dashboard card shows. Never throws: a GA outage or a bad
     * key leaves the rest of the dashboard working.
     *
     * @return array{configured: bool, error: ?string, today: int, last_7_days: int, last_30_days: int, this_month: int, trend: array<int, array{date: string, label: string, users: int}>}
     */
    public function summary(): array
    {
        $empty = [
            'configured' => $this->configured(),
            'error' => null,
            'today' => 0,
            'last_7_days' => 0,
            'last_30_days' => 0,
            'this_month' => 0,
            'trend' => [],
        ];

        if (!$this->configured()) {
            return $empty;
        }

        try {
            return Cache::remember(
                'ga4:summary:' . now()->format('Y-m-d-H') . ':' . intdiv((int) now()->format('i'), self::CACHE_TTL_MINUTES),
                now()->addMinutes(self::CACHE_TTL_MINUTES),
                fn () => array_merge($empty, $this->fetchSummary())
            );
        } catch (\Throwable $e) {
            IntegrationLogger::error('ga4', 'summary_failed', 'Could not read GA4 visitor numbers', [
                'error' => $e->getMessage(),
            ]);

            return array_merge($empty, ['error' => $e->getMessage()]);
        }
    }

    /** @return array<string, mixed> */
    private function fetchSummary(): array
    {
        $token = $this->accessToken();

        return array_merge(
            $this->fetchTotals($token),
            ['trend' => $this->fetchTrend($token)]
        );
    }

    /**
     * The four headline numbers in one request: GA allows several date ranges
     * per report, and each comes back as its own row.
     *
     * @return array<string, int>
     */
    private function fetchTotals(string $token): array
    {
        $ranges = [
            'today' => ['startDate' => 'today', 'endDate' => 'today'],
            'last_7_days' => ['startDate' => '6daysAgo', 'endDate' => 'today'],
            'last_30_days' => ['startDate' => '29daysAgo', 'endDate' => 'today'],
            'this_month' => ['startDate' => now()->startOfMonth()->toDateString(), 'endDate' => 'today'],
        ];

        $response = $this->runReport($token, [
            'dateRanges' => array_values(array_map(
                fn (array $range, string $name) => $range + ['name' => $name],
                $ranges,
                array_keys($ranges)
            )),
            'metrics' => [['name' => 'activeUsers']],
        ]);

        $totals = array_fill_keys(array_keys($ranges), 0);

        foreach ($response['rows'] ?? [] as $row) {
            $name = $row['dimensionValues'][0]['value'] ?? null;

            if ($name !== null && array_key_exists($name, $totals)) {
                $totals[$name] = (int) ($row['metricValues'][0]['value'] ?? 0);
            }
        }

        return $totals;
    }

    /**
     * Users per day for the trend bars, including days GA has no data for.
     *
     * @return array<int, array{date: string, label: string, users: int}>
     */
    private function fetchTrend(string $token): array
    {
        $response = $this->runReport($token, [
            'dateRanges' => [['startDate' => (self::DAYS_IN_TREND - 1) . 'daysAgo', 'endDate' => 'today']],
            'dimensions' => [['name' => 'date']],
            'metrics' => [['name' => 'activeUsers']],
            'orderBys' => [['dimension' => ['dimensionName' => 'date']]],
        ]);

        $byDate = [];

        foreach ($response['rows'] ?? [] as $row) {
            $date = $row['dimensionValues'][0]['value'] ?? '';

            if (preg_match('/^\d{8}$/', $date)) {
                $byDate[Carbon::createFromFormat('Ymd', $date)->toDateString()] = (int) ($row['metricValues'][0]['value'] ?? 0);
            }
        }

        $trend = [];

        for ($i = self::DAYS_IN_TREND - 1; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $key = $day->toDateString();

            $trend[] = [
                'date' => $key,
                'label' => $day->format('d/m'),
                'users' => $byDate[$key] ?? 0,
            ];
        }

        return $trend;
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    private function runReport(string $token, array $body): array
    {
        $response = Http::withToken($token)
            ->timeout(15)
            ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId()}:runReport", $body);

        if (!$response->successful()) {
            throw new \RuntimeException('GA4 runReport failed: ' . $response->body());
        }

        return $response->json() ?? [];
    }

    /** A service account access token, kept until shortly before it expires. */
    private function accessToken(): string
    {
        $credentials = $this->credentials();

        return Cache::remember(
            'ga4:token:' . md5((string) ($credentials['client_email'] ?? '')),
            now()->addMinutes(55),
            function () use ($credentials) {
                $response = Http::asForm()->timeout(15)->post(self::TOKEN_URL, [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $this->signedAssertion($credentials),
                ]);

                if (!$response->successful() || !$response->json('access_token')) {
                    throw new \RuntimeException('GA4 token request failed: ' . $response->body());
                }

                return (string) $response->json('access_token');
            }
        );
    }

    /** @param array<string, mixed> $credentials */
    private function signedAssertion(array $credentials): string
    {
        $issuedAt = time();

        $segments = [
            $this->base64Url(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])),
            $this->base64Url(json_encode([
                'iss' => $credentials['client_email'] ?? '',
                'scope' => self::SCOPE,
                'aud' => self::TOKEN_URL,
                'iat' => $issuedAt,
                'exp' => $issuedAt + 3600,
            ])),
        ];

        $input = implode('.', $segments);
        $signature = '';

        if (!openssl_sign($input, $signature, (string) ($credentials['private_key'] ?? ''), OPENSSL_ALGO_SHA256)) {
            throw new \RuntimeException('Could not sign the GA4 service account assertion.');
        }

        return $input . '.' . $this->base64Url($signature);
    }

    private function base64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function propertyId(): string
    {
        return trim((string) config('services.ga4.property_id'));
    }

    /** @return array<string, mixed>|null */
    private function credentials(): ?array
    {
        $path = trim((string) config('services.ga4.credentials'));

        if ($path === '' || !is_file($path)) {
            return null;
        }

        $credentials = json_decode((string) file_get_contents($path), true);

        return is_array($credentials) && isset($credentials['client_email'], $credentials['private_key'])
            ? $credentials
            : null;
    }
}
