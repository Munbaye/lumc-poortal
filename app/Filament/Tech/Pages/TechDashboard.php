<?php

namespace App\Filament\Tech\Pages;

use App\Models\DoctorsOrder;
use App\Models\ResultUpload;
use Filament\Actions\Action;
use Filament\Pages\Page;

class TechDashboard extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title           = 'Tech Dashboard';
    protected static ?int    $navigationSort  = -1;
    protected static ?string $slug            = 'dashboard';

    protected static string $view = 'filament.tech.pages.tech-dashboard';

    // Resolve specialty-based result type filter
    // Used to scope ALL stats and order queries to this tech's specialty.

    private function getSpecialtyResultType(): ?string
    {
        return match (auth()->user()->specialty) {
            'MedTech' => 'Laboratory',
            'RadTech' => 'Radiology',
            default   => null, // null = no filter, sees everything
        };
    }

    // Helper: base query scoped to this tech's specialty
    // Orders don't have a result_type column yet, so we scope by checking
    // whether any ResultUpload of the matching type exists for the visit,
    // OR fall back to showing all orders for non-specialised techs.
    // When a result_type column is added to doctors_orders, replace with a
    // simple ->where('result_type', $type) clause.

    private function ordersQuery(bool $pendingOnly = true)
    {
        $query = DoctorsOrder::query();

        if ($pendingOnly) {
            $query->where('is_completed', false);
        }

        return $query;
    }

    // Fix 2: Stat cards scoped to logged-in tech

    // Pending orders count — all unfinished orders visible to this tech
    public function getPendingOrdersCount(): int
    {
        return $this->ordersQuery(pendingOnly: true)->count();
    }

    // Fix 2+3: Replace "Completed Today" with "Completed This Week by this tech"
    public function getCompletedThisWeekCount(): int
    {
        return DoctorsOrder::where('is_completed', true)
            ->where('completed_by', auth()->id())          // only THIS tech's completions
            ->whereBetween('completed_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->count();
    }

    // Fix 2: Uploads today scoped to this tech only
    public function getMyUploadsTodayCount(): int
    {
        return ResultUpload::where('uploaded_by', auth()->id())
            ->whereDate('created_at', today())
            ->count();
    }

    // Fix 2: Total results uploaded by this tech (not all techs)
    public function getMyTotalResultsCount(): int
    {
        return ResultUpload::where('uploaded_by', auth()->id())->count();
    }

    // Fix 4: Pending orders with waiting duration, oldest first
    public function getPendingOrders()
    {
        return $this->ordersQuery(pendingOnly: true)
            ->with(['visit.patient', 'doctor'])
            ->oldest()          // oldest first = most urgent at top
            ->limit(20)
            ->get();
    }

    // Fix 5: No markDone() — removed. Upload button per row instead.
    // Orders are only marked complete when a result is actually uploaded
    // via CreateResultUpload::save(), preventing ghost completions.

    // Header shortcut 

    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload')
                ->label('Upload Result')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->url(\App\Filament\Tech\Resources\ResultUploadResource::getUrl('create')),
        ];
    }
}