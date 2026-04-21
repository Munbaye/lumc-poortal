<?php

namespace App\Filament\Clerk\Pages;

use App\Models\Patient;
use App\Models\Visit;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

/**
 * PatientHistory — shows every visit ever recorded for a given patient.
 *
 * Accessible from:
 *   - VisitResource table → "Patient History" row action
 *   - ViewVisit page      → "All Visits for This Patient" button
 *
 * Not in the sidebar (shouldRegisterNavigation = false).
 * Each visit row links back to VisitResource's ViewVisit page (readonly context).
 */
class PatientHistory extends Page
{
    protected static ?string $navigationIcon        = 'heroicon-o-clock';
    protected static string  $view                  = 'filament.clerk.pages.patient-history';
    protected static bool    $shouldRegisterNavigation = false;
    protected static ?string $title                 = 'Patient Visit History';

    #[Url]
    public ?int $patientId = null;

    public ?Patient $patient = null;

    public function mount(): void
    {
        if (!$this->patientId) {
            $this->redirect(\App\Filament\Clerk\Resources\VisitResource::getUrl('index'));
            return;
        }

        $this->patient = Patient::find($this->patientId);

        if (!$this->patient) {
            \Filament\Notifications\Notification::make()
                ->title('Patient not found.')
                ->danger()
                ->send();
            $this->redirect(\App\Filament\Clerk\Resources\VisitResource::getUrl('index'));
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

    public function getViewVisitUrl(int $visitId): string
    {
        return \App\Filament\Clerk\Resources\VisitResource::getUrl('view', ['record' => $visitId]);
    }

    public function getPatientListUrl(): string
    {
        return \App\Filament\Clerk\Resources\VisitResource::getUrl('index');
    }
}