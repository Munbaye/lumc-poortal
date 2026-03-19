<?php

namespace App\Filament\Tech\Pages;

use App\Models\LabRequest;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

/**
 * ViewLabRequest — tech views a pending lab request and uploads the result.
 *
 * URL: /tech/view-lab-request?requestId={id}
 *
 * Layout:
 *   - Read-only patient + request details panel
 *   - Tests ordered (read-only list)
 *   - Upload section at bottom (file + optional notes)
 */
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

    // Upload form
    public $resultFile   = null;
    public string $notes = '';

    public function mount(): void
    {
        if (!$this->requestId) {
            $this->redirect(TechDashboard::getUrl());
            return;
        }

        $this->labRequest = LabRequest::with(['visit.patient', 'doctor', 'result.uploadedBy'])
            ->find($this->requestId);

        if (!$this->labRequest) {
            Notification::make()->title('Request not found.')->danger()->send();
            $this->redirect(TechDashboard::getUrl());
        }
    }

    public function saveResult(): void
    {
        $this->validate([
            'resultFile' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:20480',
            'notes'      => 'nullable|string|max:1000',
        ]);

        // Delegate to ResultController logic via a service-style call
        // We call the same logic inline here to avoid HTTP round-trip from Livewire
        $file    = $this->resultFile;
        $year    = now()->year;
        $stored  = $file->storePublicly("results/lab/{$year}", 'public');

        \App\Models\ResultUpload::create([
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

        $this->labRequest->update(['status' => LabRequest::STATUS_COMPLETED]);

        \App\Models\ActivityLog::record(
            action:       'uploaded_lab_result',
            category:     \App\Models\ActivityLog::CAT_CLINICAL,
            subject:      $this->labRequest,
            subjectLabel: $this->labRequest->request_no . ' — ' . ($this->labRequest->patient->full_name ?? ''),
            newValues: [
                'request_no'  => $this->labRequest->request_no,
                'file'        => $file->getClientOriginalName(),
                'uploaded_by' => auth()->user()->name,
            ],
            panel: 'tech',
        );

        // Notify doctor + nurses
        $this->sendNotifications('lab');

        Notification::make()
            ->title('Result uploaded — ' . $this->labRequest->request_no . ' marked as completed.')
            ->success()->send();

        $this->redirect(TechDashboard::getUrl());
    }

    private function sendNotifications(string $type): void
    {
        $req         = $this->labRequest;
        $patientName = $req->patient?->full_name ?? 'Patient';
        $caseNo      = $req->patient?->case_no   ?? '';
        $title       = "Lab Result Ready — {$req->request_no}";
        $body        = "{$patientName} ({$caseNo})";

        $recipients = collect();
        if ($req->doctor_id) {
            $doc = \App\Models\User::find($req->doctor_id);
            if ($doc) $recipients->push($doc);
        }
        $nurses = \App\Models\User::where('is_active', true)
            ->where('panel', 'nurse')
            ->whereHas('roles', fn ($q) => $q->where('name', 'nurse'))
            ->get();
        $recipients = $recipients->merge($nurses)->unique('id');

        foreach ($recipients as $r) {
            \Filament\Notifications\Notification::make()
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