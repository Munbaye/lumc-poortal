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
    
    // ── General - Mother (Going Well) ─────────────────────────────────────────
    public bool $generalMotherHealthy = false;
    public bool $generalMotherRelaxed = false;
    public bool $generalMotherBonding = false;
    
    // ── General - Mother (Difficulty) ─────────────────────────────────────────
    public bool $generalMotherIll = false;
    public bool $generalMotherTense = false;
    public bool $generalMotherNoEyeContact = false;
    
    // ── General - Baby (Going Well) ──────────────────────────────────────────
    public bool $generalBabyHealthy = false;
    public bool $generalBabyCalm = false;
    public bool $generalBabyRoots = false;
    
    // ── General - Baby (Difficulty) ──────────────────────────────────────────
    public bool $generalBabySleepyIll = false;
    public bool $generalBabyRestlessCrying = false;
    public bool $generalBabyNoRoot = false;
    
    // ── Breast (Going Well) ──────────────────────────────────────────────────
    public bool $breastHealthy = false;
    public bool $breastNoPain = false;
    public bool $breastFingersAway = false;
    
    // ── Breast (Difficulty) ──────────────────────────────────────────────────
    public bool $breastRedSwollenSore = false;
    public bool $breastPainful = false;
    public bool $breastFingersOnAreola = false;
    
    // ── Baby's Position (Going Well) ─────────────────────────────────────────
    public bool $positionHeadBodyLine = false;
    public bool $positionHeldClose = false;
    public bool $positionBodySupported = false;
    public bool $positionNoseToNipple = false;
    
    // ── Baby's Position (Difficulty) ─────────────────────────────────────────
    public bool $positionNeckTwisted = false;
    public bool $positionNotHeldClose = false;
    public bool $positionHeadNeckOnly = false;
    public bool $positionChinToNipple = false;
    
    // ── Baby's Attachment (Going Well) ───────────────────────────────────────
    public bool $attachmentMoreAreolaAbove = false;
    public bool $attachmentMouthOpenWide = false;
    public bool $attachmentLipTurnedOut = false;
    public bool $attachmentChinTouchesBreast = false;
    
    // ── Baby's Attachment (Difficulty) ───────────────────────────────────────
    public bool $attachmentMoreAreolaBelow = false;
    public bool $attachmentMouthNotWide = false;
    public bool $attachmentLipsForwardTurnedIn = false;
    public bool $attachmentChinNotTouching = false;
    
    // ── Suckling (Going Well) ────────────────────────────────────────────────
    public bool $sucklingSlowDeepPauses = false;
    public bool $sucklingCheeksRound = false;
    public bool $sucklingBabyReleases = false;
    public bool $sucklingOxytocinReflex = false;
    
    // ── Suckling (Difficulty) ────────────────────────────────────────────────
    public bool $sucklingRapidShallow = false;
    public bool $sucklingCheeksPulledIn = false;
    public bool $sucklingMotherTakesOff = false;
    public bool $sucklingNoOxytocinReflex = false;
    
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
                'visit_id' => $this->visitId,
                'patient_id' => $this->visit->patient_id,
                'observed_by' => auth()->id(),
                'observation_date' => now()->toDateString(),
                'observation_time' => now()->toTimeString(),
                
                // General - Mother (Going Well)
                'general_mother_healthy' => $this->generalMotherHealthy,
                'general_mother_relaxed' => $this->generalMotherRelaxed,
                'general_mother_bonding' => $this->generalMotherBonding,
                
                // General - Mother (Difficulty)
                'general_mother_ill' => $this->generalMotherIll,
                'general_mother_tense' => $this->generalMotherTense,
                'general_mother_no_eye_contact' => $this->generalMotherNoEyeContact,
                
                // General - Baby (Going Well)
                'general_baby_healthy' => $this->generalBabyHealthy,
                'general_baby_calm' => $this->generalBabyCalm,
                'general_baby_roots' => $this->generalBabyRoots,
                
                // General - Baby (Difficulty)
                'general_baby_sleepy_ill' => $this->generalBabySleepyIll,
                'general_baby_restless_crying' => $this->generalBabyRestlessCrying,
                'general_baby_no_root' => $this->generalBabyNoRoot,
                
                // Breast (Going Well)
                'breast_healthy' => $this->breastHealthy,
                'breast_no_pain' => $this->breastNoPain,
                'breast_fingers_away' => $this->breastFingersAway,
                
                // Breast (Difficulty)
                'breast_red_swollen_sore' => $this->breastRedSwollenSore,
                'breast_painful' => $breastPainful ?? false,
                'breast_fingers_on_areola' => $this->breastFingersOnAreola,
                
                // Baby's Position (Going Well)
                'position_head_body_line' => $this->positionHeadBodyLine,
                'position_held_close' => $this->positionHeldClose,
                'position_body_supported' => $this->positionBodySupported,
                'position_nose_to_nipple' => $this->positionNoseToNipple,
                
                // Baby's Position (Difficulty)
                'position_neck_twisted' => $this->positionNeckTwisted,
                'position_not_held_close' => $this->positionNotHeldClose,
                'position_head_neck_only' => $this->positionHeadNeckOnly,
                'position_chin_to_nipple' => $this->positionChinToNipple,
                
                // Baby's Attachment (Going Well)
                'attachment_more_areola_above' => $this->attachmentMoreAreolaAbove,
                'attachment_mouth_open_wide' => $this->attachmentMouthOpenWide,
                'attachment_lip_turned_out' => $this->attachmentLipTurnedOut,
                'attachment_chin_touches_breast' => $this->attachmentChinTouchesBreast,
                
                // Baby's Attachment (Difficulty)
                'attachment_more_areola_below' => $this->attachmentMoreAreolaBelow,
                'attachment_mouth_not_wide' => $this->attachmentMouthNotWide,
                'attachment_lips_forward_turned_in' => $this->attachmentLipsForwardTurnedIn,
                'attachment_chin_not_touching' => $this->attachmentChinNotTouching,
                
                // Suckling (Going Well)
                'suckling_slow_deep_pauses' => $this->sucklingSlowDeepPauses,
                'suckling_cheeks_round' => $this->sucklingCheeksRound,
                'suckling_baby_releases' => $this->sucklingBabyReleases,
                'suckling_oxytocin_reflex' => $this->sucklingOxytocinReflex,
                
                // Suckling (Difficulty)
                'suckling_rapid_shallow' => $this->sucklingRapidShallow,
                'suckling_cheeks_pulled_in' => $this->sucklingCheeksPulledIn,
                'suckling_mother_takes_off' => $this->sucklingMotherTakesOff,
                'suckling_no_oxytocin_reflex' => $this->sucklingNoOxytocinReflex,
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