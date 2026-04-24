<?php

namespace App\Filament\Doctor\Pages;

use App\Models\DoctorsOrder;
use App\Models\LabRequest;
use App\Models\RadiologyRequest;
use App\Models\ResultUpload;
use App\Models\Visit;
use App\Models\ActivityLog;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

/**
 * PatientChart — main hub for admitted patients (Doctor view).
 *
 * Tabs:
 *   Profile · Visit History · Vital Signs · History & Assessment · Doctor's Orders · Lab / Radiology
 *
 * Doctor's Orders redesign:
 *   One free-text textarea — doctor types naturally, one order per line.
 *   saveOrders() splits by \n → creates one DoctorsOrder row per non-blank line.
 *   All logging, status (pending/carried/discontinued) and relationships unchanged.
 */
class PatientChart extends Page
{
    protected static ?string $navigationIcon           = 'heroicon-o-document-text';
    protected static string  $view                     = 'filament.doctor.pages.patient-chart';
    protected static ?string $title                    = 'Patient Chart';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $visitId = null;

    public ?Visit $visit = null;

    public ?int $ballardExamId = null;

    public string $activeTab             = 'orders';
    public bool   $writingOrders         = false;

    /**
     * Free-text order box — doctor types one order per line.
     * Replaces the previous orderLines[] repeater array.
     */
    public string $orderText             = '';

    public ?int   $confirmDiscontinueId  = null;

    // ── Result detail view state ──────────────────────────────────────────────
    public ?int $viewingLabRequestId = null;
    public ?int $viewingRadRequestId = null;

    // ── Patient history tab state ─────────────────────────────────────────────
    public ?int $viewingHistoryVisitId = null;

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect(\App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index'));
            return;
        }
        $this->loadVisit();

        if ($this->visit && $this->isReadonly) {
            $this->activeTab = 'profile';
        }
    }

    private function loadVisit(): void
    {
        $this->visit = Visit::with([
            'patient',
            'medicalHistory.doctor',
            'doctorsOrders' => fn($q) => $q->with('doctor')->orderBy('order_date', 'desc'),
            'ballardExams',
        ])->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect(\App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index'));
        }
    }

    // Computed Properties
    public function getLabResultsProperty()
    {
        $requestIds = LabRequest::where('visit_id', $this->visitId)
            ->where('status', 'completed')->pluck('id');
        return ResultUpload::where('request_type', 'lab')
            ->whereIn('request_id', $requestIds)
            ->with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRadResultsProperty()
    {
        $requestIds = RadiologyRequest::where('visit_id', $this->visitId)
            ->where('status', 'completed')->pluck('id');
        return ResultUpload::where('request_type', 'radiology')
            ->whereIn('request_id', $requestIds)
            ->with(['uploadedBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($upload) {
                $upload->radRequest = RadiologyRequest::find($upload->request_id);
                return $upload;
            });
    }

    public function getLabRequestsCountProperty(): int
    {
        return LabRequest::where('visit_id', $this->visitId)->count();
    }

    public function getRadRequestsCountProperty(): int
    {
        return RadiologyRequest::where('visit_id', $this->visitId)->count();
    }

    // ── Readonly mode (past / completed visits) ───────────────────────────────

    public function getIsReadonlyProperty(): bool
    {
        if (!$this->visit) return true;
        return !($this->visit->status === 'admitted'
            && $this->visit->clerk_admitted_at !== null
            && $this->visit->discharged_at === null);
    }

    // Tab Navigation
    public function setTab(string $tab): void
    {
        $this->activeTab           = $tab;
        $this->writingOrders       = false;
        $this->viewingLabRequestId = null;
        $this->viewingRadRequestId = null;
    }

    // Orders Methods (unchanged)
    public function toggleWriteOrders(): void
    {
        $this->writingOrders = !$this->writingOrders;
        if ($this->writingOrders) {
            $this->orderText = '';   // clear on open
        }
    }

    /**
     * Append a quick-insert snippet to the textarea.
     * Called by the quick-insert chip buttons in the blade.
     */
    public function quickInsert(string $text): void
    {
        $this->orderText = rtrim($this->orderText);
        if ($this->orderText !== '') {
            $this->orderText .= "\n";
        }
        $this->orderText .= $text;
    }

    /**
     * Save orders from the free-text box.
     * Splits by newline → creates one DoctorsOrder per non-blank line.
     * All status, logging, and relationships are preserved.
     */
    public function saveOrders(): void
    {
        if (trim($this->orderText) === '') {
            Notification::make()->title('Please type at least one order.')->warning()->send();
            return;
        }

        // Split by newlines, trim each line, remove blank lines
        $lines = collect(explode("\n", $this->orderText))
            ->map(fn($line) => trim($line))
            ->filter(fn($line) => $line !== '')
            ->values();

        if ($lines->isEmpty()) return;

        $saved = 0;
        $orderDate = now();

        foreach ($lines as $line) {
            DoctorsOrder::create([
                'visit_id'     => $this->visitId,
                'doctor_id'    => auth()->id(),
                'order_text'   => $line,
                'status'       => DoctorsOrder::STATUS_PENDING,
                'order_date'   => $orderDate,
                'is_completed' => false,
            ]);
            $saved++;
        }

        ActivityLog::record(
            action: ActivityLog::ACT_ADMITTED_PATIENT,
            category: ActivityLog::CAT_CLINICAL,
            subject: $this->visit,
            subjectLabel: $this->visit->patient->full_name . ' (' . $this->visit->patient->case_no . ')',
            newValues: ['orders_written' => $saved, 'doctor' => auth()->user()->name],
            panel: 'doctor',
        );

        Notification::make()
            ->title($saved . ' order' . ($saved > 1 ? 's' : '') . ' written.')
            ->success()->send();

        $this->writingOrders = false;
        $this->orderText     = '';
        $this->loadVisit();
    }

    public function discontinueOrder(int $orderId): void
    {
        $order = DoctorsOrder::where('visit_id', $this->visitId)->find($orderId);
        if (!$order) {
            Notification::make()->title('Order not found.')->danger()->send();
            return;
        }

        $order->update([
            'status'       => DoctorsOrder::STATUS_DISCONTINUED,
            'is_completed' => true,
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);

        $this->confirmDiscontinueId = null;
        $this->loadVisit();
        Notification::make()->title('Order discontinued.')->success()->send();
    }

    public function getErRecordUrl(): string
    {
        return route('forms.er-record', ['visit' => $this->visitId]) . '?readonly=1';
    }

    public function getAdmRecordUrl(): string
    {
        return route('forms.adm-record', ['visit' => $this->visitId]) . '?readonly=1';
    }

    public function getConsentUrl(): string
    {
        return route('forms.consent-to-care', ['visit' => $this->visitId]) . '?readonly=1';
    }

    public function getHistoryFormUrl(): string
    {
        return route('forms.history-form', ['visit' => $this->visitId]);
    }

    public function getPhysicalExamFormUrl(): string
    {
        return route('forms.physical-exam-form', ['visit' => $this->visitId]);
    }

    public function getPatientHistoryUrl(): string
    {
        return \App\Filament\Doctor\Pages\PatientHistory::getUrl([
            'patientId' => $this->visit?->patient_id,
        ]);
    }

    public function getPastVisitsCountProperty(): int
    {
        if (!$this->visit) return 0;

        return Visit::where('patient_id', $this->visit->patient_id)
            ->where('id', '!=', $this->visitId)
            ->whereNotNull('discharged_at')
            ->count();
    }

    public function getPastVisitsProperty()
    {
        if (!$this->visit) return collect();

        return Visit::with([
            'medicalHistory.doctor',
            'vitals',
            'doctorsOrders',
            'erRecord',
            'admissionRecord',
            'consentRecord',
        ])
        ->where('patient_id', $this->visit->patient_id)
        ->where('id', '!=', $this->visitId)
        ->whereNotNull('discharged_at')
        ->orderBy('registered_at', 'desc')
        ->get();
    }

    public function getHistoryVisitProperty(): ?Visit
    {
        if (!$this->viewingHistoryVisitId) return null;

        return Visit::with([
            'medicalHistory.doctor',
            'vitals',
            'doctorsOrders.doctor',
            'erRecord',
            'admissionRecord',
            'consentRecord',
        ])->find($this->viewingHistoryVisitId);
    }

    public function viewHistoryVisit(int $visitId): void
    {
        $this->viewingHistoryVisitId = $visitId;
    }

    public function closeHistoryView(): void
    {
        $this->viewingHistoryVisitId = null;
    }

    public function getHistoryLabResults(int $visitId)
    {
        $requestIds = LabRequest::where('visit_id', $visitId)
            ->where('status', 'completed')->pluck('id');
        return ResultUpload::where('request_type', 'lab')
            ->whereIn('request_id', $requestIds)
            ->with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getHistoryRadResults(int $visitId)
    {
        $requestIds = RadiologyRequest::where('visit_id', $visitId)
            ->where('status', 'completed')->pluck('id');
        return ResultUpload::where('request_type', 'radiology')
            ->whereIn('request_id', $requestIds)
            ->with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($upload) {
                $upload->radRequest = RadiologyRequest::find($upload->request_id);
                return $upload;
            });
    }

    public function getPastVisitErUrl(int $visitId): string
    {
        return route('forms.er-record', ['visit' => $visitId]) . '?readonly=1';
    }

    public function getPastVisitAdmUrl(int $visitId): string
    {
        return route('forms.adm-record', ['visit' => $visitId]) . '?readonly=1';
    }

    public function getPastVisitConsentUrl(int $visitId): string
    {
        return route('forms.consent-to-care', ['visit' => $visitId]) . '?readonly=1';
    }

    public function getHasBallardScoreProperty(): bool
    {
        return $this->visit && $this->visit->ballardExams->count() > 0;
    }

    public function getBallardExamsProperty()
    {
        return $this->visit ? $this->visit->ballardExams : collect();
    }
}
