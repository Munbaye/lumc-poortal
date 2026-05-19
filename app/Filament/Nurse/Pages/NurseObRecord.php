<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Visit;
use App\Models\ObRecord;
use App\Models\ObPreviousPregnancy;
use App\Models\ActivityLog;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

/**
 * NurseObRecord — Full OB Record form for nurses.
 *
 * Rules:
 *  - Only accessible for admitted OB visits (status = admitted).
 *  - IE and fetal assessment fields are READ-ONLY (doctor entered them).
 *  - Nurse fills obstetric history, previous pregnancies, symptoms, physical exam, notes.
 *  - Registered in NursePanelProvider.
 */
class NurseObRecord extends Page
{
    protected static ?string $navigationIcon         = 'heroicon-o-document-text';
    protected static string  $view                   = 'filament.nurse.pages.nurse-ob-record';
    protected static ?string $title                  = 'OB Record';
    protected static ?int    $navigationSort  = 2;
    protected static ?string $navigationGroup = 'OB Care';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit    $visit    = null;
    public ?ObRecord $obRecord = null;

    // ── Doctor-entered fields (displayed read-only) ───────────────────────────
    public ?string $diagnosisOnAdmission = null; // from doctor
    public ?string $ieCervicalDilation   = null;
    public ?string $ieEffacement         = null;
    public ?string $ieStation            = null;
    public ?string $iePresentation       = null;
    public ?string $ieMembranes          = null;
    public ?string $ieOtherFindings      = null;
    public ?string $fetalHeartTone       = null;
    public ?string $fetalPresentation    = null;
    public ?string $fetalPosition        = null;
    public ?string $fundicHeight         = null;
    public ?string $engagement           = null;

    // ── Obstetric History (nurse fills) ───────────────────────────────────────
    public ?int    $gravida  = null;
    public ?int    $para     = null;
    public ?int    $term     = null;
    public ?int    $preterm  = null;
    public ?int    $abortion = null;
    public ?int    $living   = null;

    // ── Menstrual History ─────────────────────────────────────────────────────
    public ?string $menarche       = null;
    public ?string $mensesInterval = null;
    public ?string $mensesDuration = null;
    public bool    $dysmenorrhea   = false;

    // ── Prenatal ──────────────────────────────────────────────────────────────
    public ?string $prenatalCheckupType   = null;
    public ?string $prenatalCheckupOthers = null;
    public ?int    $prenatalVisitCount    = null;

    // ── Past & Family History ─────────────────────────────────────────────────
    public ?string $pastMedicalHistory = null;
    public ?string $familyHistory      = null;

    // ── Present Pregnancy Dates ───────────────────────────────────────────────
    public ?string $lmp            = null;
    public ?string $pmp            = null;
    public ?string $edc            = null;
    public ?string $aog            = null;
    public ?string $quickeningDate = null;

    // ── Symptoms ──────────────────────────────────────────────────────────────
    public ?string $morningSickness  = null;
    public array   $abnormalSymptoms = [];
    public array   $edema            = [];
    public ?string $otherSymptoms    = null;

    // ── Contractions ──────────────────────────────────────────────────────────
    public ?string $contractionFrequency = null;
    public ?string $contractionDuration  = null;
    public bool    $bog                  = false;

    // ── Physical Exam (nurse fills) ───────────────────────────────────────────
    public ?string $conditionConscious  = null;
    public ?string $conditionStrength   = null;
    public ?string $conditionAmbulatory = null;
    public ?string $heent               = null;
    public ?string $skin                = null;
    public ?string $heart               = null;
    public ?string $lungs               = null;
    public ?string $abdomen             = null;

    // ── Nurse Notes ───────────────────────────────────────────────────────────
    public ?string $nursesNotes = null;

    // ── Previous Pregnancies (dynamic rows) ───────────────────────────────────
    public array $previousPregnancies = [];

    public array $availableSymptoms = [
        'Vaginal bleeding', 'Severe headache', 'Blurring of vision',
        'Epigastric pain', 'Decreased fetal movement', 'Fever',
        'Dysuria', 'Vaginal discharge', 'Leg cramps',
    ];

    public array $edemaOptions = ['Feet', 'Hands', 'Face', 'Generalized'];

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect('/nurse');
            return;
        }

        $this->visit = Visit::with(['patient', 'obRecord.previousPregnancies'])->find($this->visitId);

        if (!$this->visit || $this->visit->visit_type !== 'OB') {
            Notification::make()->title('Invalid visit.')->danger()->send();
            $this->redirect('/nurse');
            return;
        }

        if ($this->visit->status !== 'admitted') {
            Notification::make()
                ->title('OB Record can only be filled after the doctor admits the patient.')
                ->warning()
                ->send();
            $this->redirect('/nurse');
            return;
        }

        $this->obRecord = $this->visit->obRecord;

        if ($this->obRecord) {
            $this->loadFromRecord($this->obRecord);
        } else {
            $this->previousPregnancies = [$this->blankRow(1)];
        }
    }

    protected function loadFromRecord(ObRecord $rec): void
    {
        // ── Doctor-entered (read-only) ────────────────────────────────────────
        $this->diagnosisOnAdmission = $rec->diagnosis_on_admission;
        $this->ieCervicalDilation   = $rec->ie_cervical_dilation;
        $this->ieEffacement         = $rec->ie_effacement;
        $this->ieStation            = $rec->ie_station;
        $this->iePresentation       = $rec->ie_presentation;
        $this->ieMembranes          = $rec->ie_membranes;
        $this->ieOtherFindings      = $rec->ie_other_findings;
        $this->fetalHeartTone       = $rec->fetal_heart_tone;
        $this->fetalPresentation    = $rec->fetal_presentation;
        $this->fetalPosition        = $rec->fetal_position;
        $this->fundicHeight         = $rec->fundic_height;
        $this->engagement           = $rec->engagement;

        // ── Nurse fields ─────────────────────────────────────────────────────
        $this->gravida  = $rec->gravida;
        $this->para     = $rec->para;
        $this->term     = $rec->term;
        $this->preterm  = $rec->preterm;
        $this->abortion = $rec->abortion;
        $this->living   = $rec->living;

        $this->menarche       = $rec->menarche;
        $this->mensesInterval = $rec->menses_interval;
        $this->mensesDuration = $rec->menses_duration;
        $this->dysmenorrhea   = (bool) $rec->dysmenorrhea;

        $this->prenatalCheckupType   = $rec->prenatal_checkup_type;
        $this->prenatalCheckupOthers = $rec->prenatal_checkup_others;
        $this->prenatalVisitCount    = $rec->prenatal_visit_count;

        $this->pastMedicalHistory = $rec->past_medical_history;
        $this->familyHistory      = $rec->family_history;

        $this->lmp            = $rec->lmp ? \Carbon\Carbon::parse($rec->lmp)->format('Y-m-d') : null;
        $this->pmp            = $rec->pmp ? \Carbon\Carbon::parse($rec->pmp)->format('Y-m-d') : null;
        $this->edc            = $rec->edc ? \Carbon\Carbon::parse($rec->edc)->format('Y-m-d') : null;
        $this->aog            = $rec->aog;
        $this->quickeningDate = $rec->quickening_date ? \Carbon\Carbon::parse($rec->quickening_date)->format('Y-m-d') : null;

        $this->morningSickness  = $rec->morning_sickness;
        $this->abnormalSymptoms = $rec->abnormal_symptoms ?? [];
        $this->edema            = $rec->edema ?? [];
        $this->otherSymptoms    = $rec->other_symptoms;

        $this->contractionFrequency = $rec->contraction_frequency;
        $this->contractionDuration  = $rec->contraction_duration;
        $this->bog                  = (bool) $rec->bog;

        $this->conditionConscious  = $rec->condition_conscious;
        $this->conditionStrength   = $rec->condition_strength;
        $this->conditionAmbulatory = $rec->condition_ambulatory;
        $this->heent    = $rec->heent;
        $this->skin     = $rec->skin;
        $this->heart    = $rec->heart;
        $this->lungs    = $rec->lungs;
        $this->abdomen  = $rec->abdomen;

        $this->nursesNotes = $rec->nurses_notes;

        // Previous pregnancies
        if ($rec->previousPregnancies->isNotEmpty()) {
            $this->previousPregnancies = $rec->previousPregnancies
                ->map(fn ($p) => [
                    'gravida_order'      => $p->gravida_order,
                    'aog_term'           => $p->aog_term ?? '',
                    'manner_of_delivery' => $p->manner_of_delivery ?? '',
                    'delivery_date'      => $p->delivery_date ? \Carbon\Carbon::parse($p->delivery_date)->format('Y-m-d') : '',
                    'gender'             => $p->gender ?? '',
                    'weight_grams'       => $p->weight_grams,
                    'complications'      => $p->complications ?? '',
                ])
                ->toArray();
        } else {
            $this->previousPregnancies = [$this->blankRow(1)];
        }
    }

    protected function blankRow(int $order): array
    {
        return [
            'gravida_order'      => $order,
            'aog_term'           => '',
            'manner_of_delivery' => '',
            'delivery_date'      => '',
            'gender'             => '',
            'weight_grams'       => null,
            'complications'      => '',
        ];
    }

    public function addPregnancyRow(): void
    {
        $this->previousPregnancies[] = $this->blankRow(count($this->previousPregnancies) + 1);
    }

    public function removePregnancyRow(int $index): void
    {
        array_splice($this->previousPregnancies, $index, 1);
        foreach ($this->previousPregnancies as $i => &$row) {
            $row['gravida_order'] = $i + 1;
        }
    }

    public function save(): void
    {
        // ── Basic validation ──────────────────────────────────────────────────
        $errors = [];

        if ($this->gravida !== null && $this->para === null) {
            $errors[] = 'Para is required when Gravida is entered.';
        }
        if ($this->para !== null && $this->gravida === null) {
            $errors[] = 'Gravida is required when Para is entered.';
        }
        if ($this->lmp && $this->edc && $this->lmp > $this->edc) {
            $errors[] = 'LMP cannot be after EDC.';
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                Notification::make()->title($error)->warning()->send();
            }
            return;
        }

        DB::beginTransaction();

        try {
            $data = [
                'visit_id'   => $this->visitId,
                'patient_id' => $this->visit->patient_id,
                'filled_by'  => auth()->id(),

                // Nurse-fillable fields
                'gravida'  => $this->gravida,
                'para'     => $this->para,
                'term'     => $this->term,
                'preterm'  => $this->preterm,
                'abortion' => $this->abortion,
                'living'   => $this->living,

                'menarche'        => $this->menarche,
                'menses_interval' => $this->mensesInterval,
                'menses_duration' => $this->mensesDuration,
                'dysmenorrhea'    => $this->dysmenorrhea,

                'prenatal_checkup_type'   => $this->prenatalCheckupType,
                'prenatal_checkup_others' => $this->prenatalCheckupOthers,
                'prenatal_visit_count'    => $this->prenatalVisitCount,

                'past_medical_history' => $this->pastMedicalHistory,
                'family_history'       => $this->familyHistory,

                'lmp'             => $this->lmp ?: null,
                'pmp'             => $this->pmp ?: null,
                'edc'             => $this->edc ?: null,
                'aog'             => $this->aog,
                'quickening_date' => $this->quickeningDate ?: null,

                'morning_sickness'  => $this->morningSickness,
                'abnormal_symptoms' => $this->abnormalSymptoms ?: null,
                'edema'             => $this->edema ?: null,
                'other_symptoms'    => $this->otherSymptoms,

                'contraction_frequency' => $this->contractionFrequency,
                'contraction_duration'  => $this->contractionDuration,
                'bog'                   => $this->bog,

                'condition_conscious'  => $this->conditionConscious,
                'condition_strength'   => $this->conditionStrength,
                'condition_ambulatory' => $this->conditionAmbulatory,
                'heent'   => $this->heent,
                'skin'    => $this->skin,
                'heart'   => $this->heart,
                'lungs'   => $this->lungs,
                'abdomen' => $this->abdomen,

                'nurses_notes' => $this->nursesNotes,

                // Doctor-entered fields are NOT overwritten — only update
                // if the record doesn't exist yet (i.e. doctor never saved them).
                // We use updateOrCreate and exclude those columns from the nurse's save.
            ];

            if ($this->obRecord) {
                // Only update nurse-fillable columns — do NOT touch doctor columns
                $this->obRecord->update($data);
            } else {
                $this->obRecord = ObRecord::create($data);
            }

            // ── Sync previous pregnancies ─────────────────────────────────────
            $this->obRecord->previousPregnancies()->delete();

            foreach ($this->previousPregnancies as $row) {
                // Skip completely blank rows
                if (empty($row['aog_term']) && empty($row['manner_of_delivery']) && empty($row['delivery_date'])) {
                    continue;
                }
                ObPreviousPregnancy::create([
                    'ob_record_id'       => $this->obRecord->id,
                    'gravida_order'      => $row['gravida_order'],
                    'aog_term'           => $row['aog_term']           ?: null,
                    'manner_of_delivery' => $row['manner_of_delivery'] ?: null,
                    'delivery_date'      => $row['delivery_date']      ?: null,
                    'gender'             => $row['gender']             ?: null,
                    'weight_grams'       => $row['weight_grams']       ?: null,
                    'complications'      => $row['complications']       ?: null,
                ]);
            }

            // ── Activity log ──────────────────────────────────────────────────
            if (class_exists(ActivityLog::class)) {
                ActivityLog::record(
                    action:       'ob_record_saved',
                    category:     ActivityLog::CAT_CLINICAL,
                    subject:      $this->visit,
                    subjectLabel: ($this->visit->patient->full_name ?? '—') .
                                  ' (' . ($this->visit->patient->case_no ?? $this->visit->patient->temporary_case_no) . ')',
                    newValues: [
                        'gravida' => $this->gravida,
                        'para'    => $this->para,
                        'aog'     => $this->aog,
                    ],
                    panel: 'nurse',
                );
            }

            DB::commit();

            Notification::make()
                ->title('OB Record Saved')
                ->success()
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error Saving OB Record')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}