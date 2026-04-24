<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\NicuAdmission;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateProvisionalRecord extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static string $view = 'filament.nurse.pages.create-provisional-record';
    protected static ?string $title = 'NICU - New Baby Arrival';
    protected static ?int $navigationSort = 1;
    
    // Add these as PUBLIC properties so Blade can access them
    public string $birthDateTimeMin;
    public string $birthDateTimeMax;
    
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasAnyRole(['nurse', 'doctor', 'admin']) ?? false;
    }
    
    public $formData = [
        'mother_last_name' => '',
        'baby_sex' => '',
        'birth_datetime' => '',
        'apgar_1min' => null,
        'apgar_5min' => null,
        'apgar_10min' => null,
        'birth_weight_grams' => null,
        'birth_length_cm' => null,
        'is_transfer' => false,
        'referring_facility' => '',
        'observations' => [],
        'baby_family_name' => '',
        'baby_first_name' => '',
        'baby_middle_name' => '',
    ];
    
    public $availableObservations = [
        'respiratory_distress' => 'Respiratory distress',
        'cyanosis' => 'Cyanosis',
        'poor_tone' => 'Poor muscle tone',
        'bradycardia' => 'Bradycardia',
        'seizures' => 'Seizures',
        'jaundice' => 'Jaundice',
        'vomiting' => 'Vomiting',
        'poor_feeding' => 'Poor feeding',
    ];
    
    public function mount(): void
    {
        // Initialize date range properties
        $this->birthDateTimeMin = now()->subDays(7)->format('Y-m-d\TH:i');
        $this->birthDateTimeMax = now()->format('Y-m-d\TH:i');
    }
    
    public function getRules(): array
    {
        return [
            'formData.mother_last_name' => 'required|string|min:2|max:100',
            'formData.baby_sex' => 'required|in:Male,Female',
            'formData.birth_datetime' => 'required|date|before_or_equal:now',
            'formData.apgar_1min' => 'nullable|integer|min:0|max:10',
            'formData.apgar_5min' => 'nullable|integer|min:0|max:10',
            'formData.apgar_10min' => 'nullable|integer|min:0|max:10',
            'formData.birth_weight_grams' => 'nullable|numeric|min:200|max:7000',
            'formData.birth_length_cm' => 'nullable|numeric|min:20|max:70',
            'formData.referring_facility' => 'required_if:formData.is_transfer,true|string|nullable',
        ];
    }
    
    protected function getMessages(): array
    {
        return [
            'formData.mother_last_name.required' => 'Mother\'s last name is required to create a provisional record.',
            'formData.mother_last_name.min' => 'Please enter at least 2 characters.',
            'formData.baby_sex.required' => 'Please select the baby\'s sex.',
            'formData.birth_datetime.required' => 'Birth date and time are required.',
            'formData.birth_datetime.before_or_equal' => 'Birth date cannot be in the future.',
            'formData.referring_facility.required_if' => 'Referring facility is required for transfer patients.',
        ];
    }
    
    public function updatedFormDataApgar1min(): void
    {
        $this->checkApgarAlerts();
    }
    
    public function updatedFormDataApgar5min(): void
    {
        $this->checkApgarAlerts();
    }
    
    protected function checkApgarAlerts(): void
    {
        $one = (int) ($this->formData['apgar_1min'] ?? 10);
        $five = (int) ($this->formData['apgar_5min'] ?? 10);
        
        if ($one < 7) {
            Notification::make()
                ->title('⚠️ Low APGAR at 1 minute')
                ->body("APGAR score of {$one} at 1 minute may indicate need for resuscitation.")
                ->warning()
                ->persistent()
                ->send();
        }
        
        if ($five < 7) {
            Notification::make()
                ->title('⚠️ Low APGAR at 5 minutes')
                ->body("APGAR score of {$five} at 5 minutes. Please document 10-minute APGAR if needed.")
                ->warning()
                ->persistent()
                ->send();
        }
    }
    
    public function save(): void
    {
        $this->validate();
        
        DB::beginTransaction();
        
        try {
            $existingBaby = Patient::where('mother_last_name_at_birth', $this->formData['mother_last_name'])
                ->whereDate('birth_datetime', Carbon::parse($this->formData['birth_datetime'])->toDateString())
                ->first();
            
            if ($existingBaby) {
                Notification::make()
                    ->title('Baby record already exists')
                    ->body("A record for this baby already exists. Temporary ID: {$existingBaby->temporary_case_no}")
                    ->warning()
                    ->send();
                return;
            }
            
            $temporaryCaseNo = Patient::generateTemporaryCaseNo();
            $temporaryIdentifier = 'Baby of ' . $this->formData['mother_last_name'] . 
                ' - ' . Carbon::parse($this->formData['birth_datetime'])->format('M j, g:i A');
            
            $baby = Patient::create([
                'is_provisional' => true,
                'temporary_case_no' => $temporaryCaseNo,
                'temporary_identifier' => $temporaryIdentifier,
                'mother_last_name_at_birth' => $this->formData['mother_last_name'],
                'birth_datetime' => $this->formData['birth_datetime'],
                'sex' => $this->formData['baby_sex'],
                'is_pedia' => true,
                'address' => 'PENDING_CLERK_REGISTRATION',
                'baby_family_name' => $this->formData['baby_family_name'] ?: null,
                'baby_first_name' => $this->formData['baby_first_name'] ?: null,
                'baby_middle_name' => $this->formData['baby_middle_name'] ?: null,
            ]);
            
            $visit = Visit::create([
                'patient_id' => $baby->id,
                'visit_type' => 'NICU',
                'status' => 'provisional_registration',
                'is_provisionally_registered' => true,
                'registered_at' => now(),
                'chief_complaint' => $this->getObservationsText(),
                'referring_facility' => $this->formData['is_transfer'] ? $this->formData['referring_facility'] : null,
                'admission_type' => $this->formData['is_transfer'] ? 'transferred' : 'born_at_lumc',
            ]);
            
            NicuAdmission::create([
                'visit_id' => $visit->id,
                'patient_id' => $baby->id,
                'filled_by' => auth()->id(),
                'date_time_of_birth' => $this->formData['birth_datetime'],
                'apgar_1min' => $this->formData['apgar_1min'],
                'apgar_5min' => $this->formData['apgar_5min'],
                'apgar_10min' => $this->formData['apgar_10min'],
                'birth_weight_grams' => $this->formData['birth_weight_grams'],
                'birth_length_cm' => $this->formData['birth_length_cm'],
                'referring_facility' => $this->formData['is_transfer'] ? $this->formData['referring_facility'] : null,
                'reason_for_nicu_admission' => $this->getObservationsText(),
            ]);
            
            DB::commit();
            
            Notification::make()
                ->title('✓ Provisional Record Created')
                ->body("Temporary ID: {$temporaryCaseNo}\n\nNext: Please complete the baby's information after talking to the mother.")
                ->success()
                ->duration(10000)
                ->send();
            
            // Redirect to CompleteBabyInformation page in Nurse panel
            $this->redirect('/nurse/complete-baby-information?patientId=' . $baby->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Notification::make()
                ->title('Error Creating Provisional Record')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
    
    protected function getObservationsText(): string
    {
        if (empty($this->formData['observations'])) {
            return 'NICU admission for observation';
        }
        
        $selected = [];
        foreach ($this->formData['observations'] as $key) {
            if (isset($this->availableObservations[$key])) {
                $selected[] = $this->availableObservations[$key];
            }
        }
        
        return implode(', ', $selected);
    }
}