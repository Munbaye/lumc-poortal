<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;
use App\Models\Visit;
use App\Models\Vital;
use App\Models\MedicalHistory;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Roles ──────────────────────────────────────────────────────────
        foreach (['admin','doctor','nurse','clerk','clerk-opd','clerk-er','tech','patient'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ── 2. Admin ──────────────────────────────────────────────────────────
        $admin = User::create([
            'name' => 'Administrator', 'email' => 'admin@lumc.gov.ph',
            'password' => Hash::make('password'), 'employee_id' => 'EMP-0001',
            'panel' => 'admin', 'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // ── 3. Doctors with specialties ───────────────────────────────────────
        $doctorDefs = [
            ['Ricardo Santos',   'rsantos@lumc.gov.ph',    'Internal Medicine', 'MD-001'],
            ['Maria Reyes',      'mreyes@lumc.gov.ph',     'Pediatrics',        'MD-002'],
            ['Jose Dela Cruz',   'jdelacruz@lumc.gov.ph',  'Surgery',           'MD-003'],
            ['Ana Villanueva',   'avillanueva@lumc.gov.ph','OB-Gynecology',     'MD-004'],
            ['Roberto Castillo', 'rcastillo@lumc.gov.ph',  'Neurology',         'MD-005'],
            ['Sample Doctor',    'doctor@lumc.gov.ph',     'Internal Medicine', 'MD-006'],
        ];

        $doctors = [];
        foreach ($doctorDefs as [$name, $email, $specialty, $emp]) {
            $doc = User::create([
                'name' => $name, 'email' => $email,
                'password' => Hash::make('password'),
                'employee_id' => $emp, 'panel' => 'doctor',
                'specialty' => $specialty, 'is_active' => true,
            ]);
            $doc->assignRole('doctor');
            $doctors[] = $doc;
        }

        [$drSantos, $drReyes, $drDelaCruz, $drVillanueva, $drCastillo, $drSample] = $doctors;

        // ── 4. Nurses ─────────────────────────────────────────────────────────
        foreach ([
            ['Gloria Mendoza', 'gmendoza@lumc.gov.ph', 'RN-001'],
            ['Edgar Ramos',    'eramos@lumc.gov.ph',   'RN-002'],
        ] as [$name, $email, $emp]) {
            $n = User::create([
                'name' => $name, 'email' => $email,
                'password' => Hash::make('password'),
                'employee_id' => $emp, 'panel' => 'nurse', 'is_active' => true,
            ]);
            $n->assignRole('nurse');
        }

        // ── 5. Clerks ─────────────────────────────────────────────────────────
        $clerkOpd = User::create([
            'name' => 'Clerk OPD', 'email' => 'clerk@lumc.gov.ph',
            'password' => Hash::make('password'),
            'employee_id' => 'CLK-001', 'panel' => 'clerk', 'is_active' => true,
        ]);
        $clerkOpd->assignRole(['clerk','clerk-opd']);

        $clerkEr = User::create([
            'name' => 'Clerk ER', 'email' => 'clerk-er@lumc.gov.ph',
            'password' => Hash::make('password'),
            'employee_id' => 'CLK-002', 'panel' => 'clerk', 'is_active' => true,
        ]);
        $clerkEr->assignRole(['clerk','clerk-er']);

        // ── 6. Tech ───────────────────────────────────────────────────────────
        $tech = User::create([
            'name' => 'Lab Tech', 'email' => 'tech@lumc.gov.ph',
            'password' => Hash::make('password'),
            'employee_id' => 'TEC-001', 'panel' => 'tech', 'is_active' => true,
        ]);
        $tech->assignRole('tech');

        // ── 7. Patients + Visits ─────────────────────────────────────────────
        // Helper: create patient record
        $mkPatient = function (array $p): Patient {
            $birthday = $p['birthday'] ? Carbon::parse($p['birthday']) : null;
            $age = $birthday ? (int) $birthday->diffInYears(now()) : null;
            $isPedia = ($p['is_pedia'] ?? false) || ($age !== null && $age < 12);
            return Patient::create([
                'family_name'    => $p['family_name'],
                'first_name'     => $p['first_name'],
                'middle_name'    => $p['middle_name'] ?? null,
                'birthday'       => $p['birthday'],
                'age'            => $age,
                'sex'            => $p['sex'],
                'address'        => $p['address'],
                'contact_number' => $p['contact_number'] ?? null,
                'occupation'     => $p['occupation'] ?? null,
                'civil_status'   => $p['civil_status'] ?? null,
                'is_pedia'       => $isPedia,
            ]);
        };

        // Helper: create visit + vitals + optional assessment
        $mkVisit = function (
            Patient $patient, User $clerk, array $v,
            ?User $assessingDoctor = null
        ) {
            $at = now()->subDays($v['days_ago'])->setTimeFromTimeString($v['time']);
            $isPedia = $patient->is_pedia;

            // Determine if this visit has been assessed/admitted
            $hasAssessment = isset($v['assessment']);
            $a = $v['assessment'] ?? null;

            $status = $v['status'];
            $disposition = null;
            $paymentClass = null;
            $admittedWard = null;
            $admittedService = null;
            $assignedDoctorId = null;
            $dischargedAt = null;

            if ($hasAssessment) {
                $disposition = $a['disposition'];
                if ($disposition === 'Admitted') {
                    $paymentClass     = $a['payment_class'] ?? 'Charity';
                    $admittedWard     = $a['ward']    ?? null;
                    $admittedService  = $a['service'] ?? null;
                    $assignedDoctorId = ($paymentClass === 'Private' && isset($a['doctor']))
                        ? $a['doctor']->id : null;
                } else {
                    $dischargedAt = in_array($disposition, ['Discharged','HAMA','Expired'])
                        ? $at->copy()->addHours(rand(1,3)) : null;
                }
            }

            $visit = Visit::create([
                'patient_id'           => $patient->id,
                'clerk_id'             => $clerk->id,
                'visit_type'           => $v['type'],
                'chief_complaint'      => $v['cc'],
                'brought_by'           => $v['brought_by'] ?? null,
                'condition_on_arrival' => $v['condition']  ?? null,
                'status'               => $status,
                'disposition'          => $disposition,
                'payment_class'        => $paymentClass,
                'admitted_ward'        => $admittedWard,
                'admitted_service'     => $admittedService,
                'assigned_doctor_id'   => $assignedDoctorId,
                'discharged_at'        => $dischargedAt,
                'registered_at'        => $at,
            ]);

            // Vitals
            if (isset($v['vitals'])) {
                $vt = $v['vitals'];
                Vital::create([
                    'visit_id'         => $visit->id,
                    'patient_id'       => $patient->id,
                    'recorded_by'      => $clerk->id,
                    'nurse_name'       => 'Nurse Gloria Mendoza',
                    'temperature'      => $vt['temp'],
                    'temperature_site' => 'Axilla',
                    'pulse_rate'       => $vt['pr'],
                    'respiratory_rate' => $vt['rr'],
                    'blood_pressure'   => $isPedia ? null : ($vt['bp'] ?? null),
                    'o2_saturation'    => $vt['o2'] ?? null,
                    'weight_kg'        => $vt['wt'] ?? null,
                    'pain_scale'       => $vt['pain'] ?? null,
                    'taken_at'         => $at->copy()->addMinutes(20),
                ]);
            }

            // Assessment / Medical History
            if ($hasAssessment && $assessingDoctor) {
                MedicalHistory::create([
                    'visit_id'               => $visit->id,
                    'patient_id'             => $patient->id,
                    'doctor_id'              => $assessingDoctor->id,
                    'chief_complaint'        => $v['cc'],
                    'history_of_present_illness' => $a['hpi'] ?? null,
                    'past_medical_history'   => $a['pmh'] ?? null,
                    'drug_allergies'         => $a['allergies'] ?? 'NKDA',
                    'pe_skin'                => 'No rashes, no lesions noted',
                    'pe_head_eent'           => 'Anicteric sclerae, pink palpebral conjunctiva',
                    'pe_chest'               => 'Symmetrical chest expansion',
                    'pe_lungs'               => $a['pe_lungs']  ?? 'Clear breath sounds bilaterally',
                    'pe_cardiovascular'      => $a['pe_cvs']    ?? 'Adynamic precordium, RRR, no murmur',
                    'pe_abdomen'             => $a['pe_abd']    ?? 'Soft, non-tender, non-distended',
                    'pe_extremities'         => 'No edema, capillary refill < 2 sec',
                    'admitting_impression'   => $a['impression'] ?? $a['diagnosis'],
                    'diagnosis'              => $a['diagnosis'],
                    'differential_diagnosis' => $a['ddx'] ?? null,
                    'plan'                   => $a['plan'],
                    'disposition'            => $disposition,
                    'admitted_ward'          => $admittedWard,
                    'service'                => $admittedService,
                    'payment_type'           => $paymentClass,
                ]);
            }

            return $visit;
        };

        // ────────────────────────────────────────────────────────────────────
        // TODAY'S PATIENTS (days_ago = 0) — various stages of the workflow
        // ────────────────────────────────────────────────────────────────────

        // 1. Registered only (no vitals yet)
        $p1 = $mkPatient([
            'family_name' => 'Villanueva', 'first_name' => 'Pedro', 'middle_name' => 'Gomez',
            'birthday' => '1960-08-30', 'sex' => 'Male',
            'address' => 'Brgy. Pindangan, San Fernando, La Union',
            'contact_number' => '09611234561', 'occupation' => 'Retired', 'civil_status' => 'Widowed',
        ]);
        $mkVisit($p1, $clerkOpd, [
            'type' => 'OPD', 'days_ago' => 0, 'time' => '11:30',
            'cc' => 'Chest pain on exertion, shortness of breath', 'status' => 'registered',
        ]);

        // 2. Vitals done — waiting for doctor
        $p2 = $mkPatient([
            'family_name' => 'Garcia', 'first_name' => 'Juan', 'middle_name' => 'Santos',
            'birthday' => '1985-03-14', 'sex' => 'Male',
            'address' => 'Brgy. Poblacion, Agoo, La Union',
            'contact_number' => '09171234562', 'occupation' => 'Farmer', 'civil_status' => 'Married',
        ]);
        $mkVisit($p2, $clerkOpd, [
            'type' => 'OPD', 'days_ago' => 0, 'time' => '08:30',
            'cc' => 'Fever for 3 days, cough, difficulty breathing', 'status' => 'vitals_done',
            'vitals' => ['temp' => 38.5, 'pr' => 92, 'rr' => 22, 'bp' => '130/80', 'o2' => 96, 'wt' => 65.0, 'pain' => 4],
        ]);

        // 3. Pedia — vitals done, no BP
        $p3 = $mkPatient([
            'family_name' => 'Santos', 'first_name' => 'Ligaya', 'birthday' => '2018-04-17',
            'sex' => 'Female', 'address' => 'Brgy. Catbangen, San Fernando, La Union',
            'is_pedia' => true,
        ]);
        $mkVisit($p3, $clerkOpd, [
            'type' => 'OPD', 'days_ago' => 0, 'time' => '10:00',
            'cc' => 'High grade fever 39°C, fine macular rashes over trunk, vomiting x3', 'status' => 'vitals_done',
            'vitals' => ['temp' => 39.2, 'pr' => 132, 'rr' => 28, 'bp' => null, 'o2' => 97, 'wt' => 14.5, 'pain' => 6],
        ]);

        // 4. ER — vitals done (urgent)
        $p4 = $mkPatient([
            'family_name' => 'Navarro', 'first_name' => 'Rosario', 'middle_name' => 'Vera',
            'birthday' => '1945-02-25', 'sex' => 'Female',
            'address' => 'Brgy. Dalumpinas Oeste, San Fernando, La Union',
            'occupation' => 'Retired', 'civil_status' => 'Widowed',
        ]);
        $mkVisit($p4, $clerkEr, [
            'type' => 'ER', 'days_ago' => 0, 'time' => '07:20',
            'cc' => 'Sudden loss of consciousness, facial drooping, slurred speech',
            'brought_by' => 'Family', 'condition' => 'Poor', 'status' => 'vitals_done',
            'vitals' => ['temp' => 36.9, 'pr' => 98, 'rr' => 22, 'bp' => '185/110', 'o2' => 91, 'wt' => 58.0, 'pain' => 3],
        ]);

        // 5. ER — assessed + admitted (Charity) — Neurology
        $p5 = $mkPatient([
            'family_name' => 'Dela Cruz', 'first_name' => 'Carlos', 'middle_name' => 'Bautista',
            'birthday' => '1990-11-05', 'sex' => 'Male',
            'address' => 'Brgy. Lingsat, San Fernando, La Union',
            'occupation' => 'Driver', 'civil_status' => 'Single',
        ]);
        $mkVisit($p5, $clerkEr, [
            'type' => 'ER', 'days_ago' => 0, 'time' => '06:45',
            'cc' => 'MVA — chest pain, severe respiratory distress',
            'brought_by' => 'Ambulance', 'condition' => 'Fair', 'status' => 'admitted',
            'vitals' => ['temp' => 37.2, 'pr' => 118, 'rr' => 26, 'bp' => '88/60', 'o2' => 89, 'wt' => 68.0, 'pain' => 9],
            'assessment' => [
                'diagnosis'    => 'Blunt chest trauma; Tension pneumothorax, right',
                'hpi'          => 'Patient was in a motorcycle accident. Struck by a car. Progressive SOB after impact.',
                'pmh'          => 'No known prior illness',
                'allergies'    => 'NKDA',
                'pe_lungs'     => 'Absent breath sounds on the right, deviated trachea to left',
                'pe_cvs'       => 'Tachycardic, muffled heart sounds',
                'pe_abd'       => 'Soft, non-tender',
                'impression'   => 'Tension pneumothorax R side',
                'ddx'          => 'Hemothorax, cardiac tamponade',
                'plan'         => 'Emergency needle decompression → chest tube insertion; IVF PNSS 1L fast drip; O2 via NRM; Surgical consult',
                'disposition'  => 'Admitted',
                'payment_class'=> 'Charity',
                'ward'         => 'ICU',
                'service'      => 'Surgery',
            ],
        ], $drDelaCruz);

        // 6. OPD — assessed + discharged
        $p6 = $mkPatient([
            'family_name' => 'Reyes', 'first_name' => 'Maria', 'middle_name' => 'Cruz',
            'birthday' => '1975-07-22', 'sex' => 'Female',
            'address' => 'Brgy. San Agustin, San Fernando, La Union',
            'occupation' => 'Teacher', 'civil_status' => 'Married',
        ]);
        $mkVisit($p6, $clerkOpd, [
            'type' => 'OPD', 'days_ago' => 0, 'time' => '09:15',
            'cc' => 'Hypertension follow-up, persistent headache, blurring of vision',
            'status' => 'discharged',
            'vitals' => ['temp' => 36.8, 'pr' => 78, 'rr' => 16, 'bp' => '165/105', 'o2' => 98, 'wt' => 72.0, 'pain' => 3],
            'assessment' => [
                'diagnosis'    => 'Hypertension Stage 2, uncontrolled',
                'hpi'          => 'Headache for 2 days. BP poorly controlled despite amlodipine 5mg OD.',
                'pmh'          => 'Known hypertensive x 5 years',
                'allergies'    => 'NKDA',
                'plan'         => 'Upstep amlodipine to 10mg OD; add losartan 50mg OD; low-salt diet; return in 2 weeks',
                'disposition'  => 'Discharged',
            ],
        ], $drSantos);

        // ────────────────────────────────────────────────────────────────────
        // YESTERDAY'S PATIENTS (days_ago = 1)
        // ────────────────────────────────────────────────────────────────────

        // 7. ER — admitted, Private (Surgery)
        $p7 = $mkPatient([
            'family_name' => 'Aquino', 'first_name' => 'Rosa', 'middle_name' => 'Lim',
            'birthday' => '1995-01-20', 'sex' => 'Female',
            'address' => 'Brgy. Pagdalagan Sur, Bauang, La Union',
            'occupation' => 'Nurse Aide', 'civil_status' => 'Single',
        ]);
        $mkVisit($p7, $clerkEr, [
            'type' => 'ER', 'days_ago' => 1, 'time' => '14:00',
            'cc' => 'Severe right lower quadrant abdominal pain, nausea, vomiting, anorexia',
            'brought_by' => 'Family', 'condition' => 'Fair', 'status' => 'admitted',
            'vitals' => ['temp' => 38.2, 'pr' => 104, 'rr' => 18, 'bp' => '108/72', 'o2' => 99, 'wt' => 52.0, 'pain' => 8],
            'assessment' => [
                'diagnosis'    => 'Acute appendicitis (Alvarado score 8/10)',
                'hpi'          => 'Periumbilical pain that migrated to RLQ over 12 hours. McBurney\'s point positive. Rovsing\'s sign positive.',
                'pmh'          => 'No prior surgeries',
                'allergies'    => 'Penicillin (rash)',
                'pe_abd'       => 'Guarding and rigidity at RLQ; rebound tenderness positive',
                'impression'   => 'Acute appendicitis, non-perforated',
                'ddx'          => 'Ovarian cyst torsion, ectopic pregnancy, mesenteric lymphadenitis',
                'plan'         => 'NPO; IVF D5LR 1L x 8h; Cefuroxime 1.5g IV q8h; Metronidazole 500mg IV q8h; Prep for laparoscopic appendectomy',
                'disposition'  => 'Admitted',
                'payment_class'=> 'Private',
                'ward'         => 'Surgical Ward Rm 204',
                'service'      => 'Surgery',
                'doctor'       => $drDelaCruz,
            ],
        ], $drDelaCruz);

        // 8. OPD — discharged
        $p8 = $mkPatient([
            'family_name' => 'Bautista', 'first_name' => 'Eduardo', 'middle_name' => 'Navarro',
            'birthday' => '1970-05-10', 'sex' => 'Male',
            'address' => 'Brgy. Central, Bauang, La Union',
            'occupation' => 'Fisherman', 'civil_status' => 'Married',
        ]);
        $mkVisit($p8, $clerkOpd, [
            'type' => 'OPD', 'days_ago' => 1, 'time' => '09:00',
            'cc' => 'Diabetes follow-up — polyuria, polydipsia, weight loss over 2 months',
            'status' => 'discharged',
            'vitals' => ['temp' => 36.5, 'pr' => 74, 'rr' => 15, 'bp' => '126/82', 'o2' => 98, 'wt' => 78.0, 'pain' => 1],
            'assessment' => [
                'diagnosis'    => 'Diabetes Mellitus Type 2, poorly controlled (FBS 15.2 mmol/L)',
                'hpi'          => 'Known T2DM x 3 years, non-compliant with metformin. Recent FBS 15.2.',
                'pmh'          => 'DM Type 2 x 3 years',
                'allergies'    => 'NKDA',
                'plan'         => 'Metformin 1g BID (reinstate); add glimepiride 2mg OD; ADA diet counseling; HbA1c; return in 1 month',
                'disposition'  => 'Discharged',
            ],
        ], $drSantos);

        // ────────────────────────────────────────────────────────────────────
        // OLDER VISITS (days_ago = 3–10)
        // ────────────────────────────────────────────────────────────────────

        // 9. Referred (TB)
        $p9 = $mkPatient([
            'family_name' => 'Cruz', 'first_name' => 'Marites', 'middle_name' => 'Pascual',
            'birthday' => '1952-12-03', 'sex' => 'Female',
            'address' => 'Brgy. Tococ, Agoo, La Union',
            'occupation' => 'Housewife', 'civil_status' => 'Married',
        ]);
        $mkVisit($p9, $clerkOpd, [
            'type' => 'OPD', 'days_ago' => 7, 'time' => '10:30',
            'cc' => 'Productive cough x 3 weeks, night sweats, 8 lbs weight loss',
            'status' => 'referred',
            'vitals' => ['temp' => 37.8, 'pr' => 88, 'rr' => 20, 'bp' => '120/78', 'o2' => 93, 'wt' => 42.0, 'pain' => 2],
            'assessment' => [
                'diagnosis'    => 'Pulmonary Tuberculosis, Bacteriologically Confirmed, Category I',
                'hpi'          => 'Cough for 3 weeks productive of yellowish sputum. Drenching night sweats. Weight loss 8 lbs.',
                'pmh'          => 'No prior TB treatment',
                'allergies'    => 'NKDA',
                'pe_lungs'     => 'Coarse rales bilateral upper lobes',
                'impression'   => 'PTB Cat I',
                'plan'         => 'GeneXpert sputum; refer to DOTS center; RIPE regimen to start pending result',
                'disposition'  => 'Referred',
            ],
        ], $drSantos);

        // 10. ER seizure — discharged 3 days ago
        $p10 = $mkPatient([
            'family_name' => 'Pascual', 'first_name' => 'Miguel', 'middle_name' => 'Aguilar',
            'birthday' => '2005-09-08', 'sex' => 'Male',
            'address' => 'Brgy. Biday, San Fernando, La Union',
            'occupation' => 'Student', 'civil_status' => 'Single',
        ]);
        $mkVisit($p10, $clerkEr, [
            'type' => 'ER', 'days_ago' => 3, 'time' => '21:15',
            'cc' => 'Generalized tonic-clonic seizure, 3 minutes duration — first episode',
            'brought_by' => 'Family', 'condition' => 'Fair', 'status' => 'discharged',
            'vitals' => ['temp' => 37.0, 'pr' => 92, 'rr' => 16, 'bp' => '118/76', 'o2' => 98, 'wt' => 55.0, 'pain' => 2],
            'assessment' => [
                'diagnosis'    => 'New onset seizure, etiology to be determined',
                'hpi'          => 'Witnessed GTC seizure lasting 3 minutes at home. No prior episodes. No fever. No head trauma.',
                'pmh'          => 'No prior neurological illness',
                'allergies'    => 'NKDA',
                'pe_neurology' => 'Post-ictal state on arrival; GCS 14; pupils equal round reactive; no focal deficit noted',
                'impression'   => 'New onset seizure — r/o epilepsy, structural lesion, metabolic cause',
                'ddx'          => 'Epilepsy, intracranial mass, metabolic disturbance',
                'plan'         => 'EEG; MRI brain with contrast; CBC, BMP, blood glucose; Phenytoin 15mg/kg IV loading; neurology follow-up',
                'disposition'  => 'Discharged',
            ],
        ], $drCastillo);

        // 11. OPD pedia — admitted (Charity, Pedia)
        $p11 = $mkPatient([
            'family_name' => 'Flores', 'first_name' => 'Andres', 'birthday' => '2021-06-15',
            'sex' => 'Male', 'address' => 'Brgy. Sevilla, San Fernando, La Union',
            'is_pedia' => true,
        ]);
        $mkVisit($p11, $clerkOpd, [
            'type' => 'OPD', 'days_ago' => 2, 'time' => '08:00',
            'cc' => 'High fever, difficulty breathing, decreased feeding x 2 days',
            'status' => 'admitted',
            'vitals' => ['temp' => 39.6, 'pr' => 148, 'rr' => 52, 'bp' => null, 'o2' => 93, 'wt' => 10.2, 'pain' => 7],
            'assessment' => [
                'diagnosis'    => 'Severe Pneumonia (CAP-HR), 3-year-old male',
                'hpi'          => 'Fever 39°C for 2 days. Progressive difficulty breathing. Poor feeding. No rashes.',
                'pmh'          => 'No prior admissions. Vaccinations complete.',
                'allergies'    => 'NKDA',
                'pe_lungs'     => 'Subcostal and intercostal retractions; bilateral crackles on auscultation',
                'impression'   => 'CAP-HR (SARI)',
                'plan'         => 'Admit; O2 via face mask 2-3 LPM; IVF D5 0.3NaCl 900mL/day; Ampicillin 200mg/kg/day q6h; Chloramphenicol 75mg/kg/day',
                'disposition'  => 'Admitted',
                'payment_class'=> 'Charity',
                'ward'         => 'Pedia Ward',
                'service'      => 'Pediatrics',
            ],
        ], $drReyes);

        // 12. OPD — OB-Gyne, admitted Private
        $p12 = $mkPatient([
            'family_name' => 'Castillo', 'first_name' => 'Lourdes', 'middle_name' => 'Briones',
            'birthday' => '1993-03-22', 'sex' => 'Female',
            'address' => 'Brgy. Poro, San Fernando, La Union',
            'occupation' => 'Accountant', 'civil_status' => 'Married',
        ]);
        $mkVisit($p12, $clerkOpd, [
            'type' => 'OPD', 'days_ago' => 1, 'time' => '15:30',
            'cc' => 'G2P1 34 weeks AOG — severe headache, blurring of vision, epigastric pain',
            'status' => 'admitted',
            'vitals' => ['temp' => 36.7, 'pr' => 88, 'rr' => 16, 'bp' => '160/110', 'o2' => 99, 'wt' => 68.0, 'pain' => 7],
            'assessment' => [
                'diagnosis'    => 'Severe Pre-eclampsia, G2P1, 34 weeks AOG',
                'hpi'          => 'Known OB patient. Severe headache, scotomata, epigastric pain. BP 160/110 on recumbent position.',
                'pmh'          => 'No prior hypertension',
                'allergies'    => 'NKDA',
                'pe_abd'       => 'FH 34 cm; FHT 148 bpm; cephalic; no uterine contractions',
                'impression'   => 'Severe Pre-eclampsia',
                'ddx'          => 'Gestational hypertension, HELLP syndrome',
                'plan'         => 'Admit; Magnesium sulfate loading 4g IVP then 1g/hr; Hydralazine 10mg IVP PRN for BP >160/110; NST; Betamethasone 12mg IM q24h x 2',
                'disposition'  => 'Admitted',
                'payment_class'=> 'Private',
                'ward'         => 'OB Ward Rm 312',
                'service'      => 'OB-Gynecology',
                'doctor'       => $drVillanueva,
            ],
        ], $drVillanueva);

        // ────────────────────────────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('✅  LUMC database seeded successfully!');
        $this->command->info('');
        $this->command->info('┌─────────────────────────────────────────────────────────────────┐');
        $this->command->info('│                    LOGIN CREDENTIALS                             │');
        $this->command->info('│                  All passwords: password                         │');
        $this->command->info('├──────────────────────────┬──────────────────────────────────────┤');
        $this->command->info('│ Role                     │ Email                                │');
        $this->command->info('├──────────────────────────┼──────────────────────────────────────┤');
        $this->command->info('│ Admin                    │ admin@lumc.gov.ph                    │');
        $this->command->info('│ Doctor (Int. Medicine)   │ doctor@lumc.gov.ph                   │');
        $this->command->info('│ Doctor (Int. Medicine)   │ rsantos@lumc.gov.ph                  │');
        $this->command->info('│ Doctor (Pediatrics)      │ mreyes@lumc.gov.ph                   │');
        $this->command->info('│ Doctor (Surgery)         │ jdelacruz@lumc.gov.ph                │');
        $this->command->info('│ Doctor (OB-Gynecology)   │ avillanueva@lumc.gov.ph              │');
        $this->command->info('│ Doctor (Neurology)       │ rcastillo@lumc.gov.ph                │');
        $this->command->info('│ Nurse                    │ gmendoza@lumc.gov.ph                 │');
        $this->command->info('│ Clerk (OPD)              │ clerk@lumc.gov.ph                    │');
        $this->command->info('│ Clerk (ER)               │ clerk-er@lumc.gov.ph                 │');
        $this->command->info('│ Tech                     │ tech@lumc.gov.ph                     │');
        $this->command->info('└──────────────────────────┴──────────────────────────────────────┘');
        $this->command->info('');
        $this->command->info('12 patients seeded — today: 6 (2 registered, 2 vitals done, 1 admitted, 1 discharged)');
        $this->command->info('Panel URLs:  /admin  /doctor  /nurse  /clerk  /tech');
    }
}