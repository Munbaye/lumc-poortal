<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\AdmissionRecord;
use App\Models\ErRecord;
use App\Models\Visit;
use Illuminate\Http\Request;

class ClerkFormController extends Controller
{
    // ── ER Record ────────────────────────────────────────────────────────────

    public function erRecord(Visit $visit)
    {
        $visit->load(['patient', 'medicalHistory.doctor', 'latestVitals', 'erRecord', 'nursesNotes']);
        $erRecord = $visit->erRecord;
        return view('forms.emergency-record', compact('visit', 'erRecord'));
    }

    public function erRecordSave(Request $request, Visit $visit): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'health_record_no'                => 'nullable|string|max:50',
            'type_of_service'                 => 'nullable|string|max:100',
            'medico_legal'                    => 'nullable|boolean',
            'case_type'                       => 'nullable|string|max:10',
            'notified_proper_authority'       => 'nullable|string|max:3',
            'patient_family_name'             => 'nullable|string|max:100',
            'patient_first_name'              => 'nullable|string|max:100',
            'patient_middle_name'             => 'nullable|string|max:100',
            'permanent_address'               => 'nullable|string',
            'telephone_no'                    => 'nullable|string|max:30',
            'nationality'                     => 'nullable|string|max:50',
            'age'                             => 'nullable|string|max:10',
            'birthdate'                       => 'nullable|string|max:20',
            'sex'                             => 'nullable|string|max:10',
            'civil_status'                    => 'nullable|string|max:20',
            'employer_name'                   => 'nullable|string|max:150',
            'employer_phone'                  => 'nullable|string|max:30',
            'registration_date'               => 'nullable|string|max:20',
            'registration_time'               => 'nullable|string|max:10',
            'brought_by'                      => 'nullable|string|max:50',
            'condition_on_arrival'            => 'nullable|string|max:50',
            'temperature'                     => 'nullable|string|max:10',
            'temperature_site'                => 'nullable|string|max:20',
            'pulse_rate'                      => 'nullable|string|max:10',
            'blood_pressure'                  => 'nullable|string|max:20',
            'cardiac_rate'                    => 'nullable|string|max:10',
            'respiratory_rate'                => 'nullable|string|max:10',
            'height_cm'                       => 'nullable|string|max:10',
            'weight_kg'                       => 'nullable|string|max:10',
            'chief_complaint'                 => 'nullable|string',
            'allergies'                       => 'nullable|string',
            'current_medication'              => 'nullable|string',
            'physical_findings_and_diagnosis' => 'nullable|string',
            'treatment'                       => 'nullable|string',
            'disposition_date'                => 'nullable|string|max:20',
            'disposition_time'                => 'nullable|string|max:10',
            'disposition'                     => 'nullable|string|max:30',
            'condition_on_discharge'          => 'nullable|string',
        ]);

        // Parse dates from input[type=date] format (Y-m-d) — also handles m/d/Y fallback
        $parseDate = function (?string $d): ?string {
            if (!$d || trim($d) === '') return null;
            $d = trim($d);
            // Already Y-m-d (from input[type=date])
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) return $d;
            // m/d/Y fallback
            try { return \Carbon\Carbon::createFromFormat('m/d/Y', $d)->format('Y-m-d'); }
            catch (\Exception) { return null; }
        };

        $fillable = [
            'visit_id'    => $visit->id,
            'patient_id'  => $visit->patient_id,
            'filled_by'   => auth()->id(),
            'health_record_no'                => $data['health_record_no'] ?? null,
            'type_of_service'                 => $data['type_of_service'] ?? null,
            'medico_legal'                    => (bool)($data['medico_legal'] ?? false),
            'case_type'                       => $data['case_type'] ?? null,
            'notified_proper_authority'       => $data['notified_proper_authority'] ?? null,
            'patient_family_name'             => $data['patient_family_name'] ?? null,
            'patient_first_name'              => $data['patient_first_name'] ?? null,
            'patient_middle_name'             => $data['patient_middle_name'] ?? null,
            'permanent_address'               => $data['permanent_address'] ?? null,
            'telephone_no'                    => $data['telephone_no'] ?? null,
            'nationality'                     => $data['nationality'] ?? null,
            'age'                             => is_numeric($data['age'] ?? '') ? (int)$data['age'] : null,
            'birthdate'                       => $parseDate($data['birthdate'] ?? null),
            'sex'                             => $data['sex'] ?? null,
            'civil_status'                    => $data['civil_status'] ?? null,
            'employer_name'                   => $data['employer_name'] ?? null,
            'employer_phone'                  => $data['employer_phone'] ?? null,
            'registration_date'               => $parseDate($data['registration_date'] ?? null),
            'registration_time'               => $data['registration_time'] ?? null,
            'brought_by'                      => $data['brought_by'] ?? null,
            'condition_on_arrival'            => $data['condition_on_arrival'] ?? null,
            'temperature'                     => is_numeric($data['temperature'] ?? '') ? (float)$data['temperature'] : null,
            'temperature_site'                => $data['temperature_site'] ?? null,
            'pulse_rate'                      => is_numeric($data['pulse_rate'] ?? '') ? (int)$data['pulse_rate'] : null,
            'blood_pressure'                  => $data['blood_pressure'] ?? null,
            'cardiac_rate'                    => is_numeric($data['cardiac_rate'] ?? '') ? (int)$data['cardiac_rate'] : null,
            'respiratory_rate'                => is_numeric($data['respiratory_rate'] ?? '') ? (int)$data['respiratory_rate'] : null,
            'height_cm'                       => is_numeric($data['height_cm'] ?? '') ? (float)$data['height_cm'] : null,
            'weight_kg'                       => is_numeric($data['weight_kg'] ?? '') ? (float)$data['weight_kg'] : null,
            'chief_complaint'                 => $data['chief_complaint'] ?? null,
            'allergies'                       => $data['allergies'] ?? null,
            'current_medication'              => $data['current_medication'] ?? null,
            'physical_findings_and_diagnosis' => $data['physical_findings_and_diagnosis'] ?? null,
            'treatment'                       => $data['treatment'] ?? null,
            'disposition_date'                => $parseDate($data['disposition_date'] ?? null),
            'disposition_time'                => $data['disposition_time'] ?? null,
            'disposition'                     => $data['disposition'] ?? null,
            'condition_on_discharge'          => $data['condition_on_discharge'] ?? null,
        ];

        $erRecord = ErRecord::updateOrCreate(['visit_id' => $visit->id], $fillable);

        // Also update visit-level fields
        $visit->update([
            'brought_by'                => $data['brought_by']               ?? $visit->brought_by,
            'condition_on_arrival'      => $data['condition_on_arrival']      ?? $visit->condition_on_arrival,
            'medico_legal'              => (bool)($data['medico_legal'] ?? false),
            'type_of_service'           => $data['type_of_service']           ?? $visit->type_of_service,
            'notified_proper_authority' => $data['notified_proper_authority'] ?? $visit->notified_proper_authority,
        ]);

        ActivityLog::record(
            action:       'saved_er_record',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $erRecord,
            subjectLabel: $visit->patient->full_name . ' (' . $visit->patient->case_no . ')',
            newValues:    ['er_record_id' => $erRecord->id, 'case_type' => $erRecord->case_type],
            panel:        'clerk',
        );

        return response()->json(['success' => true, 'message' => 'ER Record saved.', 'id' => $erRecord->id]);
    }

    // ── Admission & Discharge Record ──────────────────────────────────────────

    public function admRecord(Visit $visit)
    {
        $visit->load(['patient', 'medicalHistory.doctor', 'erRecord', 'admissionRecord']);
        $erRecord  = $visit->erRecord;
        $admRecord = $visit->admissionRecord;
        return view('forms.admission-discharge-record', compact('visit', 'erRecord', 'admRecord'));
    }

    public function admRecordSave(Request $request, Visit $visit): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'patient_name_display'    => 'nullable|string|max:250', // formatted display name
            'permanent_address'       => 'nullable|string',
            'telephone_no'            => 'nullable|string|max:30',
            'sex'                     => 'nullable|string|max:10',
            'civil_status'            => 'nullable|string|max:20',
            'birthdate'               => 'nullable|string|max:20',
            'age'                     => 'nullable|string|max:10',
            'birthplace'              => 'nullable|string|max:100',
            'nationality'             => 'nullable|string|max:50',
            'religion'                => 'nullable|string|max:100',
            'occupation'              => 'nullable|string|max:100',
            'employer_name'           => 'nullable|string|max:150',
            'employer_address'        => 'nullable|string',
            'employer_phone'          => 'nullable|string|max:30',
            'father_name'             => 'nullable|string|max:150',
            'father_address'          => 'nullable|string',
            'father_phone'            => 'nullable|string|max:30',
            'mother_maiden_name'      => 'nullable|string|max:150',
            'mother_address'          => 'nullable|string',
            'mother_phone'            => 'nullable|string|max:30',
            'admission_date'          => 'nullable|string|max:20',
            'admission_time'          => 'nullable|string|max:10',
            'discharge_date'          => 'nullable|string|max:20',
            'discharge_time'          => 'nullable|string|max:10',
            'total_days'              => 'nullable|string|max:10',
            'ward_service'            => 'nullable|string|max:100',
            'type_of_admission'       => 'nullable|string|max:10',
            'social_service_class'    => 'nullable|string|max:5',
            'alert'                   => 'nullable|string|max:200',
            'allergic_to'             => 'nullable|string',
            'health_insurance_name'   => 'nullable|string|max:150',
            'philhealth_id'           => 'nullable|string|max:30',
            'philhealth_type'         => 'nullable|string|max:30',
            'data_furnished_by'       => 'nullable|string|max:150',
            'data_furnished_address'  => 'nullable|string',
            'data_furnished_relation' => 'nullable|string|max:100',
            'admission_diagnosis'     => 'nullable|string',
            'final_diagnosis'         => 'nullable|string',
            'other_diagnosis'         => 'nullable|string',
            'principal_operation'     => 'nullable|string',
            'disposition'             => 'nullable|string|max:30',
            'results'                 => 'nullable|string|max:50',
        ]);

        $parseDate = function (?string $d): ?string {
            if (!$d || trim($d) === '') return null;
            $d = trim($d);
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) return $d;
            try { return \Carbon\Carbon::createFromFormat('m/d/Y', $d)->format('Y-m-d'); }
            catch (\Exception) { return null; }
        };

        // Extract patient name components from formatted display string if available
        // Format: "FAMILY, FIRST MIDDLE"
        $patFamilyName = null;
        $patFirstName  = null;
        $patMiddleName = null;
        $displayName   = $data['patient_name_display'] ?? '';
        if ($displayName && str_contains($displayName, ',')) {
            [$fam, $rest] = explode(',', $displayName, 2);
            $nameParts = array_values(array_filter(explode(' ', trim($rest))));
            $patFamilyName = trim($fam);
            $patFirstName  = $nameParts[0] ?? null;
            $patMiddleName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : null;
        }

        // Determine payment_class from visit (already set by previous ADM save or default)
        $paymentClass = $visit->payment_class ?? 'Charity';

        $fillable = [
            'visit_id'                => $visit->id,
            'patient_id'              => $visit->patient_id,
            'filled_by'               => auth()->id(),
            'patient_family_name'     => $patFamilyName,
            'patient_first_name'      => $patFirstName,
            'patient_middle_name'     => $patMiddleName,
            'permanent_address'       => $data['permanent_address'] ?: null,
            'telephone_no'            => $data['telephone_no'] ?: null,
            'sex'                     => $data['sex'] ?: null,
            'civil_status'            => $data['civil_status'] ?: null,
            'birthdate'               => $parseDate($data['birthdate'] ?? null),
            'age'                     => is_numeric($data['age'] ?? '') ? (int)$data['age'] : null,
            'birthplace'              => $data['birthplace'] ?: null,
            'nationality'             => $data['nationality'] ?: null,
            'religion'                => $data['religion'] ?: null,
            'occupation'              => $data['occupation'] ?: null,
            'employer_name'           => $data['employer_name'] ?: null,
            'employer_address'        => $data['employer_address'] ?: null,
            'employer_phone'          => $data['employer_phone'] ?: null,
            'father_name'             => $data['father_name'] ?: null,
            'father_address'          => $data['father_address'] ?: null,
            'father_phone'            => $data['father_phone'] ?: null,
            'mother_maiden_name'      => $data['mother_maiden_name'] ?: null,
            'mother_address'          => $data['mother_address'] ?: null,
            'mother_phone'            => $data['mother_phone'] ?: null,
            'admission_date'          => $parseDate($data['admission_date'] ?? null),
            'admission_time'          => $data['admission_time'] ?: null,
            'discharge_date'          => $parseDate($data['discharge_date'] ?? null),
            'discharge_time'          => $data['discharge_time'] ?: null,
            'total_days'              => is_numeric($data['total_days'] ?? '') ? (int)$data['total_days'] : null,
            'ward_service'            => $data['ward_service'] ?: null,
            'type_of_admission'       => $data['type_of_admission'] ?: null,
            'social_service_class'    => $data['social_service_class'] ?: null,
            'payment_class'           => $paymentClass, // preserved from visit
            'alert'                   => $data['alert'] ?: null,
            'allergic_to'             => $data['allergic_to'] ?: null,
            'health_insurance_name'   => $data['health_insurance_name'] ?: null,
            'philhealth_id'           => $data['philhealth_id'] ?: null,
            'philhealth_type'         => $data['philhealth_type'] ?: null,
            'data_furnished_by'       => $data['data_furnished_by'] ?: null,
            'data_furnished_address'  => $data['data_furnished_address'] ?: null,
            'data_furnished_relation' => $data['data_furnished_relation'] ?: null,
            'admission_diagnosis'     => $data['admission_diagnosis'] ?: null,
            'final_diagnosis'         => $data['final_diagnosis'] ?: null,
            'other_diagnosis'         => $data['other_diagnosis'] ?: null,
            'principal_operation'     => $data['principal_operation'] ?: null,
            'disposition'             => $data['disposition'] ?: null,
            'results'                 => $data['results'] ?: null,
        ];

        $admRecord = AdmissionRecord::updateOrCreate(['visit_id' => $visit->id], $fillable);

        // Back-fill patient demographics
        $patient = $visit->patient;
        $patient->update(array_filter([
            'birthplace'           => $data['birthplace'] ?: null,
            'religion'             => $data['religion'] ?: null,
            'nationality'          => $data['nationality'] ?: null,
            'employer_name'        => $data['employer_name'] ?: null,
            'employer_address'     => $data['employer_address'] ?: null,
            'employer_phone'       => $data['employer_phone'] ?: null,
            'father_full_name'     => $data['father_name'] ?: null,
            'father_address'       => $data['father_address'] ?: null,
            'father_phone'         => $data['father_phone'] ?: null,
            'mother_maiden_name'   => $data['mother_maiden_name'] ?: null,
            'mother_address'       => $data['mother_address'] ?: null,
            'mother_phone'         => $data['mother_phone'] ?: null,
            'philhealth_id'        => $data['philhealth_id'] ?: null,
            'philhealth_type'      => $data['philhealth_type'] ?: null,
            'social_service_class' => $data['social_service_class'] ?: null,
        ], fn ($v) => $v !== null));

        ActivityLog::record(
            action:       'saved_admission_record',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $admRecord,
            subjectLabel: $visit->patient->full_name . ' (' . $visit->patient->case_no . ')',
            newValues:    ['adm_record_id' => $admRecord->id, 'payment_class' => $paymentClass],
            panel:        'clerk',
        );

        return response()->json([
            'success'       => true,
            'message'       => 'Admission Record saved.',
            'id'            => $admRecord->id,
            'payment_class' => $paymentClass,
        ]);
    }
}