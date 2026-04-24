<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Patient;
use App\Models\NicuAdmission;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class CompleteBabyInformation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.nurse.pages.complete-baby-information';
    protected static ?string $title = 'Complete Baby Information';
    protected static ?string $navigationGroup = 'NICU Care';
    protected static ?int $navigationSort = 2;
    
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('nurse', 'clerk') ?? false;
    }
    
    public ?Patient $baby = null;
    public ?NicuAdmission $nicuAdmission = null;
    public ?int $patientId = null;
    public bool $isPermanent = false;
    public bool $isReadOnly = false;
    
    public array $formData = [
        'baby_family_name' => '',
        'baby_first_name' => '',
        'baby_middle_name' => '',
        'mother_family_name' => '',
        'mother_first_name' => '',
        'mother_middle_name' => '',
        'mother_age' => '',
        'mother_address_full' => '',
        'mother_contact' => '',
        'mother_gravida' => null,
        'mother_para' => null,
        'prenatal_checkup_site' => null,
        'prenatal_visit_count' => null,
        'maternal_history' => '',
        'maternal_signs_symptoms' => '',
        'took_multivitamins' => false,
        'had_ultrasound' => false,
        'had_preterm_labor' => false,
        'steroids_given' => '',
    ];
    
    public function mount(): void
    {
        // Get patientId from request query string
        $patientId = request()->query('patientId');
        
        if (!$patientId) {
            Notification::make()
                ->title('No patient selected')
                ->body('Please select a patient first.')
                ->warning()
                ->send();
            $this->redirect('/nurse');
            return;
        }
        
        $this->patientId = (int) $patientId;
        $this->baby = Patient::findOrFail($this->patientId);
        
        // Check if this is a permanent record
        $this->isPermanent = !$this->baby->is_provisional;
        
        // Check if record is locked (already converted by clerk)
        if ($this->isPermanent && $this->baby->clerk_registered_at) {
            $this->isReadOnly = true;
            Notification::make()
                ->title('⚠️ Record is in Read-Only Mode')
                ->body('This baby has already been converted to a permanent record by the clerk. You can view but cannot edit.')
                ->warning()
                ->duration(8000)
                ->send();
        }
        
        $this->nicuAdmission = NicuAdmission::where('patient_id', $this->patientId)->first();
        $this->loadFormData();
    }
    
    protected function loadFormData(): void
    {
        if ($this->baby->baby_family_name) {
            $this->formData['baby_family_name'] = $this->baby->baby_family_name;
            $this->formData['baby_first_name'] = $this->baby->baby_first_name;
            $this->formData['baby_middle_name'] = $this->baby->baby_middle_name;
        }
        
        if ($this->baby->mother_family_name) {
            $this->formData['mother_family_name'] = $this->baby->mother_family_name;
            $this->formData['mother_first_name'] = $this->baby->mother_first_name;
            $this->formData['mother_middle_name'] = $this->baby->mother_middle_name;
            $this->formData['mother_age'] = $this->baby->mother_age;
            $this->formData['mother_address_full'] = $this->baby->mother_address_full;
            $this->formData['mother_contact'] = $this->baby->mother_contact;
        }
        
        if ($this->nicuAdmission) {
            $this->formData['mother_gravida'] = $this->nicuAdmission->mother_gravida;
            $this->formData['mother_para'] = $this->nicuAdmission->mother_para;
            $this->formData['prenatal_checkup_site'] = $this->nicuAdmission->prenatal_checkup_site;
            $this->formData['prenatal_visit_count'] = $this->nicuAdmission->prenatal_visit_count;
            $this->formData['maternal_history'] = $this->nicuAdmission->maternal_history;
            $this->formData['maternal_signs_symptoms'] = $this->nicuAdmission->maternal_signs_symptoms;
            $this->formData['took_multivitamins'] = (bool) $this->nicuAdmission->took_multivitamins;
            $this->formData['had_ultrasound'] = (bool) $this->nicuAdmission->had_ultrasound;
            $this->formData['had_preterm_labor'] = (bool) $this->nicuAdmission->had_preterm_labor;
            $this->formData['steroids_given'] = $this->nicuAdmission->steroids_given;
        }
    }
    
    public function getRules(): array
    {
        // If read-only, no validation needed
        if ($this->isReadOnly) {
            return [];
        }
        
        return [
            'formData.baby_family_name' => 'required|string|max:100',
            'formData.baby_first_name' => 'required|string|max:100',
            'formData.mother_family_name' => 'required|string|max:100',
            'formData.mother_first_name' => 'required|string|max:100',
            'formData.mother_address_full' => 'required|string|min:5',
            'formData.mother_contact' => 'required|string|min:10',
        ];
    }
    
    public function save(): void
    {
        // Prevent saving if read-only
        if ($this->isReadOnly) {
            Notification::make()
                ->title('Cannot Edit')
                ->body('This record has already been converted to permanent and is read-only.')
                ->warning()
                ->send();
            return;
        }
        
        $this->validate();
        
        DB::beginTransaction();
        
        try {
            // Update patient with baby and mother info
            $this->baby->update([
                'baby_family_name' => $this->formData['baby_family_name'],
                'baby_first_name' => $this->formData['baby_first_name'],
                'baby_middle_name' => $this->formData['baby_middle_name'],
                'mother_family_name' => $this->formData['mother_family_name'],
                'mother_first_name' => $this->formData['mother_first_name'],
                'mother_middle_name' => $this->formData['mother_middle_name'],
                'mother_age' => $this->formData['mother_age'],
                'mother_address_full' => $this->formData['mother_address_full'],
                'mother_contact' => $this->formData['mother_contact'],
            ]);
            
            if ($this->nicuAdmission) {
                $this->nicuAdmission->update([
                    'mother_gravida' => $this->formData['mother_gravida'],
                    'mother_para' => $this->formData['mother_para'],
                    'prenatal_checkup_site' => $this->formData['prenatal_checkup_site'],
                    'prenatal_visit_count' => $this->formData['prenatal_visit_count'],
                    'maternal_history' => $this->formData['maternal_history'],
                    'maternal_signs_symptoms' => $this->formData['maternal_signs_symptoms'],
                    'took_multivitamins' => $this->formData['took_multivitamins'],
                    'had_ultrasound' => $this->formData['had_ultrasound'],
                    'had_preterm_labor' => $this->formData['had_preterm_labor'],
                    'steroids_given' => $this->formData['steroids_given'],
                ]);
            } else {
                $visit = $this->baby->latestVisit;
                if ($visit) {
                    NicuAdmission::create([
                        'visit_id' => $visit->id,
                        'patient_id' => $this->baby->id,
                        'mother_gravida' => $this->formData['mother_gravida'],
                        'mother_para' => $this->formData['mother_para'],
                        'prenatal_checkup_site' => $this->formData['prenatal_checkup_site'],
                        'prenatal_visit_count' => $this->formData['prenatal_visit_count'],
                        'maternal_history' => $this->formData['maternal_history'],
                        'maternal_signs_symptoms' => $this->formData['maternal_signs_symptoms'],
                        'took_multivitamins' => $this->formData['took_multivitamins'],
                        'had_ultrasound' => $this->formData['had_ultrasound'],
                        'had_preterm_labor' => $this->formData['had_preterm_labor'],
                        'steroids_given' => $this->formData['steroids_given'],
                    ]);
                }
            }
            
            DB::commit();
            
            $message = $this->isPermanent 
                ? 'Baby information updated successfully!' 
                : 'Baby Information Saved! The clerk can now convert this to a permanent record.';
            
            Notification::make()
                ->title($this->isPermanent ? 'Information Updated!' : 'Baby Information Saved!')
                ->body($message)
                ->success()
                ->send();
                
            $this->redirect('/nurse');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error Saving Information')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
    
    public function getSubmitButtonLabel(): string
    {
        if ($this->isReadOnly) {
            return 'Read Only - Cannot Edit';
        }
        return $this->isPermanent ? 'Update Information' : 'Save Baby Information';
    }
}