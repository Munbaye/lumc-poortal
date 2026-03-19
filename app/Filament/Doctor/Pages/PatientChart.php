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
 * Updated: Results tab now loads real uploaded results from result_uploads.
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

    public string $activeTab      = 'orders';
    public bool   $writingOrders  = false;
    public array  $orderLines     = [['text' => '']];
    public ?int   $confirmDiscontinueId = null;

    public function mount(): void
    {
        if (!$this->visitId) {
            $this->redirect(\App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index'));
            return;
        }
        $this->loadVisit();
    }

    private function loadVisit(): void
    {
        $this->visit = Visit::with([
            'patient',
            'medicalHistory.doctor',
            'vitals'        => fn ($q) => $q->orderBy('taken_at', 'desc'),
            'doctorsOrders' => fn ($q) => $q->with('doctor')->orderBy('order_date', 'desc'),
        ])->find($this->visitId);

        if (!$this->visit) {
            Notification::make()->title('Visit not found.')->danger()->send();
            $this->redirect(\App\Filament\Doctor\Resources\AdmittedPatientsResource::getUrl('index'));
        }
    }

    // ── Computed: all results for this visit ──────────────────────────────────

    public function getLabResultsProperty()
    {
        $requestIds = LabRequest::where('visit_id', $this->visitId)
            ->where('status', 'completed')
            ->pluck('id');

        return ResultUpload::where('request_type', 'lab')
            ->whereIn('request_id', $requestIds)
            ->with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRadResultsProperty()
    {
        $requestIds = RadiologyRequest::where('visit_id', $this->visitId)
            ->where('status', 'completed')
            ->pluck('id');

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

    // ── Tab navigation ────────────────────────────────────────────────────────

    public function setTab(string $tab): void
    {
        $this->activeTab     = $tab;
        $this->writingOrders = false;
    }

    // ── Orders ────────────────────────────────────────────────────────────────

    public function addOrderLine(): void
    {
        $this->orderLines[] = ['text' => ''];
    }

    public function removeOrderLine(int $index): void
    {
        if (count($this->orderLines) <= 1) {
            $this->orderLines = [['text' => '']];
            return;
        }
        array_splice($this->orderLines, $index, 1);
    }

    public function toggleWriteOrders(): void
    {
        $this->writingOrders = !$this->writingOrders;
        if ($this->writingOrders) {
            $this->orderLines = [['text' => '']];
        }
    }

    public function saveOrders(): void
    {
        $validLines = collect($this->orderLines)
            ->filter(fn ($line) => trim($line['text'] ?? '') !== '')
            ->values();

        if ($validLines->isEmpty()) {
            Notification::make()->title('Please enter at least one order.')->warning()->send();
            return;
        }

        $saved = 0;
        foreach ($validLines as $line) {
            DoctorsOrder::create([
                'visit_id'     => $this->visitId,
                'doctor_id'    => auth()->id(),
                'order_text'   => trim($line['text']),
                'status'       => DoctorsOrder::STATUS_PENDING,
                'order_date'   => now(),
                'is_completed' => false,
            ]);
            $saved++;
        }

        ActivityLog::record(
            action:       ActivityLog::ACT_ADMITTED_PATIENT,
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $this->visit,
            subjectLabel: $this->visit->patient->full_name . ' (' . $this->visit->patient->case_no . ')',
            newValues:    ['orders_written' => $saved],
            panel:        'doctor',
        );

        Notification::make()->title($saved . ' order' . ($saved > 1 ? 's' : '') . ' written.')->success()->send();

        $this->writingOrders = false;
        $this->orderLines    = [['text' => '']];
        $this->loadVisit();
    }

    public function discontinueOrder(int $orderId): void
    {
        $order = DoctorsOrder::where('visit_id', $this->visitId)->find($orderId);
        if (!$order) { Notification::make()->title('Order not found.')->danger()->send(); return; }

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

    public function getHistoryFormUrl(): string
    {
        return route('forms.history-form', ['visit' => $this->visitId]);
    }

    public function getPhysicalExamFormUrl(): string
    {
        return route('forms.physical-exam-form', ['visit' => $this->visitId]);
    }
}