<?php

namespace App\Providers;

use App\Support\IntegrationLogger;
use Illuminate\Support\Facades\Http;

class LineServiceProvider
{
    public static function sendToMemberUserId($lineUserId, $message)
    {
        return static::send('user', 'user_id', $lineUserId, $message);
    }

    public static function sendToGroupId($groupId, $message)
    {
        return static::send('group', 'group_id', $groupId, $message);
    }

    protected static function send(string $to, string $idKey, ?string $targetId, string $message): ?string
    {
        $endpoint = (string) config('services.line.endpoint');

        if (!$endpoint || !$targetId || trim($message) === '') {
            IntegrationLogger::warning('line', 'push_skipped', 'LINE PUSH SKIPPED', [
                'to' => $to,
                'id_key' => $idKey,
                'target_id' => $targetId,
                'has_endpoint' => $endpoint !== '',
                'has_message' => trim($message) !== '',
            ]);
            return null;
        }

        try {
            IntegrationLogger::info('line', 'push_request', 'LINE PUSH REQUEST', [
                'to' => $to,
                'id_key' => $idKey,
                'target_id' => $targetId,
                'endpoint' => $endpoint,
            ]);

            $response = Http::timeout(10)->get($endpoint, [
                'to' => $to,
                $idKey => $targetId,
                'msg' => $message,
            ]);

            if (!$response->successful()) {
                IntegrationLogger::error('line', 'push_error', 'LINE PUSH ERROR', [
                    'to' => $to,
                    'target_id' => $targetId,
                    'message' => $message,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return null;
            }

            IntegrationLogger::info('line', 'push_success', 'LINE PUSH SUCCESS', [
                'to' => $to,
                'target_id' => $targetId,
                'response' => trim($response->body()),
            ]);

            return trim($response->body());
        } catch (\Throwable $e) {
            IntegrationLogger::error('line', 'push_error', 'LINE PUSH ERROR', [
                'to' => $to,
                'target_id' => $targetId,
                'message' => $message,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
