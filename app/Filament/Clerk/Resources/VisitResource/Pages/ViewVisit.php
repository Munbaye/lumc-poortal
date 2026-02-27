<?php
namespace App\Filament\Clerk\Resources\VisitResource\Pages;

use App\Filament\Clerk\Resources\VisitResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewVisit extends ViewRecord
{
    protected static string $resource = VisitResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                // ── Visit Overview ────────────────────────────────────────
                Section::make('Visit Information')
                    ->description('Basic details of this visit')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('patient.case_no')
                            ->label('Case Number')
                            ->weight('bold')
                            ->copyable(),

                        TextEntry::make('patient.full_name')
                            ->label('Patient Name')
                            ->weight('bold'),

                        TextEntry::make('patient.age_display')
                            ->label('Age'),

                        TextEntry::make('patient.sex')
                            ->label('Sex'),

                        TextEntry::make('visit_type')
                            ->label('Visit Type')
                            ->badge()
                            ->color(fn (string $state): string => $state === 'ER' ? 'danger' : 'primary'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'registered'  => 'warning',
                                'vitals_done' => 'info',
                                'assessed'    => 'success',
                                'discharged'  => 'gray',
                                'admitted'    => 'purple',
                                default       => 'gray',
                            }),

                        TextEntry::make('registered_at')
                            ->label('Registered At')
                            ->dateTime('M d, Y h:i A'),

                        TextEntry::make('chief_complaint')
                            ->label('Chief Complaint')
                            ->columnSpanFull(),

                        TextEntry::make('brought_by')
                            ->label('Brought By')
                            ->placeholder('—'),

                        TextEntry::make('condition_on_arrival')
                            ->label('Condition on Arrival')
                            ->placeholder('—'),
                    ]),

                // ── Patient Background ────────────────────────────────────
                Section::make('Patient Background')
                    ->collapsible()
                    ->collapsed()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('patient.address')
                            ->label('Address')
                            ->columnSpanFull()
                            ->placeholder('—'),

                        TextEntry::make('patient.contact_number')
                            ->label('Contact Number')
                            ->placeholder('Not provided'),

                        TextEntry::make('patient.occupation')
                            ->label('Occupation')
                            ->placeholder('—'),

                        TextEntry::make('patient.civil_status')
                            ->label('Civil Status')
                            ->placeholder('—'),

                        TextEntry::make('patient.birthday')
                            ->label('Birthday')
                            ->date('M d, Y')
                            ->placeholder('Unknown'),
                    ]),

                // ── Vital Signs ───────────────────────────────────────────
                Section::make('Vital Signs History')
                    ->description('All recorded vital signs for this visit — newest first')
                    ->collapsible()
                    ->schema([
                        // Use TextEntry with formatStateUsing to show the empty state message.
                        TextEntry::make('vitals_empty_notice')
                            ->label('')
                            ->state('No vital signs recorded yet for this visit.')
                            ->extraAttributes([
                                'class' => 'text-center italic text-gray-400 dark:text-gray-500 py-4',
                            ])
                            ->hidden(fn ($record) => $record->vitals()->exists()),

                        RepeatableEntry::make('vitals')
                            ->label('')
                            ->hidden(fn ($record) => !$record->vitals()->exists())
                            ->schema([
                                Section::make(fn ($state) =>
                                    'Recorded: ' . (
                                        isset($state['taken_at'])
                                            ? \Carbon\Carbon::parse($state['taken_at'])->format('M d, Y h:i A')
                                            : '—'
                                    )
                                )
                                ->columns(3)
                                ->schema([
                                    TextEntry::make('nurse_name')
                                        ->label('Recorded By')
                                        ->placeholder('—'),

                                    TextEntry::make('temperature')
                                        ->label('Temperature')
                                        ->formatStateUsing(fn ($state) => $state ? $state . ' °C' : '—'),

                                    TextEntry::make('temperature_site')
                                        ->label('Site')
                                        ->placeholder('—'),

                                    TextEntry::make('pulse_rate')
                                        ->label('Pulse Rate')
                                        ->formatStateUsing(fn ($state) => $state ? $state . ' bpm' : '—'),

                                    TextEntry::make('respiratory_rate')
                                        ->label('Respiratory Rate')
                                        ->formatStateUsing(fn ($state) => $state ? $state . ' /min' : '—'),

                                    TextEntry::make('blood_pressure')
                                        ->label('Blood Pressure')
                                        ->placeholder('—'),

                                    TextEntry::make('o2_saturation')
                                        ->label('O₂ Saturation')
                                        ->formatStateUsing(fn ($state) => $state ? $state . '%' : '—'),

                                    TextEntry::make('height_cm')
                                        ->label('Height')
                                        ->formatStateUsing(fn ($state) => $state ? $state . ' cm' : '—'),

                                    TextEntry::make('weight_kg')
                                        ->label('Weight')
                                        ->formatStateUsing(fn ($state) => $state ? $state . ' kg' : '—'),

                                    TextEntry::make('pain_scale')
                                        ->label('Pain Scale')
                                        ->placeholder('Not assessed'),

                                    TextEntry::make('notes')
                                        ->label('Notes / Observations')
                                        ->columnSpanFull()
                                        ->placeholder('No additional notes'),
                                ]),
                            ])
                            ->contained(false)
                            ->grid(1),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}