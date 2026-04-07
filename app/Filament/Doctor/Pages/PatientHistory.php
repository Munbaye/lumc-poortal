<?php

namespace App\Filament\Doctor\Pages;

use App\Models\Patient;
use App\Models\Visit;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

/**
 * PatientHistory (Doctor panel) — shows every visit for a given patient.
 *
 * Accessible from:
 *   - PatientChart header → "🗂️ All Visits →" button
 *
 * Mirrors App\Filament\Nurse\Pages\PatientHistory exactly,
 * only the back-link URL differs (returns to the Doctor patient list).
 */
class PatientHistory extends Page
{
    protected static ?string $navigationIcon        = 'heroicon-o-clock';
    protected static string  $view                  = 'filament.doctor.pages.patient-history';
    protected static bool    $shouldRegisterNavigation = false;
    protected static ?string $title                 = 'Patient Visit History';

    #[Url]
    public ?int $patientId = null;

    public ?Patient $patient = null;

    public function mount(): void
    {
        if (!$this->patientId) {
            $this->redirect(\App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index'));
            return;
        }

        $this->patient = Patient::find($this->patientId);

        if (!$this->patient) {
            \Filament\Notifications\Notification::make()
                ->title('Patient not found.')
                ->danger()
                ->send();
            $this->redirect(\App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index'));
        }
    }

    // ── Data ──────────────────────────────────────────────────────────────────

    public function getVisitsProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return Visit::with([
            'medicalHistory.doctor',
            'erRecord',
            'admissionRecord',
            'consentRecord',
            'vitals',
            'doctorsOrders',
        ])
        ->where('patient_id', $this->patientId)
        ->orderBy('registered_at', 'desc')
        ->get();
    }

    // ── URL helpers ───────────────────────────────────────────────────────────

    /**
     * Link to open PatientChart for a specific visit.
     */
    public function getOpenChartUrl(int $visitId): string
    {
        return PatientChart::getUrl(['visitId' => $visitId]);
    }

    /**
     * Back link — returns to the Doctor admitted patients list.
     */
    public function getPatientListUrl(): string
    {
        return \App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index');
    }
}