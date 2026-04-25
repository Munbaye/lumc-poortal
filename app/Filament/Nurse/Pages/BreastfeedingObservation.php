<?php

namespace App\Filament\Nurse\Pages;

use App\Models\Visit;
use App\Models\NicuBreastfeedingObservation;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BreastfeedingObservation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-face-smile';
    protected static string $view = 'filament.nurse.pages.breastfeeding-observation';
    protected static ?string $title = 'Breastfeeding Observation';
    protected static bool $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit $visit = null;
    public ?NicuBreastfeedingObservation $observation = null;
    public ?int $observationId = null;

    // Baby age calculation
    public ?int $babyAgeDays = null;
    public ?int $babyAgeHours = null;
    public ?string $currentDate = null;

    // ── General - Mother (Going Well) — defaults TRUE ──────────────────────
    public bool $generalMotherHealthy = true;
    public bool $generalMotherRelaxed = true;
    public bool $generalMotherBonding = true;

    // ── General - Mother (Difficulty) ─────────────────────────────────────
    public bool $generalMotherIll = false;
    public bool $generalMotherTense = false;
    public bool $generalMotherNoEyeContact = false;

    // ── General - Baby (Going Well) — defaults TRUE ────────────────────────
    public bool $generalBabyHealthy = true;
    public bool $generalBabyCalm = true;
    public bool $generalBabyRoots = true;

    // ── General - Baby (Difficulty) ────────────────────────────────────────
    public bool $generalBabySleepyIll = false;
    public bool $generalBabyRestlessCrying = false;
    public bool $generalBabyNoRoot = false;

    // ── Breast (Going Well) — defaults TRUE ────────────────────────────────
    public bool $breastHealthy = true;
    public bool $breastNoPain = true;
    public bool $breastFingersAway = true;

    // ── Breast (Difficulty) ────────────────────────────────────────────────
    public bool $breastRedSwollenSore = false;
    public bool $breastPainful = false;
    public bool $breastFingersOnAreola = false;

    // ── Baby's Position (Going Well) — defaults TRUE ───────────────────────
    public bool $positionHeadBodyLine = true;
    public bool $positionHeldClose = true;
    public bool $positionBodySupported = true;
    public bool $positionNoseToNipple = true;

    // ── Baby's Position (Difficulty) ──────────────────────────────────────
    public bool $positionNeckTwisted = false;
    public bool $positionNotHeldClose = false;
    public bool $positionHeadNeckOnly = false;
    public bool $positionChinToNipple = false;

    // ── Baby's Attachment (Going Well) — defaults TRUE ─────────────────────
    public bool $attachmentMoreAreolaAbove = true;
    public bool $attachmentMouthOpenWide = true;
    public bool $attachmentLipTurnedOut = true;
    public bool $attachmentChinTouchesBreast = true;

    // ── Baby's Attachment (Difficulty) ────────────────────────────────────
    public bool $attachmentMoreAreolaBelow = false;
    public bool $attachmentMouthNotWide = false;
    public bool $attachmentLipsForwardTurnedIn = false;
    public bool $attachmentChinNotTouching = false;

    // ── Suckling (Going Well) — defaults TRUE ──────────────────────────────
    public bool $sucklingSlowDeepPauses = true;
    public bool $sucklingCheeksRound = true;
    public bool $sucklingBabyReleases = true;
    public bool $sucklingOxytocinReflex = true;

    // ── Suckling (Difficulty) ─────────────────────────────────────────────
    public bool $sucklingRapidShallow = false;
    public bool $sucklingCheeksPulledIn = false;
    public bool $sucklingMotherTakesOff = false;
    public bool $sucklingNoOxytocinReflex = false;

    // ── Mutual-exclusivity map ─────────────────────────────────────────────
    // difficulty_field => going_well_field_to_uncheck
    protected array $mutualExclusivity = [
        // General - Mother
        'generalMotherIll'         => 'generalMotherHealthy',
        'generalMotherTense'       => 'generalMotherRelaxed',
        'generalMotherNoEyeContact' => 'generalMotherBonding',

        // General - Baby
        'generalBabySleepyIll'     => 'generalBabyHealthy',
        'generalBabyRestlessCrying' => 'generalBabyCalm',
        'generalBabyNoRoot'        => 'generalBabyRoots',

        // Breast
        'breastRedSwollenSore'     => 'breastHealthy',
        'breastPainful'            => 'breastNoPain',
        'breastFingersOnAreola'    => 'breastFingersAway',

        // Position
        'positionNeckTwisted'      => 'positionHeadBodyLine',
        'positionNotHeldClose'     => 'positionHeldClose',
        'positionHeadNeckOnly'     => 'positionBodySupported',
        'positionChinToNipple'     => 'positionNoseToNipple',

        // Attachment
        'attachmentMoreAreolaBelow'      => 'attachmentMoreAreolaAbove',
        'attachmentMouthNotWide'         => 'attachmentMouthOpenWide',
        'attachmentLipsForwardTurnedIn'  => 'attachmentLipTurnedOut',
        'attachmentChinNotTouching'      => 'attachmentChinTouchesBreast',

        // Suckling
        'sucklingRapidShallow'     => 'sucklingSlowDeepPauses',
        'sucklingCheeksPulledIn'   => 'sucklingCheeksRound',
        'sucklingMotherTakesOff'   => 'sucklingBabyReleases',
        'sucklingNoOxytocinReflex' => 'sucklingOxytocinReflex',
    ];

    // ── Livewire hook: enforce mutual exclusivity ──────────────────────────
    public function updated(string $property, mixed $value): void
    {
        // If a Difficulty checkbox was just turned ON, uncheck its Going Well pair
        if (isset($this->mutualExclusivity[$property]) && $value === true) {
            $goingWellField = $this->mutualExclusivity[$property];
            $this->{$goingWellField} = false;
        }

        // Reverse: if a Going Well checkbox was just turned ON, uncheck its Difficulty pair
        $reversedMap = array_flip($this->mutualExclusivity);
        if (isset($reversedMap[$property]) && $value === true) {
            $difficultyField = $reversedMap[$property];
            $this->{$difficultyField} = false;
        }
    }

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect('/nurse/nicu-babies');
            return;
        }

        $this->visit = Visit::with(['patient', 'nicuAdmission'])->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect('/nurse/nicu-babies');
            return;
        }

        // Only NICU babies should have breastfeeding observations
        if ($this->visit->visit_type !== 'NICU') {
            Notification::make()
                ->title('Breastfeeding observations are only available for NICU patients.')
                ->warning()
                ->send();
            $this->redirect('/nurse/nicu-babies');
            return;
        }

        $this->currentDate = now()->format('F d, Y');

        // Calculate baby's age from birth_datetime
        $birth = $this->visit->nicuAdmission?->date_time_of_birth ?? $this->visit->patient?->birth_datetime;
        if ($birth) {
            $now = now();
            $birthDate = Carbon::parse($birth);
            $this->babyAgeDays = (int) $birthDate->diffInDays($now);
            $this->babyAgeHours = (int) $birthDate->diffInHours($now);
        }
    }

    public function save(): void
    {
        DB::beginTransaction();

        try {
            $data = [
                'visit_id'   => $this->visitId,
                'patient_id' => $this->visit->patient_id,
                'observed_by' => auth()->id(),
                'observation_date' => now()->toDateString(),
                'observation_time' => now()->toTimeString(),

                // General - Mother
                'general_mother_healthy'        => $this->generalMotherHealthy,
                'general_mother_relaxed'        => $this->generalMotherRelaxed,
                'general_mother_bonding'        => $this->generalMotherBonding,
                'general_mother_ill'            => $this->generalMotherIll,
                'general_mother_tense'          => $this->generalMotherTense,
                'general_mother_no_eye_contact' => $this->generalMotherNoEyeContact,

                // General - Baby
                'general_baby_healthy'          => $this->generalBabyHealthy,
                'general_baby_calm'             => $this->generalBabyCalm,
                'general_baby_roots'            => $this->generalBabyRoots,
                'general_baby_sleepy_ill'       => $this->generalBabySleepyIll,
                'general_baby_restless_crying'  => $this->generalBabyRestlessCrying,
                'general_baby_no_root'          => $this->generalBabyNoRoot,

                // Breast
                'breast_healthy'                => $this->breastHealthy,
                'breast_no_pain'                => $this->breastNoPain,
                'breast_fingers_away'           => $this->breastFingersAway,
                'breast_red_swollen_sore'       => $this->breastRedSwollenSore,
                'breast_painful'                => $this->breastPainful,
                'breast_fingers_on_areola'      => $this->breastFingersOnAreola,

                // Position
                'position_head_body_line'       => $this->positionHeadBodyLine,
                'position_held_close'           => $this->positionHeldClose,
                'position_body_supported'       => $this->positionBodySupported,
                'position_nose_to_nipple'       => $this->positionNoseToNipple,
                'position_neck_twisted'         => $this->positionNeckTwisted,
                'position_not_held_close'       => $this->positionNotHeldClose,
                'position_head_neck_only'       => $this->positionHeadNeckOnly,
                'position_chin_to_nipple'       => $this->positionChinToNipple,

                // Attachment
                'attachment_more_areola_above'       => $this->attachmentMoreAreolaAbove,
                'attachment_mouth_open_wide'         => $this->attachmentMouthOpenWide,
                'attachment_lip_turned_out'          => $this->attachmentLipTurnedOut,
                'attachment_chin_touches_breast'     => $this->attachmentChinTouchesBreast,
                'attachment_more_areola_below'       => $this->attachmentMoreAreolaBelow,
                'attachment_mouth_not_wide'          => $this->attachmentMouthNotWide,
                'attachment_lips_forward_turned_in'  => $this->attachmentLipsForwardTurnedIn,
                'attachment_chin_not_touching'       => $this->attachmentChinNotTouching,

                // Suckling
                'suckling_slow_deep_pauses'     => $this->sucklingSlowDeepPauses,
                'suckling_cheeks_round'         => $this->sucklingCheeksRound,
                'suckling_baby_releases'        => $this->sucklingBabyReleases,
                'suckling_oxytocin_reflex'      => $this->sucklingOxytocinReflex,
                'suckling_rapid_shallow'        => $this->sucklingRapidShallow,
                'suckling_cheeks_pulled_in'     => $this->sucklingCheeksPulledIn,
                'suckling_mother_takes_off'     => $this->sucklingMotherTakesOff,
                'suckling_no_oxytocin_reflex'   => $this->sucklingNoOxytocinReflex,
            ];

            NicuBreastfeedingObservation::create($data);

            DB::commit();

            Notification::make()
                ->title('Breastfeeding Observation Saved')
                ->success()
                ->send();

            $this->redirect('/nurse/nurse-chart?visitId=' . $this->visitId . '&tab=breastfeeding');

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error Saving Observation')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}