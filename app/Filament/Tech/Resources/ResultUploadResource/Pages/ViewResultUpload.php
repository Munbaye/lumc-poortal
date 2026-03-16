<?php

namespace App\Filament\Tech\Resources\ResultUploadResource\Pages;

use App\Filament\Tech\Resources\ResultUploadResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class ViewResultUpload extends ViewRecord
{
    protected static string $resource = ResultUploadResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            Section::make('Patient Information')
                ->description('Basic identifying information only.')
                ->icon('heroicon-o-user')
                ->schema([
                    TextEntry::make('visit.patient.case_no')
                        ->label('Case No.')
                        ->copyable(),

                    TextEntry::make('visit.patient.first_name')
                        ->label('Patient Name')
                        ->formatStateUsing(fn($state, $record) =>
                            $record->visit?->patient
                                ? $record->visit->patient->first_name . ' ' . $record->visit->patient->family_name
                                : '—'
                        ),

                    TextEntry::make('visit.patient.age')
                        ->label('Age'),

                    TextEntry::make('visit.patient.sex')
                        ->label('Sex')
                        ->badge()
                        ->color(fn($state) => match($state) {
                            'Male'   => 'info',
                            'Female' => 'danger',
                            default  => 'gray',
                        }),

                    TextEntry::make('visit.patient.is_pedia')
                        ->label('Pedia Patient')
                        ->formatStateUsing(fn($state) => $state ? 'Yes' : 'No')
                        ->badge()
                        ->color(fn($state) => $state ? 'warning' : 'gray'),
                ])
                ->columns(3),

            Section::make('Visit Details')
                ->description('The visit this result is associated with.')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    TextEntry::make('visit.visit_type')
                        ->label('Visit Type')
                        ->badge()
                        ->color(fn($state) => match($state) {
                            'ER'    => 'danger',
                            'OPD'   => 'info',
                            default => 'gray',
                        }),

                    TextEntry::make('visit.status')
                        ->label('Visit Status')
                        ->badge()
                        ->color(fn($state) => match($state) {
                            'admitted'    => 'warning',
                            'assessed'    => 'info',
                            'vitals_done' => 'success',
                            'discharged'  => 'gray',
                            default       => 'gray',
                        }),

                    TextEntry::make('visit.registered_at')
                        ->label('Registered On')
                        ->dateTime('M d, Y h:i A'),
                ])
                ->columns(3),

            Section::make('Result Information')
                ->description('Details of the uploaded result.')
                ->icon('heroicon-o-beaker')
                ->schema([
                    TextEntry::make('result_type')
                        ->label('Result Type')
                        ->badge()
                        ->color(fn($state) => match($state) {
                            'Laboratory' => 'success',
                            'Radiology'  => 'warning',
                            'ECG'        => 'danger',
                            default      => 'gray',
                        }),

                    TextEntry::make('test_name')
                        ->label('Test / Exam Name'),

                    TextEntry::make('notes')
                        ->label('Technician Notes')
                        ->placeholder('No notes provided.')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Uploaded File')
                ->icon('heroicon-o-paper-clip')
                ->schema([
                    TextEntry::make('file_name')
                        ->label('File Name'),

                    TextEntry::make('file_path')
                        ->label('View / Download')
                        ->formatStateUsing(fn($state) => 'Open File')
                        ->url(fn($record) => asset('storage/' . $record->file_path))
                        ->openUrlInNewTab()
                        ->badge()
                        ->color('info'),
                ])
                ->columns(2),

            Section::make('Upload Record')
                ->icon('heroicon-o-clock')
                ->schema([
                    TextEntry::make('uploader.name')
                        ->label('Uploaded By'),

                    TextEntry::make('created_at')
                        ->label('Uploaded On')
                        ->dateTime('M d, Y h:i A'),

                    TextEntry::make('updated_at')
                        ->label('Last Modified')
                        ->dateTime('M d, Y h:i A'),
                ])
                ->columns(3),

        ]);
    }
}