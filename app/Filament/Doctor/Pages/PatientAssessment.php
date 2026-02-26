<?php
namespace App\Filament\Doctor\Pages;

use App\Models\Visit;
use App\Models\MedicalHistory;
use App\Models\DoctorsOrder;
use App\Models\ActivityLog;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class PatientAssessment extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static string  $view           = 'filament.doctor.pages.patient-assessment';
    protected static ?string $title          = 'Patient Assessment';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit $visit = null;

    public string  $chiefComplaint          = '';
    public string  $historyOfPresentIllness = '';
    public string  $pastMedicalHistory      = '';
    public string  $familyHistory           = '';
    public string  $socialHistory           = '';
    public string  $allergies               = '';
    public string  $currentMedications      = '';
    public string  $physicalExam            = '';
    public string  $diagnosis               = '';
    public string  $differentialDiagnosis   = '';
    public ?string $disposition             = null;
    public string  $admittedWard            = '';
    public string  $service                 = '';
    public string  $paymentType             = '';
    public string  $plan                    = '';
    public string  $newOrder                = '';
    public array   $orders                  = [];

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect('/doctor');
            return;
        }

        $this->visit = Visit::with(['patient', 'vitals', 'medicalHistory', 'doctorsOrders'])
            ->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect('/doctor');
            return;
        }

        $this->chiefComplaint = $this->visit->chief_complaint ?? '';

        if ($history = $this->visit->medicalHistory) {
            foreach ([
                'chiefComplaint', 'historyOfPresentIllness', 'pastMedicalHistory',
                'familyHistory', 'socialHistory', 'allergies', 'currentMedications',
                'physicalExam', 'diagnosis', 'differentialDiagnosis', 'disposition',
                'admittedWard', 'service', 'paymentType', 'plan',
            ] as $field) {
                $col = \Illuminate\Support\Str::snake($field);
                if ($history->$col) $this->$field = $history->$col;
            }
        }

        $this->orders = $this->visit->doctorsOrders->toArray();
    }

    public function addOrder(): void
    {
        if (!trim($this->newOrder)) return;

        DoctorsOrder::create([
            'visit_id'   => $this->visitId,
            'doctor_id'  => auth()->id(),
            'order_text' => $this->newOrder,
        ]);

        $this->newOrder = '';
        $this->visit->load('doctorsOrders');
        $this->orders = $this->visit->doctorsOrders->toArray();
    }

    public function save(): void
    {
        MedicalHistory::updateOrCreate(
            ['visit_id' => $this->visitId],
            [
                'patient_id'                 => $this->visit->patient_id,
                'doctor_id'                  => auth()->id(),
                'chief_complaint'            => $this->chiefComplaint,
                'history_of_present_illness' => $this->historyOfPresentIllness,
                'past_medical_history'       => $this->pastMedicalHistory,
                'family_history'             => $this->familyHistory,
                'social_history'             => $this->socialHistory,
                'allergies'                  => $this->allergies,
                'current_medications'        => $this->currentMedications,
                'physical_exam'              => $this->physicalExam,
                'diagnosis'                  => $this->diagnosis,
                'differential_diagnosis'     => $this->differentialDiagnosis,
                'disposition'                => $this->disposition,
                'admitted_ward'              => $this->admittedWard ?: null,
                'service'                    => $this->service ?: null,
                'payment_type'               => $this->paymentType ?: null,
                'plan'                       => $this->plan,
            ]
        );

        $this->visit->update([
            'status'      => 'assessed',
            'disposition' => $this->disposition,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'action'       => 'assessed_patient',
            'subject_type' => 'Visit',
            'subject_id'   => $this->visitId,
            'ip_address'   => request()->ip(),
        ]);

        Notification::make()->title('Assessment saved!')->success()->send();

        $this->redirect(\App\Filament\Doctor\Resources\PatientQueueResource::getUrl('index'));
    }
}