<?php

namespace App\Http\Controllers;

use App\Models\Visit;

/**
 * Serves the two printable clinical document forms accessible
 * from the Doctor's Patient Chart.
 *
 * Routes (add to routes/web.php):
 *   GET /forms/history-form/{visit}        → historyForm()
 *   GET /forms/physical-exam-form/{visit}  → physicalExamForm()
 *
 * Both routes require auth middleware.
 */
class ChartController extends Controller
{
    /**
     * NUR-006 — History Form
     * Pre-fills: patient demographics + medical history from the visit's medicalHistory record.
     */
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

    /**
     * NUR-005 — Physical Examination Form
     * Pre-fills: physical exam findings from the visit's medicalHistory record.
     */
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
}