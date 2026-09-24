<?php

namespace Webkul\Core\Helpers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\DB;

class SessionPurger
{
    /**
     * Create a new session purger instance.
     */
    public function __construct(protected RevokedSessions $revokedSessions) {}

    /**
     * Drop every stored session a guard holds for the given user, on the database driver alone.
     * The caller's own session is dropped but not revoked, so the reset does not sign them out.
     */
    public function forget(string $guard, int $userId): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        $table = config('session.table', 'sessions');

        $key = 'login_'.$guard.'_'.sha1(SessionGuard::class);

        $current = session()->getId();

        try {
            DB::table($table)
                ->where('user_id', $userId)
                ->get(['id', 'payload'])
                ->filter(fn ($session) => $this->belongsTo($session->payload, $key, $userId))
                ->each(function ($session) use ($table, $current) {
                    DB::table($table)->where('id', $session->id)->delete();

                    if ($session->id !== $current) {
                        $this->revokedSessions->revoke($session->id);
                    }
                });
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
