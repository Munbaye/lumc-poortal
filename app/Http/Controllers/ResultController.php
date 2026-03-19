<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\LabRequest;
use App\Models\RadiologyRequest;
use App\Models\ResultUpload;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * ResultController — handles tech result file uploads.
 */
class ResultController extends Controller
{
    /**
     * Upload a laboratory result file.
     * Called from the Tech "View Lab Request" page.
     */
    public function uploadLabResult(Request $request, LabRequest $labRequest): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'result_file' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:20480', // 20 MB max
            'notes'       => 'nullable|string|max:1000',
        ]);

        if ($labRequest->isCompleted()) {
            return response()->json(['success' => false, 'message' => 'This request is already completed.'], 422);
        }

        $file      = $request->file('result_file');
        $year      = now()->year;
        $storedAs  = $file->store("results/lab/{$year}", 'public');

        $upload = ResultUpload::create([
            'request_type' => 'lab',
            'request_id'   => $labRequest->id,
            'visit_id'     => $labRequest->visit_id,
            'patient_id'   => $labRequest->patient_id,
            'uploaded_by'  => auth()->id(),
            'file_path'    => $storedAs,
            'file_name'    => $file->getClientOriginalName(),
            'file_mime'    => $file->getClientMimeType(),
            'file_size'    => $file->getSize(),
            'notes'        => $request->notes ?: null,
        ]);

        // Mark request as completed
        $labRequest->update(['status' => LabRequest::STATUS_COMPLETED]);

        // Activity log
        ActivityLog::record(
            action:       'uploaded_lab_result',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $upload,
            subjectLabel: $labRequest->request_no . ' — ' . ($labRequest->patient->full_name ?? ''),
            newValues: [
                'request_no' => $labRequest->request_no,
                'file'       => $file->getClientOriginalName(),
                'uploaded_by'=> auth()->user()->name,
            ],
            panel: 'tech',
        );

        // Notify doctor and nurses
        $this->notifyStaff($labRequest, 'lab', $upload);

        return response()->json([
            'success'    => true,
            'message'    => "Lab result uploaded. {$labRequest->request_no} marked as completed.",
            'file_url'   => Storage::url($storedAs),
            'upload_id'  => $upload->id,
        ]);
    }

    /**
     * Upload a radiology result file with optional interpretation.
     * Called from the Tech "View Radiology Request" page.
     */
    public function uploadRadResult(Request $request, RadiologyRequest $radRequest): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'result_file'    => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:30720', // 30 MB
            'interpretation' => 'nullable|string|max:5000',
            'notes'          => 'nullable|string|max:1000',
        ]);

        if ($radRequest->isCompleted()) {
            return response()->json(['success' => false, 'message' => 'This request is already completed.'], 422);
        }

        $file     = $request->file('result_file');
        $year     = now()->year;
        $storedAs = $file->store("results/radiology/{$year}", 'public');

        $interpretation = trim($request->interpretation ?? '');

        $upload = ResultUpload::create([
            'request_type'   => 'radiology',
            'request_id'     => $radRequest->id,
            'visit_id'       => $radRequest->visit_id,
            'patient_id'     => $radRequest->patient_id,
            'uploaded_by'    => auth()->id(),
            'file_path'      => $storedAs,
            'file_name'      => $file->getClientOriginalName(),
            'file_mime'      => $file->getClientMimeType(),
            'file_size'      => $file->getSize(),
            'interpretation' => $interpretation ?: null,
            'notes'          => $request->notes ?: null,
        ]);

        // Also write interpretation back into the radiology_request record
        // so it pre-fills the request document if re-opened
        $updateData = ['status' => RadiologyRequest::STATUS_COMPLETED];
        if ($interpretation) {
            $updateData['radiologist_interpretation'] = $interpretation;
            $updateData['exam_done_at'] = now();
        }
        $radRequest->update($updateData);

        // Activity log
        ActivityLog::record(
            action:       'uploaded_radiology_result',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $upload,
            subjectLabel: $radRequest->request_no . ' — ' . ($radRequest->patient->full_name ?? ''),
            newValues: [
                'request_no'     => $radRequest->request_no,
                'modality'       => $radRequest->modality,
                'file'           => $file->getClientOriginalName(),
                'has_interpret'  => !empty($interpretation),
                'uploaded_by'    => auth()->user()->name,
            ],
            panel: 'tech',
        );

        // Notify doctor and nurses
        $this->notifyStaff($radRequest, 'radiology', $upload);

        return response()->json([
            'success'   => true,
            'message'   => "Radiology result uploaded. {$radRequest->request_no} marked as completed.",
            'file_url'  => Storage::url($storedAs),
            'upload_id' => $upload->id,
        ]);
    }

    // ── Private: send Filament DB notifications to doctor + nurses ────────────

    private function notifyStaff(LabRequest|RadiologyRequest $req, string $type, ResultUpload $upload): void
    {
        $isLab = $type === 'lab';
        $icon  = $isLab ? 'heroicon-o-beaker' : 'heroicon-o-eye-dropper';
        $label = $isLab ? 'Lab' : 'Radiology';

        $patientName = $req->patient?->full_name ?? 'Patient';
        $caseNo      = $req->patient?->case_no   ?? '';

        $title = "{$label} Result Ready — {$req->request_no}";
        $body  = "{$patientName} ({$caseNo}) · " . ($req->requesting_physician ?? '');

        // Collect recipients: doctor who ordered + nurses assigned to the visit
        $recipients = collect();

        // Ordering doctor
        if ($req->doctor_id) {
            $doc = User::find($req->doctor_id);
            if ($doc) $recipients->push($doc);
        }

        // Active nurses (they monitor all admitted patients)
        $nurses = User::where('is_active', true)
            ->where('panel', 'nurse')
            ->whereHas('roles', fn ($q) => $q->where('name', 'nurse'))
            ->get();
        $recipients = $recipients->merge($nurses)->unique('id');

        foreach ($recipients as $recipient) {
            Notification::make()
                ->title($title)
                ->body($body)
                ->icon($icon)
                ->iconColor('success')
                ->sendToDatabase($recipient);
        }
    }
}