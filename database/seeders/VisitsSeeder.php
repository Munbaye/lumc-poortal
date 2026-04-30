<?php

namespace Database\Seeders;

use App\Models\AdmissionRecord;
use App\Models\ConsentRecord;
use App\Models\DoctorsOrder;
use App\Models\ErRecord;
use App\Models\IvFluidEntry;
use App\Models\LabRequest;
use App\Models\MarDateColumn;
use App\Models\MarEntry;
use App\Models\MedicalHistory;
use App\Models\NursesNote;
use App\Models\Patient;
use App\Models\RadiologyRequest;
use App\Models\ResultUpload;
use App\Models\User;
use App\Models\Visit;
use App\Models\Vital;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VisitsSeeder extends Seeder
{
    /**
     * Enum reference (from migrations):
     *
     * visits.visit_type          : 'OPD' | 'ER'
     * visits.status              : 'registered' | 'vitals_done' | 'assessed' | 'discharged' | 'admitted' | 'referred'
     * visits.disposition         : 'Discharged' | 'Admitted' | 'Referred' | 'HAMA' | 'Expired'   ← Title-case!
     * visits.payment_class       : 'Charity' | 'Private'
     * visits.admitted_service    : plain string (no admitted_ward column)
     *
     * medical_histories.disposition : 'Discharged' | 'Admitted' | 'Referred' | 'HAMA' | 'Expired'  ← same
     *
     * vitals.temperature_site    : 'Axilla' | 'Oral' | 'Rectal'
     *
     * nurses_notes.shift         : '7-3' | '3-11' | '11-7'
     *
     * doctors_orders.status      : 'pending' | 'carried' | 'discontinued'
     *
     * lab_requests.status        : 'pending' | 'in_progress' | 'completed'
     * radiology_requests.status  : 'pending' | 'in_progress' | 'completed'
     *
     * consent_records.active_section : unsignedTinyInteger (1 or 2) — NOT a string
     */

    // ── Shared staff references ───────────────────────────────────────────────
    private User $drSantos;   // doctor@lumc.gov.ph  — primary demo doctor
    private User $drReyes;    // doctor2@lumc.gov.ph — Pediatrics
    private User $drGarcia;   // doctor3@lumc.gov.ph — Surgery
    private User $clerkOPD;
    private User $clerkER;
    private User $nurse1;     // nurse@lumc.gov.ph  — Anna Dela Cruz (primary demo nurse)
    private User $nurse2;     // nurse3@lumc.gov.ph — Leonora Flores
    private User $nurse3;     // nurse4@lumc.gov.ph — Patricia Torres
    private User $nurse4;     // nurse2@lumc.gov.ph — Carla Santos (extra nurse)
    private User $techLab1;
    private User $techLab2;
    private User $techRad;

    // ── Counters ──────────────────────────────────────────────────────────────
    private int $visitCount  = 0;
    private int $vitalCount  = 0;
    private int $orderCount  = 0;
    private int $noteCount   = 0;
    private int $ivCount     = 0;
    private int $marCount    = 0;
    private int $labCount    = 0;
    private int $radCount    = 0;
    private int $uploadCount = 0;
    private int $formCount   = 0;

    public function run(): void
    {
        $this->loadStaff();

        // ── Patient Dela Cruz ─────────────────────────────────────────────────
        $delacruz = Patient::where('family_name', 'Dela Cruz')->first();
        if ($delacruz) {
            $this->seedOpdVisit($delacruz, Carbon::create(2024, 6, 10, 9, 30, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Hypertension follow-up, stable BP');
            $this->seedAdmittedVisit($delacruz, Carbon::create(2024, 11, 3, 14, 0, 0, 'Asia/Manila'), Carbon::create(2024, 11, 7, 10, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Hypertensive urgency', 'Internal Medicine');
            $this->seedErVisit($delacruz, Carbon::create(2025, 3, 20, 22, 15, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Severe headache, BP 190/110');
            $this->seedCurrentAdmission($delacruz, Carbon::create(2026, 4, 6, 8, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Hypertensive crisis with chest discomfort', 'Internal Medicine');
        }

        // ── Patient Reyes ─────────────────────────────────────────────────────
        $reyes = Patient::where('family_name', 'Reyes')->where('first_name', 'Eduardo')->first();
        if ($reyes) {
            $this->seedAdmittedVisit($reyes, Carbon::create(2024, 2, 14, 10, 0, 0, 'Asia/Manila'), Carbon::create(2024, 2, 21, 11, 0, 0, 'Asia/Manila'), $this->drSantos, 'Private', 'DM2 with poor glycemic control', 'Internal Medicine');
            $this->seedOpdVisit($reyes, Carbon::create(2024, 8, 5, 8, 0, 0, 'Asia/Manila'), $this->drSantos, 'Private', 'DM2 monitoring, HbA1c result review');
            $this->seedAdmittedVisit($reyes, Carbon::create(2025, 1, 10, 7, 0, 0, 'Asia/Manila'), Carbon::create(2025, 1, 17, 9, 0, 0, 'Asia/Manila'), $this->drSantos, 'Private', 'DM2 with UTI, hyperglycemia', 'Internal Medicine');
            $this->seedCurrentAdmission($reyes, Carbon::create(2026, 4, 5, 9, 0, 0, 'Asia/Manila'), $this->drSantos, 'Private', 'DM2 with HHS, dehydration', 'Internal Medicine');
        }

        // ── Patient Torres (pedia) ────────────────────────────────────────────
        $torres = Patient::where('family_name', 'Torres')->where('first_name', 'Miguel')->first();
        if ($torres) {
            $this->seedErVisit($torres, Carbon::create(2024, 8, 22, 3, 0, 0, 'Asia/Manila'), $this->drReyes, 'Charity', 'High fever, febrile seizure');
            $this->seedAdmittedVisit($torres, Carbon::create(2025, 5, 18, 16, 0, 0, 'Asia/Manila'), Carbon::create(2025, 5, 22, 10, 0, 0, 'Asia/Manila'), $this->drReyes, 'Charity', 'Pneumonia', 'Pediatrics');
            $this->seedCurrentAdmission($torres, Carbon::create(2026, 4, 7, 10, 0, 0, 'Asia/Manila'), $this->drReyes, 'Charity', 'Acute gastroenteritis with mild dehydration', 'Pediatrics');
        }

        // ── Patient Villanueva ────────────────────────────────────────────────
        $villanueva = Patient::where('family_name', 'Villanueva')->first();
        if ($villanueva) {
            $this->seedOpdVisit($villanueva, Carbon::create(2024, 4, 12, 8, 30, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Asthma, mild persistent, for refill');
            $this->seedAdmittedVisit($villanueva, Carbon::create(2025, 7, 30, 20, 0, 0, 'Asia/Manila'), Carbon::create(2025, 8, 4, 9, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Acute asthma exacerbation', 'Internal Medicine');
            $this->seedErVisit($villanueva, Carbon::create(2026, 3, 15, 11, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Severe dyspnea, wheezing');
        }

        // ── Patient Castillo (incomplete info) ────────────────────────────────
        $castillo = Patient::where('family_name', 'Castillo')->where('first_name', 'Roberto')->first();
        if ($castillo) {
            $this->seedErVisit($castillo, Carbon::create(2026, 3, 28, 14, 30, 0, 'Asia/Manila'), $this->drGarcia, 'Charity', 'Laceration, right arm, work-related injury');
        }

        // ── Patient Aquino (Private) — all Private visits stay with dr.santos ──
        $aquino = Patient::where('family_name', 'Aquino')->first();
        if ($aquino) {
            $this->seedOpdVisit($aquino, Carbon::create(2025, 9, 8, 10, 0, 0, 'Asia/Manila'), $this->drSantos, 'Private', 'Annual check-up, routine labs');
            // Surgery admission: dr.garcia handles the procedure but dr.santos is assigned_doctor for Private visibility
            $this->seedAdmittedVisit($aquino, Carbon::create(2026, 2, 14, 9, 0, 0, 'Asia/Manila'), Carbon::create(2026, 2, 18, 14, 0, 0, 'Asia/Manila'), $this->drSantos, 'Private', 'Appendicitis, acute', 'Surgery');
        }

        // ── Patient Mendoza (elderly) ─────────────────────────────────────────
        $mendoza = Patient::where('family_name', 'Mendoza')->where('first_name', 'Ernesto')->first();
        if ($mendoza) {
            $this->seedAdmittedVisit($mendoza, Carbon::create(2024, 1, 8, 8, 0, 0, 'Asia/Manila'), Carbon::create(2024, 1, 14, 10, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Community-acquired pneumonia', 'Internal Medicine');
            $this->seedOpdVisit($mendoza, Carbon::create(2024, 6, 20, 9, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'COPD maintenance check');
            $this->seedErVisit($mendoza, Carbon::create(2024, 11, 15, 2, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Acute COPD exacerbation, severe dyspnea');
            $this->seedAdmittedVisit($mendoza, Carbon::create(2025, 4, 3, 11, 0, 0, 'Asia/Manila'), Carbon::create(2025, 4, 10, 9, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'COPD exacerbation, type 2 respiratory failure', 'Internal Medicine');
            $this->seedCurrentAdmission($mendoza, Carbon::create(2026, 4, 4, 7, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Pneumonia with sepsis, COPD', 'Internal Medicine');
        }

        // ── Patient Estrada ───────────────────────────────────────────────────
        $estrada = Patient::where('family_name', 'Estrada')->where('first_name', 'Lina')->first();
        if ($estrada) {
            $this->seedErVisit($estrada, Carbon::create(2025, 12, 1, 16, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Abdominal pain, nausea, vomiting');
            $this->seedCurrentAdmission($estrada, Carbon::create(2026, 4, 8, 13, 0, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Peptic ulcer disease with upper GI bleed', 'Internal Medicine');
        }

        // ── Unknown patient ───────────────────────────────────────────────────
        $unknown = Patient::where('is_unknown', true)->first();
        if ($unknown) {
            $this->seedErVisit($unknown, Carbon::create(2026, 4, 9, 10, 30, 0, 'Asia/Manila'), $this->drSantos, 'Charity', 'Unconscious, found on street, unknown history');
        }

        // ── Extra nurse (Carla Santos / nurse2@lumc.gov.ph) activity ─────────
        // Attach nurse4 records to currently-admitted visits so she shows up
        // meaningfully in monitoring sheets, IV logs, MAR, and FDAR notes.
        $this->seedExtraNurseActivity();

        // ── Summary ───────────────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  LUMC SEEDER — RECORDS CREATED');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->line("  Patients     : " . Patient::count());
        $this->command->line("  Visits       : {$this->visitCount}");
        $this->command->line("  Vitals       : {$this->vitalCount}");
        $this->command->line("  Orders       : {$this->orderCount}");
        $this->command->line("  Nurses Notes : {$this->noteCount}");
        $this->command->line("  IV Entries   : {$this->ivCount}");
        $this->command->line("  MAR Entries  : {$this->marCount}");
        $this->command->line("  Lab Requests : {$this->labCount}");
        $this->command->line("  Rad Requests : {$this->radCount}");
        $this->command->line("  Result Files : {$this->uploadCount}");
        $this->command->line("  Clerk Forms  : {$this->formCount} (ER/ADM/CTC records)");
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->newLine();
    }

    // ════════════════════════════════════════════════════════════════════════
    //  VISIT BUILDERS
    // ════════════════════════════════════════════════════════════════════════

    /**
     * OPD visit — assessed only, not admitted, discharged same day.
     */
    private function seedOpdVisit(
        Patient $patient,
        Carbon  $registeredAt,
        User    $doctor,
        string  $paymentClass,
        string  $chiefComplaint
    ): Visit {
        $clerk = $this->clerkOPD;

        $visit = Visit::create([
            'patient_id'         => $patient->id,
            'clerk_id'           => $clerk->id,
            'assigned_doctor_id' => $paymentClass === 'Private' ? $doctor->id : null,
            'visit_type'         => 'OPD',            // ✓ enum: 'OPD'|'ER'
            'chief_complaint'    => $chiefComplaint,
            'payment_class'      => $paymentClass,    // ✓ enum: 'Charity'|'Private'
            'status'             => 'discharged',     // ✓ enum: 'registered'|'vitals_done'|'assessed'|'discharged'|'admitted'|'referred'
            'disposition'        => 'Discharged',     // ✓ enum: 'Discharged'|'Admitted'|'Referred'|'HAMA'|'Expired'
            'registered_at'      => $registeredAt,
            'discharged_at'      => $registeredAt->copy()->addHours(2),
            'type_of_service'    => 'OPD',            // plain string — no enum constraint
        ]);
        $this->visitCount++;

        $this->addVital($visit, $patient, $clerk, $registeredAt->copy()->addMinutes(10));

        $assessedAt = $registeredAt->copy()->addMinutes(40);
        MedicalHistory::create([
            'visit_id'                   => $visit->id,
            'patient_id'                 => $patient->id,
            'doctor_id'                  => $doctor->id,
            'chief_complaint'            => $chiefComplaint,
            'history_of_present_illness' => 'Patient presents with ' . strtolower($chiefComplaint) . '. Duration of 3 days. No associated symptoms noted.',
            'past_medical_history'       => $this->randomPMHx(),
            'family_history'             => 'Hypertension on maternal side.',
            'drug_allergies'             => 'NKDA',
            'diagnosis'                  => $chiefComplaint . ' — stable',
            'plan'                       => 'Continue home medications. Follow up in 4 weeks.',
            'service'                    => 'OPD',
            'disposition'                => 'Discharged',  // ✓ Title-case enum
        ]);

        $this->addOrders($visit, $doctor, $assessedAt, 2, true);

        return $visit;
    }

    /**
     * ER visit — assessed in ER, treated, discharged.
     */
    private function seedErVisit(
        Patient $patient,
        Carbon  $registeredAt,
        User    $doctor,
        string  $paymentClass,
        string  $chiefComplaint
    ): Visit {
        $clerk = $this->clerkER;

        $visit = Visit::create([
            'patient_id'          => $patient->id,
            'clerk_id'            => $clerk->id,
            'assigned_doctor_id'  => $paymentClass === 'Private' ? $doctor->id : null,
            'visit_type'          => 'ER',
            'chief_complaint'     => $chiefComplaint,
            'payment_class'       => $paymentClass,
            'status'              => 'discharged',
            'disposition'         => 'Discharged',    // ✓ Title-case
            'registered_at'       => $registeredAt,
            'discharged_at'       => $registeredAt->copy()->addHours(4),
            'brought_by'          => 'Family',
            'condition_on_arrival'=> 'Fair',
            'type_of_service'     => 'Emergency',
            'medico_legal'        => false,
        ]);
        $this->visitCount++;

        $this->addVital($visit, $patient, $clerk, $registeredAt->copy()->addMinutes(5));

        $assessedAt = $registeredAt->copy()->addMinutes(30);
        MedicalHistory::create([
            'visit_id'                   => $visit->id,
            'patient_id'                 => $patient->id,
            'doctor_id'                  => $doctor->id,
            'chief_complaint'            => $chiefComplaint,
            'history_of_present_illness' => 'Patient brought to ER with complaint of ' . strtolower($chiefComplaint) . '. Sudden onset. No prior consult.',
            'past_medical_history'       => $this->randomPMHx(),
            'drug_allergies'             => 'NKDA',
            'pe_chest'                   => 'Clear breath sounds bilaterally.',
            'pe_cardiovascular'          => 'Regular rate and rhythm, no murmurs.',
            'admitting_impression'       => $chiefComplaint,
            'diagnosis'                  => $chiefComplaint,
            'plan'                       => 'Treated and observed. Discharged stable.',
            'disposition'                => 'Discharged',  // ✓ Title-case enum
            'service'                    => 'ER',
        ]);

        $this->fillErRecord($visit, $patient, $clerk, $registeredAt);
        $this->addOrders($visit, $doctor, $assessedAt, 2, true);
        $this->addNurseNote($visit, $this->nurse1, $assessedAt->copy()->addMinutes(30), '7-3');

        return $visit;
    }

    /**
     * Completed admitted visit — full clinical data, discharged.
     */
    private function seedAdmittedVisit(
        Patient $patient,
        Carbon  $registeredAt,
        Carbon  $dischargedAt,
        User    $doctor,
        string  $paymentClass,
        string  $chiefComplaint,
        string  $service
    ): Visit {
        $clerk = $this->clerkER;

        $doctorAdmittedAt = $registeredAt->copy()->addHours(2);
        $clerkAdmittedAt  = $registeredAt->copy()->addHours(3);

        $visit = Visit::create([
            'patient_id'          => $patient->id,
            'clerk_id'            => $clerk->id,
            'assigned_doctor_id'  => $paymentClass === 'Private' ? $doctor->id : null,
            'visit_type'          => 'ER',
            'chief_complaint'     => $chiefComplaint,
            'admitting_diagnosis' => $chiefComplaint,
            'payment_class'       => $paymentClass,
            'status'              => 'discharged',
            'disposition'         => 'Discharged',    // ✓ Title-case enum
            'admitted_service'    => $service,
            'registered_at'       => $registeredAt,
            'doctor_admitted_at'  => $doctorAdmittedAt,
            'clerk_admitted_at'   => $clerkAdmittedAt,
            'discharged_at'       => $dischargedAt,
            'brought_by'          => 'Family',
            'condition_on_arrival'=> 'Poor',
            'type_of_service'     => 'Emergency',
            'medico_legal'        => false,
        ]);
        $this->visitCount++;

        $this->addVital($visit, $patient, $clerk,         $registeredAt->copy()->addMinutes(10));
        $this->addVital($visit, $patient, $this->nurse1,  $clerkAdmittedAt->copy()->addHours(6));
        $this->addVital($visit, $patient, $this->nurse2,  $clerkAdmittedAt->copy()->addHours(18));
        $this->addVital($visit, $patient, $this->nurse1,  $clerkAdmittedAt->copy()->addDays(1)->addHours(8));

        MedicalHistory::create([
            'visit_id'                   => $visit->id,
            'patient_id'                 => $patient->id,
            'doctor_id'                  => $doctor->id,
            'chief_complaint'            => $chiefComplaint,
            'history_of_present_illness' => 'Patient presents with ' . strtolower($chiefComplaint) . '. Onset 2 days ago. Progressive worsening.',
            'past_medical_history'       => $this->randomPMHx(),
            'family_history'             => 'Hypertension and DM2 on paternal side.',
            'drug_allergies'             => 'NKDA',
            'pe_chest'                   => 'Decreased breath sounds at right base.',
            'pe_cardiovascular'          => 'Regular rate and rhythm.',
            'pe_abdomen'                 => 'Soft, non-tender, normoactive bowel sounds.',
            'admitting_impression'       => $chiefComplaint,
            'diagnosis'                  => $chiefComplaint,
            'plan'                       => 'Admit to ' . $service . ' service. IV antibiotics. Daily monitoring.',
            'service'                    => $service,
            'disposition'                => 'Discharged',  // ✓ Title-case enum
        ]);

        $this->fillErRecord($visit, $patient, $clerk, $registeredAt);
        $this->fillAdmissionRecord($visit, $patient, $clerk, $clerkAdmittedAt, $dischargedAt, $service);
        $this->fillConsentRecord($visit, $patient, $clerk, $clerkAdmittedAt, $doctor);

        $this->addOrders($visit, $doctor, $doctorAdmittedAt, 4, true);

        $this->addNurseNote($visit, $this->nurse1, $clerkAdmittedAt->copy()->addHours(1),  '7-3');
        $this->addNurseNote($visit, $this->nurse2, $clerkAdmittedAt->copy()->addHours(9),  '3-11');
        $this->addNurseNote($visit, $this->nurse3, $clerkAdmittedAt->copy()->addHours(17), '11-7');

        $this->addIvEntries($visit, $patient, $this->nurse1, $clerkAdmittedAt, 3);
        $this->addMarEntries($visit, $patient, $this->nurse1, $clerkAdmittedAt, $dischargedAt);

        $this->addLabRequest($visit, $patient, $doctor, $this->clerkOPD, $this->techLab1, 'completed', $clerkAdmittedAt->copy()->addHours(2));
        $this->addRadiologyRequest($visit, $patient, $doctor, $this->clerkOPD, $this->techRad, 'completed', $clerkAdmittedAt->copy()->addHours(3));

        return $visit;
    }

    /**
     * Current active admission — still admitted as of April 9, 2026.
     */
    private function seedCurrentAdmission(
        Patient $patient,
        Carbon  $registeredAt,
        User    $doctor,
        string  $paymentClass,
        string  $chiefComplaint,
        string  $service
    ): Visit {
        $clerk = $this->clerkER;

        $doctorAdmittedAt = $registeredAt->copy()->addHours(2);
        $clerkAdmittedAt  = $registeredAt->copy()->addHours(3);
        $now              = Carbon::create(2026, 4, 9, 11, 0, 0, 'Asia/Manila');

        $visit = Visit::create([
            'patient_id'          => $patient->id,
            'clerk_id'            => $clerk->id,
            'assigned_doctor_id'  => $paymentClass === 'Private' ? $doctor->id : null,
            'visit_type'          => 'ER',
            'chief_complaint'     => $chiefComplaint,
            'admitting_diagnosis' => $chiefComplaint,
            'payment_class'       => $paymentClass,
            'status'              => 'admitted',      // ✓ still admitted
            'disposition'         => null,            // ✓ no disposition yet (still in-house)
            'admitted_service'    => $service,
            'registered_at'       => $registeredAt,
            'doctor_admitted_at'  => $doctorAdmittedAt,
            'clerk_admitted_at'   => $clerkAdmittedAt,
            'discharged_at'       => null,
            'brought_by'          => 'Family',
            'condition_on_arrival'=> 'Poor',
            'type_of_service'     => 'Emergency',
            'medico_legal'        => false,
        ]);
        $this->visitCount++;

        $this->addVital($visit, $patient, $clerk,        $registeredAt->copy()->addMinutes(10));
        $this->addVital($visit, $patient, $this->nurse1, $clerkAdmittedAt->copy()->addHours(8));
        $this->addVital($visit, $patient, $this->nurse2, $clerkAdmittedAt->copy()->addHours(16));
        if ($registeredAt->diffInDays($now) >= 1) {
            $this->addVital($visit, $patient, $this->nurse3, $clerkAdmittedAt->copy()->addDays(1)->addHours(8));
        }

        MedicalHistory::create([
            'visit_id'                   => $visit->id,
            'patient_id'                 => $patient->id,
            'doctor_id'                  => $doctor->id,
            'chief_complaint'            => $chiefComplaint,
            'history_of_present_illness' => 'Patient presents with ' . strtolower($chiefComplaint) . '. Onset 1 day ago. Worsening despite home meds.',
            'past_medical_history'       => $this->randomPMHx(),
            'family_history'             => 'Hypertension, DM2, CAD on family.',
            'drug_allergies'             => 'NKDA',
            'pe_chest'                   => 'Crackles heard bilaterally on auscultation.',
            'pe_cardiovascular'          => 'Regular rate and rhythm. S1 S2 normal.',
            'pe_abdomen'                 => 'Soft, mildly tender epigastric area.',
            'admitting_impression'       => $chiefComplaint,
            'diagnosis'                  => $chiefComplaint,
            'plan'                       => 'Admit. IV fluids. Monitoring. Labs pending.',
            'service'                    => $service,
            'disposition'                => 'Admitted',  // ✓ Title-case enum — doctor has decided to admit
        ]);

        $this->fillErRecord($visit, $patient, $clerk, $registeredAt);
        $this->fillAdmissionRecord($visit, $patient, $clerk, $clerkAdmittedAt, null, $service);
        $this->fillConsentRecord($visit, $patient, $clerk, $clerkAdmittedAt, $doctor);

        $this->addOrders($visit, $doctor, $doctorAdmittedAt, 5, false);

        $this->addNurseNote($visit, $this->nurse1, $clerkAdmittedAt->copy()->addHours(2),  '7-3');
        $this->addNurseNote($visit, $this->nurse2, $clerkAdmittedAt->copy()->addHours(10), '3-11');

        $this->addIvEntries($visit, $patient, $this->nurse1, $clerkAdmittedAt, 2);
        $this->addMarEntries($visit, $patient, $this->nurse1, $clerkAdmittedAt, $now);

        $this->addLabRequest($visit, $patient, $doctor, $this->clerkOPD, $this->techLab1, 'in_progress', $clerkAdmittedAt->copy()->addHours(1));
        $this->addRadiologyRequest($visit, $patient, $doctor, $this->clerkOPD, $this->techRad, 'completed', $clerkAdmittedAt->copy()->addHours(2));

        return $visit;
    }

    // ════════════════════════════════════════════════════════════════════════
    //  CLINICAL DATA HELPERS
    // ════════════════════════════════════════════════════════════════════════

    private function addVital(Visit $visit, Patient $patient, User $recorder, Carbon $takenAt): void
    {
        $isAbnormal = rand(0, 3) === 0;

        Vital::create([
            'visit_id'         => $visit->id,
            'patient_id'       => $patient->id,
            'recorded_by'      => $recorder->id,
            'nurse_name'       => $recorder->full_name ?: $recorder->name,
            'taken_at'         => $takenAt,
            'temperature'      => $isAbnormal ? 38.5 + round(rand(0, 15) / 10, 1) : 36.5 + round(rand(0, 8) / 10, 1),
            'temperature_site' => 'Axilla',   // ✓ enum: 'Axilla'|'Oral'|'Rectal'
            'pulse_rate'       => $isAbnormal ? rand(100, 120) : rand(70, 95),
            'cardiac_rate'     => $isAbnormal ? rand(100, 120) : rand(70, 95),
            'respiratory_rate' => $isAbnormal ? rand(22, 28)  : rand(14, 20),
            'o2_saturation'    => $isAbnormal ? rand(88, 93)  : rand(96, 100),
            'blood_pressure'   => ($isAbnormal ? rand(150, 180) : rand(110, 130)) . '/' . ($isAbnormal ? rand(100, 110) : rand(70, 85)),
            'weight_kg'        => rand(50, 85),
            'height_cm'        => rand(150, 175),
            'pain_scale'       => $isAbnormal ? rand(5, 8) : rand(0, 3),
            'neurological_vs'  => 'Alert and oriented x3. GCS 15.',
            'notes'            => 'Routine vitals.',
        ]);
        $this->vitalCount++;
    }

    private function addOrders(Visit $visit, User $doctor, Carbon $orderDate, int $count, bool $allCarried): void
    {
        $orderSets = [
            [
                'IVF D5LR 1L @ 30 gtts/min',
                'Paracetamol 500mg IV q6h PRN fever > 38.5°C',
                'CBC, Urinalysis, BUN, Creatinine STAT',
                'Chest X-Ray PA view',
                'Monitor vital signs q4h',
                'NPO pending clearance',
                'Hook to cardiac monitor',
                'O2 via nasal cannula @ 2 LPM, titrate to SpO2 > 95%',
                'Blood CS x2 bottles before antibiotics',
                'Refer to Nephrology if creatinine > 2.0',
            ],
            [
                'IVF PNSS 1L @ 20 gtts/min',
                'Metronidazole 500mg IV q8h',
                'Omeprazole 40mg IV OD',
                'Monitor I&O strictly',
                'Blood glucose monitoring AC and HS',
                'Insulin Glargine 10 units SC HS',
                'Atorvastatin 40mg tab OD HS',
                'Amlodipine 10mg tab OD',
                'ECG 12-lead STAT',
                'Elevate HOB 30°',
            ],
        ];

        $set   = $orderSets[array_rand($orderSets)];
        $lines = array_slice($set, 0, min($count, count($set)));
        $nurses = [$this->nurse1, $this->nurse2, $this->nurse3];

        foreach ($lines as $line) {
            if ($allCarried) {
                $status = 'carried';
            } else {
                $r      = rand(0, 9);
                $status = $r < 4 ? 'pending' : ($r < 9 ? 'carried' : 'discontinued');
            }

            $carriedAt = null;
            $carriedBy = null;
            if ($status !== 'pending') {
                $carriedAt = $orderDate->copy()->addHours(rand(1, 4));
                $carriedBy = $nurses[array_rand($nurses)]->id;
            }

            DoctorsOrder::create([
                'visit_id'     => $visit->id,
                'doctor_id'    => $doctor->id,
                'order_text'   => $line,
                'status'       => $status,    // ✓ enum: 'pending'|'carried'|'discontinued'
                'order_date'   => $orderDate,
                'is_completed' => $status !== 'pending',
                'completed_by' => $carriedBy,
                'completed_at' => $carriedAt,
            ]);
            $this->orderCount++;
        }
    }

    private function addNurseNote(Visit $visit, User $nurse, Carbon $notedAt, string $shift): void
    {
        $fdarSets = [
            [
                'focus'    => 'Acute pain related to inflammatory process, as evidenced by patient self-report.',
                'data'     => 'Patient reports pain 7/10, throbbing in character, aggravated by movement. BP 145/90, PR 98, Temp 38.1°C.',
                'action'   => 'Administered Paracetamol 500mg IV as ordered. Patient repositioned for comfort. Monitored VS.',
                'response' => 'Patient reports pain decreased to 4/10 after 30 minutes. BP 130/85, PR 88. Resting comfortably.',
            ],
            [
                'focus'    => 'Ineffective breathing pattern related to fluid accumulation, as evidenced by O2 saturation of 91%.',
                'data'     => 'Patient with SpO2 91% on room air, RR 24/min, with accessory muscle use. Crackles on auscultation.',
                'action'   => 'O2 applied via nasal cannula @ 2 LPM as ordered. HOB elevated to 30°. Physician notified.',
                'response' => 'SpO2 improved to 96% after O2 therapy. RR 18/min. Patient more comfortable.',
            ],
            [
                'focus'    => 'Risk for fluid volume deficit related to vomiting and poor oral intake.',
                'data'     => 'Patient with emesis x3, unable to tolerate PO fluids. Mucous membranes dry. Urine output decreased.',
                'action'   => 'IVF maintained as ordered. I&O monitored strictly. Patient positioned upright. Antiemetic given.',
                'response' => 'No further emesis episode. Patient able to take small sips of water. Urine output improving.',
            ],
        ];

        $fdar = $fdarSets[array_rand($fdarSets)];

        NursesNote::create([
            'visit_id' => $visit->id,
            'nurse_id' => $nurse->id,
            'focus'    => $fdar['focus'],
            'data'     => $fdar['data'],
            'action'   => $fdar['action'],
            'response' => $fdar['response'],
            'noted_at' => $notedAt,
            'shift'    => $shift,    // ✓ enum: '7-3'|'3-11'|'11-7'
        ]);
        $this->noteCount++;
    }

    private function addIvEntries(Visit $visit, Patient $patient, User $nurse, Carbon $startedAt, int $bottles): void
    {
        $solutions = [
            'D5LR 1L @ 30 gtts/min (125 mL/hr)',
            'PNSS 1L @ 20 gtts/min (83 mL/hr)',
            'D5W 500mL @ 15 gtts/min (62 mL/hr)',
            'PLR 1L @ 25 gtts/min (104 mL/hr)',
        ];

        for ($i = 1; $i <= $bottles; $i++) {
            $bottleStart    = $startedAt->copy()->addHours(($i - 1) * 8);
            $bottleConsumed = ($i < $bottles) ? $bottleStart->copy()->addHours(8) : null;

            IvFluidEntry::create([
                'visit_id'      => $visit->id,
                'patient_id'    => $patient->id,
                'recorded_by'   => $nurse->id,
                'nurse_name'    => $nurse->full_name ?: $nurse->name,
                'date_started'  => $bottleStart->toDateString(),
                'time_started'  => $bottleStart->format('H:i:s'),
                'bottle_number' => $i,
                'iv_solution'   => $solutions[array_rand($solutions)],
                'consumed_at'   => $bottleConsumed,
                'remarks'       => 'Site: L antecubital, 20G. Patient tolerated well. No signs of infiltration.',
            ]);
            $this->ivCount++;
        }
    }

    private function addMarEntries(Visit $visit, Patient $patient, User $nurse, Carbon $admittedAt, Carbon $endAt): void
    {
        $medications = [
            'Paracetamol 500mg IV q6h',
            'Omeprazole 40mg IV OD',
            'Metronidazole 500mg IV q8h',
        ];

        $dates   = [];
        $current = $admittedAt->copy()->startOfDay();
        $limit   = min(
            $endAt->copy()->startOfDay()->timestamp,
            $admittedAt->copy()->addDays(3)->startOfDay()->timestamp
        );

        while ($current->timestamp <= $limit) {
            $dates[] = $current->toDateString();
            $current->addDay();
        }

        if (empty($dates)) {
            return;
        }

        MarDateColumn::firstOrCreate(
            ['visit_id' => $visit->id],
            ['dates'    => $dates]
        );

        foreach ($medications as $i => $med) {
            $adminData = [];
            foreach ($dates as $date) {
                $adminData[$date] = [
                    '7-3'  => '08:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT),
                    '3-11' => rand(0, 1) ? '16:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) : '',
                    '11-7' => rand(0, 1) ? '00:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) : '',
                ];
            }

            MarEntry::create([
                'visit_id'            => $visit->id,
                'patient_id'          => $patient->id,
                'created_by'          => $nurse->id,
                'medication_name'     => $med,
                'administration_data' => $adminData,
                'sort_order'          => $i + 1,
            ]);
            $this->marCount++;
        }
    }

    private function addLabRequest(
        Visit   $visit,
        Patient $patient,
        User    $doctor,
        User    $clerk,
        User    $tech,
        string  $status,    // ✓ enum: 'pending'|'in_progress'|'completed'
        Carbon  $requestedAt
    ): void {
        $testSets = [
            ['Complete Blood Count (CBC)', 'Urinalysis', 'BUN', 'Creatinine'],
            ['Fasting Blood Sugar', 'HbA1c', 'Complete Lipid Profile'],
            ['Complete Blood Count (CBC)', 'AST / SGOT', 'ALT / SGPT', 'Total Bilirubin'],
        ];
        $tests = $testSets[array_rand($testSets)];

        $lab = LabRequest::create([
            'visit_id'             => $visit->id,
            'patient_id'           => $patient->id,
            'doctor_id'            => $doctor->id,
            'submitted_by'         => $clerk->id,
            'status'               => $status,
            'request_type'         => 'routine',
            'tests'                => $tests,
            'clinical_diagnosis'   => $visit->admitting_diagnosis ?? $visit->chief_complaint,
            'requesting_physician' => 'Dr. ' . ($doctor->full_name ?: $doctor->name),
            'ward'                 => $visit->admitted_service ?? 'OPD',
            'date_requested'       => $requestedAt->toDateString(),
            'request_received_at'  => $requestedAt,
            'specimen_collected'   => 'Phlebotomist',
            'test_started_at'      => $status !== 'pending'    ? $requestedAt->copy()->addMinutes(30) : null,
            'test_done_at'         => $status === 'completed'  ? $requestedAt->copy()->addHours(2)    : null,
        ]);
        $this->labCount++;

        if ($status === 'completed') {
            ResultUpload::create([
                'request_type' => 'lab',
                'request_id'   => $lab->id,
                'visit_id'     => $visit->id,
                'patient_id'   => $patient->id,
                'uploaded_by'  => $tech->id,
                'file_path'    => 'results/lab/sample-lab-result.pdf',
                'file_name'    => 'LAB-Result-' . $lab->request_no . '.pdf',
                'file_mime'    => 'application/pdf',
                'file_size'    => 102400,
                'notes'        => 'All values within reference range. CBC normal. Renal function preserved.',
            ]);
            $this->uploadCount++;
        }
    }

    private function addRadiologyRequest(
        Visit   $visit,
        Patient $patient,
        User    $doctor,
        User    $clerk,
        User    $tech,
        string  $status,    // ✓ enum: 'pending'|'in_progress'|'completed'
        Carbon  $requestedAt
    ): void {
        $exams = [
            ['modality' => 'X-RAY',     'exam' => 'Chest X-Ray, PA View'],
            ['modality' => 'ULTRASOUND', 'exam' => 'Whole Abdomen Ultrasound'],
            ['modality' => 'X-RAY',     'exam' => 'KUB Plain Film'],
        ];
        $exam = $exams[array_rand($exams)];

        $interpretations = [
            'Impression: No active cardiopulmonary lesion. Heart size within normal limits. Clear lung fields bilaterally.',
            'Impression: Mild cardiomegaly with pulmonary venous congestion. Consider further cardiac workup.',
            'Impression: No radiographic evidence of acute bony pathology. Soft tissues unremarkable.',
            'Impression: Mild pleural effusion noted at right base. Suggest clinical correlation.',
        ];

        $rad = RadiologyRequest::create([
            'visit_id'                   => $visit->id,
            'patient_id'                 => $patient->id,
            'doctor_id'                  => $doctor->id,
            'submitted_by'               => $clerk->id,
            'status'                     => $status,
            'modality'                   => $exam['modality'],
            'examination_desired'        => $exam['exam'],
            'clinical_diagnosis'         => $visit->admitting_diagnosis ?? $visit->chief_complaint,
            'clinical_findings'          => 'Refer to admitting notes.',
            'requesting_physician'       => 'Dr. ' . ($doctor->full_name ?: $doctor->name),
            'ward'                       => $visit->admitted_service ?? 'OPD',
            'source'                     => $visit->payment_class === 'Private' ? 'PRIVATE' : 'CHARITY',
            'date_requested'             => $requestedAt->toDateString(),
            'request_received_at'        => $requestedAt,
            'exam_started_at'            => $status !== 'pending'   ? $requestedAt->copy()->addMinutes(45)         : null,
            'exam_done_at'               => $status === 'completed' ? $requestedAt->copy()->addHours(1)->addMinutes(30) : null,
            'radiologist_interpretation' => $status === 'completed' ? $interpretations[array_rand($interpretations)] : null,
        ]);
        $this->radCount++;

        if ($status === 'completed') {
            ResultUpload::create([
                'request_type'   => 'radiology',
                'request_id'     => $rad->id,
                'visit_id'       => $visit->id,
                'patient_id'     => $patient->id,
                'uploaded_by'    => $tech->id,
                'file_path'      => 'results/radiology/sample-xray.pdf',
                'file_name'      => 'RAD-Result-' . $rad->request_no . '.pdf',
                'file_mime'      => 'application/pdf',
                'file_size'      => 204800,
                'interpretation' => $rad->radiologist_interpretation,
                'notes'          => 'Radiologist-read result. Signed and verified.',
            ]);
            $this->uploadCount++;
        }
    }

    // ════════════════════════════════════════════════════════════════════════
    //  CLERK FORMS
    // ════════════════════════════════════════════════════════════════════════

    private function fillErRecord(Visit $visit, Patient $patient, User $clerk, Carbon $registeredAt): void
    {
        ErRecord::firstOrCreate(['visit_id' => $visit->id], [
            'patient_id'                      => $patient->id,
            'filled_by'                       => $clerk->id,
            'health_record_no'                => $patient->case_no,
            'type_of_service'                 => 'Emergency',
            'medico_legal'                    => false,
            'case_type'                       => 'Acute',
            'notified_proper_authority'       => 'N/A',
            'patient_family_name'             => $patient->family_name,
            'patient_first_name'              => $patient->first_name,
            'patient_middle_name'             => $patient->middle_name,
            'permanent_address'               => $patient->address,
            'telephone_no'                    => $patient->contact_number,
            'nationality'                     => $patient->nationality ?? 'Filipino',
            'age'                             => $patient->current_age,
            'birthdate'                       => $patient->birthday,
            'sex'                             => $patient->sex,
            'civil_status'                    => $patient->civil_status,
            'registration_date'               => $registeredAt->toDateString(),
            'registration_time'               => $registeredAt->format('H:i'),
            'brought_by'                      => $visit->brought_by ?? 'Family',
            'condition_on_arrival'            => $visit->condition_on_arrival ?? 'Fair',
            'chief_complaint'                 => $visit->chief_complaint,
            'physical_findings_and_diagnosis' => $visit->admitting_diagnosis ?? $visit->chief_complaint,
            'treatment'                       => 'As per doctor\'s orders.',
            'disposition'                     => 'Admitted',
            'condition_on_discharge'          => 'Improved',
        ]);
        $this->formCount++;
    }

    private function fillAdmissionRecord(Visit $visit, Patient $patient, User $clerk, Carbon $admittedAt, ?Carbon $dischargedAt, string $service): void
    {
        AdmissionRecord::firstOrCreate(['visit_id' => $visit->id], [
            'patient_id'              => $patient->id,
            'filled_by'               => $clerk->id,
            'patient_family_name'     => $patient->family_name,
            'patient_first_name'      => $patient->first_name,
            'patient_middle_name'     => $patient->middle_name,
            'permanent_address'       => $patient->address,
            'telephone_no'            => $patient->contact_number,
            'sex'                     => $patient->sex,
            'civil_status'            => $patient->civil_status,
            'birthdate'               => $patient->birthday,
            'age'                     => $patient->current_age,
            'nationality'             => $patient->nationality ?? 'Filipino',
            'religion'                => $patient->religion ?? 'Roman Catholic',
            'occupation'              => $patient->occupation,
            'philhealth_id'           => $patient->philhealth_id,
            'philhealth_type'         => $patient->philhealth_type,
            'admission_date'          => $admittedAt->toDateString(),
            'admission_time'          => $admittedAt->format('H:i'),
            'discharge_date'          => $dischargedAt?->toDateString(),
            'discharge_time'          => $dischargedAt?->format('H:i'),
            'total_days'              => $dischargedAt ? (int) $admittedAt->diffInDays($dischargedAt) : null,
            'ward_service'            => $service . ' Ward',
            'type_of_admission'       => 'Emergency',
            'social_service_class'    => $patient->social_service_class ?? 'C1',
            'payment_class'           => $visit->payment_class,
            'admission_diagnosis'     => $visit->admitting_diagnosis ?? $visit->chief_complaint,
            'final_diagnosis'         => $dischargedAt ? ($visit->admitting_diagnosis ?? $visit->chief_complaint) : null,
            'disposition'             => $dischargedAt ? 'Discharged' : 'Admitted',
            'results'                 => $dischargedAt ? 'Improved' : null,
            'data_furnished_by'       => 'Patient/Family',
            'data_furnished_relation' => 'Self/Relative',
        ]);
        $this->formCount++;
    }

    private function fillConsentRecord(Visit $visit, Patient $patient, User $clerk, Carbon $signedAt, User $doctor): void
    {
        $docName = 'Dr. ' . ($doctor->full_name ?: $doctor->name);

        ConsentRecord::firstOrCreate(['visit_id' => $visit->id], [
            'patient_id'          => $patient->id,
            'saved_by'            => $clerk->id,
            'active_section'      => 1,              // ✓ unsignedTinyInteger — NOT a string like 'both'
            'patient_name'        => $patient->consent_name,
            'doctor_name_sec1'    => $docName,
            'witness_sec1'        => 'Grace Mendoza',
            'signed_date_sec1'    => $signedAt->toDateString(),
            'guardian_name'       => $patient->father_name ?? 'N/A',
            'nok_sig_name'        => $patient->family_name . ', Family',
            'being_the'           => 'patient',
            'doctor_name_sec2'    => $docName,
            'witness_sec2'        => 'Grace Mendoza',
            'signed_date_sec2'    => $signedAt->toDateString(),
            'relation_to_patient' => 'Self',
        ]);
        $this->formCount++;
    }

    // ════════════════════════════════════════════════════════════════════════
    //  UTILITIES
    // ════════════════════════════════════════════════════════════════════════

    private function loadStaff(): void
    {
        $this->drSantos  = User::where('username', 'dr.santos')->firstOrFail();
        $this->drReyes   = User::where('username', 'dr.reyes')->firstOrFail();
        $this->drGarcia  = User::where('username', 'dr.garcia')->firstOrFail();
        $this->clerkOPD  = User::where('username', 'clerk.opd')->firstOrFail();
        $this->clerkER   = User::where('username', 'clerk.er')->firstOrFail();
        $this->nurse1    = User::where('username', 'nurse.dela')->firstOrFail();
        $this->nurse2    = User::where('username', 'nurse.flores')->firstOrFail();
        $this->nurse3    = User::where('username', 'nurse.torres')->firstOrFail();
        $this->nurse4    = User::where('username', 'nurse.santos')->firstOrFail();
        $this->techLab1  = User::where('username', 'tech.lab1')->firstOrFail();
        $this->techLab2  = User::where('username', 'tech.lab2')->firstOrFail();
        $this->techRad   = User::where('username', 'tech.rad')->firstOrFail();
    }

    /**
     * Wire nurse4 (Carla Santos / nurse2@lumc.gov.ph) into the three currently-
     * admitted visits so she has a meaningful presence in the system.
     *
     * Records created per visit:
     *   • 1 vital sign entry (11-7 shift — overnight monitoring)
     *   • 1 FDAR nurse note (11-7 shift)
     *   • 1 IV fluid bottle continuation entry
     *   • 1 MAR medication row (if the visit already has a MarDateColumn)
     */
    private function seedExtraNurseActivity(): void
    {
        $targets = [
            ['family_name' => 'Dela Cruz'],
            ['family_name' => 'Reyes', 'first_name' => 'Eduardo'],
            ['family_name' => 'Mendoza', 'first_name' => 'Ernesto'],
        ];

        foreach ($targets as $t) {
            $query = Patient::where('family_name', $t['family_name']);
            if (!empty($t['first_name'])) {
                $query->where('first_name', $t['first_name']);
            }
            $patient = $query->first();

            if (!$patient) continue;

            $visit = Visit::where('patient_id', $patient->id)
                ->where('status', 'admitted')
                ->latest('registered_at')
                ->first();

            if (!$visit) continue;

            $now = Carbon::create(2026, 4, 9, 2, 0, 0, 'Asia/Manila'); // 2 AM (11-7 shift)

            // ── Vital sign (overnight) ─────────────────────────────────────
            Vital::create([
                'visit_id'         => $visit->id,
                'patient_id'       => $patient->id,
                'recorded_by'      => $this->nurse4->id,
                'nurse_name'       => $this->nurse4->full_name ?: $this->nurse4->name,
                'taken_at'         => $now,
                'temperature'      => 36.8,
                'temperature_site' => 'Axilla',
                'pulse_rate'       => 82,
                'cardiac_rate'     => 82,
                'respiratory_rate' => 16,
                'o2_saturation'    => 97,
                'blood_pressure'   => '118/76',
                'pain_scale'       => 1,
                'neurological_vs'  => 'Sleeping, arousable. GCS 15.',
                'notes'            => '11-7 shift routine monitoring.',
            ]);
            $this->vitalCount++;

            // ── FDAR Nurse Note ───────────────────────────────────────────
            NursesNote::create([
                'visit_id' => $visit->id,
                'nurse_id' => $this->nurse4->id,
                'focus'    => 'Patient monitoring during 11-7 shift',
                'data'     => 'Patient sleeping comfortably. Vital signs stable. IVF infusing well.',
                'action'   => 'Monitored vital signs q4h. IVF regulated. Repositioned patient.',
                'response' => 'Patient remained stable. No complaints noted.',
                'noted_at' => $now,
                'shift'    => '11-7',
            ]);
            $this->noteCount++;

            // ── IV Fluid Continuation ─────────────────────────────────────
            $lastBottle = IvFluidEntry::where('visit_id', $visit->id)->max('bottle_number') ?? 0;

            IvFluidEntry::create([
                'visit_id'      => $visit->id,
                'patient_id'    => $patient->id,
                'recorded_by'   => $this->nurse4->id,
                'nurse_name'    => $this->nurse4->full_name ?: $this->nurse4->name,
                'date_started'  => $now->toDateString(),
                'time_started'  => $now->format('H:i:s'),
                'bottle_number' => $lastBottle + 1,
                'iv_solution'   => 'PNSS 1L @ 20 gtts/min',
                'consumed_at'   => null,
                'remarks'       => 'Continued from previous shift. Site patent.',
            ]);
            $this->ivCount++;

            // ── MAR Entry (Fixed) ─────────────────────────────────────────
            $marDateCol = MarDateColumn::where('visit_id', $visit->id)->first();

            if ($marDateCol && !empty($marDateCol->dates)) {
                $dates = $marDateCol->dates;           // Get array copy
                $latestDate = end($dates);             // Safe now

                $marEntry = MarEntry::firstOrCreate(
                    [
                        'visit_id'        => $visit->id,
                        'medication_name' => 'Pantoprazole 40mg IV OD',
                    ],
                    [
                        'patient_id'          => $patient->id,
                        'created_by'          => $this->nurse4->id,
                        'administration_data' => [],
                        'sort_order'          => 10,
                    ]
                );

                // Record 11-7 shift administration
                $marEntry->setTime($latestDate, '11-7', '02:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT));
                $this->marCount++;
            }
        }
    }

    private function randomPMHx(): string
    {
        $options = [
            'Hypertension (10 years). On Amlodipine 5mg OD.',
            'DM2 (5 years). On Metformin 500mg BID.',
            'Hypertension and DM2. On Losartan 50mg OD and Metformin 1g OD.',
            'No known chronic illness. No prior surgery. No prior hospitalization.',
            'Bronchial asthma since childhood. Uses Salbutamol MDI PRN.',
            'Pulmonary tuberculosis (completed treatment 2019). COPD.',
            'Hypothyroidism. On Levothyroxine 100mcg OD.',
        ];
        return $options[array_rand($options)];
    }
}