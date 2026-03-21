<?php

namespace App\Filament\Tech\Pages;

use App\Models\ActivityLog;
use App\Models\RadiologyRequest;
use App\Models\ResultUpload;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

class ViewRadRequest extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon           = 'heroicon-o-eye-dropper';
    protected static string  $view                     = 'filament.tech.pages.view-rad-request';
    protected static ?string $title                    = 'Radiology Request';
    protected static bool    $shouldRegisterNavigation = false;

    #[Url]
    public ?int $requestId = null;

    public ?RadiologyRequest $radRequest = null;

    // Tech Action fields
    public array  $resultFiles    = [];
    public string $interpretation = '';
    public string $notes          = '';

    public function mount(): void
    {
        if (!$this->requestId) {
            $this->redirect(TechDashboard::getUrl());
            return;
        }

        $this->radRequest = RadiologyRequest::with([
            'visit.patient', 'doctor', 'results.uploadedBy',
        ])->find($this->requestId);

        if (!$this->radRequest) {
            Notification::make()->title('Request not found.')->danger()->send();
            $this->redirect(TechDashboard::getUrl());
            return;
        }

        $this->interpretation = $this->radRequest->radiologist_interpretation ?? '';
    }

    // ── Step 1: Mark as Received ──────────────────────────────────────────────

    public function markReceived(): void
    {
        if ($this->radRequest->request_received_at) {
            return;
        }
        $this->radRequest->update([
            'request_received_at' => now(),
            'status'              => RadiologyRequest::STATUS_IN_PROGRESS,
        ]);
        $this->radRequest->refresh();
        Notification::make()->title('Request marked as received.')->success()->send();
    }

    // ── Step 2: Exam Started — records actual timestamp ───────────────────────

    public function markExamStarted(): void
    {
        if ($this->radRequest->exam_started_at) {
            return;
        }
        $this->radRequest->update([
            'exam_started_at' => now(),
            'status'          => RadiologyRequest::STATUS_IN_PROGRESS,
        ]);
        $this->radRequest->refresh();
        Notification::make()->title('Exam started time recorded.')->success()->send();
    }

    // ── Remove a pending file before completing ───────────────────────────────

    public function removeFile(int $index): void
    {
        $files = $this->resultFiles;
        unset($files[$index]);
        $this->resultFiles = array_values($files);
    }

    // ── Step 3: Complete — upload files, record exam_done_at ─────────────────

    public function saveResult(): void
    {
        $this->validate([
            'resultFiles'    => 'required|array|min:1',
            'resultFiles.*'  => 'file|mimes:pdf,jpg,jpeg,png,webp|max:30720',
            'interpretation' => 'nullable|string|max:5000',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $year   = now()->year;
        $interp = trim($this->interpretation);

        foreach ($this->resultFiles as $file) {
            $stored = $file->storePublicly("results/radiology/{$year}", 'public');

            ResultUpload::create([
                'request_type'   => 'radiology',
                'request_id'     => $this->radRequest->id,
                'visit_id'       => $this->radRequest->visit_id,
                'patient_id'     => $this->radRequest->patient_id,
                'uploaded_by'    => auth()->id(),
                'file_path'      => $stored,
                'file_name'      => $file->getClientOriginalName(),
                'file_mime'      => $file->getMimeType(),
                'file_size'      => $file->getSize(),
                'interpretation' => $interp ?: null,
                'notes'          => trim($this->notes) ?: null,
            ]);
        }

        $updateData = [
            'status'       => RadiologyRequest::STATUS_COMPLETED,
            'exam_done_at' => now(),   // always auto-set on complete
        ];
        if ($interp) {
            $updateData['radiologist_interpretation'] = $interp;
        }
        $this->radRequest->update($updateData);

        ActivityLog::record(
            action:       'uploaded_radiology_result',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $this->radRequest,
            subjectLabel: $this->radRequest->request_no . ' — ' . ($this->radRequest->patient->full_name ?? ''),
            newValues: [
                'request_no'    => $this->radRequest->request_no,
                'modality'      => $this->radRequest->modality,
                'files_count'   => count($this->resultFiles),
                'has_interpret' => !empty($interp),
                'uploaded_by'   => auth()->user()->name,
            ],
            panel: 'tech',
        );

        $this->sendNotifications();

        Notification::make()
            ->title($this->radRequest->request_no . ' marked as completed.')
            ->success()->send();

        $this->redirect(TechDashboard::getUrl());
    }

    private function sendNotifications(): void
    {
        $req         = $this->radRequest;
        $patientName = $req->patient?->full_name ?? 'Patient';
        $caseNo      = $req->patient?->case_no   ?? '';
        $title       = "Radiology Result Ready — {$req->request_no}";
        $body        = "{$patientName} ({$caseNo}) · " . ($req->modality ?? '');

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
                ->icon('heroicon-o-eye-dropper')->iconColor('success')
                ->sendToDatabase($r);
        }
    }

    public function goBack(): void
    {
        $this->redirect(TechDashboard::getUrl());
    }
}