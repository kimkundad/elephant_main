<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Google reCAPTCHA v2 checkbox, shared by every public form that uses it.
 */
class Recaptcha
{
    /** Whether the site has keys, i.e. whether the widget can be shown at all. */
    public function configured(): bool
    {
        return $this->siteKey() !== '' && $this->secretKey() !== '';
    }

    public function siteKey(): string
    {
        return trim((string) config('services.recaptcha.site_key'));
    }

    private function secretKey(): string
    {
        return trim((string) config('services.recaptcha.secret_key'));
    }

    /**
     * Verify the token that came with a submission.
     *
     * Fails closed: a missing token, an unconfigured secret, or an unreachable
     * endpoint all reject the submission rather than letting it through.
     */
    public function isValid(Request $request): bool
    {
        $secret = $this->secretKey();
        $token = trim((string) $request->input('g-recaptcha-response'));

        if ($secret === '' || $token === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);
        } catch (\Throwable $e) {
            report($e);

            return false;
        }

        return $response->successful() && $response->json('success') === true;
    }
}
