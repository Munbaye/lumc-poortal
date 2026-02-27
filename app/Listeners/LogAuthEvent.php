<?php
namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Request;

class LogAuthEvent
{
    public function handleLogin(Login $event): void
    {
        $panel = $this->detectPanel();

        ActivityLog::create([
            'user_id'       => $event->user->id,
            'category'      => ActivityLog::CAT_AUTH,
            'action'        => ActivityLog::ACT_LOGIN,
            'subject_type'  => 'User',
            'subject_id'    => $event->user->id,
            'subject_label' => $event->user->name,
            'new_values'    => ['panel' => $panel, 'remember' => $event->remember],
            'ip_address'    => Request::ip(),
            'user_agent'    => Request::userAgent(),
            'panel'         => $panel,
        ]);
    }

    public function handleLogout(Logout $event): void
    {
        if (!$event->user) return;

        $panel = $this->detectPanel();

        ActivityLog::create([
            'user_id'       => $event->user->id,
            'category'      => ActivityLog::CAT_AUTH,
            'action'        => ActivityLog::ACT_LOGOUT,
            'subject_type'  => 'User',
            'subject_id'    => $event->user->id,
            'subject_label' => $event->user->name,
            'ip_address'    => Request::ip(),
            'user_agent'    => Request::userAgent(),
            'panel'         => $panel,
        ]);
    }

    public function handleFailed(Failed $event): void
    {
        $panel = $this->detectPanel();

        ActivityLog::create([
            'user_id'       => null,
            'category'      => ActivityLog::CAT_AUTH,
            'action'        => ActivityLog::ACT_LOGIN_FAILED,
            'subject_type'  => 'User',
            'subject_label' => $event->credentials['email'] ?? 'unknown',
            'new_values'    => ['attempted_email' => $event->credentials['email'] ?? null],
            'ip_address'    => Request::ip(),
            'user_agent'    => Request::userAgent(),
            'panel'         => $panel,
        ]);
    }

    private function detectPanel(): ?string
    {
        $path = Request::path();
        foreach (['admin','doctor','nurse','clerk','tech'] as $p) {
            if (str_starts_with($path, $p . '/') || $path === $p) {
                return $p;
            }
        }
        return null;
    }
}