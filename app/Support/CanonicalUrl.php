<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * The one address a page should be shared and indexed under.
 *
 * A visitor can reach us on the bare domain or on www, over http behind the
 * proxy, and with any number of tracking parameters on the end. A link preview
 * and a search engine should still see a single address, so everything that
 * names the page - the canonical link, og:url - is built here instead of being
 * taken from the request as it arrived.
 */
class CanonicalUrl
{
    /** The full canonical address of the page being served, without the query string. */
    public static function current(?Request $request = null): string
    {
        $request ??= request();

        return self::host($request) . self::path($request);
    }

    /** Scheme and host, e.g. "https://www.smallelephants.com". */
    public static function host(?Request $request = null): string
    {
        $request ??= request();

        $host = trim((string) config('app.canonical_host')) ?: $request->getHost();

        // Everything lives on www in production; a visitor who typed the bare
        // domain should still be shared as the one we advertise.
        if (!str_starts_with($host, 'www.') && substr_count($host, '.') === 1 && !self::isLocal($host)) {
            $host = 'www.' . $host;
        }

        return self::scheme($request, $host) . '://' . $host;
    }

    private static function path(Request $request): string
    {
        $path = '/' . ltrim($request->path(), '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    private static function scheme(Request $request, string $host): string
    {
        // Only a local machine is allowed to be plain http: behind the proxy the
        // request reaches PHP over http even though the visitor is on https.
        return self::isLocal($host) ? $request->getScheme() : 'https';
    }

    private static function isLocal(string $host): bool
    {
        return $host === 'localhost'
            || str_ends_with($host, '.test')
            || str_ends_with($host, '.localhost')
            || filter_var($host, FILTER_VALIDATE_IP) !== false;
    }
}
