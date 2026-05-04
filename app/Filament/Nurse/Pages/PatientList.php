<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Visit;
use Filament\Pages\Page;
use Livewire\WithPagination;

class PatientList extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Patient List';
    protected static ?string $title           = 'Patient List';
    protected static string  $view            = 'filament.nurse.pages.patient-list';
    protected static ?int    $navigationSort  = 1;

    // ── Filter / view state ───────────────────────────────────────────────────
    public string $search        = '';
    public string $serviceFilter = '';
    public string $viewFilter    = 'admitted';   // 'admitted' | 'discharged' | 'all'
    public string $sexFilter     = '';
    public string $dateFrom      = '';
    public string $dateUntil     = '';
    public bool   $showFilters   = false;

    // ── Pagination resets ─────────────────────────────────────────────────────
    public function updatedSearch(): void        { $this->resetPage(); }
    public function updatedServiceFilter(): void { $this->resetPage(); }
    public function updatedSexFilter(): void     { $this->resetPage(); }
    public function updatedDateFrom(): void      { $this->resetPage(); }
    public function updatedDateUntil(): void     { $this->resetPage(); }
    public function updatedViewFilter(): void
    {
        $this->resetPage();
        $this->showFilters = false;  // close panel when switching tabs
    }

    // ── Filter panel actions ──────────────────────────────────────────────────
    public function toggleFilters(): void
    {
        $this->showFilters = !$this->showFilters;
    }

    public function clearFilters(): void
    {
        $this->sexFilter     = '';
        $this->dateFrom      = '';
        $this->dateUntil     = '';
        $this->serviceFilter = '';
        $this->showFilters   = false;
        $this->resetPage();
    }

    // ── Computed: active filter state ─────────────────────────────────────────
    public function getHasActiveFiltersProperty(): bool
    {
        return filled($this->sexFilter)
            || filled($this->dateFrom)
            || filled($this->dateUntil)
            || filled($this->serviceFilter);
    }

    public function getActiveFilterCountProperty(): int
    {
        return collect([
            $this->sexFilter,
            $this->dateFrom,
            $this->dateUntil,
            $this->serviceFilter,
        ])->filter(fn ($v) => filled($v))->count();
    }

    // ── Computed: patient list ────────────────────────────────────────────────
    public function getAdmittedPatientsProperty()
    {
        $query = Visit::query()
            ->with([
                'patient',
                'medicalHistory.doctor',
                'doctorsOrders' => fn ($q) => $q->where('status', 'pending'),
            ]);

        // Status filter per tab
        if ($this->viewFilter === 'admitted') {
            $query->whereNotNull('doctor_admitted_at')->where('status', 'admitted');
        } elseif ($this->viewFilter === 'discharged') {
            $query->where('status', 'discharged')->whereNotNull('discharged_at');
        }
        // 'all': no status constraint

        // Common filters
        $query
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
            ->when($this->sexFilter, fn ($q) =>
                $q->whereHas('patient', fn ($p) => $p->where('sex', $this->sexFilter))
            )
            ->when($this->dateFrom, fn ($q) =>
                $q->whereDate('registered_at', '>=', $this->dateFrom)
            )
            ->when($this->dateUntil, fn ($q) =>
                $q->whereDate('registered_at', '<=', $this->dateUntil)
            );

        // Ordering per tab
        if ($this->viewFilter === 'admitted') {
            $query->orderBy('doctor_admitted_at', 'asc');
        } elseif ($this->viewFilter === 'discharged') {
            $query->orderBy('discharged_at', 'desc');
        } else {
            $query->orderBy('registered_at', 'desc');
        }

        return $query->paginate(20);
    }

    public function getServiceOptionsProperty(): array
    {
        $q = Visit::whereNotNull('admitted_service')->distinct();
        if ($this->viewFilter === 'admitted') {
            $q->whereNotNull('doctor_admitted_at')->where('status', 'admitted');
        } elseif ($this->viewFilter === 'discharged') {
            $q->where('status', 'discharged');
        }
        return $q->pluck('admitted_service')->sort()->values()->toArray();
    }

    // ── Computed: header stats ────────────────────────────────────────────────
    public function getTotalAdmittedProperty(): int
    {
        return Visit::whereNotNull('doctor_admitted_at')->where('status', 'admitted')->count();
    }

    public function getTotalDischargedProperty(): int
    {
        return Visit::where('status', 'discharged')->whereNotNull('discharged_at')->count();
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