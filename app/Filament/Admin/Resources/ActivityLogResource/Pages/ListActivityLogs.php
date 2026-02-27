<?php
namespace App\Filament\Admin\Resources\ActivityLogResource\Pages;

use App\Filament\Admin\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTableQuery(): Builder
    {
        $base = ActivityLog::query()->with('user')->latest('created_at');

        return match ($this->activeTab) {
            'auth'     => $base->where('category', 'auth'),
            'patient'  => $base->where('category', 'patient'),
            'vitals'   => $base->where('category', 'vitals'),
            'clinical' => $base->whereIn('category', ['clinical', 'orders']),
            'uploads'  => $base->where('category', 'uploads'),
            'admin'    => $base->where('category', 'admin'),
            'security' => $base->where('action', 'login_failed'),
            default    => $base,   // 'all' or null
        };
    }

    // ── Tabs ──────────────────────────────────────────────────────────────────
    public function getTabs(): array
    {
        $since = now()->subDay();

        return [
            'all' => Tab::make('All Activity')
                ->icon('heroicon-o-rectangle-stack')
                ->badge(ActivityLog::where('created_at', '>=', $since)->count() ?: null)
                ->badgeColor('gray'),

            'auth' => Tab::make('Authentication')
                ->icon('heroicon-o-key')
                ->badge(ActivityLog::where('category', 'auth')->where('created_at', '>=', $since)->count() ?: null)
                ->badgeColor('info'),

            'patient' => Tab::make('Patient Registry')
                ->icon('heroicon-o-users')
                ->badge(ActivityLog::where('category', 'patient')->where('created_at', '>=', $since)->count() ?: null)
                ->badgeColor('primary'),

            'vitals' => Tab::make('Vital Signs')
                ->icon('heroicon-o-heart')
                ->badge(ActivityLog::where('category', 'vitals')->where('created_at', '>=', $since)->count() ?: null)
                ->badgeColor('warning'),

            'clinical' => Tab::make('Clinical')
                ->icon('heroicon-o-clipboard-document')
                ->badge(ActivityLog::whereIn('category', ['clinical', 'orders'])->where('created_at', '>=', $since)->count() ?: null)
                ->badgeColor('success'),

            'uploads' => Tab::make('Lab Uploads')
                ->icon('heroicon-o-arrow-up-tray')
                ->badge(ActivityLog::where('category', 'uploads')->where('created_at', '>=', $since)->count() ?: null)
                ->badgeColor('purple'),

            'admin' => Tab::make('User Management')
                ->icon('heroicon-o-cog-6-tooth')
                ->badge(ActivityLog::where('category', 'admin')->where('created_at', '>=', $since)->count() ?: null)
                ->badgeColor('danger'),

            'security' => Tab::make('Security Alerts')
                ->icon('heroicon-o-shield-exclamation')
                ->badge(ActivityLog::where('action', 'login_failed')->where('created_at', '>=', $since)->count() ?: null)
                ->badgeColor('danger'),
        ];
    }
}