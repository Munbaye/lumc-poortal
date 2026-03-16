<?php

namespace App\Filament\Tech\Resources\ResultUploadResource\Pages;

use App\Filament\Tech\Resources\ResultUploadResource;
use App\Models\ActivityLog;
use App\Models\ResultUpload;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Forms\Form;

class EditResultUpload extends EditRecord
{
    protected static string $resource = ResultUploadResource::class;

    // -------------------------------------------------------------------------
    // Override the form specifically for amendments.
    // We do NOT allow changing visit_id or patient_id on an amendment —
    // the result must stay linked to the same visit.
    // -------------------------------------------------------------------------
    public function form(Form $form): Form
    {
        return $form->schema([

            //Read-only context — shows what is being amended 
            Forms\Components\Section::make('Amending Result')
                ->description('You are correcting an existing uploaded result. The original will be preserved.')
                ->icon('heroicon-o-exclamation-triangle')
                ->schema([
                    Forms\Components\Placeholder::make('original_patient')
                        ->label('Patient')
                        ->content(fn() =>
                            $this->record->visit?->patient?->case_no
                            . ' — '
                            . $this->record->visit?->patient?->first_name
                            . ' ' . $this->record->visit?->patient?->family_name
                            ?? '—'
                        ),

                    Forms\Components\Placeholder::make('original_test')
                        ->label('Original Test')
                        ->content(fn() => $this->record->test_name ?? '—'),

                    Forms\Components\Placeholder::make('original_type')
                        ->label('Original Type')
                        ->content(fn() => $this->record->result_type ?? '—'),

                    Forms\Components\Placeholder::make('original_file')
                        ->label('Original File')
                        ->content(fn() => $this->record->file_name ?? '—'),
                ])
                ->columns(2)
                ->collapsible(),

            //Amendment reason — required
            Forms\Components\Textarea::make('amendment_reason')
                ->label('Reason for Amendment')
                ->placeholder('e.g., Wrong file uploaded, sample was hemolyzed, incorrect test name...')
                ->required()
                ->rows(2)
                ->maxLength(1000)
                ->helperText('Required. Explain why this result is being corrected.'),

            //Corrected fields — tech updates what was wrong 
            Forms\Components\Select::make('result_type')
                ->label('Result Type')
                ->options([
                    'Laboratory' => 'Laboratory',
                    'Radiology'  => 'Radiology / Imaging',
                    'ECG'        => 'ECG / Cardiology',
                    'Other'      => 'Other',
                ])
                ->required(),

            Forms\Components\TextInput::make('test_name')
                ->label('Test / Exam Name')
                ->required()
                ->maxLength(255),

            Forms\Components\FileUpload::make('file_path')
                ->label('Upload Corrected File')
                ->disk('public')
                ->directory('results')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                ->maxSize(10240)
                ->required()
                ->storeFileNamesIn('file_name')
                ->helperText('Upload the corrected file. Original file is preserved.'),

            Forms\Components\Textarea::make('notes')
                ->label('Technician Notes')
                ->placeholder('Optional additional remarks...')
                ->rows(2)
                ->maxLength(1000),
        ]);
    }

    // -------------------------------------------------------------------------
    // Instead of editing the original record directly, we CREATE a new
    // ResultUpload that points back to the original via amended_from_id.
    // The original record is left completely untouched.
    // -------------------------------------------------------------------------
    protected function handleRecordUpdate($record, array $data): ResultUpload
    {
        // Snapshot the original values before anything changes
        $oldValues = [
            'test_name'   => $record->test_name,
            'result_type' => $record->result_type,
            'file_name'   => $record->file_name,
            'notes'       => $record->notes,
        ];

        // Create a NEW record — the amendment — instead of overwriting original
        $amendment = ResultUpload::create([
            'visit_id'         => $record->visit_id,
            'patient_id'       => $record->patient_id,
            'uploaded_by'      => auth()->id(),
            'result_type'      => $data['result_type'],
            'test_name'        => $data['test_name'],
            'file_path'        => $data['file_path'],
            'file_name'        => $data['file_name'],
            'notes'            => $data['notes'] ?? null,
            'amended_from_id'  => $record->id,       // link back to original
            'amendment_reason' => $data['amendment_reason'],
        ]);

        // Log the amendment with before/after values
        ActivityLog::record(
            action:       'amended_result',
            category:     ActivityLog::CAT_UPLOADS,
            subject:      $amendment,
            subjectLabel: $amendment->test_name . ' — ' . $amendment->visit?->patient?->case_no,
            oldValues:    $oldValues,
            newValues:    [
                'test_name'        => $amendment->test_name,
                'result_type'      => $amendment->result_type,
                'file_name'        => $amendment->file_name,
                'amendment_reason' => $amendment->amendment_reason,
                'amended_from_id'  => $record->id,
            ],
        );

        // Return the new amendment so Filament redirects to it
        return $amendment;
    }

    // Redirect to the list after amending
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}