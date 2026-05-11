<?php
namespace App\Filament\Clerk\Pages;

use App\Models\Visit;
use Filament\Pages\Page;

class PendingAdmissions extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-arrow-right-circle';
    protected static string  $view            = 'filament.clerk.pages.pending-admissions';
    protected static ?string $title           = 'Pending Admissions';
    protected static ?string $navigationLabel = 'Pending Admissions';
    protected static ?int    $navigationSort  = 2;

    /**
     * Returns visits where:
     *   - doctor_admitted_at IS NOT NULL  → doctor has made the "Admit" decision
     *   - clerk_admitted_at  IS NULL      → clerk has NOT yet completed the form
     *   - visit_type != 'NICU'            → NICU babies have their own registration flow
     */
    public function getPendingVisits(): \Illuminate\Database\Eloquent\Collection
    {
        return Visit::with(['patient', 'medicalHistory.doctor', 'doctorsOrders'])
            ->whereNotNull('doctor_admitted_at')
            ->whereNull('clerk_admitted_at')
            ->where('visit_type', '!=', 'NICU')
            ->whereHas('patient', fn ($q) => $q->where('is_provisional', false))
            ->whereDate('registered_at', '>=', now()->subDays(30))
            ->orderBy('doctor_admitted_at', 'asc')
            ->get();
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            $count = Visit::whereNotNull('doctor_admitted_at')
                ->whereNull('clerk_admitted_at')
                ->where('visit_type', '!=', 'NICU')
                ->whereHas('patient', fn ($q) => $q->where('is_provisional', false))
                ->whereDate('registered_at', '>=', now()->subDays(30))
                ->count();
            return $count > 0 ? (string) $count : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }
}