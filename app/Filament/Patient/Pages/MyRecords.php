<?php

namespace App\Filament\Patient\Pages;

use App\Models\Patient;
use App\Models\Visit;
use Filament\Pages\Page;

class MyRecords extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static string  $view            = 'filament.patient.pages.my-records';
    protected static ?string $title           = 'My Medical Records';
    protected static ?string $navigationLabel = 'My Records';
    protected static ?int    $navigationSort  = 1;

    public ?Patient $patient = null;
    public $visits;

    public function mount(): void
    {
        $this->patient = auth()->user()?->patientRecord;

        if ($this->patient) {
            $this->visits = Visit::where('patient_id', $this->patient->id)
                ->with([
                    'latestVitals',
                    'medicalHistory',
                    'vitals',
                    'labRequests.results',
                    'radiologyRequests.results',
                    'admissionRecord',
                    'assignedDoctor',
                ])
                ->orderByDesc('registered_at')
                ->get();
        } else {
            $this->visits = collect();
        }
    }
}