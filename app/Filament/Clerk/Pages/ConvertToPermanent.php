<?php

namespace App\Filament\Clerk\Pages;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\NicuAdmission;
use App\Models\AdmissionRecord;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class ConvertToPermanent extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static string $view = 'filament.clerk.pages.convert-to-permanent';
    protected static ?string $title = 'Admission & Discharge Record';
    protected static ?string $navigationGroup = 'NICU Management';
    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?Visit         $visit         = null;
    public ?Patient       $baby          = null;
    public ?NicuAdmission $nicuAdmission = null;
    public ?AdmissionRecord $admRecord   = null;
    public ?int           $visitId       = null;

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
            'nicuAdmission',
            'admissionRecord',
            'medicalHistory.doctor',
            'erRecord',
        ])->find($this->visitId);

        if (!$this->visit) {
            Notification::make()
                ->title('Visit not found')
                ->danger()
                ->send();
            $this->redirect('/clerk/visits');
            return;
        }

        $this->baby          = $this->visit->patient;
        $this->nicuAdmission = $this->visit->nicuAdmission;
        $this->admRecord     = $this->visit->admissionRecord;

        if (!$this->baby || !$this->baby->is_provisional) {
            Notification::make()
                ->title('This is already a permanent record or invalid.')
                ->warning()
                ->send();
            $this->redirect('/clerk/visits');
        }
    }

    public function convert(): void
    {
        if (!$this->baby) {
            Notification::make()
                ->title('Error')
                ->body('No baby record found.')
                ->danger()
                ->send();
            return;
        }

        try {
            // convertToPermanent() handles its own transaction — do NOT wrap again
            $this->baby->convertToPermanent(auth()->id());

            $freshBaby = $this->baby->fresh();

            Notification::make()
                ->title('✓ Record Converted to Permanent')
                ->body("Permanent Case Number: {$freshBaby->case_no} — Please complete the Consent to Care form.")
                ->success()
                ->send();

            // Task 2: After ADR/conversion is complete, go directly to Consent to Care
            $this->redirect(route('forms.consent-to-care', ['visit' => $this->visit->id]));

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Converting Record')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // ── Helpers for blade ─────────────────────────────────────────────────────

    public function getAdmRecordSaveUrl(): string
    {
        return route('forms.adm-record.save', ['visit' => $this->visit->id]);
    }

    public function getCsrfToken(): string
    {
        return csrf_token();
    }
}