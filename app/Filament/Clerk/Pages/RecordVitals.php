<?php
namespace App\Filament\Clerk\Pages;

use App\Models\Visit;
use App\Models\Vital;
use App\Models\ActivityLog;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class RecordVitals extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static string  $view = 'filament.clerk.pages.record-vitals';
    protected static ?string $title = 'Record Vital Signs';

    // Hide from sidebar — only accessible via redirect from registration
    protected static bool $shouldRegisterNavigation = false;

    public ?int   $visitId = null;
    public ?Visit $visit   = null;

    public string  $nurseName       = '';
    public ?float  $temperature     = null;
    public ?string $temperatureSite = 'Axilla';
    public ?int    $pulseRate       = null;
    public ?int    $respiratoryRate = null;
    public ?string $bloodPressure   = null;
    public ?float  $heightCm        = null;
    public ?float  $weightKg        = null;
    public ?int    $o2Saturation    = null;
    public ?string $painScale       = null;
    public string  $notes           = '';

    // ✅ Filament passes query params to mount() automatically
    // URL will be: /clerk/pages/record-vitals?visit=123
    public function mount(?int $visit = null): void
    {
        if (!$visit) {
            Notification::make()->title('No visit specified.')->danger()->send();
            $this->redirect(\App\Filament\Clerk\Pages\RegisterPatient::getUrl());
            return;
        }

        $this->visitId = $visit;
        $this->visit   = Visit::with('patient')->findOrFail($visit);
    }

    // Computed property — hide BP for pedia patients
    public function getShowBpAttribute(): bool
    {
        if ($this->weightKg && $this->weightKg < 10) return false;
        if ($this->visit?->patient?->is_pedia) return false;
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

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'action'       => 'recorded_vitals',
            'subject_type' => 'Visit',
            'subject_id'   => $this->visitId,
            'new_values'   => [
                'nurse_name'  => $this->nurseName,
                'temperature' => $this->temperature,
                'pulse_rate'  => $this->pulseRate,
            ],
            'ip_address' => request()->ip(),
        ]);

        Notification::make()->title('Vital signs recorded!')->success()->send();

        $this->redirect('/clerk');
    }
}