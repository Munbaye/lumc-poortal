<?php

namespace App\Filament\Shared\Pages;

use App\Models\Patient;
use App\Models\Visit;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Url;

abstract class BasePatientHistoryPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Patient Visit History';

    #[Url]
    public ?int $patientId = null;

    public ?Patient $patient = null;

    public function mount(): void
    {
        if (! $this->patientId) {
            $this->redirect($this->getPatientListUrl());
            return;
        }

        $this->patient = Patient::find($this->patientId);

        if (! $this->patient) {
            Notification::make()
                ->title('Patient not found.')
                ->danger()
                ->send();

            $this->redirect($this->getPatientListUrl());
        }
    }

    public function getVisitsProperty(): Collection
    {
        return Visit::with([
            'medicalHistory.doctor',
            'erRecord',
            'admissionRecord',
            'consentRecord',
            'vitals',
            'doctorsOrders',
        ])
            ->where('patient_id', $this->patientId)
            ->orderBy('registered_at', 'desc')
            ->get();
    }

    public function getOpenChartUrl(int $visitId): string
    {
        return $this->getVisitUrl($visitId);
    }

    public function getViewVisitUrl(int $visitId): string
    {
        return $this->getVisitUrl($visitId);
    }

    abstract protected function getVisitUrl(int $visitId): string;

    abstract public function getPatientListUrl(): string;
}
