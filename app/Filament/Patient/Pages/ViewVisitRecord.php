<?php

namespace App\Filament\Patient\Pages;

use App\Models\Visit;
use App\Models\Patient;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

class ViewVisitRecord extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static string  $view           = 'filament.patient.pages.view-visit-record';
    protected static ?string $title          = 'Visit Details';

    protected static bool $shouldRegisterNavigation = false;

    public ?Visit   $visit   = null;
    public ?Patient $patient = null;

    // Pre-computed properties passed to blade
    public $mh        = null;
    public $vt        = null;
    public $vitals;
    public $labReqs;
    public $radReqs;
    public $admRec    = null;
    public $doctor    = null;
    public bool $isEr = false;

    public string $statusLabel = '';

    public function mount(): void
    {
        $this->patient = auth()->user()?->patientRecord;

        if (! $this->patient) {
            $this->redirect(MyRecords::getUrl());
            return;
        }

        $visitId = request()->query('visitId');

        if (! $visitId) {
            $this->redirect(MyRecords::getUrl());
            return;
        }

        $this->visit = Visit::where('id', $visitId)
            ->where('patient_id', $this->patient->id)
            ->with([
                'latestVitals',
                'vitals',
                'medicalHistory',
                'labRequests.results',
                'radiologyRequests.results',
                'admissionRecord',
                'assignedDoctor',
            ])
            ->first();

        if (! $this->visit) {
            $this->redirect(MyRecords::getUrl());
            return;
        }

        // Pre-compute everything so blade stays clean
        $this->mh      = $this->visit->medicalHistory;
        $this->vt      = $this->visit->latestVitals;
        $this->vitals  = $this->visit->vitals->sortBy('taken_at');
        $this->labReqs = $this->visit->labRequests ?? collect();
        $this->radReqs = $this->visit->radiologyRequests ?? collect();
        $this->admRec  = $this->visit->admissionRecord;
        $this->doctor  = $this->visit->assignedDoctor;
        $this->isEr    = $this->visit->visit_type === 'ER';

        $this->statusLabel = match($this->visit->status) {
            'registered'  => 'Registered',
            'vitals_done' => 'Vitals Recorded',
            'assessed'    => 'Assessed',
            'admitted'    => 'Admitted',
            'discharged'  => 'Discharged',
            'referred'    => 'Referred',
            default       => ucfirst($this->visit->status),
        };
    }

    public function getFileUrl(string $path): string
    {
        return Storage::url($path);
    }
}