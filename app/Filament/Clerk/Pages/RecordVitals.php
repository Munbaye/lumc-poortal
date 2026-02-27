<?php
namespace App\Filament\Clerk\Pages;

use App\Models\Visit;
use App\Models\Vital;
use App\Models\ActivityLog;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

class RecordVitals extends Page
{
    protected static ?string $navigationIcon        = 'heroicon-o-heart';
    protected static string  $view                  = 'filament.clerk.pages.record-vitals';
    protected static ?string $title                 = 'Record Vital Signs';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit $visit = null;

    // Vital sign fields
    public string  $nurseName       = '';
    public ?float  $temperature     = null;
    public string  $temperatureSite = 'Axilla';
    public ?int    $pulseRate       = null;
    public ?int    $respiratoryRate = null;
    public ?string $bloodPressure   = null;
    public ?float  $heightCm        = null;
    public ?float  $weightKg        = null;
    public ?int    $o2Saturation    = null;
    public ?string $painScale       = null;
    public string  $notes           = '';

    public function mount(): void
    {
        if (!$this->visitId) {
            Notification::make()->title('No visit specified.')->danger()->send();
            $this->redirect(RegisterPatient::getUrl());
            return;
        }

        $this->visit = Visit::with('patient')->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect(RegisterPatient::getUrl());
        }
    }

    #[Computed]
    public function showBp(): bool
    {
        if ($this->weightKg && $this->weightKg < 10) return false;
        if ($this->visit?->patient?->is_pedia)        return false;
        return true;
    }

    public function updatedWeightKg(): void
    {
        if ($this->weightKg && $this->weightKg < 10 && $this->visit?->patient) {
            $this->visit->patient->update(['is_pedia' => true]);
        }
    }

    public function save(): void
    {
        $this->validate([
            'nurseName'       => 'required|string|max:200',
            'temperature'     => 'required|numeric|between:30,45',
            'pulseRate'       => 'required|integer|between:20,300',
            'respiratoryRate' => 'required|integer|between:0,80',
        ], [
            'nurseName.required'       => 'Please enter the name of the nurse or person who took vitals.',
            'temperature.required'     => 'Temperature is required.',
            'temperature.between'      => 'Temperature must be between 30°C and 45°C.',
            'pulseRate.required'       => 'Pulse rate is required.',
            'pulseRate.between'        => 'Pulse rate must be between 20 and 300 bpm.',
            'respiratoryRate.required' => 'Respiratory rate is required.',
            'respiratoryRate.between'  => 'Respiratory rate must be between 0 and 80 breaths/min.',
        ]);

        Vital::create([
            'visit_id'         => $this->visitId,
            'patient_id'       => $this->visit->patient_id,
            'recorded_by'      => auth()->id(),
            'nurse_name'       => $this->nurseName,
            'temperature'      => $this->temperature,
            'temperature_site' => $this->temperatureSite,
            'pulse_rate'       => $this->pulseRate,
            'respiratory_rate' => $this->respiratoryRate,
            'blood_pressure'   => $this->showBp ? $this->bloodPressure : null,
            'height_cm'        => $this->heightCm,
            'weight_kg'        => $this->weightKg,
            'o2_saturation'    => $this->o2Saturation,
            'pain_scale'       => $this->painScale,
            'notes'            => $this->notes,
            'taken_at'         => now(),
        ]);

        $this->visit->update(['status' => 'vitals_done']);

        // ── Activity log ──────────────────────────────────────────────────────
        ActivityLog::record(
            action:       ActivityLog::ACT_RECORDED_VITALS,
            category:     ActivityLog::CAT_VITALS,
            subject:      $this->visit,
            subjectLabel: $this->visit->patient->full_name
                          . ' (' . $this->visit->patient->case_no . ')'
                          . ' — ' . $this->visit->visit_type,
            newValues: array_filter([
                'recorded_by'      => $this->nurseName,
                'temperature'      => $this->temperature . ' °C (' . $this->temperatureSite . ')',
                'pulse_rate'       => $this->pulseRate . ' bpm',
                'respiratory_rate' => $this->respiratoryRate . ' /min',
                'blood_pressure'   => $this->showBp ? ($this->bloodPressure ?? 'not taken') : 'N/A (pedia/low weight)',
                'o2_saturation'    => $this->o2Saturation ? $this->o2Saturation . '%' : null,
                'weight_kg'        => $this->weightKg ? $this->weightKg . ' kg' : null,
                'height_cm'        => $this->heightCm ? $this->heightCm . ' cm' : null,
                'pain_scale'       => $this->painScale ? $this->painScale . '/10' : null,
                'notes'            => $this->notes ?: null,
            ]),
            panel: 'clerk',
        );

        Notification::make()->title('Vital signs saved successfully!')->success()->send();

        $this->redirect(\App\Filament\Clerk\Resources\VisitResource::getUrl('index'));
    }
}