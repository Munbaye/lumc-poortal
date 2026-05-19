<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\ObRecord;
use App\Models\Vital;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateProvisionalObRecord extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-heart';
    protected static string  $view            = 'filament.nurse.pages.create-provisional-ob-record';
    protected static ?string $title           = 'OB - New Patient Arrival';
    protected static ?string $navigationLabel = 'OB - New Patient Arrival';
    protected static ?string $navigationGroup = 'OB Care';
    protected static ?int    $navigationSort  = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasAnyRole(['nurse', 'doctor', 'admin']) ?? false;
    }

    // ── Patient Personal Profile ──────────────────────────────────────────────
    public array $formData = [
        // Identity
        'family_name'  => '',
        'first_name'   => '',
        'middle_name'  => '',
        'address'      => '',
        'birthday'     => '',
        'occupation'   => '',
        'civil_status' => '',
        'sex'          => 'Female',  // Always Female for OB
        'age'          => null,
        'spouse_name'  => '',
        'father_name'  => '',
        'mother_name'  => '',

        // Vital Signs (triage)
        'height_cm'    => null,
        'weight_kg'    => null,
        'temperature'  => null,
        'pulse'        => '',
        'bp'           => '',
        'rr'           => '',

        // OB quick data
        'chief_complaint' => '',
        'lmp'             => '',
        'aog'             => '',
        'gravida'         => null,
        'para'            => null,
    ];

    public function getRules(): array
    {
        return [
            'formData.family_name'  => 'required|string|min:2|max:100',
            'formData.first_name'   => 'required|string|min:2|max:100',
            'formData.address'      => 'required|string|min:3|max:500',
            'formData.birthday'     => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $bd = Carbon::parse($value, 'Asia/Manila');
                    if ($bd->isFuture()) {
                        $fail('Birthday cannot be in the future.');
                    }
                    if ($bd->diffInYears(now()) < 12) {
                        $fail('Patient must be at least 12 years old for OB.');
                    }
                },
            ],
            'formData.civil_status' => 'required|in:Single,Married,Widowed,Separated,Annulled',
            'formData.chief_complaint' => 'required|string|min:2',
            'formData.gravida'      => 'nullable|integer|min:1|max:20',
            'formData.para'         => 'nullable|integer|min:0|max:20',
            'formData.lmp'          => 'nullable|date',
            'formData.bp'           => 'nullable|string|max:20',
        ];
    }

    protected function getMessages(): array
    {
        return [
            'formData.family_name.required'      => 'Family name is required.',
            'formData.first_name.required'       => 'First name is required.',
            'formData.address.required'          => 'Address is required.',
            'formData.birthday.required'         => 'Birthday is required.',
            'formData.civil_status.required'     => 'Civil status is required.',
            'formData.chief_complaint.required'  => 'Chief complaint is required.',
        ];
    }

    // Auto-calculate age when birthday changes
    public function updatedFormDataBirthday(string $value): void
    {
        if ($value) {
            try {
                $this->formData['age'] = (int) Carbon::parse($value)->diffInYears(now());
            } catch (\Exception) {}
        }
    }

    public function save(): void
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Check for duplicate (same name + birthday)
            $existing = Patient::where('family_name', $this->formData['family_name'])
                ->where('first_name', $this->formData['first_name'])
                ->whereDate('birthday', Carbon::parse($this->formData['birthday'])->toDateString())
                ->first();

            if ($existing) {
                Notification::make()
                    ->title('Patient record may already exist')
                    ->body("A patient with this name and birthday already exists. Case: {$existing->case_no}")
                    ->warning()
                    ->send();
                // Continue anyway — different visits are allowed for same patient
            }

            $temporaryCaseNo = Patient::generateTemporaryCaseNo();

            $fullName = strtoupper($this->formData['family_name']) . ', '
                . $this->formData['first_name']
                . ($this->formData['middle_name'] ? ' ' . $this->formData['middle_name'] : '');

            $patient = Patient::create([
                'is_provisional'       => true,
                'temporary_case_no'    => $temporaryCaseNo,
                'temporary_identifier' => $fullName,
                'family_name'          => $this->formData['family_name'],
                'first_name'           => $this->formData['first_name'],
                'middle_name'          => $this->formData['middle_name'] ?: null,
                'address'              => $this->formData['address'],
                'birthday'             => $this->formData['birthday'],
                'age'                  => $this->formData['age'],
                'sex'                  => 'Female',
                'occupation'           => $this->formData['occupation'] ?: null,
                'civil_status'         => $this->formData['civil_status'],
                'spouse_name'          => $this->formData['spouse_name'] ?: null,
                'father_name'          => $this->formData['father_name'] ?: null,
                'mother_name'          => $this->formData['mother_name'] ?: null,
                'is_pedia'             => false,
            ]);

            $visit = Visit::create([
                'patient_id'                  => $patient->id,
                'visit_type'                  => 'OB',
                'status'                      => 'provisional_registration',
                'is_provisionally_registered' => true,
                'registered_at'               => now(),
                'chief_complaint'             => $this->formData['chief_complaint'],
            ]);

            // Create vital signs record
            $vital = new Vital();
            $vital->visit_id = $visit->id;
            $vital->patient_id = $patient->id;
            $vital->recorded_by = auth()->id();
            $vital->nurse_name = auth()->user()->name;
            $vital->taken_at = \Carbon\Carbon::now();
            $vital->height_cm = $this->formData['height_cm'] ?: null;
            $vital->weight_kg = $this->formData['weight_kg'] ?: null;
            $vital->temperature = $this->formData['temperature'] ?: null;
            $vital->pulse_rate = $this->formData['pulse'] ?: null;
            $vital->blood_pressure = $this->formData['bp'] ?: null;
            $vital->respiratory_rate = $this->formData['rr'] ?: null;
            $vital->save();

            // Seed a stub ObRecord with the quick OB data so the doctor page has it
            ObRecord::create([
                'visit_id'   => $visit->id,
                'patient_id' => $patient->id,
                'filled_by'  => auth()->id(),
                'gravida'    => $this->formData['gravida'] ?: null,
                'para'       => $this->formData['para']    ?: null,
                'lmp'        => $this->formData['lmp']     ?: null,
                'aog'        => $this->formData['aog']     ?: null,
            ]);

            DB::commit();

            Notification::make()
                ->title('OB Provisional Record Created')
                ->icon('heroicon-o-check-circle')
                ->body("Temporary ID: {$temporaryCaseNo} — Patient is now in the OB queue for doctor assessment.")
                ->success()
                ->duration(8000)
                ->send();

            $this->redirect('/nurse/ob-patients');

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error Creating OB Record')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}