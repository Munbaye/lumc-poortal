<?php

namespace App\Filament\Tech\Pages;

use App\Models\ActivityLog;
use App\Models\LabRequest;
use App\Models\ResultUpload;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

class ViewLabRequest extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon           = 'heroicon-o-beaker';
    protected static string  $view                     = 'filament.tech.pages.view-lab-request';
    protected static ?string $title                    = 'Lab Request';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $requestId = null;

    public ?LabRequest $labRequest = null;

    // Tech Action fields
    public array  $resultFiles       = [];
    public string $specimenCollected = '';
    public string $notes             = '';

    public function mount(): void
    {
        if (!$this->requestId) {
            $this->redirect(TechDashboard::getUrl());
            return;
        }

        $this->labRequest = LabRequest::with([
            'visit.patient', 'doctor', 'results.uploadedBy',
        ])->find($this->requestId);

        if (!$this->labRequest) {
            Notification::make()->title('Request not found.')->danger()->send();
            $this->redirect(TechDashboard::getUrl());
            return;
        }

        $this->specimenCollected = $this->labRequest->specimen_collected ?? '';
    }

    // ── Step 1: Mark as Received ──────────────────────────────────────────────

    public function markReceived(): void
    {
        if ($this->labRequest->request_received_at) {
            return;
        }
        $this->labRequest->update([
            'request_received_at' => now(),
            'status'              => LabRequest::STATUS_IN_PROGRESS,
        ]);
        $this->labRequest->refresh();
        Notification::make()->title('Request marked as received.')->success()->send();
    }

    // ── Step 2: Save specimen text ────────────────────────────────────────────

    public function saveSpecimen(): void
    {
        $this->labRequest->update([
            'specimen_collected' => trim($this->specimenCollected) ?: null,
        ]);
        $this->labRequest->refresh();
        Notification::make()->title('Specimen saved.')->success()->send();
    }

    // ── Step 3: Test Started ──────────────────────────────────────────────────

    public function markTestStarted(): void
    {
        if ($this->labRequest->test_started_at) {
            return;
        }
        $this->labRequest->update([
            'test_started_at' => now(),
            'status'          => LabRequest::STATUS_IN_PROGRESS,
        ]);
        $this->labRequest->refresh();
        Notification::make()->title('Test started time recorded.')->success()->send();
    }

    // ── Remove a pending file before completing ───────────────────────────────

    public function removeFile(int $index): void
    {
        $files = $this->resultFiles;
        unset($files[$index]);
        $this->resultFiles = array_values($files);
    }

    // ── Step 4: Complete — upload files, auto-set test_done_at ───────────────

    public function saveResult(): void
    {
        $this->validate([
            'resultFiles'   => 'required|array|min:1',
            'resultFiles.*' => 'file|mimes:pdf,jpg,jpeg,png,webp|max:20480',
            'notes'         => 'nullable|string|max:1000',
        ]);

        $year = now()->year;

        foreach ($this->resultFiles as $file) {
            $stored = $file->storePublicly("results/lab/{$year}", 'public');

            ResultUpload::create([
                'request_type' => 'lab',
                'request_id'   => $this->labRequest->id,
                'visit_id'     => $this->labRequest->visit_id,
                'patient_id'   => $this->labRequest->patient_id,
                'uploaded_by'  => auth()->id(),
                'file_path'    => $stored,
                'file_name'    => $file->getClientOriginalName(),
                'file_mime'    => $file->getMimeType(),
                'file_size'    => $file->getSize(),
                'notes'        => trim($this->notes) ?: null,
            ]);
        }

        $this->labRequest->update([
            'specimen_collected' => trim($this->specimenCollected) ?: $this->labRequest->specimen_collected,
            'test_done_at'       => now(),   // always auto-set on complete
            'status'             => LabRequest::STATUS_COMPLETED,
        ]);

        ActivityLog::record(
            action:       'uploaded_lab_result',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $this->labRequest,
            subjectLabel: $this->labRequest->request_no . ' — ' . ($this->labRequest->patient->full_name ?? ''),
            newValues: [
                'request_no'  => $this->labRequest->request_no,
                'files_count' => count($this->resultFiles),
                'uploaded_by' => auth()->user()->name,
            ],
            panel: 'tech',
        );

        $this->sendNotifications();

        Notification::make()
            ->title($this->labRequest->request_no . ' marked as completed.')
            ->success()->send();

        $this->redirect(TechDashboard::getUrl());
    }

    private function sendNotifications(): void
    {
        $req         = $this->labRequest;
        $patientName = $req->patient?->full_name ?? 'Patient';
        $caseNo      = $req->patient?->case_no   ?? '';
        $title       = "Lab Result Ready — {$req->request_no}";
        $body        = "{$patientName} ({$caseNo})";

        $recipients = collect();
        if ($req->doctor_id) {
            $doc = User::find($req->doctor_id);
            if ($doc) $recipients->push($doc);
        }
        $nurses = User::where('is_active', true)
            ->where('panel', 'nurse')
            ->whereHas('roles', fn ($q) => $q->where('name', 'nurse'))
            ->get();
        $recipients = $recipients->merge($nurses)->unique('id');

        foreach ($recipients as $r) {
            Notification::make()
                ->title($title)->body($body)
                ->icon('heroicon-o-beaker')->iconColor('success')
                ->sendToDatabase($r);
        }
    }

    public function goBack(): void
    {
        $this->redirect(TechDashboard::getUrl());
    }
}