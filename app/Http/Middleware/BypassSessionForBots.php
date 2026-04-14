<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BypassSessionForBots
{
    /**
     * Common crawler user agents to block before they hit the web stack.
     *
     * @var string[]
     */
    private array $botSignatures = [
        'meta-webindexer',
        'facebookexternalhit',
        'facebot',
        'twitterbot',
        'linkedinbot',
        'slackbot',
        'telegrambot',
        'discordbot',
        'whatsapp',
        'googlebot',
        'bingbot',
    ];

    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldBlock($request)) {
            return response('', 204, [
                'X-Robots-Tag' => 'noindex, nofollow, noarchive',
            ]);
        }

        return $next($request);
    }

    private function shouldBlock(Request $request): bool
    {
        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            return false;
        }

        $userAgent = strtolower((string) $request->userAgent());
        if ($userAgent === '') {
            return false;
        }

        foreach ($this->botSignatures as $signature) {
            if (str_contains($userAgent, $signature)) {
                return true;
            }
        }

        return false;
    }
}
