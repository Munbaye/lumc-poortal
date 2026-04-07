<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Visit;
use Filament\Pages\Page;
use Livewire\WithPagination;

/**
 * PatientList — landing page for the Nurse panel.
 *
 * Default view: admitted patients (doctor_admitted_at IS NOT NULL, status = admitted).
 * Nurses can switch to "All Patients" to see every status (OPD, ER, registered,
 * vitals_done, assessed, discharged, referred).
 */
class PatientList extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon  = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Patient List';
    protected static ?string $title           = 'Patient List';
    protected static string  $view            = 'filament.nurse.pages.patient-list';
    protected static ?int    $navigationSort  = 1;

    // ── Filter state ──────────────────────────────────────────────────────────
    public string $search        = '';
    public string $serviceFilter = '';
    public string $viewFilter    = 'admitted'; // 'admitted' | 'all'

    // ── Pagination reset ──────────────────────────────────────────────────────
    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedServiceFilter(): void { $this->resetPage(); }
    public function updatedViewFilter(): void    { $this->resetPage(); }

    // ── Data ──────────────────────────────────────────────────────────────────

    public function getAdmittedPatientsProperty()
    {
        $query = Visit::query()
            ->with([
                'patient',
                'medicalHistory.doctor',
                'doctorsOrders' => fn ($q) => $q->where('status', 'pending'),
            ]);

        if ($this->viewFilter === 'admitted') {
            $query->whereNotNull('doctor_admitted_at')->where('status', 'admitted');
        } else {
            // All patients — any status, order newest first
            $query->orderBy('registered_at', 'desc');
        }

        return $query
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
            ->when($this->viewFilter === 'admitted', fn ($q) =>
                $q->orderBy('doctor_admitted_at', 'asc')
            )
            ->paginate(20);
    }

    public function getServiceOptionsProperty(): array
    {
        $q = Visit::whereNotNull('admitted_service')->distinct();
        if ($this->viewFilter === 'admitted') {
            $q->whereNotNull('doctor_admitted_at')->where('status', 'admitted');
        }
        return $q->pluck('admitted_service')->sort()->values()->toArray();
    }

    // Stats — always based on admitted
    public function getTotalAdmittedProperty(): int
    {
        return Visit::whereNotNull('doctor_admitted_at')->where('status', 'admitted')->count();
    }

    public function getTotalPendingOrdersProperty(): int
    {
        return \App\Models\DoctorsOrder::whereHas('visit', fn ($q) =>
            $q->whereNotNull('doctor_admitted_at')->where('status', 'admitted')
        )->where('status', 'pending')->count();
    }

    // ── Navigation ────────────────────────────────────────────────────────────

    public function openChart(int $visitId): void
    {
        $this->redirect(NurseChart::getUrl(['visitId' => $visitId]));
    }
}