<?php

namespace App\Filament\Tech\Pages;

use App\Models\LabRequest;
use App\Models\RadiologyRequest;
use App\Models\ResultUpload;
use Filament\Pages\Page;

class TechDashboard extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title           = '';

    public function getTitle(): string
    {
        return '';
    }

    protected static string  $view           = 'filament.tech.pages.tech-dashboard';
    protected static ?int    $navigationSort = 1;

    public string $search      = '';
    public string $queueFilter = 'pending';

    public function updatedSearch(): void {}

    // ── Specialty detection ───────────────────────────────────────────────────

    public function getIsMedtechProperty(): bool
    {
        $spec = strtolower(auth()->user()->specialty ?? '');
        return str_contains($spec, 'med tech')
            || str_contains($spec, 'medical tech')
            || str_contains($spec, 'laboratory')
            || str_contains($spec, 'medtech')
            || str_contains($spec, 'medical technologist');
    }

    public function getIsRadtechProperty(): bool
    {
        $spec = strtolower(auth()->user()->specialty ?? '');
        return str_contains($spec, 'radiolog')
            || str_contains($spec, 'x-ray')
            || str_contains($spec, 'rad tech')
            || str_contains($spec, 'radtech');
    }

    public function getQueueTypeProperty(): string
    {
        if ($this->isMedtech && !$this->isRadtech) return 'lab';
        if ($this->isRadtech && !$this->isMedtech) return 'radiology';
        return 'both';
    }

    // ── Stats ─────────────────────────────────────────────────────────────────

    public function getPendingLabCountProperty(): int
    {
        return LabRequest::where('status', '!=', 'completed')->count();
    }

    public function getPendingRadCountProperty(): int
    {
        return RadiologyRequest::where('status', '!=', 'completed')->count();
    }

    public function getMyCompletedTodayProperty(): int
    {
        return ResultUpload::where('uploaded_by', auth()->id())
            ->whereDate('created_at', today())
            ->count();
    }

    public function getMyTotalResultsProperty(): int
    {
        return ResultUpload::where('uploaded_by', auth()->id())->count();
    }

    // ── Lab queue ─────────────────────────────────────────────────────────────

    public function getLabQueueProperty()
    {
        if ($this->queueType === 'radiology') return collect();

        $statusFilter = $this->queueFilter === 'completed' ? 'completed' : ['pending', 'in_progress'];

        return LabRequest::with(['visit.patient', 'doctor'])
            ->when(is_array($statusFilter),
                fn ($q) => $q->whereIn('status', $statusFilter),
                fn ($q) => $q->where('status', $statusFilter)
            )
            ->when($this->search, function ($q) {
                $s = '%' . $this->search . '%';
                $q->where(function ($sub) use ($s) {
                    $sub->where('request_no', 'like', $s)
                        ->orWhere('clinical_diagnosis', 'like', $s)
                        ->orWhereHas('patient', fn ($p) =>
                            $p->where('family_name', 'like', $s)
                              ->orWhere('first_name',  'like', $s)
                              ->orWhere('case_no',     'like', $s)
                        );
                });
            })
            ->orderByRaw("FIELD(status, 'pending', 'in_progress', 'completed')")
            ->orderByRaw("request_type = 'stat' DESC")
            ->orderBy('created_at', 'asc')
            ->get();
    }

    // ── Radiology queue ───────────────────────────────────────────────────────

    public function getRadQueueProperty()
    {
        if ($this->queueType === 'lab') return collect();

        $statusFilter = $this->queueFilter === 'completed' ? 'completed' : ['pending', 'in_progress'];

        return RadiologyRequest::with(['visit.patient', 'doctor'])
            ->when(is_array($statusFilter),
                fn ($q) => $q->whereIn('status', $statusFilter),
                fn ($q) => $q->where('status', $statusFilter)
            )
            ->when($this->search, function ($q) {
                $s = '%' . $this->search . '%';
                $q->where(function ($sub) use ($s) {
                    $sub->where('request_no', 'like', $s)
                        ->orWhere('clinical_diagnosis', 'like', $s)
                        ->orWhereHas('patient', fn ($p) =>
                            $p->where('family_name', 'like', $s)
                              ->orWhere('first_name',  'like', $s)
                              ->orWhere('case_no',     'like', $s)
                        );
                });
            })
            ->orderByRaw("FIELD(status, 'pending', 'in_progress', 'completed')")
            ->orderBy('created_at', 'asc')
            ->get();
    }

    // ── Navigation ────────────────────────────────────────────────────────────

    public function openLabRequest(int $id): void
    {
        $this->redirect(ViewLabRequest::getUrl(['requestId' => $id]));
    }

    public function openRadRequest(int $id): void
    {
        $this->redirect(ViewRadRequest::getUrl(['requestId' => $id]));
    }
}