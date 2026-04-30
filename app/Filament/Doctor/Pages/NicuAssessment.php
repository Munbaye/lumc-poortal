<?php

namespace App\Filament\Doctor\Pages;

use App\Models\Visit;
use App\Models\NicuAdmission;
use App\Models\NicuPhysicalExam;
use App\Models\DoctorsOrder;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

class NicuAssessment extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static string $view = 'filament.doctor.pages.nicu-assessment';
    protected static ?string $title = 'NICU Baby Assessment';
    protected static bool $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit $visit = null;
    public ?NicuAdmission $nicuAdmission = null;
    public ?NicuPhysicalExam $physicalExam = null;

    // ── Exam Metadata ─────────────────────────────────────────────────────────
    public ?string $examDate = null;
    public ?int $hoursAfterBirth = null;

    // ── APGAR ─────────────────────────────────────────────────────────────────
    public ?int $apgarBirth = null;
    public ?int $apgar5Min = null;
    public ?int $apgar10Min = null;

    // ── General ──────────────────────────────────────────────────────────────
    public ?string $generalCondition = null;

    // ── Measurements ─────────────────────────────────────────────────────────
    public ?float $headCircumferenceCm = null;
    public ?float $chestCircumferenceCm = null;
    public ?float $abdominalCircumferenceCm = null;
    public ?float $birthWeightG = null;
    public ?float $birthLengthCm = null;

    // ── Neuromuscular ────────────────────────────────────────────────────────
    public ?string $generalMuscularTonus = null;

    // ── Skin ─────────────────────────────────────────────────────────────────
    public ?string $skinColor = null;
    public ?string $skinTurgor = null;
    public ?string $skinRash = null;
    public ?string $skinDesquamation = null;

    // ── Head ─────────────────────────────────────────────────────────────────
    public ?string $headMolding = null;
    public ?string $headScalp = null;
    public ?string $headFontanelles = null;
    public ?string $headSuture = null;
    public ?string $face = null;

    // ── Eyes ─────────────────────────────────────────────────────────────────
    public ?string $eyesConjunctiva = null;
    public ?string $eyesSclera = null;
    public ?string $eyesPupils = null;
    public ?string $eyesDischarge = null;

    // ── Ears & Nose ──────────────────────────────────────────────────────────
    public ?string $ears = null;
    public ?string $nose = null;

    // ── Mouth ────────────────────────────────────────────────────────────────
    public ?string $mouthLip = null;
    public ?string $mouthTongue = null;
    public ?string $mouthPalate = null;

    // ── Neck ─────────────────────────────────────────────────────────────────
    public ?string $neckSternocleidomastoid = null;
    public ?string $neckFistula = null;

    // ── Chest ────────────────────────────────────────────────────────────────
    public ?string $chestShape = null;
    public ?string $chestRespiration = null;
    public ?string $chestClavicles = null;
    public ?string $chestBreast = null;
    public ?string $chestHeart = null;
    public ?string $chestLungs = null;

    // ── Abdomen ──────────────────────────────────────────────────────────────
    public ?string $abdomen = null;
    public ?string $spleen = null;
    public ?string $kidneys = null;
    public ?string $liver = null;
    public ?string $umbilicalCord = null;

    // ── Hernia ───────────────────────────────────────────────────────────────
    public ?string $inguinalHernia = null;
    public ?string $diastasisRecti = null;

    // ── Genitals ─────────────────────────────────────────────────────────────
    public ?string $genitalsMale = null;
    public ?string $genitalsFemale = null;

    // ── Extremities & Orthopaedic ────────────────────────────────────────────
    public ?string $extremities = null;
    public ?string $clubfoot = null;
    public ?string $hipDislocation = null;
    public ?string $femoralPulse = null;
    public ?string $spine = null;
    public ?string $anus = null;

    // ── Impression & Orders ──────────────────────────────────────────────────
    public ?string $impression = null;
    public string $orderText = '';

    // ── Admission Decision ───────────────────────────────────────────────────
    public bool $isAdmitted = false;
    public bool $admitToNICU = false;

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect('/doctor/nicu-babies');
            return;
        }

        $this->visit = Visit::with(['patient', 'nicuAdmission', 'nicuPhysicalExam'])
            ->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect('/doctor/nicu-babies');
            return;
        }

        $this->nicuAdmission = $this->visit->nicuAdmission;
        $this->physicalExam = $this->visit->nicuPhysicalExam;

        // Check current admission status
        $this->isAdmitted = $this->visit->status === 'admitted';

        // Load NicuAdmission data
        if ($this->nicuAdmission) {
            $this->birthWeightG = $this->nicuAdmission->birth_weight_grams;
            $this->birthLengthCm = $this->nicuAdmission->birth_length_cm;
            $this->headCircumferenceCm = $this->nicuAdmission->head_circumference_cm;
            $this->chestCircumferenceCm = $this->nicuAdmission->chest_circumference_cm;
            $this->abdominalCircumferenceCm = $this->nicuAdmission->abdominal_circumference_cm;
            $this->apgarBirth = $this->nicuAdmission->apgar_1min;
            $this->apgar5Min = $this->nicuAdmission->apgar_5min;
            $this->apgar10Min = $this->nicuAdmission->apgar_10min;
        }

        // Load Physical Exam data
        if ($this->physicalExam) {
            $this->examDate = $this->physicalExam->exam_date?->format('Y-m-d');
            $this->hoursAfterBirth = $this->physicalExam->hours_after_birth;
            $this->apgarBirth = $this->physicalExam->apgar_birth ?? $this->apgarBirth;
            $this->apgar5Min = $this->physicalExam->apgar_5min ?? $this->apgar5Min;
            $this->apgar10Min = $this->physicalExam->apgar_10min ?? $this->apgar10Min;
            $this->generalCondition = $this->physicalExam->general_condition;
            $this->headCircumferenceCm = $this->physicalExam->head_circumference_cm ?? $this->headCircumferenceCm;
            $this->chestCircumferenceCm = $this->physicalExam->chest_circumference_cm ?? $this->chestCircumferenceCm;
            $this->abdominalCircumferenceCm = $this->physicalExam->abdominal_circumference_cm ?? $this->abdominalCircumferenceCm;
            $this->birthWeightG = $this->physicalExam->birth_weight_g ?? $this->birthWeightG;
            $this->birthLengthCm = $this->physicalExam->birth_length_cm ?? $this->birthLengthCm;
            $this->generalMuscularTonus = $this->physicalExam->general_muscular_tonus;
            $this->skinColor = $this->physicalExam->skin_color;
            $this->skinTurgor = $this->physicalExam->skin_turgor;
            $this->skinRash = $this->physicalExam->skin_rash;
            $this->skinDesquamation = $this->physicalExam->skin_desquamation;
            $this->headMolding = $this->physicalExam->head_molding;
            $this->headScalp = $this->physicalExam->head_scalp;
            $this->headFontanelles = $this->physicalExam->head_fontanelles;
            $this->headSuture = $this->physicalExam->head_suture;
            $this->face = $this->physicalExam->face;
            $this->eyesConjunctiva = $this->physicalExam->eyes_conjunctiva;
            $this->eyesSclera = $this->physicalExam->eyes_sclera;
            $this->eyesPupils = $this->physicalExam->eyes_pupils;
            $this->eyesDischarge = $this->physicalExam->eyes_discharge;
            $this->ears = $this->physicalExam->ears;
            $this->nose = $this->physicalExam->nose;
            $this->mouthLip = $this->physicalExam->mouth_lip;
            $this->mouthTongue = $this->physicalExam->mouth_tongue;
            $this->mouthPalate = $this->physicalExam->mouth_palate;
            $this->neckSternocleidomastoid = $this->physicalExam->neck_sternocleidomastoid;
            $this->neckFistula = $this->physicalExam->neck_fistula;
            $this->chestShape = $this->physicalExam->chest_shape;
            $this->chestRespiration = $this->physicalExam->chest_respiration;
            $this->chestClavicles = $this->physicalExam->chest_clavicles;
            $this->chestBreast = $this->physicalExam->chest_breast;
            $this->chestHeart = $this->physicalExam->chest_heart;
            $this->chestLungs = $this->physicalExam->chest_lungs;
            $this->abdomen = $this->physicalExam->abdomen;
            $this->spleen = $this->physicalExam->spleen;
            $this->kidneys = $this->physicalExam->kidneys;
            $this->liver = $this->physicalExam->liver;
            $this->umbilicalCord = $this->physicalExam->umbilical_cord;
            $this->inguinalHernia = $this->physicalExam->inguinal_hernia;
            $this->diastasisRecti = $this->physicalExam->diastasis_recti;
            $this->genitalsMale = $this->physicalExam->genitals_male;
            $this->genitalsFemale = $this->physicalExam->genitals_female;
            $this->extremities = $this->physicalExam->extremities;
            $this->clubfoot = $this->physicalExam->clubfoot;
            $this->hipDislocation = $this->physicalExam->hip_dislocation;
            $this->femoralPulse = $this->physicalExam->femoral_pulse;
            $this->spine = $this->physicalExam->spine;
            $this->anus = $this->physicalExam->anus;
            $this->impression = $this->physicalExam->impression;
        }
    }

    public function save(): void
    {
        DB::beginTransaction();

        try {
            // 1. Save Physical Exam
            $physicalExamData = [
                'visit_id' => $this->visitId,
                'patient_id' => $this->visit->patient_id,
                'examined_by' => auth()->id(),
                'exam_date' => $this->examDate,
                'hours_after_birth' => $this->hoursAfterBirth,
                'apgar_birth' => $this->apgarBirth,
                'apgar_5min' => $this->apgar5Min,
                'apgar_10min' => $this->apgar10Min,
                'general_condition' => $this->generalCondition,
                'head_circumference_cm' => $this->headCircumferenceCm,
                'chest_circumference_cm' => $this->chestCircumferenceCm,
                'abdominal_circumference_cm' => $this->abdominalCircumferenceCm,
                'birth_weight_g' => $this->birthWeightG,
                'birth_length_cm' => $this->birthLengthCm,
                'general_muscular_tonus' => $this->generalMuscularTonus,
                'skin_color' => $this->skinColor,
                'skin_turgor' => $this->skinTurgor,
                'skin_rash' => $this->skinRash,
                'skin_desquamation' => $this->skinDesquamation,
                'head_molding' => $this->headMolding,
                'head_scalp' => $this->headScalp,
                'head_fontanelles' => $this->headFontanelles,
                'head_suture' => $this->headSuture,
                'face' => $this->face,
                'eyes_conjunctiva' => $this->eyesConjunctiva,
                'eyes_sclera' => $this->eyesSclera,
                'eyes_pupils' => $this->eyesPupils,
                'eyes_discharge' => $this->eyesDischarge,
                'ears' => $this->ears,
                'nose' => $this->nose,
                'mouth_lip' => $this->mouthLip,
                'mouth_tongue' => $this->mouthTongue,
                'mouth_palate' => $this->mouthPalate,
                'neck_sternocleidomastoid' => $this->neckSternocleidomastoid,
                'neck_fistula' => $this->neckFistula,
                'chest_shape' => $this->chestShape,
                'chest_respiration' => $this->chestRespiration,
                'chest_clavicles' => $this->chestClavicles,
                'chest_breast' => $this->chestBreast,
                'chest_heart' => $this->chestHeart,
                'chest_lungs' => $this->chestLungs,
                'abdomen' => $this->abdomen,
                'spleen' => $this->spleen,
                'kidneys' => $this->kidneys,
                'liver' => $this->liver,
                'umbilical_cord' => $this->umbilicalCord,
                'inguinal_hernia' => $this->inguinalHernia,
                'diastasis_recti' => $this->diastasisRecti,
                'genitals_male' => $this->genitalsMale,
                'genitals_female' => $this->genitalsFemale,
                'extremities' => $this->extremities,
                'clubfoot' => $this->clubfoot,
                'hip_dislocation' => $this->hipDislocation,
                'femoral_pulse' => $this->femoralPulse,
                'spine' => $this->spine,
                'anus' => $this->anus,
                'impression' => $this->impression,
                'pediatrician_name' => auth()->user()->name,
            ];

            if ($this->physicalExam) {
                $this->physicalExam->update($physicalExamData);
            } else {
                $this->physicalExam = NicuPhysicalExam::create($physicalExamData);
            }

            // 2. Update NicuAdmission with measurements
            if ($this->nicuAdmission) {
                $this->nicuAdmission->update([
                    'birth_weight_grams' => $this->birthWeightG,
                    'birth_length_cm' => $this->birthLengthCm,
                    'head_circumference_cm' => $this->headCircumferenceCm,
                    'chest_circumference_cm' => $this->chestCircumferenceCm,
                    'abdominal_circumference_cm' => $this->abdominalCircumferenceCm,
                    'apgar_1min' => $this->apgarBirth,
                    'apgar_5min' => $this->apgar5Min,
                    'apgar_10min' => $this->apgar10Min,
                ]);
            }

            // 3. Save Doctor's Orders (only if provided)
            if (trim($this->orderText) !== '') {
                $lines = collect(explode("\n", $this->orderText))
                    ->map(fn($line) => trim($line))
                    ->filter(fn($line) => $line !== '')
                    ->values();

                foreach ($lines as $text) {
                    $exists = DoctorsOrder::where('visit_id', $this->visitId)
                        ->where('order_text', $text)
                        ->exists();

                    if (!$exists) {
                        DoctorsOrder::create([
                            'visit_id' => $this->visitId,
                            'doctor_id' => auth()->id(),
                            'order_text' => $text,
                            'status' => DoctorsOrder::STATUS_PENDING,
                            'order_date' => now(),
                            'is_completed' => false,
                        ]);
                    }
                }
            }

            // 4. Update Visit Status if admitting (AFTER assessment)
            if ($this->admitToNICU && !$this->isAdmitted) {
                $this->visit->update([
                    'status' => 'admitted',
                    'admitted_service' => 'NICU',
                    'admitting_diagnosis' => $this->impression,
                    'doctor_admitted_at' => now(),
                    'clerk_admitted_at' => null,
                ]);

                // Notify clerks
                $clerks = User::where('is_active', true)
                    ->whereHas('roles', fn($q) => $q->whereIn('name', ['clerk', 'clerk-opd', 'clerk-er']))
                    ->get();

                foreach ($clerks as $clerk) {
                    Notification::make()
                        ->title('New NICU Admission - ' . $this->visit->patient->display_name)
                        ->body($this->impression ?? 'No diagnosis entered')
                        ->icon('heroicon-o-baby')
                        ->iconColor('success')
                        ->sendToDatabase($clerk);
                }

                $this->isAdmitted = true;
            }

            DB::commit();

            Notification::make()
                ->title($this->admitToNICU && !$this->isAdmitted ? '✓ Baby Admitted to NICU' : 'Assessment Saved')
                ->success()
                ->send();

            $this->redirect('/doctor/nicu-babies');

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error Saving Assessment')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}