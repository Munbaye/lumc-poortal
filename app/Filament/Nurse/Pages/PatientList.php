<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Visit;
use Filament\Pages\Page;
use Livewire\WithPagination;

/**
 * PatientList — default landing page for the Nurse panel.
 *
 * Shows all fully admitted patients (clerk_admitted_at IS NOT NULL, status = admitted).
 * Nurses see every admitted patient across all services/wards.
 *
 * Features:
 *   - Live search by patient name or case number
 *   - Service filter dropdown
 *   - Pending orders badge per patient
 *   - Click row → NurseChart page
 */
class PatientList extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon  = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Patient List';
    protected static ?string $title           = 'Admitted Patients';
    protected static string  $view            = 'filament.nurse.pages.patient-list';
    protected static ?int    $navigationSort  = 1;

    // ── Search & filter state ─────────────────────────────────────────────────
    public string  $search          = '';
    public string  $serviceFilter   = '';

    // ── Reset pagination on filter change ────────────────────────────────────

    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedServiceFilter(): void { $this->resetPage(); }

    // ── Data ──────────────────────────────────────────────────────────────────

    public function getAdmittedPatientsProperty()
    {
        return Visit::query()
            ->with([
                'patient',
                'medicalHistory.doctor',
                'doctorsOrders' => fn ($q) => $q->where('status', 'pending'),
            ])
            ->whereNotNull('doctor_admitted_at')
            ->where('status', 'admitted')
            // Search by patient name fields or case number
            ->when($this->search, function ($q) {
                $search = '%' . $this->search . '%';
                $q->whereHas('patient', fn ($p) =>
                    $p->where('family_name', 'like', $search)
                    ->orWhere('first_name',  'like', $search)
                    ->orWhere('case_no',     'like', $search)
                );
            })
            ->when($this->serviceFilter, fn ($q) =>
                $q->where('admitted_service', $this->serviceFilter)
            )
            ->orderBy('doctor_admitted_at', 'asc')  // ← sort by doctor order time
            ->paginate(20);
    }

    public function getServiceOptionsProperty(): array
    {
        return Visit::whereNotNull('doctor_admitted_at')
            ->where('status', 'admitted')
            ->whereNotNull('admitted_service')
            ->distinct()
            ->pluck('admitted_service')
            ->sort()
            ->values()
            ->toArray();
    }

    public function getTotalAdmittedProperty(): int
    {
        return Visit::whereNotNull('doctor_admitted_at')
            ->where('status', 'admitted')
            ->count();
    }

    public function getTotalPendingOrdersProperty(): int
    {
        return \App\Models\DoctorsOrder::whereHas('visit', fn ($q) =>
            $q->whereNotNull('doctor_admitted_at')->where('status', 'admitted')
        )->where('status', 'pending')->count();
    }

    // ── Navigation to patient chart ───────────────────────────────────────────

    public function openChart(int $visitId): void
    {
        $this->redirect(
            NurseChart::getUrl(['visitId' => $visitId])
        );
    }
}