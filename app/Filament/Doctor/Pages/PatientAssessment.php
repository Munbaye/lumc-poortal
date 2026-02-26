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

    // ── NUR-006 History ──────────────────────────────────────
    public string  $chiefComplaint          = '';
    public string  $historyOfPresentIllness = '';  // "History of Present Complaint"
    public string  $pastMedicalHistory      = '';  // "Past History: Previous Illness and Operations"
    public string  $familyHistory           = '';
    public string  $occupationEnvironment   = '';
    public string  $drugAllergies           = '';
    public string  $drugTherapy             = '';
    public string  $otherAllergies          = '';

    // ── NUR-005 Physical Exam ─────────────────────────────────
    public string  $peSkin            = '';
    public string  $peHeadEent        = '';
    public string  $peLymphNodes      = '';
    public string  $peChest           = '';
    public string  $peLungs           = '';
    public string  $peCardiovascular  = '';
    public string  $peBreast          = '';
    public string  $peAbdomen         = '';
    public string  $peRectum          = '';
    public string  $peGenitalia       = '';
    public string  $peMusculoskeletal = '';
    public string  $peExtremities     = '';
    public string  $peNeurology       = '';

    // ── Assessment / Disposition ──────────────────────────────
    public string  $admittingImpression  = '';  // From NUR-005
    public string  $diagnosis            = '';
    public string  $differentialDiagnosis= '';
    public ?string $disposition          = null;
    public string  $admittedWard         = '';
    public string  $service              = '';
    public string  $paymentType          = '';
    public string  $plan                 = '';

    // ── Doctor's Orders ───────────────────────────────────────
    public string  $newOrder = '';
    public array   $orders   = [];

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

        if ($h = $this->visit->medicalHistory) {
            $this->chiefComplaint         = $h->chief_complaint         ?? $this->chiefComplaint;
            $this->historyOfPresentIllness= $h->history_of_present_illness ?? '';
            $this->pastMedicalHistory     = $h->past_medical_history    ?? '';
            $this->familyHistory          = $h->family_history          ?? '';
            $this->occupationEnvironment  = $h->occupation_environment  ?? '';
            $this->drugAllergies          = $h->drug_allergies          ?? '';
            $this->drugTherapy            = $h->drug_therapy            ?? '';
            $this->otherAllergies         = $h->other_allergies         ?? '';
            $this->peSkin                 = $h->pe_skin                 ?? '';
            $this->peHeadEent             = $h->pe_head_eent            ?? '';
            $this->peLymphNodes           = $h->pe_lymph_nodes          ?? '';
            $this->peChest                = $h->pe_chest                ?? '';
            $this->peLungs                = $h->pe_lungs                ?? '';
            $this->peCardiovascular       = $h->pe_cardiovascular       ?? '';
            $this->peBreast               = $h->pe_breast               ?? '';
            $this->peAbdomen              = $h->pe_abdomen              ?? '';
            $this->peRectum               = $h->pe_rectum               ?? '';
            $this->peGenitalia            = $h->pe_genitalia            ?? '';
            $this->peMusculoskeletal      = $h->pe_musculoskeletal      ?? '';
            $this->peExtremities          = $h->pe_extremities          ?? '';
            $this->peNeurology            = $h->pe_neurology            ?? '';
            $this->admittingImpression    = $h->diagnosis               ?? '';
            $this->diagnosis              = $h->diagnosis               ?? '';
            $this->differentialDiagnosis  = $h->differential_diagnosis  ?? '';
            $this->disposition            = $h->disposition;
            $this->admittedWard           = $h->admitted_ward           ?? '';
            $this->service                = $h->service                 ?? '';
            $this->paymentType            = $h->payment_type            ?? '';
            $this->plan                   = $h->plan                    ?? '';
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
                // NUR-006 History
                'chief_complaint'            => $this->chiefComplaint,
                'history_of_present_illness' => $this->historyOfPresentIllness,
                'past_medical_history'       => $this->pastMedicalHistory,
                'family_history'             => $this->familyHistory,
                'occupation_environment'     => $this->occupationEnvironment,
                'drug_allergies'             => $this->drugAllergies,
                'drug_therapy'               => $this->drugTherapy,
                'other_allergies'            => $this->otherAllergies,
                // NUR-005 Physical Exam
                'pe_skin'                    => $this->peSkin,
                'pe_head_eent'               => $this->peHeadEent,
                'pe_lymph_nodes'             => $this->peLymphNodes,
                'pe_chest'                   => $this->peChest,
                'pe_lungs'                   => $this->peLungs,
                'pe_cardiovascular'          => $this->peCardiovascular,
                'pe_breast'                  => $this->peBreast,
                'pe_abdomen'                 => $this->peAbdomen,
                'pe_rectum'                  => $this->peRectum,
                'pe_genitalia'               => $this->peGenitalia,
                'pe_musculoskeletal'         => $this->peMusculoskeletal,
                'pe_extremities'             => $this->peExtremities,
                'pe_neurology'               => $this->peNeurology,
                // Assessment
                'diagnosis'                  => $this->admittingImpression ?: $this->diagnosis,
                'differential_diagnosis'     => $this->differentialDiagnosis,
                'disposition'                => $this->disposition,
                'admitted_ward'              => $this->admittedWard   ?: null,
                'service'                    => $this->service        ?: null,
                'payment_type'               => $this->paymentType    ?: null,
                'plan'                       => $this->plan,
            ]
        );

        $status = match($this->disposition) {
            'Discharged' => 'discharged',
            'Admitted'   => 'admitted',
            'Referred'   => 'referred',
            default      => 'assessed',
        };

        $this->visit->update([
            'status'      => $status,
            'disposition' => $this->disposition,
            'discharged_at' => in_array($this->disposition, ['Discharged','HAMA','Expired']) ? now() : null,
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