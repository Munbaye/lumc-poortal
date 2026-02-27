<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'action',
        'subject_type',
        'subject_id',
        'subject_label',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'panel',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Static helper — call ActivityLog::record(...) anywhere in the app.
    // Automatically fills ip_address, user_agent, user_id, panel from context.
    // ──────────────────────────────────────────────────────────────────────────
    public static function record(
        string $action,
        string $category       = 'system',
        ?Model $subject        = null,
        ?string $subjectLabel  = null,
        array  $oldValues      = [],
        array  $newValues      = [],
        ?string $panel         = null
    ): self {
        // Detect current Filament panel from URL
        if (!$panel) {
            $path = Request::path();
            foreach (['admin','doctor','nurse','clerk','tech'] as $p) {
                if (str_starts_with($path, $p . '/') || $path === $p) {
                    $panel = $p;
                    break;
                }
            }
        }

        return static::create([
            'user_id'       => auth()->id(),
            'category'      => $category,
            'action'        => $action,
            'subject_type'  => $subject ? class_basename($subject) : null,
            'subject_id'    => $subject?->getKey(),
            'subject_label' => $subjectLabel,
            'old_values'    => $oldValues  ?: null,
            'new_values'    => $newValues  ?: null,
            'ip_address'    => Request::ip(),
            'user_agent'    => Request::userAgent(),
            'panel'         => $panel,
        ]);
    }

    // ── Category constants ────────────────────────────────────────────────────
    const CAT_AUTH     = 'auth';
    const CAT_PATIENT  = 'patient';
    const CAT_VITALS   = 'vitals';
    const CAT_CLINICAL = 'clinical';
    const CAT_ORDERS   = 'orders';
    const CAT_UPLOADS  = 'uploads';
    const CAT_ADMIN    = 'admin';
    const CAT_SYSTEM   = 'system';

    // ── Action constants ──────────────────────────────────────────────────────
    // AUTH
    const ACT_LOGIN        = 'login';
    const ACT_LOGOUT       = 'logout';
    const ACT_LOGIN_FAILED = 'login_failed';
    // PATIENT
    const ACT_CREATED_PATIENT  = 'created_patient';
    const ACT_UPDATED_PATIENT  = 'updated_patient';
    const ACT_DELETED_PATIENT  = 'deleted_patient';
    const ACT_RESTORED_PATIENT = 'restored_patient';
    // VITALS
    const ACT_RECORDED_VITALS = 'recorded_vitals';
    // CLINICAL
    const ACT_ASSESSED_PATIENT = 'assessed_patient';
    const ACT_ADMITTED_PATIENT = 'admitted_patient';
    const ACT_DISCHARGED_PATIENT = 'discharged_patient';
    // ORDERS
    const ACT_ADDED_ORDER    = 'added_order';
    const ACT_COMPLETED_ORDER = 'completed_order';
    // UPLOADS
    const ACT_UPLOADED_RESULT = 'uploaded_result';
    // ADMIN
    const ACT_CREATED_USER = 'created_user';
    const ACT_UPDATED_USER = 'updated_user';
    const ACT_DELETED_USER = 'deleted_user';
    const ACT_TOGGLED_USER = 'toggled_user_active';

    // ── Human-readable labels (for display) ──────────────────────────────────
    public static function actionLabel(string $action): string
    {
        return match ($action) {
            'login'               => 'Logged In',
            'logout'              => 'Logged Out',
            'login_failed'        => 'Login Failed',
            'created_patient'     => 'Patient Created',
            'updated_patient'     => 'Patient Updated',
            'deleted_patient'     => 'Patient Deleted',
            'restored_patient'    => 'Patient Restored',
            'recorded_vitals'     => 'Vitals Recorded',
            'assessed_patient'    => 'Assessment Saved',
            'admitted_patient'    => 'Patient Admitted',
            'discharged_patient'  => 'Patient Discharged',
            'added_order'         => 'Order Added',
            'completed_order'     => 'Order Completed',
            'uploaded_result'     => 'Result Uploaded',
            'created_user'        => 'User Created',
            'updated_user'        => 'User Updated',
            'deleted_user'        => 'User Deleted',
            'toggled_user_active' => 'User Activated/Deactivated',
            default               => ucwords(str_replace('_', ' ', $action)),
        };
    }

    public static function categoryLabel(string $category): string
    {
        return match ($category) {
            'auth'     => 'Authentication',
            'patient'  => 'Patient Registry',
            'vitals'   => 'Vital Signs',
            'clinical' => 'Clinical',
            'orders'   => 'Doctor\'s Orders',
            'uploads'  => 'Lab Uploads',
            'admin'    => 'User Management',
            'system'   => 'System',
            default    => ucfirst($category),
        };
    }
}