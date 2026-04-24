<?php

namespace App\Filament\Doctor\Pages;

use App\Models\Visit;
use App\Models\NicuBallardExam;
use App\Models\NicuAdmission;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BallardScore extends Page
{
    protected static ?string $navigationIcon         = 'heroicon-o-document-chart-bar';
    protected static string  $view                   = 'filament.doctor.pages.ballard-score';
    protected static ?string $title                  = 'Ballard Maturity Score';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit $visit          = null;
    public ?NicuBallardExam $ballardExam = null;
    public ?int $examNumber       = 1;

    // ── Neuromuscular Maturity ────────────────────────────────────────────────
    // Posture:         0–4
    // Square Window:   0–5
    // Arm Recoil:      0–4
    // Popliteal Angle: 0–5
    // Scarf Sign:      0–4
    // Heel to Ear:     0–5
    public ?int $nmPosture        = null;
    public ?int $nmSquareWindow   = null;
    public ?int $nmArmRecoil      = null;
    public ?int $nmPoplitealAngle = null;
    public ?int $nmScarfSign      = null;
    public ?int $nmHeelToEar      = null;

    // ── Physical Maturity ─────────────────────────────────────────────────────
    // Skin:            0–5
    // Lanugo:          0–4
    // Plantar Surface: 0–4
    // Breast:          0–5
    // Eye/Ear:         0–5
    // Genitals:        0–5
    public ?int $pmSkin           = null;
    public ?int $pmLanugo         = null;
    public ?int $pmPlantarSurface = null;
    public ?int $pmBreast         = null;
    public ?int $pmEyeEar         = null;
    public ?int $pmGenitals       = null;

    // ── Exam Metadata ─────────────────────────────────────────────────────────
    public ?string $examDatetime  = null;

    // ── Calculated ────────────────────────────────────────────────────────────
    public ?int $totalScore        = null;
    public ?int $estimatedGaWeeks  = null;

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect('/doctor');
            return;
        }

        $this->visit = Visit::with([
            'patient',
            'nicuAdmission',
            'ballardExams',
        ])->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect('/doctor');
            return;
        }

        // Check if we are creating a 2nd exam
        $isSecondExam = request()->query('exam') == '2';
        
        if ($isSecondExam) {
            // For 2nd exam, DO NOT load 1st exam data
            $this->examNumber = 2;
            // Leave all scores as null (empty form)
            // Set default exam datetime
            $birthDt = $this->visit->nicuAdmission?->date_time_of_birth;
            $this->examDatetime = $birthDt
                ? Carbon::parse($birthDt)->addHours(48)->format('Y-m-d\TH:i')
                : now()->format('Y-m-d\TH:i');
        } else {
            // For 1st exam, load existing data if available
            $firstExam = $this->visit->ballardExams->firstWhere('exam_number', 1);
            if ($firstExam) {
                $this->examNumber = $firstExam->exam_number;
                $this->ballardExam = $firstExam;
                $this->loadExamData($firstExam);
            } else {
                // New 1st exam - set default datetime
                $birthDt = $this->visit->nicuAdmission?->date_time_of_birth;
                $this->examDatetime = $birthDt
                    ? Carbon::parse($birthDt)->addHours(24)->format('Y-m-d\TH:i')
                    : now()->format('Y-m-d\TH:i');
            }
        }
    }

    protected function loadExamData(NicuBallardExam $exam): void
    {
        $this->nmPosture        = $exam->nm_posture;
        $this->nmSquareWindow   = $exam->nm_square_window;
        $this->nmArmRecoil      = $exam->nm_arm_recoil;
        $this->nmPoplitealAngle = $exam->nm_popliteal_angle;
        $this->nmScarfSign      = $exam->nm_scarf_sign;
        $this->nmHeelToEar      = $exam->nm_heel_to_ear;
        $this->pmSkin           = $exam->pm_skin;
        $this->pmLanugo         = $exam->pm_lanugo;
        $this->pmPlantarSurface = $exam->pm_plantar_surface;
        $this->pmBreast         = $exam->pm_breast;
        $this->pmEyeEar         = $exam->pm_eye_ear;
        $this->pmGenitals       = $exam->pm_genitals;
        $this->examDatetime     = $exam->exam_datetime?->format('Y-m-d\TH:i');
        $this->totalScore       = $exam->total_score;
        $this->estimatedGaWeeks = $exam->estimated_ga_weeks;

        // Recompute subtotals
        $this->calculateScore();
    }

    // ── Livewire: recalculate on any score change ────────────────────────────
    public function updated(string $property): void
    {
        $scoreFields = [
            'nmPosture', 'nmSquareWindow', 'nmArmRecoil', 'nmPoplitealAngle',
            'nmScarfSign', 'nmHeelToEar',
            'pmSkin', 'pmLanugo', 'pmPlantarSurface', 'pmBreast',
            'pmEyeEar', 'pmGenitals',
        ];

        if (in_array($property, $scoreFields)) {
            $this->calculateScore();
        }
    }

    public function calculateScore(): void
    {
        $criteria = [
            $this->nmPosture, $this->nmSquareWindow, $this->nmArmRecoil,
            $this->nmPoplitealAngle, $this->nmScarfSign, $this->nmHeelToEar,
            $this->pmSkin, $this->pmLanugo, $this->pmPlantarSurface,
            $this->pmBreast, $this->pmEyeEar, $this->pmGenitals,
        ];

        $filled = array_filter($criteria, fn ($v) => $v !== null);

        if (count($filled) === 0) {
            return;
        }

        $runningTotal = (int) array_sum(array_filter($criteria, fn ($v) => $v !== null));

        if (count($filled) === 12) {
            $this->totalScore       = $runningTotal;
            $this->estimatedGaWeeks = $this->lookupGa($runningTotal);
        } else {
            if (!$this->ballardExam) {
                $this->totalScore       = null;
                $this->estimatedGaWeeks = null;
            }
        }
    }

    /**
     * Look up GA weeks from Ballard score using the official maturity rating table.
     * Interpolates linearly between anchor points.
     */
    protected function lookupGa(int $score): ?int
    {
        $lookup = NicuBallardExam::$gaLookup;
        $keys   = array_keys($lookup);

        // Clamp to table bounds
        $minKey = min($keys);
        $maxKey = max($keys);

        if ($score <= $minKey) return $lookup[$minKey];
        if ($score >= $maxKey) return $lookup[$maxKey];

        // Find closest key
        $closestKey = collect($keys)
            ->sortBy(fn ($key) => abs($key - $score))
            ->first();

        return $lookup[$closestKey];
    }

    public function save(): void
    {
        $criteria = [
            'nmPosture', 'nmSquareWindow', 'nmArmRecoil', 'nmPoplitealAngle',
            'nmScarfSign', 'nmHeelToEar',
            'pmSkin', 'pmLanugo', 'pmPlantarSurface', 'pmBreast',
            'pmEyeEar', 'pmGenitals',
        ];

        foreach ($criteria as $field) {
            if ($this->{$field} === null) {
                Notification::make()
                    ->title('Please fill in all 12 criteria before saving.')
                    ->warning()
                    ->send();
                return;
            }
        }

        if (!$this->examDatetime) {
            Notification::make()->title('Please enter exam date and time.')->warning()->send();
            return;
        }

        $birthDateTime  = $this->visit->nicuAdmission?->date_time_of_birth;
        $ageAtExamHours = null;
        if ($birthDateTime) {
            $ageAtExamHours = (int) Carbon::parse($birthDateTime)
                ->diffInHours(Carbon::parse($this->examDatetime));
        }

        $allValues  = [
            $this->nmPosture, $this->nmSquareWindow, $this->nmArmRecoil,
            $this->nmPoplitealAngle, $this->nmScarfSign, $this->nmHeelToEar,
            $this->pmSkin, $this->pmLanugo, $this->pmPlantarSurface,
            $this->pmBreast, $this->pmEyeEar, $this->pmGenitals,
        ];
        $finalTotal = (int) array_sum($allValues);
        $finalGa    = $this->lookupGa($finalTotal);

        DB::beginTransaction();

        try {
            $data = [
                'visit_id'           => $this->visitId,
                'patient_id'         => $this->visit->patient_id,
                'examiner_id'        => auth()->id(),
                'exam_number'        => $this->examNumber,
                'exam_datetime'      => $this->examDatetime,
                'age_at_exam_hours'  => $ageAtExamHours,
                'nm_posture'         => $this->nmPosture,
                'nm_square_window'   => $this->nmSquareWindow,
                'nm_arm_recoil'      => $this->nmArmRecoil,
                'nm_popliteal_angle' => $this->nmPoplitealAngle,
                'nm_scarf_sign'      => $this->nmScarfSign,
                'nm_heel_to_ear'     => $this->nmHeelToEar,
                'pm_skin'            => $this->pmSkin,
                'pm_lanugo'          => $this->pmLanugo,
                'pm_plantar_surface' => $this->pmPlantarSurface,
                'pm_breast'          => $this->pmBreast,
                'pm_eye_ear'         => $this->pmEyeEar,
                'pm_genitals'        => $this->pmGenitals,
                'total_score'        => $finalTotal,
                'estimated_ga_weeks' => $finalGa,
            ];

            if ($this->ballardExam) {
                $this->ballardExam->update($data);
                $message = 'Ballard score updated successfully.';
            } else {
                $this->ballardExam = NicuBallardExam::create($data);
                $message = 'Ballard score saved. Estimated GA: ' . ($finalGa ?? '?') . ' weeks.';
            }

            if ($finalGa) {
                $nicuAdmission = NicuAdmission::where('visit_id', $this->visitId)->first();
                if ($nicuAdmission) {
                    $nicuAdmission->update([
                        'ga_by_ballard_weeks'    => $finalGa,
                        'newborn_classification' => $this->classifyNewborn($finalGa),
                    ]);
                }
            }

            $this->totalScore       = $finalTotal;
            $this->estimatedGaWeeks = $finalGa;

            DB::commit();

            Notification::make()
                ->title('Ballard Score Saved')
                ->body($message)
                ->success()
                ->send();

            // Redirect back to Patient Chart with Ballard tab active
            $this->redirect('/doctor/patient-chart?visitId=' . $this->visitId . '&tab=ballard');

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error saving Ballard score')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // ── Computed subtotals ────────────────────────────────────────────────────

    public function nmSubtotal(): int
    {
        return (int) (
            ($this->nmPosture ?? 0) + ($this->nmSquareWindow ?? 0) +
            ($this->nmArmRecoil ?? 0) + ($this->nmPoplitealAngle ?? 0) +
            ($this->nmScarfSign ?? 0) + ($this->nmHeelToEar ?? 0)
        );
    }

    public function pmSubtotal(): int
    {
        return (int) (
            ($this->pmSkin ?? 0) + ($this->pmLanugo ?? 0) +
            ($this->pmPlantarSurface ?? 0) + ($this->pmBreast ?? 0) +
            ($this->pmEyeEar ?? 0) + ($this->pmGenitals ?? 0)
        );
    }

    public function getNmRanges(): array
    {
        return NicuBallardExam::$nmRanges;
    }

    public function getPmRanges(): array
    {
        return NicuBallardExam::$pmRanges;
    }

    public function getNmDescriptions(): array
    {
        return NicuBallardExam::$nmDescriptions;
    }

    public function getPmDescriptions(): array
    {
        return NicuBallardExam::$pmDescriptions;
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function classifyNewborn(int $gaWeeks): string
    {
        if ($gaWeeks < 34) return 'Very Preterm';
        if ($gaWeeks < 37) return 'Preterm';
        if ($gaWeeks > 42) return 'Post-term';
        return 'Term AGA';
    }
}