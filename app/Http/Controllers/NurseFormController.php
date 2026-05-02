<?php

namespace App\Http\Controllers;

use App\Models\Visit;

class NurseFormController extends Controller
{

public function tprRecord(Visit $visit)
{
    $vitals = \App\Models\Vital::where('visit_id', $visit->id)->get();

    $temps = [];
    $pulse = [];
    $resp = [];

    foreach ($vitals as $v) {
        $day = \Carbon\Carbon::parse($v->created_at)->day;

        $temps[$day] = $v->temperature;
        $pulse[$day] = $v->pulse;
        $resp[$day] = $v->respiration;
    }

    return view('forms.tpr-record', compact(
        'visit','temps','pulse','resp'
    ));
}
    /**
     * GET /forms/vital-sign-monitoring-sheet/{visit}
     *
     * Renders the printable Vital Sign Monitoring Sheet (NUR-014)
     * populated with all Vital records for the given visit.
     */
    public function vitalSignSheet(Visit $visit)
    {
        // Eager-load what the blade needs
        $visit->loadMissing([
            'patient',
            'medicalHistory',
        ]);

        return view('forms.vital-sign-monitoring-sheet', compact('visit'));
    }

    /**
     * GET /forms/iv-bt-sheet/{visit}
     *
     * Renders the printable IV / Blood Transfusion Sheet (NUR-012)
     * populated with all IvFluidEntry records for the given visit.
     */
    public function ivBtSheet(Visit $visit)
    {
        $visit->loadMissing([
            'patient',
            'medicalHistory',
        ]);

        return view('forms.iv-bt-sheet', compact('visit'));
    }

    /**
     * GET /forms/nurses-notes/{visit}
     * Renders the printable Nurse's Notes sheet (NUR-010).
     */
    public function nursesNotes(Visit $visit)
    {
        $visit->loadMissing(['patient', 'medicalHistory']);
        return view('forms.nurses-notes', compact('visit'));
    }

    /**
     * GET /forms/medication-records/{visit}
     * Printable Medication Administration Record / Medication Records (NUR-011).
     */
    public function medicationRecords(Visit $visit)
    {
        $visit->loadMissing(['patient', 'medicalHistory']);
        return view('forms.medication-records', compact('visit'));
    }

    /**
     * GET /forms/breastfeeding-observation/{visit}
     * Printable Breastfeeding Observation Job Aid (NUR-044-0).
     */
    public function breastfeedingObservation(Visit $visit)
    {
        $visit->loadMissing([
            'patient',
            'nicuAdmission',
        ]);

        return view('forms.breastfeeding-observation', compact('visit'));
    }

    public function obRecord($visitId)
{
    $visit = \App\Models\Visit::with('patient')->findOrFail($visitId);

    return view('forms.ob-record', compact('visit'));
}
}
