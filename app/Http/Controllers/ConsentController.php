<?php

namespace App\Http\Controllers;

use App\Models\ConsentRecord;
use App\Models\Visit;
use Illuminate\Http\Request;

class ConsentController extends Controller
{

    public function consentToCare(Visit $visit): \Illuminate\View\View
    {
        $visit->loadMissing(['patient', 'medicalHistory.doctor', 'consentRecord']);

        $patient = $visit->patient;
        $doctor  = $visit->medicalHistory?->doctor;
        $consent = $visit->consentRecord;

        return view('forms.consent-to-care', [
            'visit'       => $visit,
            'patient'     => $patient,
            'consent'     => $consent,
            // consent_name: "JUAN M. DELA CRUZ" — First MI. FAMILY, all caps
            'patientName' => strtoupper($patient->consent_name ?? $patient->full_name ?? ''),
            // Doctor name all caps (no "Dr." prefix)
            'doctorName'  => strtoupper($doctor?->name ?? ''),
            'today'       => now()->timezone('Asia/Manila')->format('F j, Y'),
            // true when ?readonly=1 is in the URL — hides save toolbar
            'readonly'    => (bool) request()->query('readonly', false),
        ]);
    }

    /**
     * Save Consent to Care data to the database.
     */
    public function consentSave(Request $request, Visit $visit): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'active_section'      => 'required|integer|in:1,2',
            // Section 1
            'patient_name'        => 'nullable|string|max:200',
            'doctor_name_sec1'    => 'nullable|string|max:200',
            'witness_sec1'        => 'nullable|string|max:200',
            'signed_date_sec1'    => 'nullable|string|max:50',
            // Section 2
            'guardian_name'       => 'nullable|string|max:200',
            'nok_sig_name'        => 'nullable|string|max:200',
            'being_the'           => 'nullable|string|max:100',
            'doctor_name_sec2'    => 'nullable|string|max:200',
            'witness_sec2'        => 'nullable|string|max:200',
            'signed_date_sec2'    => 'nullable|string|max:50',
            'relation_to_patient' => 'nullable|string|max:100',
        ]);

        // ── Null-out the INACTIVE section so only the active one is persisted ──
        // This prevents stale data from a previously saved section bleeding through
        // into Step 4 when the clerk switches between Section 1 and Section 2.
        if ((int) $data['active_section'] === 1) {
            // Guardian/next-of-kin section was cleared by JS — force all nulls
            $data['guardian_name']       = null;
            $data['nok_sig_name']        = null;
            $data['being_the']           = null;
            $data['doctor_name_sec2']    = null;
            $data['witness_sec2']        = null;
            $data['signed_date_sec2']    = null;
            $data['relation_to_patient'] = null;
        } else {
            // Patient consent section was cleared by JS — force all nulls
            $data['patient_name']     = null;
            $data['doctor_name_sec1'] = null;
            $data['witness_sec1']     = null;
            $data['signed_date_sec1'] = null;
        }

        $record = ConsentRecord::updateOrCreate(
            ['visit_id' => $visit->id],
            array_merge(
                array_map(fn ($v) => is_string($v) ? strtoupper(trim($v)) : $v, $data),
                ['patient_id' => $visit->patient_id, 'saved_by' => auth()->id()]
            )
        );

        return response()->json([
            'success'   => true,
            'message'   => 'Consent to Care saved.',
            'record_id' => $record->id,
        ]);
    }
}