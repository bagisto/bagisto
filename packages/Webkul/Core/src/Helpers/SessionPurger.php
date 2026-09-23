<?php

namespace Webkul\Core\Helpers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\DB;

class SessionPurger
{
    /**
     * Drop every stored session a guard holds for the given user, on the database driver alone.
     */
    public function forget(string $guard, int $userId): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        $table = config('session.table', 'sessions');

        $key = 'login_'.$guard.'_'.sha1(SessionGuard::class);

        try {
            DB::table($table)
                ->where('user_id', $userId)
                ->get(['id', 'payload'])
                ->filter(fn ($session) => $this->belongsTo($session->payload, $key, $userId))
                ->each(fn ($session) => DB::table($table)->where('id', $session->id)->delete());
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Whether a stored session signed the given user in under that guard, rather than another guard
     * whose own identifiers happen to collide.
     */
    private function belongsTo(string $payload, string $key, int $userId): bool
    {
        $session = @unserialize(base64_decode($payload, true) ?: '');

        return is_array($session)
            && isset($session[$key])
            && (int) $session[$key] === $userId;
    }
}
