<?php

namespace App\Support;

use App\Models\IntegrationLog;
use Illuminate\Support\Facades\Log;

class IntegrationLogger
{
    public static function info(string $channel, string $event, string $message, array $context = []): void
    {
        static::write('info', $channel, $event, $message, $context);
    }

    public static function warning(string $channel, string $event, string $message, array $context = []): void
    {
        static::write('warning', $channel, $event, $message, $context);
    }

    public static function error(string $channel, string $event, string $message, array $context = []): void
    {
        static::write('error', $channel, $event, $message, $context);
    }

    protected static function write(string $level, string $channel, string $event, string $message, array $context = []): void
    {
        Log::log($level, $message, $context);

        try {
            IntegrationLog::create([
                'channel' => $channel,
                'event' => $event,
                'level' => $level,
                'message' => $message,
                'context' => $context,
            ]);
        } catch (\Throwable $e) {
            Log::error('INTEGRATION DB LOG ERROR', [
                'channel' => $channel,
                'event' => $event,
                'level' => $level,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
