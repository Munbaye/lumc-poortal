<?php

namespace App\Filament\Tech\Resources\ResultUploadResource\Pages;

use App\Filament\Tech\Resources\ResultUploadResource;
use App\Models\ActivityLog;
use App\Models\DoctorsOrder;
use App\Models\ResultUpload;
use App\Models\Visit;
use App\Models\User;
use App\Notifications\ResultUploadedNotification;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class CreateResultUpload extends Page
{
    protected static string $resource = ResultUploadResource::class;
    protected static string $view     = 'filament.tech.pages.create-result-upload';

    public ?array $data = [];

    public function getTitle(): string | Htmlable
    {
        return 'Upload Lab Result';
    }

    public function mount(): void
    {
        $this->form->fill([
            'performed_by' => auth()->user()->name,
        ]);
    }

    public function form(Form $form): Form
    {
        $specialty  = auth()->user()->specialty;
        $resultType = match ($specialty) {
            'MedTech' => 'Laboratory',
            'RadTech' => 'Radiology',
            default   => null,
        };

        $resultTypeOptions = [
            'Laboratory' => 'Laboratory',
            'Radiology'  => 'Radiology / Imaging',
            'ECG'        => 'ECG / Cardiology',
            'Other'      => 'Other',
        ];

        return $form
            ->schema([

                // ── STEP 1: Select Doctor's Order ─────────────────────────────
                Forms\Components\Section::make('step_1')
                    ->heading('Step 1 — Select Doctor\'s Order')
                    ->description('Choose the pending order you are fulfilling. Patient details will fill in automatically.')
                    ->schema([
                        Forms\Components\Select::make('doctors_order_id')
                            ->label('Doctor\'s Order')
                            ->placeholder('Search by patient name, case no., or order...')
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->getSearchResultsUsing(function (string $search) {
                                return DoctorsOrder::with(['visit.patient', 'doctor'])
                                    ->where('is_completed', false)
                                    ->whereHas('visit.patient', fn($q) =>
                                        $q->where('first_name',   'like', "%{$search}%")
                                          ->orWhere('family_name', 'like', "%{$search}%")
                                          ->orWhere('case_no',     'like', "%{$search}%")
                                    )
                                    ->latest()
                                    ->limit(20)
                                    ->get()
                                    ->filter(fn($o) => $o->visit?->patient !== null)
                                    ->mapWithKeys(fn($o) => [
                                        $o->id =>
                                            ($o->visit->patient->case_no ?? '')
                                            . ' — '
                                            . ($o->visit->patient->first_name ?? '')
                                            . ' ' . ($o->visit->patient->family_name ?? '')
                                            . ' | ' . Str::limit($o->order_text, 50),
                                    ]);
                            })
                            ->getOptionLabelUsing(fn($value) => optional(
                                DoctorsOrder::with(['visit.patient'])->find($value)
                            )?->visit?->patient?->case_no
                                . ' — '
                                . optional(DoctorsOrder::with(['visit.patient'])->find($value))?->visit?->patient?->first_name
                                . ' ' . optional(DoctorsOrder::with(['visit.patient'])->find($value))?->visit?->patient?->family_name
                            )
                            ->afterStateUpdated(function ($state, $set) {
                                if (! $state) {
                                    $set('_patient_info',      null);
                                    $set('_requesting_doctor', null);
                                    $set('_order_text',        null);
                                    $set('visit_id',           null);
                                    return;
                                }
                                $order = DoctorsOrder::with(['visit.patient', 'doctor'])->find($state);
                                $set('visit_id', $order?->visit_id);
                                $set('_patient_info',
                                    $order?->visit?->patient
                                        ? $order->visit->patient->case_no
                                          . ' | ' . $order->visit->patient->first_name
                                          . ' ' . $order->visit->patient->family_name
                                          . ' | Age: ' . ($order->visit->patient->age ?? '—')
                                          . ' | ' . ($order->visit->patient->sex ?? '—')
                                        : null
                                );
                                $set('_requesting_doctor', $order?->doctor?->name ?? 'No doctor assigned');
                                $set('_order_text',        $order?->order_text ?? null);
                            })
                            ->helperText('Only pending (not yet completed) orders are shown.'),

                        Forms\Components\Hidden::make('visit_id'),

                        Forms\Components\Placeholder::make('_patient_info')
                            ->label('Patient')
                            ->content(fn($state) => $state ?? '— Select an order above'),

                        Forms\Components\Placeholder::make('_requesting_doctor')
                            ->label('Requesting Doctor')
                            ->content(fn($state) => $state ?? '—'),

                        Forms\Components\Placeholder::make('_order_text')
                            ->label('Order / Instruction')
                            ->content(fn($state) => $state ?? '—')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                // ── STEP 2: Initial Reading — Three Phases ────────────────────
                Forms\Components\Section::make('step_2')
                    ->heading('Step 2 — Initial Reading')
                    ->description('Complete all three phases before uploading. This ensures the result is accurate, reliable, and ready for the doctor.')
                    ->schema([

                        // PRE-ANALYTICAL
                        Forms\Components\Fieldset::make('pre_analytical_fieldset')
                            ->label('Pre-Analytical Phase — Before the Test Was Run')
                            ->schema([
                                Forms\Components\CheckboxList::make('pre_analytical_checks')
                                    ->label('')
                                    ->options([
                                        'patient_match'       => 'Patient name on result matches the selected patient',
                                        'specimen_labeled'    => 'Specimen is properly labeled with patient ID',
                                        'collection_recorded' => 'Date and time of specimen collection is recorded',
                                        'specimen_condition'  => 'Specimen condition is acceptable (no hemolysis, lipemia, or icterus)',
                                        'test_matches_order'  => 'Test requested matches the doctor\'s order',
                                    ])
                                    ->required()
                                    ->rules(['array', 'min:5'])
                                    ->validationMessages([
                                        'min' => 'All 5 pre-analytical items must be confirmed.',
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpanFull(),

                        // ANALYTICAL
                        Forms\Components\Fieldset::make('analytical_fieldset')
                            ->label('Analytical Phase — During the Test')
                            ->schema([
                                Forms\Components\CheckboxList::make('analytical_checks')
                                    ->label('')
                                    ->options([
                                        'qc_passed'           => 'Quality Control (QC) was performed and passed',
                                        'reference_ranges'    => 'Reference ranges are appropriate for patient age and sex',
                                        'no_instrument_error' => 'No instrument or equipment errors were encountered',
                                        'reportable_range'    => 'Result is within the reportable range of the method',
                                    ])
                                    ->required()
                                    ->rules(['array', 'min:4'])
                                    ->validationMessages([
                                        'min' => 'All 4 analytical items must be confirmed.',
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpanFull(),

                        // POST-ANALYTICAL
                        Forms\Components\Fieldset::make('post_analytical_fieldset')
                            ->label('Post-Analytical Phase — After Results Are Generated')
                            ->schema([
                                Forms\Components\CheckboxList::make('post_analytical_checks')
                                    ->label('')
                                    ->options([
                                        'result_legible'        => 'Result is legible and clearly readable',
                                        'clinically_consistent' => 'Values are consistent with patient\'s clinical condition',
                                        'previous_reviewed'     => 'Result has been reviewed against previous results (if available)',
                                        'reference_indicated'   => 'Reference ranges are indicated on the result',
                                    ])
                                    ->required()
                                    ->rules(['array', 'min:4'])
                                    ->validationMessages([
                                        'min' => 'All 4 post-analytical items must be confirmed.',
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpanFull(),

                        // INITIAL IMPRESSION
                        Forms\Components\Textarea::make('initial_impression')
                            ->label('Initial Impression')
                            ->placeholder('Summarize your reading of this result (e.g. WBC elevated at 11.8 x10⁹/L, consistent with possible infection. Hemoglobin slightly low. Other CBC parameters within normal limits).')
                            ->helperText('Required — write your clinical impression of this result before submitting.')
                            ->rows(4)
                            ->required()
                            ->maxLength(2000)
                            ->columnSpanFull(),

                        // CRITICAL VALUE FLAG
                        Forms\Components\Toggle::make('is_critical')
                            ->label('🚨  Flag as Critical Value')
                            ->helperText('Enable if any value is dangerously abnormal and requires immediate doctor attention.')
                            ->reactive()
                            ->onColor('danger')
                            ->offColor('gray')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('critical_reason')
                            ->label('Critical Value — Description')
                            ->placeholder('Describe the critical finding and its significance (e.g. Hemoglobin critically low at 42 g/L — immediate clinical review and possible transfusion required).')
                            ->helperText('Required when flagged as critical. The requesting doctor will be notified immediately upon submission.')
                            ->rows(3)
                            ->required(fn($get) => (bool) $get('is_critical'))
                            ->hidden(fn($get) => ! $get('is_critical'))
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),

                // ── STEP 3: Result Details ────────────────────────────────────
                Forms\Components\Section::make('step_3')
                    ->heading('Step 3 — Result Details')
                    ->description('Specify the test and who performed it.')
                    ->schema([
                        $resultType
                            ? Forms\Components\Placeholder::make('_result_type_display')
                                ->label('Result Type')
                                ->content($resultTypeOptions[$resultType])
                            : Forms\Components\Select::make('result_type')
                                ->label('Result Type')
                                ->options($resultTypeOptions)
                                ->required(),

                        Forms\Components\Hidden::make('result_type')
                            ->default($resultType),

                        Forms\Components\TextInput::make('test_name')
                            ->label('Test / Exam Name')
                            ->placeholder('e.g., Complete Blood Count, Chest X-Ray PA')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('performed_by')
                            ->label('Performed By')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Change if an intern or volunteer performed the test.'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Additional Notes')
                            ->placeholder('Optional remarks...')
                            ->rows(2)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                // ── STEP 4: Attach Files ──────────────────────────────────────
                Forms\Components\Section::make('step_4')
                    ->heading('Step 4 — Attach Result Files')
                    ->description('Upload one or more result files. JPEG, PNG, or PDF — max 10 MB each.')
                    ->schema([
                        Forms\Components\FileUpload::make('files')
                            ->label('')
                            ->disk('public')
                            ->directory('results')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                            ->maxSize(10240)
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->required()
                            ->minFiles(1)
                            ->helperText('JPEG, PNG, or PDF — max 10 MB each. You can attach multiple files at once.'),
                    ]),

            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $orderId = $data['doctors_order_id'] ?? null;
        $visitId = $data['visit_id']         ?? null;
        $files   = $data['files']            ?? [];

        // ── Basic validation ──────────────────────────────────────────────────
        if (! $orderId) {
            Notification::make()->title('Please select a doctor\'s order.')->danger()->send();
            return;
        }
        if (! $visitId) {
            Notification::make()->title('Could not determine the patient visit. Please re-select the order.')->danger()->send();
            return;
        }
        if (empty($files)) {
            Notification::make()->title('Please attach at least one result file.')->danger()->send();
            return;
        }

        // ── Hard gate: all checklist items must be checked ────────────────────
        $preChecks  = $data['pre_analytical_checks']  ?? [];
        $anaChecks  = $data['analytical_checks']       ?? [];
        $postChecks = $data['post_analytical_checks']  ?? [];

        if (count($preChecks) < 5 || count($anaChecks) < 4 || count($postChecks) < 4) {
            Notification::make()
                ->title('Initial reading incomplete.')
                ->body('All checklist items across all three phases must be confirmed before submitting.')
                ->danger()
                ->send();
            return;
        }

        // ── Resolve context ───────────────────────────────────────────────────
        $order      = DoctorsOrder::with(['visit.patient', 'doctor'])->find($orderId);
        $visit      = Visit::with(['patient', 'assignedDoctor'])->find($visitId);
        $patientId  = $visit?->patient_id;
        $doctorId   = $order?->doctor_id ?? $visit?->assigned_doctor_id;
        $isCritical = (bool) ($data['is_critical'] ?? false);

        $specialty  = auth()->user()->specialty;
        $resultType = match ($specialty) {
            'MedTech' => 'Laboratory',
            'RadTech' => 'Radiology',
            default   => $data['result_type'] ?? 'Other',
        };

        $readabilityChecks = [
            'pre_analytical'  => $preChecks,
            'analytical'      => $anaChecks,
            'post_analytical' => $postChecks,
        ];

        $lastRecord = null;

        // ── Save one record per file ──────────────────────────────────────────
        foreach ($files as $filePath) {
            $lastRecord = ResultUpload::create([
                'visit_id'           => $visitId,
                'patient_id'         => $patientId,
                'uploaded_by'        => auth()->id(),
                'requested_by'       => $doctorId,
                'performed_by'       => $data['performed_by'] ?? auth()->user()->name,
                'result_type'        => $resultType,
                'test_name'          => $data['test_name'],
                'file_path'          => $filePath,
                'file_name'          => basename($filePath),
                'notes'              => $data['notes']              ?? null,
                'readability_checks' => $readabilityChecks,
                'initial_impression' => $data['initial_impression'],
                'is_critical'        => $isCritical,
                'critical_reason'    => $isCritical ? ($data['critical_reason'] ?? null) : null,
                'status'             => 'pending_validation',
            ]);

            ActivityLog::record(
                action:       ActivityLog::ACT_UPLOADED_RESULT,
                category:     ActivityLog::CAT_UPLOADS,
                subject:      $lastRecord,
                subjectLabel: $lastRecord->test_name . ' — ' . $visit?->patient?->case_no,
                oldValues:    [],
                newValues:    [
                    'test_name'          => $lastRecord->test_name,
                    'result_type'        => $lastRecord->result_type,
                    'file_name'          => $lastRecord->file_name,
                    'patient_id'         => $lastRecord->patient_id,
                    'visit_id'           => $lastRecord->visit_id,
                    'requested_by'       => $doctorId,
                    'performed_by'       => $lastRecord->performed_by,
                    'is_critical'        => $lastRecord->is_critical,
                    'initial_impression' => $lastRecord->initial_impression,
                ],
            );
        }

        // ── Auto-complete the doctor's order ──────────────────────────────────
        $order?->update([
            'is_completed' => true,
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);

        // ── Notify doctor ─────────────────────────────────────────────────────
        if ($doctorId && $lastRecord) {
            $doctor = User::find($doctorId);
            if ($doctor) {
                $doctor->notify(new ResultUploadedNotification(
                    result:        $lastRecord,
                    visit:         $visit,
                    totalUploaded: count($files),
                    isCritical:    $isCritical,
                ));

                if ($isCritical) {
                    ResultUpload::where('visit_id', $visitId)
                        ->where('test_name', $data['test_name'])
                        ->whereNull('critical_notified_at')
                        ->update(['critical_notified_at' => now()]);
                }
            }
        }

        $count = count($files);
        $body  = $isCritical
            ? '🚨 Critical value flagged. The doctor has been notified immediately.'
            : 'Order marked as complete. Result is pending validation by a senior tech or pathologist.';

        Notification::make()
            ->title("{$count} file(s) uploaded successfully.")
            ->body($body)
            ->success()
            ->send();

        $this->redirect($this->getResource()::getUrl('index'));
    }

    public function discard(): void
    {
        $this->redirect($this->getResource()::getUrl('index'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to List')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Submit Results')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->action('save'),

            Action::make('discard')
                ->label('Discard')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->action('discard'),
        ];
    }
}