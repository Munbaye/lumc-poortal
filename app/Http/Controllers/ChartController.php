<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\LabRequest;
use App\Models\RadiologyRequest;
use App\Models\Visit;
use Illuminate\Http\Request;

/**
 * Serves and saves all printable clinical document forms for the Doctor module.
 *
 * Routes (in routes/web.php inside auth middleware group):
 *
 *   GET  /forms/history-form/{visit}              → historyForm()
 *   GET  /forms/physical-exam-form/{visit}        → physicalExamForm()
 *   GET  /forms/lab-request/{visit}               → labRequest()
 *   POST /forms/lab-request/{visit}               → labRequestStore()
 *   GET  /forms/radiology-request/{visit}         → radiologyRequest()
 *   POST /forms/radiology-request/{visit}         → radiologyRequestStore()
 */
class ChartController extends Controller
{
    // ── Existing document forms ───────────────────────────────────────────────

    public function historyForm(Visit $visit): \Illuminate\View\View
    {
        $visit->loadMissing(['patient', 'medicalHistory.doctor']);
        return view('forms.history-form', [
            'visit'   => $visit,
            'patient' => $visit->patient,
            'history' => $visit->medicalHistory,
            'doctor'  => $visit->medicalHistory?->doctor,
            'today'   => now()->timezone('Asia/Manila')->format('F j, Y'),
        ]);
    }

    public function physicalExamForm(Visit $visit): \Illuminate\View\View
    {
        $visit->loadMissing(['patient', 'medicalHistory.doctor', 'latestVitals']);
        return view('forms.physical-examination-form', [
            'visit'   => $visit,
            'patient' => $visit->patient,
            'history' => $visit->medicalHistory,
            'doctor'  => $visit->medicalHistory?->doctor,
            'vitals'  => $visit->latestVitals,
            'today'   => now()->timezone('Asia/Manila')->format('F j, Y'),
        ]);
    }

    // ── Clinical Laboratory Request ───────────────────────────────────────────

    /**
     * Show the lab request form, pre-filled with patient/visit data.
     * Generates a new LAB-YYYY-NNNNN request number for the session.
     */
    public function labRequest(Visit $visit): \Illuminate\View\View
    {
        $visit->loadMissing(['patient', 'medicalHistory.doctor']);

        $patient    = $visit->patient;
        $doctor     = $visit->medicalHistory?->doctor;
        $requestNo  = LabRequest::generateRequestNo();

        return view('forms.lab-request-form', [
            'visit'               => $visit,
            'patient'             => $patient,
            'doctor'              => $doctor,
            'requestNo'           => $requestNo,
            'today'               => now()->timezone('Asia/Manila')->format('Y-m-d'),
            'todayDisplay'        => now()->timezone('Asia/Manila')->format('F j, Y'),
            'familyName'          => strtoupper($patient->family_name ?? ''),
            'firstName'           => strtoupper($patient->first_name  ?? ''),
            'middleName'          => strtoupper($patient->middle_name  ?? ''),
            'address'             => $patient->address    ?? '',
            'dateOfBirth'         => $patient->birthday   ? $patient->birthday->format('Y-m-d') : '',
            'age'                 => $patient->current_age ?? '',
            'sex'                 => $patient->sex ?? '',
            'hospitalNo'          => $patient->case_no  ?? '',
            'ward'                => $visit->admitted_service ?? $visit->medicalHistory?->service ?? '',
            'clinicalDiagnosis'   => $visit->admitting_diagnosis ?? $visit->medicalHistory?->diagnosis ?? '',
            'requestingPhysician' => $doctor ? $doctor->name : '',
        ]);
    }

    /**
     * Save a submitted lab request to the database and log the action.
     * Called via POST from the lab request form's "Submit Request" button.
     */
    public function labRequestStore(Request $request, Visit $visit): \Illuminate\Http\JsonResponse
    {
        $visit->loadMissing(['patient', 'medicalHistory.doctor']);

        $data = $request->validate([
            'request_no'           => 'required|string',
            'request_type'         => 'required|in:routine,stat',
            'stat_justification'   => 'nullable|string|max:500',
            'ward'                 => 'nullable|string|max:100',
            'clinical_diagnosis'   => 'nullable|string|max:500',
            'requesting_physician' => 'nullable|string|max:200',
            'tests'                => 'nullable|array',
            'tests.*'              => 'string|max:200',
            'specimen'             => 'nullable|string|max:200',
            'antibiotics_taken'    => 'nullable|string|max:300',
            'antibiotics_duration' => 'nullable|string|max:100',
            'other_tests'          => 'nullable|string|max:500',
            'date_requested'       => 'nullable|date',
        ]);

        $labRequest = LabRequest::create([
            'request_no'           => $data['request_no'],
            'visit_id'             => $visit->id,
            'patient_id'           => $visit->patient_id,
            'doctor_id'            => $visit->medicalHistory?->doctor_id,
            'submitted_by'         => auth()->id(),
            'ward'                 => $data['ward'] ?? null,
            'request_type'         => $data['request_type'],
            'stat_justification'   => $data['stat_justification'] ?? null,
            'clinical_diagnosis'   => $data['clinical_diagnosis'] ?? null,
            'requesting_physician' => $data['requesting_physician'] ?? null,
            'tests'                => $data['tests'] ?? [],
            'specimen'             => $data['specimen'] ?? null,
            'antibiotics_taken'    => $data['antibiotics_taken'] ?? null,
            'antibiotics_duration' => $data['antibiotics_duration'] ?? null,
            'other_tests'          => $data['other_tests'] ?? null,
            'date_requested'       => $data['date_requested'] ?? now()->toDateString(),
        ]);

        // Activity log
        $testCount = count($labRequest->tests ?? []);
        ActivityLog::record(
            action:       'submitted_lab_request',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $labRequest,
            subjectLabel: $labRequest->request_no . ' — ' . $visit->patient->full_name,
            newValues: [
                'request_no'    => $labRequest->request_no,
                'request_type'  => $labRequest->request_type,
                'tests_count'   => $testCount,
                'tests'         => $labRequest->tests,
                'submitted_by'  => auth()->user()->name,
            ],
            panel: 'doctor',
        );

        return response()->json([
            'success'    => true,
            'request_no' => $labRequest->request_no,
            'message'    => "Lab request {$labRequest->request_no} saved — {$testCount} test(s) selected.",
        ]);
    }

    // ── Radiology Request ─────────────────────────────────────────────────────

    /**
     * Show the radiology request form, pre-filled with patient/visit data.
     * Generates a new RAD-YYYY-NNNNN request number.
     */
    public function radiologyRequest(Visit $visit): \Illuminate\View\View
    {
        $visit->loadMissing(['patient', 'medicalHistory.doctor']);

        $patient   = $visit->patient;
        $doctor    = $visit->medicalHistory?->doctor;
        $requestNo = RadiologyRequest::generateRequestNo();

        return view('forms.radiology-request-form', [
            'visit'               => $visit,
            'patient'             => $patient,
            'doctor'              => $doctor,
            'requestNo'           => $requestNo,
            'today'               => now()->timezone('Asia/Manila')->format('Y-m-d'),
            'todayDisplay'        => now()->timezone('Asia/Manila')->format('F j, Y'),
            'familyName'          => strtoupper($patient->family_name ?? ''),
            'firstName'           => strtoupper($patient->first_name  ?? ''),
            'middleName'          => strtoupper($patient->middle_name  ?? ''),
            'address'             => $patient->address    ?? '',
            'dateOfBirth'         => $patient->birthday   ? $patient->birthday->format('Y-m-d') : '',
            'age'                 => $patient->current_age ?? '',
            'sex'                 => $patient->sex ?? '',
            'hospitalNo'          => $patient->case_no  ?? '',
            'ward'                => $visit->admitted_service ?? $visit->medicalHistory?->service ?? '',
            'paymentClass'        => $visit->payment_class ?? '',
            'clinicalDiagnosis'   => $visit->admitting_diagnosis ?? $visit->medicalHistory?->diagnosis ?? '',
            'requestingPhysician' => $doctor ? $doctor->name : '',
        ]);
    }

    /**
     * Save a submitted radiology request to the database and log the action.
     */
    public function radiologyRequestStore(Request $request, Visit $visit): \Illuminate\Http\JsonResponse
    {
        $visit->loadMissing(['patient', 'medicalHistory.doctor']);

        $data = $request->validate([
            'request_no'                 => 'required|string',
            'modality'                   => 'nullable|string|max:50',
            'source'                     => 'nullable|string|max:50',
            'ward'                       => 'nullable|string|max:100',
            'examination_desired'        => 'nullable|string|max:1000',
            'clinical_diagnosis'         => 'nullable|string|max:500',
            'clinical_findings'          => 'nullable|string|max:1000',
            'radiologist_interpretation' => 'nullable|string|max:2000',
            'requesting_physician'       => 'nullable|string|max:200',
            'date_requested'             => 'nullable|date',
        ]);

        $radRequest = RadiologyRequest::create([
            'request_no'                 => $data['request_no'],
            'visit_id'                   => $visit->id,
            'patient_id'                 => $visit->patient_id,
            'doctor_id'                  => $visit->medicalHistory?->doctor_id,
            'submitted_by'               => auth()->id(),
            'modality'                   => $data['modality'] ?? null,
            'source'                     => $data['source']   ?? null,
            'ward'                       => $data['ward']     ?? null,
            'examination_desired'        => $data['examination_desired']        ?? null,
            'clinical_diagnosis'         => $data['clinical_diagnosis']         ?? null,
            'clinical_findings'          => $data['clinical_findings']          ?? null,
            'radiologist_interpretation' => $data['radiologist_interpretation'] ?? null,
            'requesting_physician'       => $data['requesting_physician']       ?? null,
            'date_requested'             => $data['date_requested'] ?? now()->toDateString(),
        ]);

        ActivityLog::record(
            action:       'submitted_radiology_request',
            category:     ActivityLog::CAT_CLINICAL,
            subject:      $radRequest,
            subjectLabel: $radRequest->request_no . ' — ' . $visit->patient->full_name,
            newValues: [
                'request_no'  => $radRequest->request_no,
                'modality'    => $radRequest->modality,
                'examination' => $radRequest->examination_desired,
                'submitted_by'=> auth()->user()->name,
            ],
            panel: 'doctor',
        );

        return response()->json([
            'success'    => true,
            'request_no' => $radRequest->request_no,
            'message'    => "Radiology request {$radRequest->request_no} saved.",
        ]);
    }
}