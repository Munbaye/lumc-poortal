<?php

namespace App\Notifications;

use App\Models\ResultUpload;
use App\Models\Visit;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ResultUploadedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ResultUpload $result,
        public Visit        $visit,
        public int          $totalUploaded,      // how many files were uploaded in this batch
        public bool         $isCritical = false, // NEW: critical value flag from tech's initial reading
    ) {}

    // Deliver via database (Filament reads from this for bell notifications)
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // What gets stored in the notifications table
    public function toDatabase(object $notifiable): array
    {
        $patient = $this->visit->patient;

        return [
            // ── Original fields — unchanged ───────────────────────────────────
            'title'       => $this->isCritical
                                ? '🚨 CRITICAL Result — ' . $this->result->test_name
                                : 'New Result Uploaded',
            'body'        => $this->isCritical
                                ? $this->totalUploaded . ' result(s) uploaded for '
                                    . $patient?->first_name . ' ' . $patient?->family_name
                                    . ' (' . $patient?->case_no . '). '
                                    . 'Critical value flagged — immediate review required.'
                                : $this->totalUploaded . ' result(s) uploaded for '
                                    . $patient?->first_name . ' ' . $patient?->family_name
                                    . ' (' . $patient?->case_no . ')',
            'result_id'   => $this->result->id,
            'visit_id'    => $this->visit->id,
            'test_name'   => $this->result->test_name,
            'result_type' => $this->result->result_type,
            'patient'     => $patient?->first_name . ' ' . $patient?->family_name,
            'case_no'     => $patient?->case_no,
            'icon'        => $this->isCritical
                                ? 'heroicon-o-exclamation-triangle'
                                : 'heroicon-o-beaker',
            'color'       => $this->isCritical ? 'danger' : 'success',

            // ── NEW: Critical value fields for the doctor's panel team ─────────
            // Always present so the doctor's interface can read them
            // directly from the notification data without a separate DB query.
            'is_critical'          => $this->isCritical,
            'critical_reason'      => $this->result->critical_reason ?? null,
            'critical_notified_at' => $this->result->critical_notified_at?->toISOString() ?? null,

            // ── NEW: Tech's initial impression ────────────────────────────────
            // Shown in the doctor's notification so they get a quick summary
            // before opening the full result file.
            'initial_impression'   => $this->result->initial_impression ?? null,

            // ── NEW: Result status ────────────────────────────────────────────
            'status'               => $this->result->status ?? 'pending_validation',
        ];
    }
}