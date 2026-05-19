<?php

namespace App\Filament\Clerk\Pages;

use App\Models\Visit;
use App\Models\Patient;
use App\Models\ActivityLog;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Schema;

/**
 * ER Triage Queue — Clerk side
 *
 * Registration logic based on triage category:
 *
 * UNKNOWN patient        → 1-click register (no form)
 * RED / ORANGE (critical)→ 1-click register (no form), fill demographics later
 * YELLOW / GREEN         → small modal (address, civil status, payment + optional PhilHealth)
 *
 * PhilHealth is always optional — can be filled later.
 */
class ErTriageQueue extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'ER Triage Queue';
    protected static ?string $title           = 'ER Triage Queue';
    protected static ?string $navigationGroup = 'Patient Management';
    protected static ?int    $navigationSort  = 0;
    protected static string  $view            = 'filament.clerk.pages.er-triage-queue';

    public string $search = '';

    // ── Inline registration modal ─────────────────────────────────────────────
    public bool   $showRegisterModal = false;
    public ?int   $registerVisitId   = null;

    public array $regData = [
        'family_name'      => '',
        'first_name'       => '',
        'middle_name'      => '',
        'sex'              => '',
        'birthday'         => null,
        'age'              => null,
        'contact_number'   => '',
        'address'          => '',
        'civil_status'     => null,
        'occupation'       => '',
        'payment_class'    => null,
        'philhealth_id'    => '',      // optional
        'philhealth_type'  => null,    // optional
        'chief_complaint'  => '',
    ];

    // ── Navigation badge ──────────────────────────────────────────────────────
    public static function getNavigationBadge(): ?string
    {
        $count = self::baseQuery()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string { return 'danger'; }

    // ── Queue query ───────────────────────────────────────────────────────────
    public function getTriageVisitsProperty()
    {
        return self::baseQuery()
            ->with(['patient', 'latestVitals', 'triageNurse'])
            ->when($this->search, function ($q) {
                $s = '%' . $this->search . '%';
                $q->whereHas('patient', fn($p) =>
                    $p->where('family_name', 'like', $s)
                      ->orWhere('first_name', 'like', $s)
                );
            })
            ->orderBy('registered_at', 'asc')
            ->get();
    }

    private static function baseQuery()
    {
        $migrated = Schema::hasColumn('visits', 'triage_nurse_id');
        return Visit::where('visit_type', 'ER')
            ->where(function ($q) use ($migrated) {
                if ($migrated) {
                    $q->where('status', 'triage');
                } else {
                    $q->whereNull('clerk_id')
                      ->whereIn('status', ['registered', 'vitals_done']);
                }
            });
    }

    /**
     * Determine if a visit needs a modal or can be 1-click registered.
     * Critical categories (red/orange) + unknown = 1-click.
     * Non-critical (yellow/green) + known = modal for demographics.
     */
    public static function needsModal(Visit $visit): bool
    {
        if ($visit->patient->is_unknown) return false;

        $category = $visit->triage_category ?? null;

        // Critical — register immediately, fill demographics later
        if (in_array($category, ['red', 'orange', null])) return false;

        // Non-critical — clerk fills missing demographics
        return true;
    }

    // ── 1-click register (unknown + critical patients) ────────────────────────
    public function quickRegister(int $visitId): void
    {
        $visit   = Visit::with('patient')->findOrFail($visitId);
        $patient = $visit->patient;

        if (!$patient->case_no) {
            $patient->update([
                'case_no'             => Patient::generateCaseNo(),
                'has_incomplete_info' => true,
            ]);
        }

        $visit->update([
            'clerk_id'      => auth()->id(),
            'status'        => 'registered',
            'registered_at' => now(),
        ]);

        $this->notifyNurses($patient, $visit);

        Notification::make()
            ->title('Patient registered — ' . $patient->case_no)
            ->body('Demographics can be completed once patient is stabilized.')
            ->success()
            ->send();
    }

    // ── Open modal (non-critical known patients) ──────────────────────────────
    public function openRegisterModal(int $visitId): void
    {
        $visit   = Visit::with('patient')->findOrFail($visitId);
        $patient = $visit->patient;

        $this->registerVisitId = $visitId;
        $this->regData = [
            'family_name'     => $patient->family_name  ?? '',
            'first_name'      => $patient->first_name   ?? '',
            'middle_name'     => $patient->middle_name  ?? '',
            'sex'             => $patient->sex           ?? '',
            'birthday'        => $patient->birthday
                                    ? \Carbon\Carbon::parse($patient->birthday)->format('Y-m-d')
                                    : null,
            'age'             => $patient->age ?? ($patient->birthday
                                    ? (int) \Carbon\Carbon::parse($patient->birthday)->diffInYears(now())
                                    : null),
            'contact_number'  => $patient->contact_number ?? '',
            'address'         => ($patient->address && !in_array($patient->address, ['For completion','Unknown']))
                                    ? $patient->address : '',
            'civil_status'    => $patient->civil_status  ?? null,
            'occupation'      => $patient->occupation    ?? '',
            'payment_class'   => null,
            'philhealth_id'   => $patient->philhealth_id ?? '',
            'philhealth_type' => $patient->philhealth_type ?? null,
            'chief_complaint' => $visit->chief_complaint ?? '',
        ];

        $this->showRegisterModal = true;
    }

    public function updatedRegDataBirthday(): void
    {
        if ($this->regData['birthday']) {
            $this->regData['age'] = (int) \Carbon\Carbon::parse($this->regData['birthday'])->diffInYears(now());
        }
    }

    public function closeModal(): void
    {
        $this->showRegisterModal = false;
        $this->registerVisitId   = null;
    }

    // ── Save modal registration ───────────────────────────────────────────────
    public function saveRegistration(): void
    {
        $this->validate([
            'regData.family_name'  => 'required|string|min:2',
            'regData.first_name'   => 'required|string|min:2',
            'regData.sex'          => 'required|in:Male,Female',
            'regData.address'      => 'required|string|min:5',
            'regData.civil_status' => 'required',
            'regData.payment_class'=> 'required',
            // PhilHealth — optional
            'regData.philhealth_id'  => 'nullable|string|max:20',
            'regData.philhealth_type'=> 'nullable|in:Government,Indigent,Private,Self-Employed',
        ], [
            'regData.family_name.required'  => 'Family name is required.',
            'regData.first_name.required'   => 'First name is required.',
            'regData.sex.required'          => 'Sex is required.',
            'regData.address.required'      => 'Address is required.',
            'regData.civil_status.required' => 'Civil status is required.',
            'regData.payment_class.required'=> 'Payment class is required.',
        ]);

        $visit   = Visit::with('patient')->findOrFail($this->registerVisitId);
        $patient = $visit->patient;

        if (!$patient->case_no) {
            $patient->update(['case_no' => Patient::generateCaseNo()]);
        }

        $patient->update([
            'family_name'         => $this->properName($this->regData['family_name']),
            'first_name'          => $this->properName($this->regData['first_name']),
            'middle_name'         => $this->regData['middle_name']
                                        ? $this->properName($this->regData['middle_name'])
                                        : null,
            'sex'                 => $this->regData['sex'],
            'birthday'            => $this->regData['birthday'] ?: null,
            'age'                 => $this->regData['age'] ?: null,
            'contact_number'      => $this->regData['contact_number'] ?: null,
            'address'             => $this->regData['address'],
            'civil_status'        => $this->regData['civil_status'],
            'occupation'          => $this->regData['occupation'] ?: null,
            'philhealth_id'       => $this->regData['philhealth_id'] ?: null,
            'philhealth_type'     => $this->regData['philhealth_type'] ?: null,
            'has_incomplete_info' => false,
        ]);

        $visit->update([
            'clerk_id'        => auth()->id(),
            'chief_complaint' => $this->regData['chief_complaint'],
            'payment_class'   => $this->regData['payment_class'],
            'status'          => 'registered',
            'registered_at'   => now(),
        ]);

        ActivityLog::record(
            action: 'er_patient_registered',
            category: ActivityLog::CAT_PATIENT,
            subject: $visit,
            subjectLabel: $patient->full_name . ' (' . $patient->case_no . ')',
            newValues: [
                'case_no'        => $patient->case_no,
                'registered_by'  => auth()->user()->full_name,
                'payment_class'  => $this->regData['payment_class'],
                'philhealth_id'  => $this->regData['philhealth_id'] ?: 'not provided',
            ],
            panel: 'clerk',
        );

        $this->notifyNurses($patient, $visit);

        Notification::make()
            ->title('Patient registered — ' . $patient->case_no)
            ->success()
            ->send();

        $this->closeModal();
    }

    // ── Shared nurse notification ─────────────────────────────────────────────
    private function notifyNurses(Patient $patient, Visit $visit): void
    {
        $nurses = User::where('is_active', true)
            ->whereHas('roles', fn($q) => $q->where('name', 'nurse'))
            ->get();

        if ($nurses->isEmpty()) return;

        Notification::make()
            ->title('Patient registered — assign doctor')
            ->body(
                $patient->full_name . ' (' . $patient->case_no . ') ' .
                'has been registered. Please assign a doctor in ER Triage → Assign Doctor tab.'
            )
            ->icon('heroicon-o-user-plus')
            ->iconColor('success')
            ->actions([
                \Filament\Notifications\Actions\Action::make('assign')
                    ->label('Assign Doctor')
                    ->url('/nurse/er-triage')
                    ->markAsRead(),
            ])
            ->sendToDatabase($nurses);
    }

    private function properName(string $name): string
    {
        return implode(' ', array_map(
            fn($w) => ucfirst(strtolower($w)),
            explode(' ', trim($name))
        ));
    }
}