<?php

namespace App\Http\Controllers;

use App\Models\Visit;

class ConsentController extends Controller
{
    /**
     * Render the print-ready Consent to Care form.
     *
     * Route : GET /forms/consent-to-care/{visit}
     * Auth  : requires authenticated staff (middleware 'auth')
     *
     * Variables passed to the view:
     *   $patientName  — "JUAN M. DELA CRUZ"  (First MI. FAMILY, all caps)
     *                   Uses the Patient::getConsentNameAttribute() accessor.
     *   $doctorName   — admitting doctor's name (all caps, no "Dr." prefix)
     *   $today        — "May 14, 2026"
     */
    public function consentToCare(Visit $visit): \Illuminate\View\View
    {
        $visit->loadMissing(['patient', 'medicalHistory.doctor']);

        $patient = $visit->patient;
        $doctor  = $visit->medicalHistory?->doctor;

        return view('forms.consent-to-care', [
            'visit'       => $visit,
            'patient'     => $patient,
            // consent_name: "JUAN M. DELA CRUZ" — First MI. FAMILY, all caps
            'patientName' => strtoupper($patient->consent_name),
            // Doctor name in all caps (JS uppercases on the fly, but we send it ready)
            'doctorName'  => strtoupper($doctor?->name ?? ''),
            'today'       => now()->timezone('Asia/Manila')->format('F j, Y'),
        ]);
    }
}