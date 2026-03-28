<?php

namespace App\Filament\Clerk\Resources\VisitResource\Pages;

use App\Filament\Clerk\Resources\VisitResource;
use App\Models\Visit;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * ViewVisit — Clerk visit detail page.
 *
 * Extends ViewRecord so Filament handles the route-model binding automatically.
 * resolveRecord() eager-loads all needed relations.
 *
 * View: resources/views/filament/clerk/pages/view-visit.blade.php
 */
class ViewVisit extends ViewRecord
{
    protected static string $resource = VisitResource::class;
    protected static string $view     = 'filament.clerk.pages.view-visit';

    protected function resolveRecord(int|string $key): Model
    {
        return Visit::with([
            'patient',
            'medicalHistory.doctor',
            'erRecord',
            'admissionRecord',
            'consentRecord',   // eager-load consent so hasConsentRecord() is free
        ])->findOrFail($key);
    }

    public function getTitle(): string
    {
        return 'Visit — ' . ($this->record->patient?->case_no ?? $this->record->id);
    }

    // ── Existence checks ──────────────────────────────────────────────────────

    public function hasErRecord(): bool
    {
        return $this->record->erRecord !== null;
    }

    public function hasAdmRecord(): bool
    {
        return $this->record->admissionRecord !== null;
    }

    public function hasConsentRecord(): bool
    {
        return $this->record->consentRecord !== null;
    }

    // ── URLs ─────────────────────────────────────────────────────────────────

    /** Read-only view: toolbar hidden via ?readonly=1 */
    public function getErRecordUrl(): string
    {
        return route('forms.er-record', ['visit' => $this->record->id]) . '?readonly=1';
    }

    public function getAdmRecordUrl(): string
    {
        return route('forms.adm-record', ['visit' => $this->record->id]) . '?readonly=1';
    }

    /**
     * Consent to Care read-only iframe.
     * ?readonly=1 hides the toolbar (save/print bar) inside the consent form blade.
     */
    public function getConsentReadonlyUrl(): string
    {
        return route('forms.consent-to-care', ['visit' => $this->record->id]) . '?readonly=1';
    }

    /**
     * Editable consent URL — used for the "Open / Print" button.
     */
    public function getConsentUrl(): string
    {
        return route('forms.consent-to-care', ['visit' => $this->record->id]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}