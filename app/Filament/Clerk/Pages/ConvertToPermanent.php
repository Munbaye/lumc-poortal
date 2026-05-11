<?php

namespace App\Filament\Clerk\Pages;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\AdmissionRecord;
use App\Models\NicuAdmission;
use App\Models\ObRecord;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

/**
 * ConvertToPermanent — Generic clerk page to:
 *   1. Display & save the embedded ADM-001 Admission & Discharge Record form.
 *   2. Convert a provisional patient to a permanent record.
 *
 * Works for ALL visit types (NICU, OB, etc.) — no hardcoded type assumptions.
 */
class ConvertToPermanent extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-check';
    protected static string  $view            = 'filament.clerk.pages.convert-to-permanent';
    protected static ?string $title           = 'Admission & Discharge Record';
    protected static ?string $navigationGroup = 'Registration';
    protected static ?int    $navigationSort  = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?Visit           $visit           = null;
    public ?Patient         $baby            = null;   // generic name kept for blade compatibility
    public ?AdmissionRecord $admRecord       = null;
    public ?NicuAdmission   $nicuAdmission   = null;
    public ?ObRecord        $obRecord        = null;
    public ?int             $visitId         = null;

    public function mount(?int $visitId = null): void
    {
        $this->visitId = $visitId ?? request()->query('visitId');

        if (!$this->visitId) {
            Notification::make()
                ->title('No visit selected')
                ->body('Please select a provisional patient to convert.')
                ->warning()
                ->send();
            $this->redirect('/clerk/visits?tab=provisional');
            return;
        }

        $this->visit = Visit::with([
            'patient',
            'admissionRecord',
            'medicalHistory.doctor',
            'obRecord',       // loaded if OB, null otherwise — no harm done
            'nicuAdmission',  // loaded if NICU, null otherwise
        ])->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found')->danger()->send();
            $this->redirect('/clerk/visits');
            return;
        }

        $this->baby            = $this->visit->patient;
        $this->admRecord       = $this->visit->admissionRecord;
        $this->nicuAdmission   = $this->visit->nicuAdmission;
        $this->obRecord        = $this->visit->obRecord;

        if (!$this->baby || !$this->baby->is_provisional) {
            Notification::make()
                ->title('This is already a permanent record or invalid.')
                ->warning()
                ->send();
            $this->redirect('/clerk/visits');
        }
    }

    /**
     * Convert the provisional patient to a permanent record.
     * No type-specific logic — Patient::convertToPermanent() handles everything.
     * After conversion, redirect to the Consent to Care form.
     */
    public function convert(): void
    {
        if (!$this->baby) {
            Notification::make()->title('Error')->body('No patient record found.')->danger()->send();
            return;
        }

        try {
            $this->baby->convertToPermanent(auth()->id());

            // Mark admission as fully processed by clerk
            $this->visit->update([
                'clerk_admitted_at' => now(),
                'status' => 'admitted', // ensure it's admitted
            ]);

            $freshPatient = $this->baby->fresh();

            Notification::make()
                ->title('Record Converted to Permanent')
                ->icon('heroicon-o-check-circle')
                ->body("Permanent Case Number: {$freshPatient->case_no}")
                ->success()
                ->send();

            // Redirect to Consent to Care form (same as original NICU flow)
            $this->redirect(route('forms.consent-to-care', ['visit' => $this->visit->id]));

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Converting Record')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // ── Helpers used by blade ─────────────────────────────────────────────────

    public function getAdmRecordSaveUrl(): string
    {
        return route('forms.adm-record.save', ['visit' => $this->visit->id]);
    }

    public function getCsrfToken(): string
    {
        return csrf_token();
    }
}