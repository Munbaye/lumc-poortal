<?php

namespace Database\Seeders;

use App\Models\LabRequest;
use App\Models\Patient;
use App\Models\RadiologyRequest;
use App\Models\ResultUpload;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;

/**
 * LabRadSeeder
 *
 * Attaches completed lab and radiology requests (with result file stubs)
 * to a selection of currently-admitted visits so the Lab/Radiology tab
 * shows data immediately on first login.
 *
 * Also adds a few PENDING requests so the Tech panel has a queue.
 *
 * Schema notes:
 *   lab_requests.request_received_at (NOT received_at)
 *   result_uploads.request_type / request_id / file_path / file_name etc.
 */
class LabRadSeeder extends Seeder
{
    public function run(): void
    {
        $tech = User::where('email', 'medtech@lumc.gov.ph')->first();
        $radTech = User::where('email', 'radtech@lumc.gov.ph')->first();

        // ── Find currently-admitted visits by patient family_name ──────────

        $visits = $this->findAdmittedVisits([
            'DELA ROSA'   => 0,  // Eduardo — HPN/Chest pain
            'GARCIA'      => 1,  // Maria Theresa — Appendicitis
            'MANALO'      => 8,  // Kevin — Dengue
            'FRANCISCO'   => 13, // Jorge — Recurrent CAP
        ]);

        // ── Eduardo Dela Rosa — completed lab + radiology (he has results) ──

        if (isset($visits['DELA ROSA'])) {
            $visit   = $visits['DELA ROSA'];
            $patient = $visit->patient;
            $doctor  = User::where('email', 'doctor@lumc.gov.ph')->first();

            $labReq = LabRequest::create([
                'request_no'           => 'LAB-' . now()->year . '-00001',
                'visit_id'             => $visit->id,
                'patient_id'           => $patient->id,
                'doctor_id'            => $doctor->id,
                'submitted_by'         => $doctor->id,
                'ward'                 => 'Internal Medicine',
                'request_type'         => 'stat',
                'clinical_diagnosis'   => 'Hypertensive Urgency with Chest Pain',
                'requesting_physician' => 'DR. R. SANTOS',
                'tests'                => [
                    'Complete Blood Count (CBC)',
                    'Troponin-T',
                    'Fasting Blood Sugar',
                    'Sodium, Potassium, Chloride',
                    'BUN',
                    'Creatinine',
                ],
                'date_requested'        => now()->toDateString(),
                'status'                => 'completed',
                'request_received_at'   => now()->subHours(4),
                'test_started_at'       => now()->subHours(3)->subMinutes(30),
                'test_done_at'          => now()->subHours(2)->subMinutes(30),
            ]);

            ResultUpload::create([
                'request_type' => 'lab',
                'request_id'   => $labReq->id,
                'visit_id'     => $visit->id,
                'patient_id'   => $patient->id,
                'uploaded_by'  => $tech?->id,
                'file_path'    => 'results/demo/lab_result_dela_rosa.pdf',
                'file_name'    => 'LAB-Result-DelaRosa-CBC-Troponin.pdf',
                'file_mime'    => 'application/pdf',
                'file_size'    => 245760,
                'notes'        => 'Troponin I: 0.04 ng/mL (borderline). CBC: Hgb 128 g/L, WBC 11.2 x10^9/L. FBS: 8.4 mmol/L. Na 138, K 4.1, Creatinine 98.',
            ]);

            $radReq = RadiologyRequest::create([
                'request_no'          => 'RAD-' . now()->year . '-00001',
                'visit_id'            => $visit->id,
                'patient_id'          => $patient->id,
                'doctor_id'           => $doctor->id,
                'submitted_by'        => $doctor->id,
                'modality'            => 'X-RAY',
                'source'              => 'ER',
                'ward'                => 'Internal Medicine',
                'examination_desired' => 'Chest X-Ray, PA view',
                'clinical_diagnosis'  => 'Hypertensive Urgency with Chest Pain, r/o ACS',
                'requesting_physician'=> 'DR. R. SANTOS',
                'date_requested'      => now()->toDateString(),
                'status'              => 'completed',
                'request_received_at' => now()->subHours(4),
                'exam_started_at'     => now()->subHours(3),
                'exam_done_at'        => now()->subHours(2)->subMinutes(45),
                'radiologist_interpretation' =>
                    "CHEST X-RAY: PA VIEW\n\n" .
                    "Findings:\n" .
                    "- Cardiomegaly (cardiothoracic ratio ~0.56)\n" .
                    "- Mild pulmonary vascular congestion bilaterally\n" .
                    "- No active infiltrates\n" .
                    "- No pleural effusion\n" .
                    "- Aortic knuckle prominent\n\n" .
                    "Impression:\n" .
                    "Cardiomegaly with pulmonary vascular engorgement consistent with " .
                    "hypertensive heart disease. Clinical correlation with echocardiography recommended.",
            ]);

            ResultUpload::create([
                'request_type' => 'radiology',
                'request_id'   => $radReq->id,
                'visit_id'     => $visit->id,
                'patient_id'   => $patient->id,
                'uploaded_by'  => $radTech?->id,
                'file_path'    => 'results/demo/cxr_dela_rosa.jpg',
                'file_name'    => 'CXR-DelaRosa-PA-View.jpg',
                'file_mime'    => 'image/jpeg',
                'file_size'    => 1548288,
                'notes'        => 'Cardiomegaly noted. Radiologist reviewed.',
            ]);
        }

        // ── Maria Theresa Garcia — pending lab (pre-op appendicitis) ──────

        if (isset($visits['GARCIA'])) {
            $visit   = $visits['GARCIA'];
            $patient = $visit->patient;
            $doctor  = User::where('email', 'jdelacruz@lumc.gov.ph')->first();

            LabRequest::create([
                'request_no'           => 'LAB-' . now()->year . '-00002',
                'visit_id'             => $visit->id,
                'patient_id'           => $patient->id,
                'doctor_id'            => $doctor->id,
                'submitted_by'         => $doctor->id,
                'ward'                 => 'Surgery Ward',
                'request_type'         => 'stat',
                'clinical_diagnosis'   => 'Acute Appendicitis — pre-op work-up',
                'requesting_physician' => 'DR. J. DELA CRUZ',
                'tests'                => [
                    'Complete Blood Count (CBC)',
                    'Prothrombin Time (PT-PA)',
                    'APTT',
                    'Blood Typing',
                    'Creatinine',
                    'Routine Urinalysis',
                ],
                'date_requested'      => now()->subDay()->toDateString(),
                'status'              => 'pending',
            ]);

            RadiologyRequest::create([
                'request_no'          => 'RAD-' . now()->year . '-00002',
                'visit_id'            => $visit->id,
                'patient_id'          => $patient->id,
                'doctor_id'           => $doctor->id,
                'submitted_by'        => $doctor->id,
                'modality'            => 'ULTRASOUND',
                'source'              => 'ER',
                'ward'                => 'Surgery Ward',
                'examination_desired' => 'Abdominal Ultrasound — r/o perforated appendicitis',
                'clinical_diagnosis'  => 'Acute Appendicitis',
                'requesting_physician'=> 'DR. J. DELA CRUZ',
                'date_requested'      => now()->subDay()->toDateString(),
                'status'              => 'pending',
            ]);
        }

        // ── Kevin Manalo — completed CBC for dengue trending ─────────────

        if (isset($visits['MANALO'])) {
            $visit   = $visits['MANALO'];
            $patient = $visit->patient;
            $doctor  = User::where('email', 'rbautista@lumc.gov.ph')->first();

            $labReq2 = LabRequest::create([
                'request_no'           => 'LAB-' . now()->year . '-00003',
                'visit_id'             => $visit->id,
                'patient_id'           => $patient->id,
                'doctor_id'            => $doctor->id,
                'submitted_by'         => $doctor->id,
                'ward'                 => 'Internal Medicine',
                'request_type'         => 'stat',
                'clinical_diagnosis'   => 'Dengue Hemorrhagic Fever Grade II',
                'requesting_physician' => 'DR. R. BAUTISTA',
                'tests'                => [
                    'Complete Blood Count (CBC)',
                    'Dengue NS1 + IgM/IgG (Combo)',
                ],
                'date_requested'      => now()->toDateString(),
                'status'              => 'completed',
                'request_received_at' => now()->subHours(6),
                'test_started_at'     => now()->subHours(5)->subMinutes(30),
                'test_done_at'        => now()->subHours(4)->subMinutes(30),
            ]);

            ResultUpload::create([
                'request_type' => 'lab',
                'request_id'   => $labReq2->id,
                'visit_id'     => $visit->id,
                'patient_id'   => $patient->id,
                'uploaded_by'  => $tech?->id,
                'file_path'    => 'results/demo/lab_result_manalo_dengue.pdf',
                'file_name'    => 'LAB-Manalo-CBC-Dengue-NS1.pdf',
                'file_mime'    => 'application/pdf',
                'file_size'    => 189440,
                'notes'        => 'Dengue NS1: POSITIVE. IgM: POSITIVE (recent infection). Platelet: 68,000/µL (↓). Hematocrit: 42% (baseline). WBC: 3.2 x10^9/L (leukopenia). Repeat CBC in 6 hours as ordered.',
            ]);
        }

        // ── Jorge Francisco — pending lab (third CAP) ─────────────────────

        if (isset($visits['FRANCISCO'])) {
            $visit   = $visits['FRANCISCO'];
            $patient = $visit->patient;
            $doctor  = User::where('email', 'doctor@lumc.gov.ph')->first();

            LabRequest::create([
                'request_no'           => 'LAB-' . now()->year . '-00004',
                'visit_id'             => $visit->id,
                'patient_id'           => $patient->id,
                'doctor_id'            => $doctor->id,
                'submitted_by'         => $doctor->id,
                'ward'                 => 'Internal Medicine',
                'request_type'         => 'stat',
                'clinical_diagnosis'   => 'Recurrent CAP Severe — r/o immunodeficiency',
                'requesting_physician' => 'DR. R. SANTOS',
                'tests'                => [
                    'Complete Blood Count (CBC)',
                    'CRP — Semi-Quantitative',
                    'Fasting Blood Sugar',
                    'BUN',
                    'Creatinine',
                ],
                'date_requested'      => now()->toDateString(),
                'status'              => 'in_progress',
                'request_received_at' => now()->subHours(5),
                'test_started_at'     => now()->subHours(4),
            ]);

            RadiologyRequest::create([
                'request_no'          => 'RAD-' . now()->year . '-00003',
                'visit_id'            => $visit->id,
                'patient_id'          => $patient->id,
                'doctor_id'           => $doctor->id,
                'submitted_by'        => $doctor->id,
                'modality'            => 'X-RAY',
                'source'              => 'ER',
                'ward'                => 'Internal Medicine',
                'examination_desired' => 'Chest X-Ray PA view',
                'clinical_diagnosis'  => 'Recurrent CAP Severe Risk',
                'clinical_findings'   => 'Febrile, tachypneic. O2 90% on room air. Bilateral crackles.',
                'requesting_physician'=> 'DR. R. SANTOS',
                'date_requested'      => now()->toDateString(),
                'status'              => 'pending',
            ]);
        }

        $this->command->info('   ✓ Lab/Radiology requests seeded (2 completed with results, 4 pending/in-progress)');
    }

    /**
     * For each given family_name, find the most recent admitted visit.
     * Returns ['FAMILY_NAME' => Visit, ...]
     */
    private function findAdmittedVisits(array $familyNames): array
    {
        $result = [];
        foreach ($familyNames as $familyName => $idx) {
            $patient = Patient::where('family_name', $familyName)->first();
            if (!$patient) continue;

            $visit = Visit::where('patient_id', $patient->id)
                ->where('status', 'admitted')
                ->latest('registered_at')
                ->with('patient')
                ->first();

            if ($visit) {
                $result[$familyName] = $visit;
            }
        }
        return $result;
    }
}