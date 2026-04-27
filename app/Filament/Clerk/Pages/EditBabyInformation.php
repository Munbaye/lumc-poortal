<?php

namespace App\Filament\Clerk\Pages;

use App\Models\Patient;
use App\Models\NicuAdmission;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class EditBabyInformation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static string $view = 'filament.clerk.pages.edit-baby-information';
    protected static ?string $title = 'Edit Baby Information';
    protected static ?string $navigationGroup = 'NICU Management';
    protected static ?int $navigationSort = 2;
    
    public static function shouldRegisterNavigation(): bool
    {
        return false; // Hide from navigation - accessed via button only
    }
    
    public ?Patient $baby = null;
    public ?NicuAdmission $nicuAdmission = null;
    public ?int $patientId = null;
    
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
        $patientId = request()->query('patientId');
        
        if (!$patientId) {
            Notification::make()
                ->title('No patient selected')
                ->warning()
                ->send();
            $this->redirect('/clerk/visits?tab=provisional');
            return;
        }
        
        $this->patientId = (int) $patientId;
        $this->baby = Patient::findOrFail($this->patientId);
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
        $this->validate();
        
        DB::beginTransaction();
        
        try {
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
            }
            
            DB::commit();
            
            Notification::make()
                ->title('Baby Information Updated!')
                ->success()
                ->send();
                
            // Redirect back to the convert page
            if ($this->baby->is_provisional) {
                $visit = $this->baby->latestVisit;
                if ($visit) {
                    $this->redirect('/clerk/convert-to-permanent?visitId=' . $visit->id);
                } else {
                    $this->redirect('/clerk/visits?tab=provisional');
                }
            } else {
                $this->redirect('/clerk/visits');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error Saving Information')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}