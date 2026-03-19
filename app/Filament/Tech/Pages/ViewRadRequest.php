<?php

namespace App\Filament\Tech\Pages;

use App\Models\RadiologyRequest;
use App\Models\ActivityLog;
use App\Models\ResultUpload;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

/**
 * ViewRadRequest — tech views a pending radiology request and uploads the result.
 *
 * Unlike lab requests, radiology also has an "interpretation" textarea
 * for the radiologist to fill in their findings.
 */
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

    // Upload form
    public $resultFile          = null;
    public string $interpretation = '';
    public string $notes          = '';

    public function mount(): void
    {
        if (!$this->requestId) {
            $this->redirect(TechDashboard::getUrl());
            return;
        }

        $this->radRequest = RadiologyRequest::with(['visit.patient', 'doctor', 'result.uploadedBy'])
            ->find($this->requestId);

        if (!$this->radRequest) {
            Notification::make()->title('Request not found.')->danger()->send();
            $this->redirect(TechDashboard::getUrl());
            return;
        }

        // Pre-fill interpretation if already saved on the request
        $this->interpretation = $this->radRequest->radiologist_interpretation ?? '';
    }

    public function saveResult(): void
    {
        $this->validate([
            'resultFile'     => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:30720',
            'interpretation' => 'nullable|string|max:5000',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $file   = $this->resultFile;
        $year   = now()->year;
        $stored = $file->storePublicly("results/radiology/{$year}", 'public');

        $interp = trim($this->interpretation);

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

        // Mark completed + write interpretation back to request record
        $updateData = ['status' => RadiologyRequest::STATUS_COMPLETED];
        if ($interp) {
            $updateData['radiologist_interpretation'] = $interp;
            $updateData['exam_done_at'] = now();
        }
        $this->radRequest->update($updateData);

        ActivityLog::record(
            action:       'uploaded_radiology_result',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $this->radRequest,
            subjectLabel: $this->radRequest->request_no . ' — ' . ($this->radRequest->patient->full_name ?? ''),
            newValues: [
                'request_no'     => $this->radRequest->request_no,
                'modality'       => $this->radRequest->modality,
                'file'           => $file->getClientOriginalName(),
                'has_interpret'  => !empty($interp),
                'uploaded_by'    => auth()->user()->name,
            ],
            panel: 'tech',
        );

        $this->sendNotifications();

        Notification::make()
            ->title('Result uploaded — ' . $this->radRequest->request_no . ' marked as completed.')
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