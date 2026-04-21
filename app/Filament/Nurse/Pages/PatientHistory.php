<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Patient;
use App\Models\Visit;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

/**
 * PatientHistory (Nurse panel) — shows every visit for a given patient.
 *
 * Accessible from:
 *   - NurseChart header → "All Visits for This Patient →" button
 *   - PatientList → (future: Patient History action per row)
 *
 * Mirrors App\Filament\Clerk\Pages\PatientHistory exactly,
 * only the back-link URL differs (returns to the Nurse PatientList).
 */
class PatientHistory extends Page
{
    protected static ?string $navigationIcon        = 'heroicon-o-clock';
    protected static string  $view                  = 'filament.nurse.pages.patient-history';
    protected static bool    $shouldRegisterNavigation = false;
    protected static ?string $title                 = 'Patient Visit History';

    #[Url]
    public ?int $patientId = null;

    public ?Patient $patient = null;

    public function mount(): void
    {
        if (!$this->patientId) {
            $this->redirect(PatientList::getUrl());
            return;
        }

        $this->patient = Patient::find($this->patientId);

        if (!$this->patient) {
            \Filament\Notifications\Notification::make()
                ->title('Patient not found.')
                ->danger()
                ->send();
            $this->redirect(PatientList::getUrl());
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
     * Link to open NurseChart for a specific visit.
     */
    public function getOpenChartUrl(int $visitId): string
    {
        return NurseChart::getUrl(['visitId' => $visitId]);
    }

    /**
     * Back link — returns to the Nurse patient list.
     */
    public function getPatientListUrl(): string
    {
        return PatientList::getUrl();
    }
}