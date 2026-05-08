<?php

namespace App\Filament\Clerk\Pages;

use App\Filament\Clerk\Resources\VisitResource;
use App\Filament\Shared\Pages\BasePatientHistoryPage;
use Filament\Actions\Action;

class PatientHistory extends BasePatientHistoryPage
{
    protected static string $view = 'filament.clerk.pages.patient-history';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Patient Visits')
                ->icon('heroicon-o-arrow-left')
                ->url($this->getPatientListUrl())
                ->color('gray')
                ->outlined(),
        ];
    }

    protected function getVisitUrl(int $visitId): string
    {
        return VisitResource::getUrl('view', ['record' => $visitId]);
    }

    public function getPatientListUrl(): string
    {
        return VisitResource::getUrl('index');
    }
}
