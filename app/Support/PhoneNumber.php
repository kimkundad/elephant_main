<?php

namespace App\Support;

/**
 * Phone numbers are stored in E.164 (+66958467417). The browser sends that
 * form already; this cleans up what arrives and finds the matching old rows,
 * which were saved as local numbers (0958467417).
 */
class PhoneNumber
{
    /** Strip spaces, dashes and brackets, keeping a single leading +. */
    public static function normalize(?string $phone): ?string
    {
        $phone = trim((string) $phone);
        if ($phone === '') {
            return null;
        }

        $hasPlus = str_starts_with($phone, '+');
        $digits = preg_replace('/\D+/', '', $phone);

        if ($digits === '') {
            return null;
        }

        return ($hasPlus ? '+' : '') . $digits;
    }

    /** ISO-3166 alpha-2, upper case, or null when it is not two letters. */
    public static function country(?string $country): ?string
    {
        $country = strtoupper(trim((string) $country));

        return preg_match('/^[A-Z]{2}$/', $country) === 1 ? $country : null;
    }

    /**
     * The forms the same number may already be stored as, so a returning guest
     * is matched instead of creating a second customer row.
     *
     * @return array<int, string>
     */
    public static function variants(?string $phone, ?string $country = null): array
    {
        $normalized = static::normalize($phone);
        if ($normalized === null) {
            return [];
        }

        $variants = [$normalized];

        if (str_starts_with($normalized, '+')) {
            // +66958467417 -> 0958467417 for Thai-style local numbers.
            $dialCodes = ['TH' => '66'];
            $dial = $dialCodes[static::country($country) ?? ''] ?? null;

            if ($dial && str_starts_with($normalized, '+' . $dial)) {
                $variants[] = '0' . substr($normalized, strlen($dial) + 1);
            }

            $variants[] = substr($normalized, 1);
        } else {
            $variants[] = '+' . $normalized;

            if (str_starts_with($normalized, '0')) {
                $variants[] = '+66' . substr($normalized, 1);
            }
        }

        return array_values(array_unique(array_filter($variants)));
    }
}
