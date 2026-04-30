<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class NicuBallardExam extends Model
{
    protected $table = 'nicu_ballard_exams';

    protected $fillable = [
        'visit_id', 'patient_id', 'examiner_id',
        'exam_number', 'exam_datetime', 'age_at_exam_hours',
        'nm_posture', 'nm_square_window', 'nm_arm_recoil',
        'nm_popliteal_angle', 'nm_scarf_sign', 'nm_heel_to_ear',
        'pm_skin', 'pm_lanugo', 'pm_plantar_surface',
        'pm_breast', 'pm_eye_ear', 'pm_genitals',
        'total_score', 'estimated_ga_weeks',
    ];

    protected $casts = [
        'exam_datetime' => 'datetime',
    ];

    /**
     * Ballard Score → Gestational Age Lookup Table
     * Based on Ballard JL et al., J Pediatrics 1991
     * Matches the MATURITY RATING table printed on the official form.
     *
     *  Score | Weeks GA
     * -------+---------
     *    10  |   28
     *    15  |   30
     *    20  |   32
     *    25  |   34
     *    30  |   36
     *    35  |   38
     *    40  |   40
     *    45  |   42
     *    50  |   44
     *
     * Values between anchor points are interpolated to the nearest anchor.
     */
    public static $gaLookup = [
        10 => 28,
        15 => 30,
        20 => 32,
        25 => 34,
        30 => 36,
        35 => 38,
        40 => 40,
        45 => 42,
        50 => 44,
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Score ranges per criterion
    // ──────────────────────────────────────────────────────────────────────────
    //
    //  Neuromuscular Maturity
    //  ┌──────────────────────┬────────┐
    //  │ Posture              │ 0 – 4  │
    //  │ Square Window (wrist)│ 0 – 5  │
    //  │ Arm Recoil           │ 0 – 4  │
    //  │ Popliteal Angle      │ 0 – 5  │
    //  │ Scarf Sign           │ 0 – 4  │
    //  │ Heel to Ear          │ 0 – 5  │
    //  └──────────────────────┴────────┘
    //  Max NM subtotal = 4+5+4+5+4+5 = 27
    //
    //  Physical Maturity
    //  ┌──────────────────────┬────────┐
    //  │ Skin                 │ 0 – 5  │
    //  │ Lanugo               │ 0 – 4  │
    //  │ Plantar Surface      │ 0 – 4  │
    //  │ Breast               │ 0 – 5  │
    //  │ Eye / Ear            │ 0 – 5  │
    //  │ Genitals             │ 0 – 5  │
    //  └──────────────────────┴────────┘
    //  Max PM subtotal = 5+4+4+5+5+5 = 28
    //
    //  Total max = 55  (maps to ≈44 wks GA)

    // ──────────────────────────────────────────────────────────────────────────
    // Neuromuscular criterion score ranges (for blade iteration)
    // ──────────────────────────────────────────────────────────────────────────
    public static $nmRanges = [
        'posture'         => [0, 1, 2, 3, 4],
        'square_window'   => [0, 1, 2, 3, 4, 5],
        'arm_recoil'      => [0, 1, 2, 3, 4],
        'popliteal_angle' => [0, 1, 2, 3, 4, 5],
        'scarf_sign'      => [0, 1, 2, 3, 4],
        'heel_to_ear'     => [0, 1, 2, 3, 4, 5],
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Physical criterion score ranges
    // ──────────────────────────────────────────────────────────────────────────
    public static $pmRanges = [
        'skin'            => [0, 1, 2, 3, 4, 5],
        'lanugo'          => [0, 1, 2, 3, 4],
        'plantar_surface' => [0, 1, 2, 3, 4],
        'breast'          => [0, 1, 2, 3, 4, 5],
        'eye_ear'         => [0, 1, 2, 3, 4, 5],
        'genitals'        => [0, 1, 2, 3, 4, 5],
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Neuromuscular descriptions — one line per score value
    // ──────────────────────────────────────────────────────────────────────────
    public static $nmDescriptions = [
        'posture' => [
            0 => 'All extremities extended, hips flat',
            1 => 'Slight flexion of hips and knees; arms extended',
            2 => 'Moderate flexion of hips and knees; arms extended',
            3 => 'Brisk flexion of hips and knees; arms slightly flexed',
            4 => 'Full flexion of all four extremities',
        ],
        'square_window' => [
            0 => '> 90°',
            1 => '90°',
            2 => '60°',
            3 => '45°',
            4 => '30°',
            5 => '0°',
        ],
        'arm_recoil' => [
            0 => '180° – no recoil',
            1 => '140°–180° – slow recoil',
            2 => '110°–140° – brisk recoil',
            3 => '90°–110° – very brisk recoil',
            4 => '< 90° – extremely brisk',
        ],
        'popliteal_angle' => [
            0 => '180°',
            1 => '160°',
            2 => '140°',
            3 => '120°',
            4 => '100°',
            5 => '90°',
        ],
        'scarf_sign' => [
            0 => 'Elbow beyond opposite axillary line',
            1 => 'Elbow to opposite anterior axillary line',
            2 => 'Elbow at midsternal line',
            3 => 'Elbow does not reach midsternal line',
            4 => 'Elbow does not cross anterior axillary line of same side',
        ],
        'heel_to_ear' => [
            0 => '90° – heel easily to ear',
            1 => '≈80°',
            2 => '≈70°',
            3 => '≈60°',
            4 => '≈50°',
            5 => '≈40° – significant resistance',
        ],
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Physical maturity descriptions
    // ──────────────────────────────────────────────────────────────────────────
    public static $pmDescriptions = [
        'skin' => [
            0 => 'Gelatinous, red, translucent',
            1 => 'Smooth, pink, visible veins',
            2 => 'Superficial peeling &/or rash; few veins',
            3 => 'Cracking, pale areas; rare veins',
            4 => 'Parchment, deep cracking; no vessels',
            5 => 'Leathery, cracked, wrinkled',
        ],
        'lanugo' => [
            0 => 'None (sparse)',
            1 => 'Abundant',
            2 => 'Thinning',
            3 => 'Bald areas',
            4 => 'Mostly bald',
        ],
        'plantar_surface' => [
            0 => '>50 mm; no crease',
            1 => 'Faint red marks',
            2 => 'Anterior transverse crease only',
            3 => 'Creases on anterior 2/3',
            4 => 'Creases over entire sole',
        ],
        'breast' => [
            0 => 'Imperceptible',
            1 => 'Barely perceptible',
            2 => 'Flat areola; no bud',
            3 => 'Stippled areola; 1–2 mm bud',
            4 => 'Raised areola; 3–4 mm bud',
            5 => 'Full areola; 5–10 mm bud',
        ],
        'eye_ear' => [
            0 => 'Lids fused loosely (–1)',
            1 => 'Lids open; pinna flat, stays folded',
            2 => 'Sl. curved pinna; soft, slow recoil',
            3 => 'Well-curved pinna; soft but ready recoil',
            4 => 'Formed & firm; instant recoil',
            5 => 'Thick cartilage; ear stiff',
        ],
        'genitals_male' => [
            0 => 'Scrotum flat, smooth',
            1 => 'Scrotum empty; faint rugae',
            2 => 'Testes in upper canal; rare rugae',
            3 => 'Testes descending; few rugae',
            4 => 'Testes down; good rugae',
            5 => 'Testes pendulous; deep rugae',
        ],
        'genitals_female' => [
            0 => 'Clitoris prominent; labia flat',
            1 => 'Clitoris prominent; small labia minora',
            2 => 'Clitoris prominent; enlarging minora',
            3 => 'Majora & minora equally prominent',
            4 => 'Majora large; minora small',
            5 => 'Majora covers clitoris & minora',
        ],
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Boot: auto-calculate totals before saving
    // ──────────────────────────────────────────────────────────────────────────
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($exam) {
            $fields = [
                'nm_posture', 'nm_square_window', 'nm_arm_recoil',
                'nm_popliteal_angle', 'nm_scarf_sign', 'nm_heel_to_ear',
                'pm_skin', 'pm_lanugo', 'pm_plantar_surface',
                'pm_breast', 'pm_eye_ear', 'pm_genitals',
            ];

            $total = 0;
            $filledCount = 0;

            foreach ($fields as $field) {
                if ($exam->$field !== null) {
                    $total += (int) $exam->$field;
                    $filledCount++;
                }
            }

            if ($filledCount >= 12) {
                $exam->total_score = $total;

                $closestKey = collect(array_keys(self::$gaLookup))
                    ->sortBy(fn ($key) => abs($key - $total))
                    ->first();

                $exam->estimated_ga_weeks = self::$gaLookup[$closestKey] ?? null;
            }
        });
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────────────────────────────────

    public function getClassificationAttribute(): string
    {
        $ga = $this->estimated_ga_weeks;
        if (!$ga) return 'Not determined';
        if ($ga < 34) return 'Very Preterm (<34 weeks)';
        if ($ga < 37) return 'Preterm (34–36 weeks)';
        if ($ga <= 42) return 'Term (37–42 weeks)';
        return 'Post-term (>42 weeks)';
    }

    public function getNmSubtotalAttribute(): int
    {
        return (int) (
            ($this->nm_posture ?? 0) + ($this->nm_square_window ?? 0) +
            ($this->nm_arm_recoil ?? 0) + ($this->nm_popliteal_angle ?? 0) +
            ($this->nm_scarf_sign ?? 0) + ($this->nm_heel_to_ear ?? 0)
        );
    }

    public function getPmSubtotalAttribute(): int
    {
        return (int) (
            ($this->pm_skin ?? 0) + ($this->pm_lanugo ?? 0) +
            ($this->pm_plantar_surface ?? 0) + ($this->pm_breast ?? 0) +
            ($this->pm_eye_ear ?? 0) + ($this->pm_genitals ?? 0)
        );
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function examiner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'examiner_id');
    }
}