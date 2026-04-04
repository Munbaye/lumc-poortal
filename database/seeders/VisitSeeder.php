<?php

namespace Database\Seeders;

use App\Models\AdmissionRecord;
use App\Models\ConsentRecord;
use App\Models\DoctorsOrder;
use App\Models\ErRecord;
use App\Models\MedicalHistory;
use App\Models\NursesNote;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use App\Models\Vital;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * VisitSeeder
 *
 * Creates all 20 patients and their visits (2–4 per patient).
 * Also wires up the two patient portal User accounts created in StaffSeeder.
 *
 * Schema notes (see migrations for exact columns):
 *   patients      — NO payment_class, NO social_service_class as free strings
 *                   philhealth_type enum: Government|Indigent|Private|Self-Employed
 *   vitals        — recorded_by (FK to users), nurse_name (string), NO nurse_id
 *   medical_histories — NO notes column
 *   er_records    — NO doctor_name column
 *   lab_requests / radiology_requests — request_received_at (NOT received_at)
 */
class VisitSeeder extends Seeder
{
    // ── Staff user references (looked up once) ────────────────────────────
    private User $dSantos;
    private User $dReyes;
    private User $dDelaCruz;
    private User $dMendoza;
    private User $dBautista;
    private User $dCastillo;
    private User $nGonzalez;
    private User $nTorres;
    private User $nRamos;
    private User $clerkRosa;
    private User $clerkMark;

    public function run(): void
    {
        $this->dSantos   = User::where('email', 'doctor@lumc.gov.ph')->firstOrFail();
        $this->dReyes    = User::where('email', 'mreyes@lumc.gov.ph')->firstOrFail();
        $this->dDelaCruz = User::where('email', 'jdelacruz@lumc.gov.ph')->firstOrFail();
        $this->dMendoza  = User::where('email', 'amendoza@lumc.gov.ph')->firstOrFail();
        $this->dBautista = User::where('email', 'rbautista@lumc.gov.ph')->firstOrFail();
        $this->dCastillo = User::where('email', 'lcastillo@lumc.gov.ph')->firstOrFail();
        $this->nGonzalez = User::where('email', 'nurse@lumc.gov.ph')->firstOrFail();
        $this->nTorres   = User::where('email', 'btorres@lumc.gov.ph')->firstOrFail();
        $this->nRamos    = User::where('email', 'cramos@lumc.gov.ph')->firstOrFail();
        $this->clerkRosa = User::where('email', 'clerk@lumc.gov.ph')->firstOrFail();
        $this->clerkMark = User::where('email', 'maquino@lumc.gov.ph')->firstOrFail();

        $patients = $this->createPatients();
        $this->createAllVisits($patients);
        $this->linkPortalAccounts($patients);

        $this->command->info('   ✓ Patients and visits seeded');
    }

    // ══════════════════════════════════════════════════════════════════════
    //  PATIENTS
    //  Note: payment_class lives on visits, NOT patients.
    //        social_service_class on patients is enum(A,B,C1,C2,C3,D).
    //        philhealth_type on patients is enum(Government,Indigent,Private,Self-Employed).
    // ══════════════════════════════════════════════════════════════════════

    private function createPatients(): array
    {
        $defs = [
            // 0 — Eduardo Dela Rosa (repeat Internal Medicine)
            [
                'family_name'     => 'DELA ROSA',
                'first_name'      => 'Eduardo',
                'middle_name'     => 'Santos',
                'sex'             => 'Male',
                'birthday'        => '1962-04-15',
                'address'         => 'Brgy. Nazareno, Agoo, La Union',
                'contact_number'  => '09171234501',
                'philhealth_id'   => 'PH-001-234-567',
                'philhealth_type' => 'Indigent',
                'religion'        => 'Roman Catholic',
                'civil_status'    => 'Married',
                'occupation'      => 'Farmer',
            ],
            // 1 — Maria Theresa Garcia (Dengue → Appendicitis)
            [
                'family_name'    => 'GARCIA',
                'first_name'     => 'Maria Theresa',
                'middle_name'    => 'Reyes',
                'sex'            => 'Female',
                'birthday'       => '1998-09-22',
                'address'        => 'Brgy. San Isidro, Agoo, La Union',
                'contact_number' => '09281234502',
                'religion'       => 'Roman Catholic',
                'civil_status'   => 'Single',
                'occupation'     => 'Student',
            ],
            // 2 — Liza Bautista (Private OB — portal account)
            [
                'family_name'     => 'BAUTISTA',
                'first_name'      => 'Liza',
                'middle_name'     => 'Cruz',
                'sex'             => 'Female',
                'birthday'        => '1990-12-08',
                'address'         => 'Poblacion, Agoo, La Union',
                'contact_number'  => '09151234503',
                'philhealth_id'   => 'PH-002-345-678',
                'philhealth_type' => 'Self-Employed',
                'religion'        => 'Roman Catholic',
                'civil_status'    => 'Married',
                'occupation'      => 'Teacher',
            ],
            // 3 — Andrei Santos (Pedia)
            [
                'family_name'    => 'SANTOS',
                'first_name'     => 'Andrei',
                'middle_name'    => 'Lopez',
                'sex'            => 'Male',
                'birthday'       => '2020-03-10',
                'address'        => 'Brgy. Macalva Norte, Agoo, La Union',
                'contact_number' => '09391234504',
                'religion'       => 'Roman Catholic',
                'civil_status'   => 'Single',
                'occupation'     => 'N/A',
                'is_pedia'       => true,
                'father_name'    => 'Antonio Santos',
                'mother_name'    => 'Josephine Lopez-Santos',
            ],
            // 4 — Conchita Villanueva (Elderly DM/HPN)
            [
                'family_name'     => 'VILLANUEVA',
                'first_name'      => 'Conchita',
                'middle_name'     => 'Macaraeg',
                'sex'             => 'Female',
                'birthday'        => '1948-07-30',
                'address'         => 'Brgy. Purok 2, San Fernando, La Union',
                'contact_number'  => '09261234505',
                'philhealth_id'   => 'PH-003-456-789',
                'philhealth_type' => 'Government',
                'religion'        => 'Roman Catholic',
                'civil_status'    => 'Widowed',
                'occupation'      => 'Retired',
            ],
            // 5 — Ramon Mendoza (Private Surgery — portal account)
            [
                'family_name'     => 'MENDOZA',
                'first_name'      => 'Ramon',
                'middle_name'     => 'Abad',
                'sex'             => 'Male',
                'birthday'        => '1985-11-20',
                'address'         => 'Brgy. Catbangen, San Fernando, La Union',
                'contact_number'  => '09171234506',
                'philhealth_id'   => 'PH-004-567-890',
                'philhealth_type' => 'Self-Employed',
                'religion'        => 'Roman Catholic',
                'civil_status'    => 'Married',
                'occupation'      => 'Engineer',
            ],
            // 6 — Rolando Pagaduan (TB + Hemoptysis)
            [
                'family_name'    => 'PAGADUAN',
                'first_name'     => 'Rolando',
                'middle_name'    => 'Ferrer',
                'sex'            => 'Male',
                'birthday'       => '1975-05-18',
                'address'        => 'Brgy. Bungro, Agoo, La Union',
                'contact_number' => '09481234507',
                'religion'       => 'Aglipayan',
                'civil_status'   => 'Married',
                'occupation'     => 'Construction Worker',
            ],
            // 7 — Felicidad Castillo (Cardiology / CHF)
            [
                'family_name'     => 'CASTILLO',
                'first_name'      => 'Felicidad',
                'middle_name'     => 'Quinto',
                'sex'             => 'Female',
                'birthday'        => '1965-01-25',
                'address'         => 'Brgy. Paringao, Bauang, La Union',
                'contact_number'  => '09201234508',
                'philhealth_id'   => 'PH-005-678-901',
                'philhealth_type' => 'Indigent',
                'religion'        => 'Roman Catholic',
                'civil_status'    => 'Married',
                'occupation'      => 'Housewife',
            ],
            // 8 — Kevin Manalo (Dengue)
            [
                'family_name'    => 'MANALO',
                'first_name'     => 'Kevin',
                'middle_name'    => 'Ramos',
                'sex'            => 'Male',
                'birthday'       => '2005-08-14',
                'address'        => 'Brgy. Sevilla, San Fernando, La Union',
                'contact_number' => '09351234509',
                'religion'       => 'Roman Catholic',
                'civil_status'   => 'Single',
                'occupation'     => 'Student',
            ],
            // 9 — Stephanie Aquino (Private OB — active labor)
            [
                'family_name'     => 'AQUINO',
                'first_name'      => 'Stephanie',
                'middle_name'     => 'Chan',
                'sex'             => 'Female',
                'birthday'        => '1993-06-02',
                'address'         => '123 Quezon Ave., Bauang, La Union',
                'contact_number'  => '09171234510',
                'philhealth_id'   => 'PH-006-789-012',
                'philhealth_type' => 'Self-Employed',
                'religion'        => 'Roman Catholic',
                'civil_status'    => 'Married',
                'occupation'      => 'Nurse (Private)',
            ],
            // 10 — Maricel Evangelista (Asthma)
            [
                'family_name'    => 'EVANGELISTA',
                'first_name'     => 'Maricel',
                'middle_name'    => 'Domingo',
                'sex'            => 'Female',
                'birthday'       => '1988-03-30',
                'address'        => 'Brgy. Magsaysay, Agoo, La Union',
                'contact_number' => '09461234511',
                'religion'       => 'Roman Catholic',
                'civil_status'   => 'Married',
                'occupation'     => 'Market Vendor',
            ],
            // 11 — Fernando Pascual (Trauma / Fracture)
            [
                'family_name'    => 'PASCUAL',
                'first_name'     => 'Fernando',
                'middle_name'    => 'Mabini',
                'sex'            => 'Male',
                'birthday'       => '1978-10-05',
                'address'        => 'Brgy. Urayong, Agoo, La Union',
                'contact_number' => '09271234512',
                'religion'       => 'Roman Catholic',
                'civil_status'   => 'Married',
                'occupation'     => 'Tricycle Driver',
            ],
            // 12 — Sofia Torres (Pedia AGE)
            [
                'family_name'    => 'TORRES',
                'first_name'     => 'Sofia',
                'middle_name'    => 'Navarro',
                'sex'            => 'Female',
                'birthday'       => '2022-11-19',
                'address'        => 'Brgy. Consolacion, Agoo, La Union',
                'contact_number' => '09181234513',
                'religion'       => 'Roman Catholic',
                'civil_status'   => 'Single',
                'occupation'     => 'N/A',
                'is_pedia'       => true,
                'father_name'    => 'Marco Torres',
                'mother_name'    => 'Anita Navarro-Torres',
            ],
            // 13 — Jorge Francisco (Private — Recurrent CAP)
            [
                'family_name'     => 'FRANCISCO',
                'first_name'      => 'Jorge',
                'middle_name'     => 'Rivera',
                'sex'             => 'Male',
                'birthday'        => '1955-09-12',
                'address'         => '45 Rizal St., Bauang, La Union',
                'contact_number'  => '09231234514',
                'philhealth_id'   => 'PH-007-890-123',
                'philhealth_type' => 'Government',
                'religion'        => 'Roman Catholic',
                'civil_status'    => 'Married',
                'occupation'      => 'Retired Government Employee',
            ],
            // 14 — Natividad Reyes (UTI / Pyelonephritis)
            [
                'family_name'    => 'REYES',
                'first_name'     => 'Natividad',
                'middle_name'    => 'Sison',
                'sex'            => 'Female',
                'birthday'       => '1970-02-14',
                'address'        => 'Brgy. Balawarte, Agoo, La Union',
                'contact_number' => '09421234515',
                'religion'       => 'Born Again Christian',
                'civil_status'   => 'Married',
                'occupation'     => 'Dressmaker',
            ],
            // 15 — Dante Lopez (Hypertensive Crisis / Stroke)
            [
                'family_name'     => 'LOPEZ',
                'first_name'      => 'Dante',
                'middle_name'     => 'Ignacio',
                'sex'             => 'Male',
                'birthday'        => '1960-06-28',
                'address'         => 'Brgy. Catbangen, San Fernando, La Union',
                'contact_number'  => '09151234516',
                'philhealth_id'   => 'PH-008-901-234',
                'philhealth_type' => 'Indigent',
                'religion'        => 'Iglesia ni Cristo',
                'civil_status'    => 'Married',
                'occupation'      => 'Fisherman',
            ],
            // 16 — Rosario Magno (Pre-eclampsia)
            [
                'family_name'    => 'MAGNO',
                'first_name'     => 'Rosario',
                'middle_name'    => 'Alcantara',
                'sex'            => 'Female',
                'birthday'       => '1996-08-03',
                'address'        => 'Brgy. Duplas, Agoo, La Union',
                'contact_number' => '09361234517',
                'religion'       => 'Roman Catholic',
                'civil_status'   => 'Married',
                'occupation'     => 'Housewife',
            ],
            // 17 — Christian Ocampo (Private Appendicitis)
            [
                'family_name'     => 'OCAMPO',
                'first_name'      => 'Christian',
                'middle_name'     => 'Buenaventura',
                'sex'             => 'Male',
                'birthday'        => '2001-04-07',
                'address'         => '78 Mabini St., San Fernando, La Union',
                'contact_number'  => '09171234518',
                'philhealth_id'   => 'PH-009-012-345',
                'philhealth_type' => 'Self-Employed',
                'religion'        => 'Roman Catholic',
                'civil_status'    => 'Single',
                'occupation'      => 'Call Center Agent',
            ],
            // 18 — Remedios Domingo (Elderly Stroke)
            [
                'family_name'     => 'DOMINGO',
                'first_name'      => 'Remedios',
                'middle_name'     => 'Galvez',
                'sex'             => 'Female',
                'birthday'        => '1944-12-01',
                'address'         => 'Brgy. Sevilla, San Fernando, La Union',
                'contact_number'  => '09291234519',
                'philhealth_id'   => 'PH-010-123-456',
                'philhealth_type' => 'Government',
                'religion'        => 'Roman Catholic',
                'civil_status'    => 'Widowed',
                'occupation'      => 'Retired',
            ],
            // 19 — Jaime Natividad (Repeat CAP / COPD)
            [
                'family_name'     => 'NATIVIDAD',
                'first_name'      => 'Jaime',
                'middle_name'     => 'Tabios',
                'sex'             => 'Male',
                'birthday'        => '1950-03-21',
                'address'         => 'Brgy. Macalva Sur, Agoo, La Union',
                'contact_number'  => '09131234520',
                'philhealth_id'   => 'PH-011-234-567',
                'philhealth_type' => 'Government',
                'religion'        => 'Roman Catholic',
                'civil_status'    => 'Married',
                'occupation'      => 'Retired Teacher',
            ],
        ];

        $patients = [];
        foreach ($defs as $def) {
            $birthday = isset($def['birthday']) ? Carbon::parse($def['birthday']) : null;
            $age      = $birthday ? (int) $birthday->diffInYears(now()) : null;

            $patients[] = Patient::create([
                'family_name'     => $def['family_name'],
                'first_name'      => $def['first_name'],
                'middle_name'     => $def['middle_name']    ?? null,
                'sex'             => $def['sex'],
                'birthday'        => $def['birthday']        ?? null,
                'age'             => $age,
                'address'         => $def['address'],
                'contact_number'  => $def['contact_number']  ?? null,
                'philhealth_id'   => $def['philhealth_id']   ?? null,
                'philhealth_type' => $def['philhealth_type'] ?? null,
                'religion'        => $def['religion']         ?? null,
                'civil_status'    => $def['civil_status']     ?? null,
                'occupation'      => $def['occupation']       ?? null,
                'father_name'     => $def['father_name']      ?? null,
                'mother_name'     => $def['mother_name']      ?? null,
                'is_pedia'        => $def['is_pedia']         ?? false,
                'has_incomplete_info' => false,
                'is_unknown'      => false,
            ]);
        }

        return $patients;
    }

    // ══════════════════════════════════════════════════════════════════════
    //  VISIT FACTORY HELPERS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Create a complete admitted visit:
     *   Visit + MedicalHistory + Vitals[] + DoctorsOrders[] + NursesNotes[]
     *   + ErRecord + AdmissionRecord + ConsentRecord
     */
    private function admitted(
        Patient $patient,
        User    $doctor,
        User    $clerk,
        User    $nurse,
        array   $o
    ): Visit {
        $regAt      = $o['registered_at'];
        $docAdmAt   = $o['doctor_admitted_at']  ?? $regAt->copy()->addMinutes(rand(40, 90));
        $clerkAdmAt = $o['clerk_admitted_at']   ?? $docAdmAt->copy()->addMinutes(rand(20, 45));
        $disAt      = $o['discharged_at']       ?? null;
        $status     = $disAt ? 'discharged' : 'admitted';
        $payClass   = $o['payment_class'] ?? 'Charity';
        $visitType  = $o['visit_type']    ?? 'ER';

        // ── Visit ──────────────────────────────────────────────────────────
        $visit = Visit::create([
            'patient_id'          => $patient->id,
            'clerk_id'            => $clerk->id,
            'assigned_doctor_id'  => $doctor->id,
            'visit_type'          => $visitType,
            'chief_complaint'     => $o['chief_complaint'],
            'admitting_diagnosis' => $o['admitting_diagnosis'],
            'admitted_service'    => $o['service'],
            'disposition'         => $disAt ? 'Discharged' : 'Admitted',
            'payment_class'       => $payClass,
            'status'              => $status,
            'brought_by'          => $o['brought_by']          ?? null,
            'condition_on_arrival'=> $o['condition_on_arrival'] ?? null,
            'registered_at'       => $regAt,
            'doctor_admitted_at'  => $docAdmAt,
            'clerk_admitted_at'   => $clerkAdmAt,
            'discharged_at'       => $disAt,
        ]);

        // ── Medical History ────────────────────────────────────────────────
        MedicalHistory::create([
            'visit_id'   => $visit->id,
            'patient_id' => $patient->id,
            'doctor_id'  => $doctor->id,
            'diagnosis'  => $o['admitting_diagnosis'],
            'service'    => $o['service'],
            'disposition'=> $disAt ? 'Discharged' : 'Admitted',
        ]);

        // ── Vitals ─────────────────────────────────────────────────────────
        // Note: column is `recorded_by` (FK), `nurse_name` (string) — NOT nurse_id
        $vitalsRows = $o['vitals'] ?? [[
            'bp' => '130/80', 'pr' => 88, 'rr' => 18, 'temp' => 37.2, 'o2' => 98, 'pain' => 4,
        ]];
        foreach ($vitalsRows as $idx => $vd) {
            Vital::create([
                'visit_id'         => $visit->id,
                'patient_id'       => $patient->id,
                'recorded_by'      => $nurse->id,        // FK — correct column name
                'nurse_name'       => $nurse->name,      // string — correct column name
                'blood_pressure'   => $vd['bp'],
                'pulse_rate'       => $vd['pr'],
                'respiratory_rate' => $vd['rr'],
                'temperature'      => $vd['temp'],
                'o2_saturation'    => $vd['o2'],
                'pain_scale'       => $vd['pain'] ?? null,
                'weight_kg'        => $vd['weight'] ?? null,
                'height_cm'        => $vd['height'] ?? null,
                'taken_at'         => $regAt->copy()->addMinutes(20 + $idx * 240),
            ]);
        }

        // ── Doctor's Orders ────────────────────────────────────────────────
        $orders    = $o['orders'] ?? ['Monitor vital signs q4h'];
        $orderDate = $docAdmAt->copy();
        foreach ($orders as $orderText) {
            DoctorsOrder::create([
                'visit_id'     => $visit->id,
                'doctor_id'    => $doctor->id,
                'order_text'   => $orderText,
                'status'       => DoctorsOrder::STATUS_CARRIED,
                'order_date'   => $orderDate,
                'is_completed' => true,
                'completed_by' => $nurse->id,
                'completed_at' => $orderDate->copy()->addMinutes(rand(15, 60)),
            ]);
        }

        // ── Nurse's Notes ──────────────────────────────────────────────────
        $fdarNotes = $o['fdar_notes'] ?? [[
        'F' => 'Admission assessment.',
        'D' => 'VS stable. IV patent. Patient alert and cooperative.',
        'A' => 'Admission care rendered. Medications given as ordered.',
        'R' => 'Patient tolerated interventions. No adverse reactions noted.',
    ]];
    foreach ($fdarNotes as $idx => $note) {
        NursesNote::create([
            'visit_id' => $visit->id,
            'nurse_id' => $nurse->id,
            'focus'    => $note['F'] ?? null,
            'data'     => $note['D'] ?? null,
            'action'   => $note['A'] ?? null,
            'response' => $note['R'] ?? null,
            'noted_at' => $clerkAdmAt->copy()->addHours(2 + $idx * 8),
        ]);
    }

        // ── ER Record ──────────────────────────────────────────────────────
        // Note: er_records has NO doctor_name column
        ErRecord::create([
            'visit_id'                        => $visit->id,
            'patient_id'                      => $patient->id,
            'filled_by'                       => $clerk->id,
            'health_record_no'                => $patient->case_no,
            'type_of_service'                 => $visitType === 'ER' ? 'Emergency' : 'Out-Patient',
            'medico_legal'                    => false,
            'case_type'                       => $visitType === 'ER' ? 'Emergency' : 'Elective',
            'patient_family_name'             => $patient->family_name,
            'patient_first_name'              => $patient->first_name,
            'patient_middle_name'             => $patient->middle_name,
            'permanent_address'               => $patient->address,
            'telephone_no'                    => $patient->contact_number,
            'nationality'                     => 'Filipino',
            'age'                             => Carbon::parse($patient->birthday)->diffInYears(now()),
            'birthdate'                       => $patient->birthday,
            'sex'                             => $patient->sex,
            'civil_status'                    => $patient->civil_status,
            'registration_date'               => $regAt->format('Y-m-d'),
            'registration_time'               => $regAt->format('H:i'),
            'brought_by'                      => $o['brought_by']          ?? null,
            'condition_on_arrival'            => $o['condition_on_arrival'] ?? null,
            'temperature'                     => (float) ($vitalsRows[0]['temp'] ?? 37.2),
            'pulse_rate'                      => (int)   ($vitalsRows[0]['pr']   ?? 88),
            'blood_pressure'                  => $vitalsRows[0]['bp'] ?? '130/80',
            'respiratory_rate'                => (int)   ($vitalsRows[0]['rr']   ?? 18),
            'chief_complaint'                 => $o['chief_complaint'],
            'allergies'                       => $o['allergies'] ?? 'NKDA',
            'physical_findings_and_diagnosis' => $o['admitting_diagnosis'],
            'treatment'                       => $o['treatment'] ?? 'IVF started; medications as ordered',
            'disposition'                     => $disAt ? 'Discharged' : 'For Admission',
            'disposition_date'                => $disAt ? $disAt->format('Y-m-d') : $docAdmAt->format('Y-m-d'),
            'disposition_time'                => $disAt ? $disAt->format('H:i') : $docAdmAt->format('H:i'),
            'condition_on_discharge'          => $disAt ? ($o['condition_on_discharge'] ?? 'Stable, improved') : null,
        ]);

        // ── Admission Record ───────────────────────────────────────────────
        $admDays = ($disAt && $clerkAdmAt) ? max(0, (int) $clerkAdmAt->diffInDays($disAt)) : null;

        AdmissionRecord::create([
            'visit_id'             => $visit->id,
            'patient_id'           => $patient->id,
            'filled_by'            => $clerk->id,
            'patient_family_name'  => $patient->family_name,
            'patient_first_name'   => $patient->first_name,
            'patient_middle_name'  => $patient->middle_name,
            'permanent_address'    => $patient->address,
            'telephone_no'         => $patient->contact_number,
            'sex'                  => $patient->sex,
            'civil_status'         => $patient->civil_status,
            'birthdate'            => $patient->birthday,
            'age'                  => Carbon::parse($patient->birthday)->diffInYears(now()),
            'birthplace'           => $patient->birthplace ?? 'La Union',
            'nationality'          => 'Filipino',
            'religion'             => $patient->religion,
            'occupation'           => $patient->occupation,
            'father_name'          => $patient->father_name,
            'mother_maiden_name'   => $patient->mother_name,
            'admission_date'       => $clerkAdmAt->format('Y-m-d'),
            'admission_time'       => $clerkAdmAt->format('H:i'),
            'discharge_date'       => $disAt ? $disAt->format('Y-m-d') : null,
            'discharge_time'       => $disAt ? $disAt->format('H:i') : null,
            'total_days'           => $admDays,
            'ward_service'         => $o['service'],
            'type_of_admission'    => 'Emergency',
            'payment_class'        => $payClass,
            'allergic_to'          => $o['allergies'] ?? 'NKDA',
            'alert'                => $o['alert'] ?? null,
            'philhealth_id'        => $patient->philhealth_id,
            'philhealth_type'      => $patient->philhealth_type,
            'admission_diagnosis'  => $o['admitting_diagnosis'],
            'final_diagnosis'      => $o['final_diagnosis'] ?? $o['admitting_diagnosis'],
            'disposition'          => $disAt ? 'Discharged' : 'Admitted',
            'results'              => $o['results'] ?? ($disAt ? 'Responded to treatment' : null),
            'data_furnished_by'    => $o['data_furnished_by'] ?? 'Patient / Family',
        ]);

        // ── Consent Record ─────────────────────────────────────────────────
        $useGuardian = ($patient->is_pedia ?? false) || ($o['use_guardian'] ?? false);
        $consentDate = $clerkAdmAt->format('F j, Y');

        ConsentRecord::create([
            'visit_id'           => $visit->id,
            'patient_id'         => $patient->id,
            'saved_by'           => $clerk->id,
            'active_section'     => $useGuardian ? 2 : 1,
            'patient_name'       => $useGuardian ? null : strtoupper("{$patient->first_name} {$patient->middle_name} {$patient->family_name}"),
            'doctor_name_sec1'   => $useGuardian ? null : strtoupper(ltrim($doctor->name, 'Dr. ')),
            'witness_sec1'       => $useGuardian ? null : $nurse->name,
            'signed_date_sec1'   => $useGuardian ? null : $consentDate,
            'guardian_name'      => $useGuardian ? ($patient->mother_name ?? $patient->father_name ?? 'Guardian') : null,
            'nok_sig_name'       => $useGuardian ? ($patient->mother_name ?? $patient->father_name ?? 'Guardian') : null,
            'being_the'          => $useGuardian ? 'Parent / Guardian' : null,
            'doctor_name_sec2'   => $useGuardian ? strtoupper(ltrim($doctor->name, 'Dr. ')) : null,
            'witness_sec2'       => $useGuardian ? $nurse->name : null,
            'signed_date_sec2'   => $useGuardian ? $consentDate : null,
            'relation_to_patient'=> $useGuardian ? 'Parent' : null,
        ]);

        return $visit;
    }

    /**
     * Create a simpler OPD/ER visit assessed but not admitted (no forms, no orders).
     */
    private function assessed(
        Patient $patient,
        User    $doctor,
        User    $clerk,
        array   $o
    ): Visit {
        $regAt = $o['registered_at'];

        $visit = Visit::create([
            'patient_id'          => $patient->id,
            'clerk_id'            => $clerk->id,
            'assigned_doctor_id'  => $doctor->id,
            'visit_type'          => $o['visit_type'] ?? 'OPD',
            'chief_complaint'     => $o['chief_complaint'],
            'admitting_diagnosis' => $o['diagnosis'] ?? null,
            'admitted_service'    => $o['service']   ?? null,
            'disposition'         => $o['disposition'] ?? 'Discharged',
            'payment_class'       => $o['payment_class'] ?? 'Charity',
            'status'              => $o['status'] ?? 'discharged',
            'registered_at'       => $regAt,
            'discharged_at'       => $o['discharged_at'] ?? $regAt->copy()->addHours(3),
        ]);

        MedicalHistory::create([
            'visit_id'   => $visit->id,
            'patient_id' => $patient->id,
            'doctor_id'  => $doctor->id,
            'diagnosis'  => $o['diagnosis'] ?? $o['chief_complaint'],
            'service'    => $o['service'] ?? 'General Medicine',
            'disposition'=> $o['disposition'] ?? 'Discharged',
        ]);

        return $visit;
    }

    // ══════════════════════════════════════════════════════════════════════
    //  ALL VISITS
    // ══════════════════════════════════════════════════════════════════════

    private function createAllVisits(array $p): void
    {
        // Short aliases
        [$dSan, $dRey, $dDC, $dMen, $dBau, $dCas] =
            [$this->dSantos, $this->dReyes, $this->dDelaCruz,
             $this->dMendoza, $this->dBautista, $this->dCastillo];
        [$nGon, $nTor, $nRam] = [$this->nGonzalez, $this->nTorres, $this->nRamos];
        [$cRosa, $cMark] = [$this->clerkRosa, $this->clerkMark];

        // ─ 0: Eduardo Dela Rosa — repeat HPN/DM Internal Medicine ────────

        $this->admitted($p[0], $dSan, $cRosa, $nGon, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subMonths(14)->setTime(22, 15),
            'discharged_at'       => now()->subMonths(14)->addDays(5)->setTime(9, 0),
            'chief_complaint'     => 'Severe headache and blurring of vision',
            'admitting_diagnosis' => 'Hypertensive Crisis, r/o Hypertensive Urgency',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Wife',
            'condition_on_arrival'=> 'Ambulatory, distressed',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'200/110','pr'=>95,'rr'=>20,'temp'=>37.0,'o2'=>97,'pain'=>7],
                ['bp'=>'175/100','pr'=>90,'rr'=>18,'temp'=>36.8,'o2'=>98,'pain'=>5],
                ['bp'=>'150/90', 'pr'=>82,'rr'=>17,'temp'=>36.7,'o2'=>99,'pain'=>3],
            ],
            'orders' => [
                'IVF PNSS 1L @ KVO',
                'Captopril 25mg SL now, repeat in 30 mins if BP > 180/110',
                'Amlodipine 10mg OD PO',
                'Losartan 50mg OD PO',
                'CBC, Blood Chemistry (FBS, Creatinine, SGOT, SGPT, UA) STAT',
                'ECG stat',
                'O2 via nasal cannula @ 2 LPM — titrate to SpO2 > 95%',
                'Monitor VS q1h x4h then q4h',
                'Low sodium diet',
            ],
            'fdar_notes' => [[
                'F' => 'Patient reports severe headache 8/10, blurring of vision. No chest pain. Anxious.',
                'D' => 'BP 200/110, PR 95, RR 20, Temp 37.0°C, O2 sat 97%. Alert and oriented. IV patent.',
                'A' => 'Hypertensive crisis. Risk for end-organ damage. Continuous monitoring required.',
                'R' => 'Captopril SL given. BP monitoring q1h. Dr. Santos notified. Family counseled.',
            ]],
            'final_diagnosis' => 'Hypertensive Urgency; Hypertension Stage II; Diabetes Mellitus Type 2',
        ]);

        $this->assessed($p[0], $dSan, $cRosa, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonths(7)->setTime(9, 30),
            'discharged_at'  => now()->subMonths(7)->setTime(11, 0),
            'status'         => 'discharged',
            'chief_complaint'=> 'Follow-up for Hypertension and Diabetes',
            'diagnosis'      => 'Hypertension, DM2 — Controlled on current medications',
            'service'        => 'Internal Medicine',
            'payment_class'  => 'Charity',
        ]);

        $this->admitted($p[0], $dSan, $cMark, $nTor, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subMonths(2)->setTime(3, 40),
            'discharged_at'       => now()->subMonths(2)->addDays(4)->setTime(10, 0),
            'chief_complaint'     => 'Dizziness, weakness of left side, BP 190/105',
            'admitting_diagnosis' => 'Hypertensive Urgency with Transient Ischemic Attack (TIA)',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Son',
            'condition_on_arrival'=> 'Ambulatory with support, oriented',
            'allergies'           => 'NKDA',
            'alert'               => 'Fall risk',
            'vitals'              => [
                ['bp'=>'190/105','pr'=>92,'rr'=>19,'temp'=>36.9,'o2'=>97,'pain'=>6],
                ['bp'=>'165/95', 'pr'=>84,'rr'=>18,'temp'=>36.8,'o2'=>98,'pain'=>3],
            ],
            'orders' => [
                'IVF PNSS 1L @ KVO',
                'Amlodipine 10mg OD',
                'Metformin 500mg BID with meals — hold if NPO',
                'CBC, FBS, Lipid Profile, Creatinine STAT',
                'ECG stat; Chest X-Ray AP',
                'Cranial CT scan plain — refer radiology',
                'Neurologic VS q2h',
                'Fall precautions — bed rails up at all times',
                'Low salt, low fat, diabetic diet',
            ],
            'fdar_notes' => [[
                'F' => 'Sudden onset dizziness and left arm/leg weakness 2 hours PTA. BP very high at home.',
                'D' => 'BP 190/105, PR 92, O2 sat 97%. Alert. Left grip weaker than right. No facial droop.',
                'A' => 'Hypertensive urgency with suspected TIA. High stroke risk.',
                'R' => 'Antihypertensives given. CT scan requested. Fall precautions in place.',
            ]],
            'final_diagnosis' => 'Hypertensive Urgency; TIA; Hypertension Stage II; DM2',
        ]);

        // CURRENT admission — admitted today
        $this->admitted($p[0], $dSan, $cRosa, $nGon, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subHours(6),
            'doctor_admitted_at'  => now()->subHours(5),
            'clerk_admitted_at'   => now()->subHours(4)->subMinutes(30),
            'chief_complaint'     => 'Chest pain, difficulty of breathing, BP 185/100 at home',
            'admitting_diagnosis' => 'Hypertensive Urgency with Chest Pain — r/o Acute Coronary Syndrome',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Wife and children',
            'condition_on_arrival'=> 'Ambulatory, distressed, diaphoretic',
            'allergies'           => 'NKDA',
            'alert'               => 'Cardiac monitoring; fall risk',
            'vitals'              => [
                ['bp'=>'185/100','pr'=>98,'rr'=>22,'temp'=>37.1,'o2'=>95,'pain'=>8],
                ['bp'=>'160/95', 'pr'=>90,'rr'=>20,'temp'=>37.0,'o2'=>97,'pain'=>5],
            ],
            'orders' => [
                'IVF PNSS 1L @ KVO',
                'O2 via face mask @ 6 LPM; titrate to SpO2 ≥ 95%',
                'Captopril 25mg SL now',
                'Amlodipine 10mg OD',
                'Aspirin 160mg STAT (crush and give now)',
                'ECG 12-lead STAT',
                'Troponin I STAT; repeat in 6 hours',
                'CBC, Electrolytes, BUN, Creatinine, FBS, Lipid profile STAT',
                'CXR PA',
                'Hook to cardiac monitor — continuous 12-lead',
                'NPO pending evaluation',
                'Strict bed rest, HOB 30-45°',
                'VS and SpO2 q1h',
                'Cardiology referral — Dr. Castillo',
            ],
            'fdar_notes' => [[
                'F' => 'Crushing chest pain 8/10 radiating to left shoulder, profuse sweating, shortness of breath.',
                'D' => 'BP 185/100, PR 98, RR 22, Temp 37.1°C, O2 sat 95%. Diaphoretic and distressed.',
                'A' => 'Hypertensive urgency with chest pain — cannot rule out ACS. High risk.',
                'R' => 'O2, Captopril SL, Aspirin given. ECG done. STAT labs drawn. Dr. Santos and cardiology notified.',
            ]],
        ]);

        // ─ 1: Maria Theresa Garcia — Dengue then Appendicitis ────────────

        $this->admitted($p[1], $dBau, $cRosa, $nRam, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subMonths(11)->setTime(14, 20),
            'discharged_at'       => now()->subMonths(11)->addDays(6)->setTime(10, 0),
            'chief_complaint'     => 'High grade fever 4 days, body malaise, rash on trunk',
            'admitting_diagnosis' => 'Dengue Fever with Warning Signs',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Parents',
            'condition_on_arrival'=> 'Ambulatory, febrile, weak-looking',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'100/70','pr'=>102,'rr'=>20,'temp'=>38.9,'o2'=>98,'pain'=>5],
                ['bp'=>'108/72','pr'=>95, 'rr'=>19,'temp'=>38.2,'o2'=>99,'pain'=>3],
            ],
            'orders' => [
                'IVF PNSS 1L @ 30 gtts/min; adjust per BP and hematocrit',
                'Paracetamol 500mg IV q6h PRN for T ≥ 38°C (NO aspirin)',
                'CBC with platelet q6h',
                'Dengue NS1 Ag + IgM/IgG combo STAT',
                'Strict I&O',
                'Regular diet; encourage PO fluids ≥ 2L/day',
                'Alert MD: platelet < 50,000 / BP drop / abdominal pain / sudden fever drop',
            ],
            'fdar_notes' => [[
                'F' => 'High fever 4 days unresponsive to paracetamol at home. Body aches. Rash noted on trunk.',
                'D' => 'Temp 38.9°C, BP 100/70, PR 102. Petechial rash. Positive tourniquet test.',
                'A' => 'Dengue with warning signs. Risk for plasma leakage. Close monitoring essential.',
                'R' => 'Dengue NS1 sent. CBC q6h. Fluid management per protocol. Family instructed on warning signs.',
            ]],
            'final_diagnosis' => 'Dengue Fever with Warning Signs; Thrombocytopenia',
        ]);

        // CURRENT — admitted yesterday, appendicitis
        $this->admitted($p[1], $dDC, $cMark, $nTor, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subDay()->setTime(21, 30),
            'doctor_admitted_at'  => now()->subDay()->setTime(22, 45),
            'clerk_admitted_at'   => now()->subDay()->setTime(23, 15),
            'chief_complaint'     => 'Severe RLQ abdominal pain 8 hours, nausea and vomiting',
            'admitting_diagnosis' => 'Acute Appendicitis — for appendectomy',
            'service'             => 'Surgery',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Boyfriend',
            'condition_on_arrival'=> 'Ambulatory, moderate distress, guarding',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'115/75','pr'=>104,'rr'=>21,'temp'=>38.3,'o2'=>99,'pain'=>9],
                ['bp'=>'118/76','pr'=>96, 'rr'=>19,'temp'=>38.0,'o2'=>99,'pain'=>7],
            ],
            'orders' => [
                'NPO — prepare for OR',
                'IVF PNSS 1L @ 30 gtts/min',
                'Ketorolac 30mg IV q8h PRN',
                'Cefazolin 1g IV q8h (1st dose 30 mins before OR)',
                'Metronidazole 500mg IV q8h',
                'CBC, Electrolytes, BUN, Creatinine, Blood typing STAT',
                'Abdominal ultrasound ASAP',
                'Consent for appendectomy',
            ],
            'fdar_notes' => [[
                'F' => 'Severe RLQ pain 9/10 migrating from periumbilical. Nausea, 2 vomiting episodes. Low grade fever.',
                'D' => 'Temp 38.3°C. McBurney\'s point tenderness. Rovsing\'s sign positive. Rebound tenderness.',
                'A' => 'Acute appendicitis. High perforation risk. Urgent surgical intervention.',
                'R' => 'NPO. Labs and ultrasound STAT. Surgeon notified. Consent ongoing.',
            ]],
        ]);

        // ─ 2: Liza Bautista — Private OB ─────────────────────────────────

        $this->assessed($p[2], $dMen, $cRosa, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonths(10)->setTime(10, 0),
            'discharged_at'  => now()->subMonths(10)->setTime(11, 30),
            'status'         => 'discharged',
            'chief_complaint'=> 'Prenatal check-up, 28 weeks AOG',
            'diagnosis'      => 'Pregnancy 28 weeks G2P1, normal',
            'service'        => 'OB-Gyne',
            'payment_class'  => 'Private',
        ]);

        $this->admitted($p[2], $dMen, $cRosa, $nRam, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subMonths(7)->setTime(2, 10),
            'discharged_at'       => now()->subMonths(7)->addDays(3)->setTime(9, 0),
            'chief_complaint'     => 'Active labor pains, 39 weeks AOG, membranes intact',
            'admitting_diagnosis' => 'Pregnancy Full Term Active Labor G2P1',
            'service'             => 'OB-Gyne',
            'payment_class'       => 'Private',
            'brought_by'          => 'Husband',
            'condition_on_arrival'=> 'Ambulatory, active labor',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'120/80','pr'=>88,'rr'=>20,'temp'=>36.9,'o2'=>99,'pain'=>7,'weight'=>72,'height'=>162],
            ],
            'orders' => [
                'IVF D5LR 1L @ 30 gtts/min',
                'FHT monitoring q30min during active labor',
                'Oxytocin 10IU in D5LR 500mL IVTT — augmentation per protocol',
                'Paracetamol 500mg IV q6h PRN',
                'Left lateral decubitus position',
                'NPO except ice chips',
                'Prep delivery room',
            ],
            'fdar_notes' => [[
                'F' => 'Regular contractions q4-5 min, 45 sec duration. Membranes intact. AOG 39 weeks.',
                'D' => 'BP 120/80, PR 88, FHT 144 bpm. IE: 4cm dilated, 50% effaced, -1 station.',
                'A' => 'Active labor, progressing. Fetal status stable.',
                'R' => 'IV started. FHT monitoring. Labor progress tracked. Delivery room being prepared.',
            ]],
            'final_diagnosis' => 'Delivery Full Term NSD G2P2 (2002). Live born female 3.2 kg.',
        ]);

        $this->assessed($p[2], $dMen, $cMark, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonths(3)->setTime(9, 0),
            'discharged_at'  => now()->subMonths(3)->setTime(10, 0),
            'status'         => 'discharged',
            'chief_complaint'=> 'Post-partum check-up, 6 weeks after NSD',
            'diagnosis'      => 'Post-partum 6 weeks — normal recovery, breastfeeding',
            'service'        => 'OB-Gyne',
            'payment_class'  => 'Private',
        ]);

        // ─ 3: Andrei Santos — Pedia bronchiolitis → febrile seizure ──────

        $this->admitted($p[3], $dRey, $cRosa, $nGon, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subMonths(8)->setTime(19, 30),
            'discharged_at'       => now()->subMonths(8)->addDays(4)->setTime(10, 0),
            'chief_complaint'     => '3-year-old with fever, cough, difficulty breathing for 2 days',
            'admitting_diagnosis' => 'Bronchiolitis, Moderate Severity',
            'service'             => 'Pediatrics',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Mother',
            'condition_on_arrival'=> 'Carried by mother, moderate respiratory distress',
            'allergies'           => 'NKDA',
            'use_guardian'        => true,
            'vitals'              => [
                ['bp'=>'90/60','pr'=>118,'rr'=>42,'temp'=>38.5,'o2'=>93,'pain'=>5,'weight'=>12,'height'=>88],
                ['bp'=>'92/62','pr'=>108,'rr'=>36,'temp'=>37.8,'o2'=>96,'pain'=>3],
            ],
            'orders' => [
                'O2 via nasal prongs @ 1-2 LPM; keep SpO2 ≥ 95%',
                'IVF D5 0.3% NaCl @ 44 mL/hr',
                'Salbutamol nebu q4h',
                'Paracetamol drops 120mg/5ml — 1.2mL q6h PRN T ≥ 38°C',
                'Chest physiotherapy TID',
                'Strict I&O; hold oral feeds if RR > 60/min',
                'CXR portable',
            ],
            'fdar_notes' => [[
                'F' => 'Mother: child febrile, poor feeding, no wet diapers 4 hrs. Intercostal retractions.',
                'D' => 'Temp 38.5°C, PR 118, RR 42, O2 93%. Bilateral wheeze. Subcostal retractions.',
                'A' => 'Moderate bronchiolitis with respiratory distress.',
                'R' => 'O2 started. Nebu given. IV inserted. Mother counseled on precautions.',
            ]],
            'final_diagnosis' => 'Bronchiolitis moderate; Community-Acquired Pneumonia',
        ]);

        // CURRENT — admitted 2 days ago, febrile seizure
        $this->admitted($p[3], $dRey, $cMark, $nGon, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subDays(2)->setTime(8, 15),
            'doctor_admitted_at'  => now()->subDays(2)->setTime(9, 0),
            'clerk_admitted_at'   => now()->subDays(2)->setTime(9, 30),
            'chief_complaint'     => '3-year-old, generalized tonic-clonic seizure 2 minutes, high fever',
            'admitting_diagnosis' => 'Febrile Seizure Simple; Acute Tonsillitis as trigger',
            'service'             => 'Pediatrics',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Both parents',
            'condition_on_arrival'=> 'Carried by father, post-ictal, febrile',
            'allergies'           => 'NKDA',
            'use_guardian'        => true,
            'vitals'              => [
                ['bp'=>'88/58','pr'=>128,'rr'=>34,'temp'=>39.5,'o2'=>97,'weight'=>12.5,'height'=>90],
                ['bp'=>'90/60','pr'=>110,'rr'=>28,'temp'=>38.2,'o2'=>98],
            ],
            'orders' => [
                'O2 via nasal prongs @ 1 LPM',
                'IVF D5 0.3% NaCl @ 44 mL/hr',
                'Paracetamol 120mg/5ml — 1.5mL q4h PRN T ≥ 38°C',
                'Tepid sponge bath PRN T ≥ 38.5°C',
                'Diazepam 0.5mg/kg IV PRN recurrence — keep at bedside',
                'Seizure precautions: padded side rails, suction at bedside',
                'CBC, blood culture, throat culture STAT',
            ],
            'fdar_notes' => [[
                'F' => 'GTC seizure 2 min at home. Fever 39°C for 1 day. Post-ictal on arrival.',
                'D' => 'Temp 39.5°C, PR 128. Post-ictal, drowsy. Throat: Grade 3 tonsils, hyperemic.',
                'A' => 'Febrile seizure simple. Tonsillitis as fever source. Recurrence possible.',
                'R' => 'Fever controlled. Diazepam at bedside. Seizure precautions in place. Mother taught first aid.',
            ]],
        ]);

        // ─ 4: Conchita Villanueva — Elderly DM/HPN ───────────────────────

        $this->assessed($p[4], $dSan, $cRosa, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subYear()->setTime(9, 0),
            'discharged_at'  => now()->subYear()->setTime(10, 30),
            'status'         => 'discharged',
            'chief_complaint'=> 'Routine check-up, Hypertension and Diabetes',
            'diagnosis'      => 'Hypertension, DM2 — FBS 210, adjust medications',
            'service'        => 'Internal Medicine',
            'payment_class'  => 'Charity',
        ]);

        $this->admitted($p[4], $dSan, $cRosa, $nRam, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subMonths(5)->setTime(16, 0),
            'discharged_at'       => now()->subMonths(5)->addDays(5)->setTime(10, 0),
            'chief_complaint'     => 'Weakness, polyuria, polydipsia, blood sugar 380 at home',
            'admitting_diagnosis' => 'Uncontrolled DM2 with Hyperglycemic Hyperosmolar State (HHS)',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Daughter',
            'condition_on_arrival'=> 'Ambulatory with assistance, confused',
            'allergies'           => 'Penicillin — rash',
            'alert'               => 'Penicillin allergy. Fall risk. Elderly.',
            'vitals'              => [
                ['bp'=>'155/90','pr'=>98,'rr'=>22,'temp'=>37.5,'o2'=>96,'pain'=>3,'weight'=>58,'height'=>148],
                ['bp'=>'145/85','pr'=>88,'rr'=>20,'temp'=>37.2,'o2'=>97,'pain'=>2],
            ],
            'orders' => [
                'IVF PNSS 1L @ 200 mL/hr first hour then reassess',
                'Regular Insulin 10 units IV bolus then 0.1 units/kg/hr continuous',
                'FBS, electrolytes, BUN, Creatinine STAT',
                'CBC, ECG stat',
                'NO Penicillin-based antibiotics (allergy)',
                'CBG q1h — alert if < 150 or > 400',
                'Strict I&O',
                'Strict fall precautions',
                'Diabetic diet — dietitian consult',
            ],
            'fdar_notes' => [[
                'F' => 'Elderly patient. Blood sugar 380 at home. Not taking insulin for 2 days (ran out).',
                'D' => 'BP 155/90, PR 98, CBG 385. Dehydrated — dry mucous membranes. Confused.',
                'A' => 'Hyperglycemic crisis, insulin non-compliance. Dehydration. Fall risk.',
                'R' => 'Fluids started. Insulin drip initiated. Electrolytes monitored. Family educated on compliance.',
            ]],
            'final_diagnosis' => 'Uncontrolled DM2; HHS; Hypertension',
        ]);

        $this->assessed($p[4], $dSan, $cMark, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subWeeks(3)->setTime(9, 0),
            'discharged_at'  => now()->subWeeks(3)->setTime(10, 0),
            'status'         => 'discharged',
            'chief_complaint'=> 'Follow-up after hospitalization, insulin management',
            'diagnosis'      => 'DM2, Hypertension — improving, on insulin',
            'service'        => 'Internal Medicine',
            'payment_class'  => 'Charity',
        ]);

        // ─ 5: Ramon Mendoza — Private surgical hernia ────────────────────

        $this->assessed($p[5], $dDC, $cRosa, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonths(3)->setTime(14, 0),
            'discharged_at'  => now()->subMonths(3)->setTime(15, 0),
            'status'         => 'discharged',
            'chief_complaint'=> 'Recurring right inguinal bulge, reducible hernia',
            'diagnosis'      => 'Right Inguinal Hernia Direct — for elective repair',
            'service'        => 'Surgery',
            'payment_class'  => 'Private',
        ]);

        $this->admitted($p[5], $dDC, $cMark, $nTor, [
            'visit_type'          => 'OPD',
            'registered_at'       => now()->subMonths(2)->setTime(7, 0),
            'doctor_admitted_at'  => now()->subMonths(2)->setTime(7, 30),
            'clerk_admitted_at'   => now()->subMonths(2)->setTime(7, 45),
            'discharged_at'       => now()->subMonths(2)->addDays(2)->setTime(10, 0),
            'chief_complaint'     => 'Elective admission for right inguinal herniorrhaphy',
            'admitting_diagnosis' => 'Right Inguinal Hernia — Lichtenstein Hernioplasty',
            'service'             => 'Surgery',
            'payment_class'       => 'Private',
            'brought_by'          => 'Wife',
            'condition_on_arrival'=> 'Ambulatory, stable, elective',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'125/80','pr'=>76,'rr'=>16,'temp'=>36.6,'o2'=>99,'pain'=>1,'weight'=>78,'height'=>172],
                ['bp'=>'128/82','pr'=>78,'rr'=>17,'temp'=>36.8,'o2'=>99,'pain'=>3],
            ],
            'orders' => [
                'NPO after midnight',
                'IVF D5LR 1L @ 30 gtts/min post-op',
                'Cefazolin 1g IV 30 mins before OR incision',
                'Ketorolac 30mg IV q8h x2 days',
                'Ambulate 6 hrs post-op with assistance',
                'Soft diet, advance as tolerated',
            ],
            'fdar_notes' => [[
                'F' => 'Mild scrotal swelling, pain 3/10. Tolerating liquids. Flatus noted.',
                'D' => 'BP 128/82. Wound right groin: clean, dry, intact. Dressing dry.',
                'A' => 'Post-op day 1 hernioplasty — recovering well.',
                'R' => 'Ambulated with escort. Pain meds given. Wound care done.',
            ]],
            'final_diagnosis' => 'Right Inguinal Hernia — Lichtenstein Hernioplasty, uncomplicated',
        ]);

        $this->assessed($p[5], $dDC, $cRosa, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonth()->setTime(9, 30),
            'discharged_at'  => now()->subMonth()->setTime(10, 0),
            'status'         => 'discharged',
            'chief_complaint'=> 'Post-op wound check 1 month after hernioplasty',
            'diagnosis'      => 'Post hernioplasty — wound healed, no recurrence',
            'service'        => 'Surgery',
            'payment_class'  => 'Private',
        ]);

        // ─ 6: Rolando Pagaduan — TB + Hemoptysis (current) ───────────────

        $this->assessed($p[6], $dSan, $cRosa, [
            'visit_type'      => 'ER',
            'registered_at'   => now()->subMonths(9)->setTime(11, 0),
            'discharged_at'   => now()->subMonths(9)->setTime(14, 0),
            'status'          => 'discharged',
            'chief_complaint' => 'Productive cough 3 weeks, night sweats, weight loss',
            'diagnosis'       => 'Pulmonary Tuberculosis Category I — referred for DOTS',
            'service'         => 'Internal Medicine',
            'payment_class'   => 'Charity',
        ]);

        $this->admitted($p[6], $dSan, $cMark, $nRam, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subDays(3)->setTime(9, 30),
            'doctor_admitted_at'  => now()->subDays(3)->setTime(10, 30),
            'clerk_admitted_at'   => now()->subDays(3)->setTime(11, 0),
            'chief_complaint'     => 'Hemoptysis ~100mL bright red blood over 2 hours',
            'admitting_diagnosis' => 'Pulmonary Tuberculosis Retreatment; Moderate Hemoptysis',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Wife',
            'condition_on_arrival'=> 'Ambulatory, anxious, bloody sputum on tissue',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'100/65','pr'=>108,'rr'=>24,'temp'=>37.8,'o2'=>92,'pain'=>4,'weight'=>48,'height'=>162],
                ['bp'=>'105/70','pr'=>98, 'rr'=>22,'temp'=>37.5,'o2'=>94,'pain'=>3],
            ],
            'orders' => [
                'O2 via nasal cannula @ 2-3 LPM; keep SpO2 ≥ 95%',
                'IVF PNSS 1L @ 30 gtts/min',
                'Tranexamic acid 500mg IV q8h',
                'Codeine 30mg PO q8h PRN cough suppression',
                'CBC, PT-INR, APTT STAT',
                'Sputum AFB x3 consecutive days',
                'CXR PA now',
                'Blood typing and crossmatching — hold 2 units PRBC',
                'Strict bed rest — HOB 30°',
                'Respiratory isolation — N95 mask for all staff',
            ],
            'fdar_notes' => [[
                'F' => 'Half a cup bright red blood this morning. PTB 9 months ago, stopped treatment.',
                'D' => 'BP 100/65, PR 108, O2 92%. Bloody sputum. Dullness left upper lobe.',
                'A' => 'Moderate hemoptysis in PTB retreatment case. Hemodynamically borderline.',
                'R' => 'O2 started. Tranexamic acid given. N95 mask on. Isolation. Blood bank alerted.',
            ]],
        ]);

        // ─ 7: Felicidad Castillo — CHF current ───────────────────────────

        $this->assessed($p[7], $dCas, $cRosa, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonths(6)->setTime(8, 30),
            'discharged_at'  => now()->subMonths(6)->setTime(9, 30),
            'status'         => 'discharged',
            'chief_complaint'=> 'Palpitations, easy fatigability, bilateral leg swelling',
            'diagnosis'      => 'Congestive Heart Failure NYHA Class II; Atrial Fibrillation',
            'service'        => 'Cardiology',
            'payment_class'  => 'Charity',
        ]);

        $this->admitted($p[7], $dCas, $cRosa, $nTor, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subDay()->setTime(3, 0),
            'doctor_admitted_at'  => now()->subDay()->setTime(4, 0),
            'clerk_admitted_at'   => now()->subDay()->setTime(4, 30),
            'chief_complaint'     => 'Severe dyspnea, unable to lie flat, bilateral leg swelling worsened',
            'admitting_diagnosis' => 'Acute Decompensated CHF; Atrial Fibrillation with Rapid Ventricular Response',
            'service'             => 'Cardiology',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Husband and son',
            'condition_on_arrival'=> 'Wheelchair, severe respiratory distress, tripod position',
            'allergies'           => 'NKDA',
            'alert'               => 'Fluid restriction 1L/24h. Cardiac monitoring.',
            'vitals'              => [
                ['bp'=>'165/95','pr'=>130,'rr'=>30,'temp'=>36.8,'o2'=>88,'pain'=>6,'weight'=>70,'height'=>152],
                ['bp'=>'150/90','pr'=>110,'rr'=>26,'temp'=>36.7,'o2'=>93,'pain'=>4],
            ],
            'orders' => [
                'O2 via NRB mask @ 10-15 LPM',
                'Semi-Fowler\'s ≥ 60°',
                'Furosemide 40mg IV STAT then 20mg IV q12h',
                'Digoxin 0.25mg IV STAT (rate control)',
                'Bisoprolol 2.5mg PO OD (hold if HR < 60)',
                'Strict fluid restriction 1L/24h',
                'Low sodium diet',
                'ECG 12-lead stat; Echocardiogram ASAP',
                'CXR portable',
                'BNP, Troponin, electrolytes, BUN, Creatinine STAT',
                'Foley catheter — hourly I&O',
                'Weigh daily',
                'Cardiac monitor — continuous',
            ],
            'fdar_notes' => [[
                'F' => 'Cannot lie flat. Woken by dyspnea. Legs more swollen. Not taking diuretics for a week.',
                'D' => 'BP 165/95, PR 130 irregular, RR 30, O2 88%. Tripod position. JVD. Bilateral crackles mid-lung. Pitting edema +3.',
                'A' => 'Acute decompensated CHF, hypoxia. AFib RVR contributing to decompensation.',
                'R' => 'O2 via NRB. Furosemide IV. Foley inserted — 800mL output. Dr. Castillo notified.',
            ]],
        ]);

        // ─ 8: Kevin Manalo — Dengue (current) ────────────────────────────

        $this->assessed($p[8], $dBau, $cMark, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonths(4)->setTime(10, 0),
            'discharged_at'  => now()->subMonths(4)->setTime(11, 0),
            'status'         => 'discharged',
            'chief_complaint'=> 'Fever and cough 3 days, throat pain',
            'diagnosis'      => 'Community-Acquired Pharyngitis — Amoxicillin given',
            'service'        => 'Internal Medicine',
            'payment_class'  => 'Charity',
        ]);

        $this->admitted($p[8], $dBau, $cRosa, $nGon, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subHours(8),
            'doctor_admitted_at'  => now()->subHours(7),
            'clerk_admitted_at'   => now()->subHours(6)->subMinutes(30),
            'chief_complaint'     => 'High grade fever 39.5°C 3 days, severe body pain, rash, gum bleeding',
            'admitting_diagnosis' => 'Dengue Hemorrhagic Fever Grade II, Day 3',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Mother',
            'condition_on_arrival'=> 'Ambulatory, febrile, weak, petechial rash',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'105/70','pr'=>106,'rr'=>21,'temp'=>39.5,'o2'=>98,'pain'=>6,'weight'=>58,'height'=>168],
                ['bp'=>'108/72','pr'=>98, 'rr'=>19,'temp'=>38.8,'o2'=>99,'pain'=>4],
            ],
            'orders' => [
                'IVF PNSS 1L @ 30 gtts/min; reassess q4h',
                'Paracetamol 500mg IV q6h PRN T ≥ 38.5°C (NO aspirin, NO NSAIDs)',
                'Dengue NS1 + IgM/IgG combo STAT',
                'CBC with platelet q6h',
                'Alert MD: platelet < 50,000; BP drops; sudden fever cessation; abdominal pain',
                'Regular diet; encourage oral fluids',
                'No IM injections',
                'Daily weight',
            ],
            'fdar_notes' => [[
                'F' => 'High fever 3 days unresponsive to paracetamol. Severe myalgia, arthralgia, rash on trunk. Gum bleeding brushing teeth.',
                'D' => 'Temp 39.5°C, BP 105/70, PR 106. Petechial rash. Positive tourniquet test.',
                'A' => 'DHF Grade II. Critical phase next 24-48 hrs — risk of plasma leakage.',
                'R' => 'IV started. NS1 sent. CBC q6h. Patient and mother extensively counseled on warning signs.',
            ]],
        ]);

        // ─ 9: Stephanie Aquino — Private OB active labor (current) ───────

        $this->admitted($p[9], $dMen, $cMark, $nRam, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subHours(5),
            'doctor_admitted_at'  => now()->subHours(4)->subMinutes(30),
            'clerk_admitted_at'   => now()->subHours(4),
            'chief_complaint'     => 'Active labor pains 38 weeks AOG G1P0',
            'admitting_diagnosis' => 'Pregnancy 38 wks AOG Active Labor G1P0 Cephalic',
            'service'             => 'OB-Gyne',
            'payment_class'       => 'Private',
            'brought_by'          => 'Husband',
            'condition_on_arrival'=> 'Ambulatory, active labor, contracting',
            'allergies'           => 'Sulfa drugs',
            'alert'               => 'Sulfa allergy. G1P0 — first delivery.',
            'vitals'              => [
                ['bp'=>'122/78','pr'=>84,'rr'=>18,'temp'=>36.7,'o2'=>99,'pain'=>6,'weight'=>68,'height'=>158],
            ],
            'orders' => [
                'IVF D5LR 1L @ 30 gtts/min',
                'Oxytocin 10IU in D5LR 500mL — augmentation per protocol',
                'FHT monitoring q30min; continuous EFM',
                'IE q4h or PRN',
                'Left lateral decubitus position',
                'Paracetamol 1g IV q6h PRN',
                'NO sulfa-based medications',
                'NPO except ice chips',
                'Ready DR set and NB resuscitation',
            ],
            'fdar_notes' => [[
                'F' => 'G1P0. Contractions q3-4 min, 45-50 sec, moderate-strong. Membranes intact.',
                'D' => 'BP 122/78, PR 84, FHT 142. IE: 6cm dilated, 80% effaced, station 0.',
                'A' => 'Active labor, good progress. Primigravida.',
                'R' => 'IV and FHT monitoring. DR being prepared. Dr. Mendoza notified.',
            ]],
        ]);

        // ─ 10: Maricel Evangelista — Asthma (current) ────────────────────

        $this->admitted($p[10], $dBau, $cRosa, $nGon, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subYear()->setTime(20, 0),
            'discharged_at'       => now()->subYear()->addDays(3)->setTime(9, 0),
            'chief_complaint'     => 'Severe difficulty breathing, wheeze, unable to complete sentences',
            'admitting_diagnosis' => 'Bronchial Asthma Severe Persistent Acute Exacerbation',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Husband',
            'condition_on_arrival'=> 'Ambulatory with difficulty, severe distress, audible wheeze',
            'allergies'           => 'Aspirin, NSAIDs — bronchoconstriction',
            'alert'               => 'Aspirin/NSAID allergy — do NOT give.',
            'vitals'              => [
                ['bp'=>'130/85','pr'=>112,'rr'=>32,'temp'=>37.1,'o2'=>88,'pain'=>7],
                ['bp'=>'125/80','pr'=>98, 'rr'=>24,'temp'=>37.0,'o2'=>93,'pain'=>4],
            ],
            'orders' => [
                'O2 via NRB mask @ 8-10 LPM',
                'Salbutamol 2.5mg + Ipratropium 0.5mg nebu q20min x3 then reassess',
                'Methylprednisolone 40mg IV q8h',
                'NO Aspirin, NO NSAIDs, NO beta-blockers',
                'CXR PA; ABG after nebu',
                'CBC, electrolytes STAT',
            ],
            'fdar_notes' => [[
                'F' => 'Severely dyspneic. 3 puffs MDI at home, no relief. Known asthma x5 years. Triggered by dust.',
                'D' => 'O2 88% RA. RR 32. Bilateral expiratory wheeze. All accessory muscles used.',
                'A' => 'Severe asthma exacerbation with hypoxia. Risk for respiratory failure.',
                'R' => 'O2 and back-to-back nebu started. Steroids IV. High Fowler\'s. ICU on standby.',
            ]],
            'final_diagnosis' => 'Bronchial Asthma Severe Persistent Exacerbation — improved',
        ]);

        $this->assessed($p[10], $dBau, $cMark, [
            'visit_type'      => 'ER',
            'registered_at'   => now()->subMonths(3)->setTime(22, 30),
            'discharged_at'   => now()->subMonths(3)->addHours(4),
            'status'          => 'discharged',
            'chief_complaint' => 'Difficulty breathing, wheeze — moderate asthma attack',
            'diagnosis'       => 'Bronchial Asthma Moderate Exacerbation — responded to nebu',
            'service'         => 'Internal Medicine',
            'payment_class'   => 'Charity',
        ]);

        $this->admitted($p[10], $dBau, $cRosa, $nGon, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subDay()->setTime(15, 0),
            'doctor_admitted_at'  => now()->subDay()->setTime(15, 45),
            'clerk_admitted_at'   => now()->subDay()->setTime(16, 15),
            'chief_complaint'     => 'Progressive difficulty breathing 2 hours, moderate wheeze',
            'admitting_diagnosis' => 'Bronchial Asthma Moderate-Severe Exacerbation; Respiratory infection as trigger',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Husband',
            'condition_on_arrival'=> 'Ambulatory, moderate respiratory distress',
            'allergies'           => 'Aspirin, NSAIDs',
            'vitals'              => [
                ['bp'=>'128/82','pr'=>102,'rr'=>26,'temp'=>37.4,'o2'=>91,'pain'=>5],
                ['bp'=>'124/80','pr'=>94, 'rr'=>22,'temp'=>37.2,'o2'=>95,'pain'=>3],
            ],
            'orders' => [
                'O2 via Venturi mask 28% FiO2; keep SpO2 ≥ 93%',
                'Salbutamol + Ipratropium nebu q4h',
                'Methylprednisolone 40mg IV q8h then switch to oral prednisone',
                'Azithromycin 500mg IV OD',
                'NO Aspirin, NO NSAIDs',
                'CBC, CRP, CXR STAT',
            ],
            'fdar_notes' => [[
                'F' => 'Worsening breathing 2 hrs. MDI 5x at home, partial relief. Yellow sputum, low grade fever.',
                'D' => 'O2 91% RA. RR 26. Bilateral wheeze moderate. Short phrases only.',
                'A' => 'Moderate-severe asthma exacerbation + infection. Partial bronchodilator response.',
                'R' => 'O2, nebu given. Steroids and azithromycin started. Monitoring closely.',
            ]],
        ]);

        // ─ 11: Fernando Pascual — Polytrauma (current) ────────────────────

        $this->admitted($p[11], $dDC, $cMark, $nTor, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subHours(4),
            'doctor_admitted_at'  => now()->subHours(3),
            'clerk_admitted_at'   => now()->subHours(2)->subMinutes(30),
            'chief_complaint'     => 'Motorcycle accident — right leg deformity, forearm laceration, head injury',
            'admitting_diagnosis' => 'Closed Fracture Right Femur Midshaft; Laceration Right Forearm; Head Injury Mild',
            'service'             => 'Surgery',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Bystanders and ambulance',
            'condition_on_arrival'=> 'Ambulance stretcher, conscious, confused, right leg splinted',
            'allergies'           => 'NKDA',
            'alert'               => 'Trauma. Cervical spine precautions. Head injury — neuro monitoring.',
            'vitals'              => [
                ['bp'=>'108/72','pr'=>118,'rr'=>22,'temp'=>36.5,'o2'=>96,'pain'=>9,'weight'=>70,'height'=>168],
                ['bp'=>'115/75','pr'=>104,'rr'=>20,'temp'=>36.6,'o2'=>97,'pain'=>7],
            ],
            'orders' => [
                'O2 via nasal cannula @ 2 LPM',
                'IVF PNSS 1L @ 30 gtts/min — two large bore IV',
                'Cervical spine precautions until imaging clearance',
                'Morphine 3mg IV q4h PRN',
                'Ketorolac 30mg IV q8h',
                'Cefazolin 1g IV q8h (open wound prophylaxis)',
                'Tetanus Toxoid 0.5mL IM STAT',
                'Skull X-ray AP/Lateral; CXR; Right femur X-ray STAT',
                'Cranial CT scan plain STAT',
                'GCS q1h',
                'Foley catheter',
                'NPO — possible OR',
            ],
            'fdar_notes' => [[
                'F' => 'Motorcycle collision. No helmet. Conscious at scene, disoriented. Right leg deformity.',
                'D' => 'BP 108/72, PR 118, GCS 13. Right femur deformity, distal pulse intact. 8cm forearm laceration.',
                'A' => 'Polytrauma: femur fracture, laceration, head injury. Borderline hemodynamics.',
                'R' => 'Two IVs. Fluid bolus — BP improved. Cervical collar. Wound dressed. OR team alerted.',
            ]],
        ]);

        // ─ 12: Sofia Torres — Pedia AGE (current) ────────────────────────

        $this->assessed($p[12], $dRey, $cRosa, [
            'visit_type'      => 'ER',
            'registered_at'   => now()->subMonths(5)->setTime(20, 0),
            'discharged_at'   => now()->subMonths(5)->addHours(3),
            'status'          => 'discharged',
            'chief_complaint' => '1.5-year-old with fever 38.5°C and runny nose',
            'diagnosis'       => 'Acute Nasopharyngitis — paracetamol given',
            'service'         => 'Pediatrics',
            'payment_class'   => 'Charity',
        ]);

        $this->admitted($p[12], $dRey, $cMark, $nGon, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subHours(6),
            'doctor_admitted_at'  => now()->subHours(5),
            'clerk_admitted_at'   => now()->subHours(4)->subMinutes(30),
            'chief_complaint'     => '2-year-old, vomiting 8x and watery diarrhea 10x for 12 hours',
            'admitting_diagnosis' => 'Acute Gastroenteritis; Moderate Dehydration',
            'service'             => 'Pediatrics',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Mother',
            'condition_on_arrival'=> 'Carried by mother, lethargic, dry lips, sunken eyes',
            'allergies'           => 'NKDA',
            'use_guardian'        => true,
            'vitals'              => [
                ['bp'=>'88/55','pr'=>132,'rr'=>32,'temp'=>38.2,'o2'=>98,'pain'=>6,'weight'=>10.5,'height'=>82],
                ['bp'=>'90/58','pr'=>118,'rr'=>28,'temp'=>37.8,'o2'=>99,'pain'=>3],
            ],
            'orders' => [
                'IVF D5 0.3% NaCl @ 70 mL/hr (100 mL/kg rehydration)',
                'Zinc sulfate 10mg OD x14 days',
                'ORS 10mL/kg per loose stool',
                'Paracetamol drops 120mg/5ml — 1mL q6h PRN T ≥ 38°C',
                'BRAT diet, advance gradually',
                'Stool GS/CS, stool OP',
                'CBC, electrolytes STAT',
                'Strict I&O — measure wet diapers',
            ],
            'fdar_notes' => [[
                'F' => '10 loose stools, 8 vomiting episodes. No wet diaper 5 hrs. Decreased activity.',
                'D' => 'Temp 38.2°C, PR 132. Lethargic. Sunken eyes. Dry mucous membranes. Capillary refill 2.5 sec.',
                'A' => 'AGE with moderate dehydration. Prompt IV rehydration needed.',
                'R' => 'IV rehydration started. Antiemetic given. Zinc started. Mother taught ORS prep.',
            ]],
        ]);

        // ─ 13: Jorge Francisco — Recurrent CAP (current) ─────────────────

        $this->admitted($p[13], $dSan, $cRosa, $nTor, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subYear()->setTime(10, 0),
            'discharged_at'       => now()->subYear()->addDays(7)->setTime(10, 0),
            'chief_complaint'     => 'Fever, productive cough, chest pain on breathing for 4 days',
            'admitting_diagnosis' => 'Community-Acquired Pneumonia Moderate Risk; Hypertension',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Private',
            'brought_by'          => 'Wife',
            'condition_on_arrival'=> 'Ambulatory, febrile, dyspneic',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'145/90','pr'=>96,'rr'=>24,'temp'=>38.8,'o2'=>93,'pain'=>5,'weight'=>68,'height'=>165],
                ['bp'=>'138/88','pr'=>88,'rr'=>20,'temp'=>38.1,'o2'=>95,'pain'=>3],
            ],
            'orders' => [
                'O2 via nasal cannula @ 2-3 LPM',
                'IVF PNSS 1L @ 20 gtts/min',
                'Ceftriaxone 1g IV q12h',
                'Azithromycin 500mg IV OD',
                'Paracetamol 1g IV q6h PRN T ≥ 38.5°C',
                'CXR PA; blood culture x2 STAT',
                'Amlodipine 10mg OD (continue home meds)',
            ],
            'fdar_notes' => [[
                'F' => 'Productive cough 4 days, green sputum, right-sided pleuritic pain. Self-medicated Amoxicillin.',
                'D' => 'Temp 38.8°C, O2 93%. Dullness right lower lobe. Bronchial breath sounds right base.',
                'A' => 'CAP moderate severity PSI class III.',
                'R' => 'Blood cultures before antibiotics. IV antibiotics started.',
            ]],
            'final_diagnosis' => 'CAP Right Lower Lobe; Hypertension',
        ]);

        $this->admitted($p[13], $dSan, $cMark, $nRam, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subMonths(4)->setTime(8, 30),
            'discharged_at'       => now()->subMonths(4)->addDays(6)->setTime(10, 0),
            'chief_complaint'     => 'Recurrent cough with fever, chills, yellowish sputum 5 days',
            'admitting_diagnosis' => 'Community-Acquired Pneumonia Moderate-Severe Risk; HTN; DM2',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Private',
            'brought_by'          => 'Son',
            'condition_on_arrival'=> 'Ambulatory with walking stick, febrile',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'150/95','pr'=>100,'rr'=>26,'temp'=>39.1,'o2'=>91,'pain'=>4,'weight'=>65,'height'=>165],
            ],
            'orders' => [
                'O2 via nasal cannula @ 3 LPM',
                'Ceftriaxone 1g IV q12h; Azithromycin 500mg IV OD',
                'Metformin HOLD; Insulin sliding scale if glucose > 200',
                'Blood culture x2, CBC, CXR STAT',
            ],
            'final_diagnosis' => 'Recurrent CAP Moderate-Severe; HTN; DM2',
        ]);

        // CURRENT — third CAP
        $this->admitted($p[13], $dSan, $cRosa, $nTor, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subHours(7),
            'doctor_admitted_at'  => now()->subHours(6),
            'clerk_admitted_at'   => now()->subHours(5)->subMinutes(30),
            'chief_complaint'     => 'Third pneumonia episode this year — fever, cough, shortness of breath',
            'admitting_diagnosis' => 'Recurrent CAP Severe Risk; Possible Immunodeficiency or Structural Lung Disease',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Private',
            'brought_by'          => 'Wife',
            'condition_on_arrival'=> 'Ambulatory with assistance, febrile, dyspneic',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'148/92','pr'=>105,'rr'=>28,'temp'=>39.4,'o2'=>90,'pain'=>5,'weight'=>62,'height'=>165],
                ['bp'=>'142/88','pr'=>96, 'rr'=>24,'temp'=>38.8,'o2'=>93,'pain'=>3],
            ],
            'orders' => [
                'O2 via nasal cannula @ 3-4 LPM',
                'Piperacillin-Tazobactam 4.5g IV q8h',
                'Levofloxacin 750mg IV OD',
                'Paracetamol 1g IV q6h PRN',
                'CBC, blood culture x2 STAT; sputum GS/CS, AFB x3',
                'CT chest with contrast',
                'HIV screening',
                'Pulmonology referral',
            ],
            'fdar_notes' => [[
                'F' => 'Third admission for pneumonia this year. Patient and wife very worried. 3 kg lost.',
                'D' => 'O2 90% RA. RR 28, Temp 39.4°C. Bilateral crackles. Wasted appearance.',
                'A' => 'Recurrent severe CAP. Suspect immunocompromised state. Broad coverage needed.',
                'R' => 'Broad-spectrum antibiotics. CT chest. HIV screening. Pulmonology consult. Family counseled.',
            ]],
        ]);

        // ─ 14: Natividad Reyes — Pyelonephritis ──────────────────────────

        $this->admitted($p[14], $dSan, $cRosa, $nTor, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subMonths(3)->setTime(16, 0),
            'discharged_at'       => now()->subMonths(3)->addDays(3)->setTime(9, 0),
            'chief_complaint'     => 'Fever with chills, flank pain, painful urination for 3 days',
            'admitting_diagnosis' => 'Acute Pyelonephritis Uncomplicated',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Husband',
            'condition_on_arrival'=> 'Ambulatory, febrile, uncomfortable',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'120/80','pr'=>102,'rr'=>22,'temp'=>39.2,'o2'=>98,'pain'=>7],
                ['bp'=>'118/78','pr'=>92, 'rr'=>19,'temp'=>38.4,'o2'=>99,'pain'=>4],
            ],
            'orders' => [
                'IVF PNSS 1L @ 30 gtts/min',
                'Ceftriaxone 1g IV q12h',
                'Paracetamol 500mg IV q6h PRN',
                'Urinalysis with C&S STAT',
                'BUN, Creatinine STAT',
                'Renal ultrasound (r/o abscess or obstruction)',
            ],
            'fdar_notes' => [[
                'F' => 'Burning urination and frequency for 5 days. Right flank pain and fever 3 days. Cotrimoxazole not effective.',
                'D' => 'Temp 39.2°C. Right CVA tenderness +. UA: WBC > 100, bacteria 3+, nitrite positive.',
                'A' => 'Acute pyelonephritis — inadequate initial antibiotic (likely resistance).',
                'R' => 'IV Ceftriaxone started. Ultrasound ordered. Adequate hydration encouraged.',
            ]],
            'final_diagnosis' => 'Acute Pyelonephritis uncomplicated',
        ]);

        $this->assessed($p[14], $dSan, $cMark, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonths(3)->addWeek()->setTime(9, 0),
            'discharged_at'  => now()->subMonths(3)->addWeek()->setTime(10, 0),
            'status'         => 'discharged',
            'chief_complaint'=> 'Follow-up after pyelonephritis, repeat urine culture',
            'diagnosis'      => 'Pyelonephritis resolving — culture negative, good response',
            'service'        => 'Internal Medicine',
            'payment_class'  => 'Charity',
        ]);

        // ─ 15: Dante Lopez — Stroke (current) ────────────────────────────

        $this->assessed($p[15], $dSan, $cRosa, [
            'visit_type'      => 'ER',
            'registered_at'   => now()->subMonths(6)->setTime(14, 0),
            'discharged_at'   => now()->subMonths(6)->addHours(3),
            'status'          => 'discharged',
            'chief_complaint' => 'Severe headache, BP 180/100 at BHS',
            'diagnosis'       => 'Hypertensive Urgency — oral meds given, discharged',
            'service'         => 'Internal Medicine',
            'payment_class'   => 'Charity',
        ]);

        $this->admitted($p[15], $dSan, $cMark, $nGon, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subHours(5),
            'doctor_admitted_at'  => now()->subHours(4),
            'clerk_admitted_at'   => now()->subHours(3)->subMinutes(30),
            'chief_complaint'     => 'Woke up with right arm and leg weakness, facial drooping, slurred speech',
            'admitting_diagnosis' => 'Acute Ischemic Stroke r/o Hemorrhagic Stroke; Hypertensive Emergency',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Wife',
            'condition_on_arrival'=> 'Ambulance stretcher, conscious, dysarthric, right-sided weakness',
            'allergies'           => 'NKDA',
            'alert'               => 'Possible stroke — time-sensitive. Thrombolysis window being assessed.',
            'vitals'              => [
                ['bp'=>'205/115','pr'=>88,'rr'=>20,'temp'=>37.2,'o2'=>96,'pain'=>4],
                ['bp'=>'190/110','pr'=>84,'rr'=>18,'temp'=>37.0,'o2'=>97,'pain'=>3],
            ],
            'orders' => [
                'O2 via nasal cannula @ 2-3 LPM; SpO2 > 94%',
                'IVF PNSS 1L @ KVO',
                'Nicardipine 5mg/hr IV — titrate to SBP < 185/110',
                'NPO — aspiration risk, dysphagia screen first',
                'Cranial CT scan PLAIN STAT',
                'ECG STAT',
                'CBC, PT-INR, APTT, Electrolytes, FBS STAT',
                'Document time of symptom onset — critical for thrombolysis decision',
                'NIHSS scoring q1h',
                'GCS q1h',
                'Foley catheter',
                'Neurology referral STAT',
            ],
            'fdar_notes' => [[
                'F' => 'Wife: woke up at 5am with right weakness and slurred speech. Normal at midnight. Non-compliant HPN meds. Smoker.',
                'D' => 'BP 205/115, GCS 14. Right facial droop. Right arm drift. Right leg weakness 3/5. Dysarthria. NIHSS 8.',
                'A' => 'Acute stroke. Hypertensive emergency. Symptom onset ~4 hours — within 4.5-hour thrombolysis window.',
                'R' => 'Stroke protocol activated. CT STAT. BP managed carefully. Neurology notified. Consent for intervention ongoing.',
            ]],
        ]);

        // ─ 16: Rosario Magno — Pre-eclampsia (current) ───────────────────

        $this->assessed($p[16], $dMen, $cRosa, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonths(6)->setTime(8, 0),
            'discharged_at'  => now()->subMonths(6)->setTime(9, 0),
            'status'         => 'discharged',
            'chief_complaint'=> 'Prenatal check 16 weeks AOG',
            'diagnosis'      => 'Pregnancy 16 weeks G3P2 — low risk',
            'service'        => 'OB-Gyne',
            'payment_class'  => 'Charity',
        ]);

        $this->admitted($p[16], $dMen, $cMark, $nRam, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subHours(9),
            'doctor_admitted_at'  => now()->subHours(8),
            'clerk_admitted_at'   => now()->subHours(7)->subMinutes(30),
            'chief_complaint'     => 'Severe headache, visual disturbances, epigastric pain, BP 165/110, 36 weeks AOG',
            'admitting_diagnosis' => 'Severe Pre-eclampsia 36 wks AOG G3P2 — prepare for delivery',
            'service'             => 'OB-Gyne',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Husband',
            'condition_on_arrival'=> 'Ambulatory, hypertensive, edematous',
            'allergies'           => 'NKDA',
            'alert'               => 'Severe pre-eclampsia — seizure precautions. Prepare for CS.',
            'vitals'              => [
                ['bp'=>'165/110','pr'=>94,'rr'=>20,'temp'=>36.8,'o2'=>98,'pain'=>7,'weight'=>72,'height'=>150],
                ['bp'=>'158/105','pr'=>88,'rr'=>18,'temp'=>36.7,'o2'=>98,'pain'=>5],
            ],
            'orders' => [
                'MgSO4 4g IV loading dose in 50mL PNSS over 15-20 min STAT',
                'MgSO4 maintenance 1g/hr IV via pump',
                'Labetalol 20mg IV q20min (max 300mg) for BP > 160/110',
                'O2 via nasal cannula @ 2 LPM',
                'IVF PNSS 1L @ 100 mL/hr',
                'Foley catheter — hourly urine output ≥ 30 mL/hr',
                'Serum MgSO4 q4h (therapeutic 4-7 mEq/L)',
                'Calcium gluconate 1g at bedside (antidote)',
                'CBC, LFTs, BUN, Creatinine, Uric acid STAT',
                'Continuous EFM',
                'Betamethasone 12mg IM q24h x2 doses',
                'Seizure precautions: padded rails, dim lighting, suction at bedside',
                'NPO — prepare for CS',
            ],
            'fdar_notes' => [[
                'F' => 'G3P2, 36 weeks. Severe headache, visual blurring, epigastric pain for 4 hours. BP 165/110 at BHS.',
                'D' => 'BP 165/110, 3+ proteinuria on dipstick. Facial and lower extremity edema. FHT 138 regular.',
                'A' => 'Severe pre-eclampsia with severe features. High risk for eclampsia and HELLP.',
                'R' => 'MgSO4 loading done. Labetalol given x1. BP 155/100. Foley inserted. CS team notified.',
            ]],
        ]);

        // ─ 17: Christian Ocampo — Private Appendicitis (current) ─────────

        $this->admitted($p[17], $dDC, $cRosa, $nTor, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subHours(3),
            'doctor_admitted_at'  => now()->subHours(2)->subMinutes(30),
            'clerk_admitted_at'   => now()->subHours(2),
            'chief_complaint'     => 'RLQ pain 8/10, fever, nausea, vomiting',
            'admitting_diagnosis' => 'Acute Appendicitis — emergent appendectomy',
            'service'             => 'Surgery',
            'payment_class'       => 'Private',
            'brought_by'          => 'Girlfriend',
            'condition_on_arrival'=> 'Ambulatory with difficulty, guarding RLQ',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'118/76','pr'=>108,'rr'=>22,'temp'=>38.6,'o2'=>99,'pain'=>8,'weight'=>70,'height'=>175],
                ['bp'=>'120/78','pr'=>100,'rr'=>20,'temp'=>38.2,'o2'=>99,'pain'=>6],
            ],
            'orders' => [
                'NPO immediately',
                'IVF PNSS 1L @ 30 gtts/min',
                'Cefazolin 1g IV STAT',
                'Metronidazole 500mg IV q8h',
                'Ketorolac 30mg IV q8h',
                'Morphine 3mg IV PRN severe pain',
                'CBC, Electrolytes, Blood typing STAT',
                'Abdominal ultrasound ASAP',
                'Consent for appendectomy',
            ],
            'fdar_notes' => [[
                'F' => 'Acute onset RLQ pain since yesterday, migrating from periumbilical. Fever, anorexia, 2 vomiting.',
                'D' => 'Temp 38.6°C. McBurney\'s tenderness. Rovsing\'s positive. Psoas sign positive. Rebound tenderness.',
                'A' => 'Acute appendicitis — possible perforation risk.',
                'R' => 'NPO. IV started. Labs and ultrasound ordered. OR team alerted. Consent in process.',
            ]],
        ]);

        // ─ 18: Remedios Domingo — Elderly Hemorrhagic Stroke ─────────────

        $this->assessed($p[18], $dCas, $cRosa, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonths(8)->setTime(10, 0),
            'discharged_at'  => now()->subMonths(8)->setTime(11, 30),
            'status'         => 'discharged',
            'chief_complaint'=> 'Follow-up — hypertension and atrial fibrillation on warfarin',
            'diagnosis'      => 'Hypertension, Atrial Fibrillation — controlled on warfarin',
            'service'        => 'Cardiology',
            'payment_class'  => 'Charity',
        ]);

        $this->admitted($p[18], $dCas, $cMark, $nRam, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subDays(2)->setTime(7, 0),
            'doctor_admitted_at'  => now()->subDays(2)->setTime(7, 45),
            'clerk_admitted_at'   => now()->subDays(2)->setTime(8, 15),
            'chief_complaint'     => 'Found unresponsive, left-sided weakness, incontinence',
            'admitting_diagnosis' => 'Hemorrhagic Stroke ICH Right Hemisphere; Hypertension; AFib on Warfarin',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Son and daughter-in-law',
            'condition_on_arrival'=> 'Ambulance stretcher, unconscious, GCS 8',
            'allergies'           => 'NKDA',
            'alert'               => 'On Warfarin — bleeding risk. Do NOT give anticoagulants.',
            'vitals'              => [
                ['bp'=>'195/110','pr'=>88,'rr'=>18,'temp'=>37.0,'o2'=>92],
                ['bp'=>'182/105','pr'=>84,'rr'=>16,'temp'=>36.9,'o2'=>95],
            ],
            'orders' => [
                'O2 via face mask @ 8 LPM',
                'IVF PNSS 1L @ KVO — avoid hypotonic fluids',
                'Nicardipine 5-15mg/hr IV — target SBP 140-160',
                'Mannitol 20% 1g/kg over 20-30 mins STAT',
                'Vitamin K 10mg IV STAT (Warfarin reversal)',
                'FFP 4 units STAT',
                'HOLD Warfarin indefinitely',
                'Cranial CT scan PLAIN STAT; repeat in 24h',
                'PT-INR STAT, CBC, electrolytes',
                'Foley catheter',
                'NG tube insertion — tube feeds once stabilized',
                'GCS q1h',
                'HOB 30°; no Valsalva',
                'No IM injections',
                'Neurology referral',
            ],
            'fdar_notes' => [[
                'F' => 'Found unresponsive at 6am. Last seen normal at midnight. On Warfarin, not always compliant.',
                'D' => 'GCS 8. BP 195/110, PR 88 irregular AFib. Left hemiplegia. Left Babinski positive. Right pupil 3mm reactive, left 4mm sluggish.',
                'A' => 'Massive hemorrhagic stroke in elderly anticoagulated patient. Life-threatening. ICP concern.',
                'R' => 'Warfarin reversed urgently. Mannitol started. BP managed. Neurology consulted. Family counseled — Full Code.',
            ]],
        ]);

        // ─ 19: Jaime Natividad — Repeat CAP / COPD ───────────────────────

        $this->admitted($p[19], $dSan, $cRosa, $nGon, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subYear()->addMonths(1)->setTime(11, 0),
            'discharged_at'       => now()->subYear()->addMonths(1)->addDays(5)->setTime(9, 0),
            'chief_complaint'     => 'Cough, fever, chills for 5 days',
            'admitting_diagnosis' => 'Community-Acquired Pneumonia Low Risk; Hypertension',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Son',
            'condition_on_arrival'=> 'Ambulatory with cane, febrile',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'150/90','pr'=>92,'rr'=>22,'temp'=>38.6,'o2'=>94,'pain'=>3,'weight'=>60,'height'=>162],
            ],
            'orders' => [
                'O2 via nasal cannula @ 2 LPM',
                'IVF PNSS 1L @ 20 gtts/min',
                'Ceftriaxone 1g IV q12h',
                'Azithromycin 500mg IV OD',
                'Paracetamol 500mg q6h PRN',
                'CBC, blood culture x2, CXR STAT',
            ],
            'final_diagnosis' => 'CAP Low Risk; Hypertension',
        ]);

        $this->assessed($p[19], $dSan, $cMark, [
            'visit_type'     => 'OPD',
            'registered_at'  => now()->subMonths(6)->setTime(8, 0),
            'discharged_at'  => now()->subMonths(6)->setTime(9, 0),
            'status'         => 'discharged',
            'chief_complaint'=> 'Follow-up after pneumonia, persistent dry cough',
            'diagnosis'      => 'Post-CAP resolving; Hypertension controlled; Pneumococcal vaccine given',
            'service'        => 'Internal Medicine',
            'payment_class'  => 'Charity',
        ]);

        $this->admitted($p[19], $dSan, $cRosa, $nTor, [
            'visit_type'          => 'ER',
            'registered_at'       => now()->subWeek()->setTime(15, 0),
            'discharged_at'       => now()->subDays(2)->setTime(9, 0),
            'chief_complaint'     => 'Fever, cough purulent sputum, chest pain, chills',
            'admitting_diagnosis' => 'Community-Acquired Pneumonia Moderate Risk; Hypertension; COPD suspected',
            'service'             => 'Internal Medicine',
            'payment_class'       => 'Charity',
            'brought_by'          => 'Daughter',
            'condition_on_arrival'=> 'Ambulatory, febrile, productive cough',
            'allergies'           => 'NKDA',
            'vitals'              => [
                ['bp'=>'152/92','pr'=>96,'rr'=>24,'temp'=>38.9,'o2'=>92,'pain'=>4,'weight'=>57,'height'=>162],
                ['bp'=>'144/88','pr'=>88,'rr'=>20,'temp'=>38.2,'o2'=>95,'pain'=>2],
            ],
            'orders' => [
                'O2 via nasal cannula @ 3 LPM',
                'Ceftriaxone 1g IV q12h; Azithromycin 500mg IV OD',
                'Salbutamol + Ipratropium nebu q6h',
                'Paracetamol 500mg q6h PRN',
                'Spirometry after recovery (COPD staging)',
                'CBC, blood culture x2, sputum GS/CS, CXR STAT',
            ],
            'final_diagnosis' => 'CAP Moderate Risk PSI III; Hypertension; Probable COPD',
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  LINK PATIENT PORTAL ACCOUNTS
    // ══════════════════════════════════════════════════════════════════════

    private function linkPortalAccounts(array $patients): void
    {
        // Liza Bautista → patients[2]
        User::where('email', 'liza.bautista@email.com')
            ->update(['patient_id' => $patients[2]->id]);

        // Ramon Mendoza → patients[5]
        User::where('email', 'ramon.mendoza@email.com')
            ->update(['patient_id' => $patients[5]->id]);
    }
}
