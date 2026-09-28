<?php

namespace Tests\Feature\Admin;

use App\Services\Ga4Analytics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VisitorStatsTest extends TestCase
{
    use RefreshDatabase;

    private const SERVICE_ACCOUNT_EMAIL = 'dashboard@example.iam.gserviceaccount.com';

    private string $credentialsPath;
    private ?string $privateKey = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Keep the cache out of the test database transaction.
        config(['cache.default' => 'array']);
        Cache::flush();

        $this->privateKey = $this->generatePrivateKey();

        $this->credentialsPath = storage_path('framework/testing/ga4-credentials.json');
        @mkdir(dirname($this->credentialsPath), 0777, true);
        file_put_contents($this->credentialsPath, json_encode([
            'client_email' => self::SERVICE_ACCOUNT_EMAIL,
            'private_key' => $this->privateKey ?? 'no-key-on-this-machine',
        ]));
    }

    protected function tearDown(): void
    {
        @unlink($this->credentialsPath);

        parent::tearDown();
    }

    /** A throwaway key pair, or null where this PHP cannot generate one. */
    private function generatePrivateKey(): ?string
    {
        $key = @openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        if ($key === false) {
            while (openssl_error_string()) {
                // Drain the queue so a later openssl call does not report these.
            }

            return null;
        }

        @openssl_pkey_export($key, $privateKey);

        return $privateKey ?: null;
    }

    private function useGaCredentials(): void
    {
        config([
            'services.ga4.property_id' => '123456789',
            'services.ga4.credentials' => $this->credentialsPath,
        ]);

        if ($this->privateKey === null) {
            // Signing needs a real key; hand the service a token so the rest
            // of the flow can still be tested on machines without one.
            Cache::put('ga4:token:' . md5(self::SERVICE_ACCOUNT_EMAIL), 'token-123', 3600);
        }
    }

    private function fakeGoogle(): void
    {
        Http::fake([
            'oauth2.googleapis.com/*' => Http::response(['access_token' => 'token-123', 'expires_in' => 3599]),
            'analyticsdata.googleapis.com/*' => Http::sequence()
                ->push([
                    'rows' => [
                        ['dimensionValues' => [['value' => 'today']], 'metricValues' => [['value' => '12']]],
                        ['dimensionValues' => [['value' => 'last_7_days']], 'metricValues' => [['value' => '80']]],
                        ['dimensionValues' => [['value' => 'last_30_days']], 'metricValues' => [['value' => '300']]],
                        ['dimensionValues' => [['value' => 'this_month']], 'metricValues' => [['value' => '250']]],
                    ],
                ])
                ->push([
                    'rows' => [
                        ['dimensionValues' => [['value' => now()->format('Ymd')]], 'metricValues' => [['value' => '12']]],
                        ['dimensionValues' => [['value' => now()->subDay()->format('Ymd')]], 'metricValues' => [['value' => '9']]],
                    ],
                ]),
        ]);
    }

    public function test_without_credentials_the_card_says_so(): void
    {
        config(['services.ga4.property_id' => null, 'services.ga4.credentials' => '']);

        $summary = (new Ga4Analytics())->summary();

        $this->assertFalse($summary['configured']);
        $this->assertSame(0, $summary['today']);

        $this->actingAsAdmin()
            ->get(url('admin/dashboard'))
            ->assertOk()
            ->assertSee('ยังไม่ได้เชื่อม Google Analytics');
    }

    public function test_the_headline_numbers_come_from_ga(): void
    {
        $this->useGaCredentials();
        $this->fakeGoogle();

        $summary = (new Ga4Analytics())->summary();

        $this->assertTrue($summary['configured']);
        $this->assertNull($summary['error']);
        $this->assertSame(12, $summary['today']);
        $this->assertSame(80, $summary['last_7_days']);
        $this->assertSame(300, $summary['last_30_days']);
        $this->assertSame(250, $summary['this_month']);
    }

    public function test_the_trend_covers_thirty_days_including_quiet_ones(): void
    {
        $this->useGaCredentials();
        $this->fakeGoogle();

        $trend = (new Ga4Analytics())->summary()['trend'];

        $this->assertCount(30, $trend);
        $this->assertSame(now()->subDays(29)->toDateString(), $trend[0]['date']);
        $this->assertSame(now()->toDateString(), $trend[29]['date']);
        $this->assertSame(12, $trend[29]['users']);
        $this->assertSame(9, $trend[28]['users']);
        $this->assertSame(0, $trend[0]['users'], 'Days GA has no row for count as zero.');
    }

    public function test_a_failing_google_call_does_not_break_the_dashboard(): void
    {
        $this->useGaCredentials();
        Http::fake([
            'oauth2.googleapis.com/*' => Http::response(['access_token' => 'token-123']),
            'analyticsdata.googleapis.com/*' => Http::response(['error' => 'permission denied'], 403),
        ]);

        $summary = (new Ga4Analytics())->summary();

        $this->assertTrue($summary['configured']);
        $this->assertNotNull($summary['error']);
        $this->assertSame(0, $summary['today']);

        $this->actingAsAdmin()
            ->get(url('admin/dashboard'))
            ->assertOk()
            ->assertSee('อ่านข้อมูลจาก Google Analytics ไม่ได้');
    }

    public function test_the_numbers_are_cached_so_the_dashboard_does_not_call_ga_every_load(): void
    {
        $this->useGaCredentials();
        $this->fakeGoogle();

        (new Ga4Analytics())->summary();
        $callsAfterFirstRead = count(Http::recorded());

        (new Ga4Analytics())->summary();

        $this->assertCount($callsAfterFirstRead, Http::recorded(), 'The second read came from the cache.');
    }

    public function test_the_service_account_assertion_is_signed(): void
    {
        if ($this->privateKey === null) {
            $this->markTestSkipped('This PHP cannot generate an RSA key (no openssl.cnf).');
        }

        $this->useGaCredentials();
        $this->fakeGoogle();

        (new Ga4Analytics())->summary();

        Http::assertSent(function ($request) {
            if (!str_contains($request->url(), 'oauth2.googleapis.com')) {
                return false;
            }

            [, $payload] = explode('.', $request['assertion']);
            $claims = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

            return $claims['iss'] === self::SERVICE_ACCOUNT_EMAIL
                && $claims['scope'] === 'https://www.googleapis.com/auth/analytics.readonly';
        });
    }
}
