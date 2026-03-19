<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\DoctorsOrder;
use App\Models\LabRequest;
use App\Models\MedicalHistory;
use App\Models\NursesNote;
use App\Models\Patient;
use App\Models\RadiologyRequest;
use App\Models\User;
use App\Models\Visit;
use App\Models\Vital;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

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

        // ── 3. Doctors ────────────────────────────────────────────────────────
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
        $nurses = [];
        foreach ([
            ['Gloria Mendoza', 'gmendoza@lumc.gov.ph', 'RN-001'],
            ['Edgar Ramos',    'eramos@lumc.gov.ph',   'RN-002'],
            ['Sample Nurse',   'nurse@lumc.gov.ph',    'RN-003'],
        ] as [$name, $email, $emp]) {
            $n = User::create([
                'name' => $name, 'email' => $email,
                'password' => Hash::make('password'),
                'employee_id' => $emp, 'panel' => 'nurse', 'is_active' => true,
            ]);
            $n->assignRole('nurse');
            $nurses[] = $n;
        }
        [$nurseMendoza, $nurseRamos, $nurseSample] = $nurses;

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

        // ── 6. Tech (MedTech + RadTech) ───────────────────────────────────────
        // MedTech — sees Lab queue only
        $medtech = User::create([
            'name'        => 'Maria Lorenzo',
            'email'       => 'medtech@lumc.gov.ph',
            'password'    => Hash::make('password'),
            'employee_id' => 'TEC-001',
            'panel'       => 'tech',
            'specialty'   => 'Medical Technologist',  // triggers lab-only queue
            'is_active'   => true,
        ]);
        $medtech->assignRole('tech');

        // RadTech — sees Radiology queue only
        $radtech = User::create([
            'name'        => 'Carlos Espino',
            'email'       => 'radtech@lumc.gov.ph',
            'password'    => Hash::make('password'),
            'employee_id' => 'TEC-002',
            'panel'       => 'tech',
            'specialty'   => 'Radiologic Technologist', // triggers radiology-only queue
            'is_active'   => true,
        ]);
        $radtech->assignRole('tech');

        // Generic tech (sees both)
        $tech = User::create([
            'name'        => 'Sample Tech',
            'email'       => 'tech@lumc.gov.ph',
            'password'    => Hash::make('password'),
            'employee_id' => 'TEC-003',
            'panel'       => 'tech',
            'specialty'   => 'Medical Technologist',
            'is_active'   => true,
        ]);
        $tech->assignRole('tech');

        // ── 7. Patients & Visits ──────────────────────────────────────────────
        $mkPatient = function (array $p): Patient {
            $birthday = $p['birthday'] ? Carbon::parse($p['birthday']) : null;
            $age      = $birthday ? (int) $birthday->diffInYears(now()) : null;
            $isPedia  = ($p['is_pedia'] ?? false) || ($age !== null && $age < 12);
            return Patient::create([
                'family_name'    => $p['family_name'],  'first_name'  => $p['first_name'],
                'middle_name'    => $p['middle_name'] ?? null,
                'birthday'       => $p['birthday'],     'age'         => $age,
                'sex'            => $p['sex'],           'address'     => $p['address'],
                'contact_number' => $p['contact_number'] ?? null,
                'occupation'     => $p['occupation']     ?? null,
                'civil_status'   => $p['civil_status']   ?? null,
                'is_pedia'       => $isPedia,
            ]);
        };

        $mkVisit = function (Patient $patient, User $clerk, array $v, ?User $assessingDoctor = null) {
            $at      = now()->subDays($v['days_ago'])->setTimeFromTimeString($v['time']);
            $isPedia = $patient->is_pedia;
            $a       = $v['assessment'] ?? null;

            $status           = $v['status'];
            $disposition      = $a ? $a['disposition'] : null;
            $paymentClass     = null;
            $admittedService  = null;
            $assignedDoctorId = null;
            $dischargedAt     = null;
            $doctorAdmittedAt = null;
            $clerkAdmittedAt  = null;

            if ($a) {
                if ($disposition === 'Admitted') {
                    $paymentClass     = $a['payment_class'] ?? 'Charity';
                    $admittedService  = $a['service'] ?? null;
                    $assignedDoctorId = ($paymentClass === 'Private' && isset($a['doctor']))
                        ? $a['doctor']->id : null;
                    $doctorAdmittedAt = $at->copy()->addHours(1);
                    $clerkAdmittedAt  = $at->copy()->addHours(2);
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
                'admitted_service'     => $admittedService,
                'assigned_doctor_id'   => $assignedDoctorId,
                'discharged_at'        => $dischargedAt,
                'registered_at'        => $at,
                'doctor_admitted_at'   => $doctorAdmittedAt,
                'clerk_admitted_at'    => $clerkAdmittedAt,
                'admitting_diagnosis'  => ($a && $disposition === 'Admitted')
                    ? ($a['diagnosis'] ?? $a['impression'] ?? null) : null,
            ]);

            if (isset($v['vitals'])) {
                $vt = $v['vitals'];
                Vital::create([
                    'visit_id'         => $visit->id,
                    'patient_id'       => $patient->id,
                    'recorded_by'      => $clerk->id,
                    'nurse_name'       => 'RN Gloria Mendoza',
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

            if ($a && $assessingDoctor) {
                MedicalHistory::create([
                    'visit_id'                   => $visit->id,
                    'patient_id'                 => $patient->id,
                    'doctor_id'                  => $assessingDoctor->id,
                    'chief_complaint'            => $v['cc'],
                    'history_of_present_illness' => $a['hpi'] ?? null,
                    'past_medical_history'       => $a['pmh'] ?? null,
                    'drug_allergies'             => $a['allergies'] ?? 'NKDA',
                    'pe_skin'                    => 'No rashes, no lesions noted',
                    'pe_head_eent'               => 'Anicteric sclerae, pink palpebral conjunctiva',
                    'pe_chest'                   => 'Symmetrical chest expansion',
                    'pe_lungs'                   => $a['pe_lungs']  ?? 'Clear breath sounds bilaterally',
                    'pe_cardiovascular'          => $a['pe_cvs']    ?? 'Adynamic precordium, RRR, no murmur',
                    'pe_abdomen'                 => $a['pe_abd']    ?? 'Soft, non-tender, non-distended',
                    'pe_extremities'             => 'No edema, capillary refill < 2 sec',
                    'admitting_impression'       => $a['impression'] ?? $a['diagnosis'],
                    'diagnosis'                  => $a['diagnosis'],
                    'differential_diagnosis'     => $a['ddx'] ?? null,
                    'plan'                       => $a['plan'],
                    'disposition'                => $disposition,
                    'service'                    => $admittedService,
                ]);
            }

            return $visit;
        };

        // ── TODAY ─────────────────────────────────────────────────────────────

        $p1 = $mkPatient(['family_name'=>'Villanueva','first_name'=>'Pedro','middle_name'=>'Gomez','birthday'=>'1960-08-30','sex'=>'Male','address'=>'Brgy. Pindangan, San Fernando, La Union','contact_number'=>'09611234561','occupation'=>'Retired','civil_status'=>'Widowed']);
        $mkVisit($p1,$clerkOpd,['type'=>'OPD','days_ago'=>0,'time'=>'11:30','cc'=>'Chest pain on exertion, shortness of breath','status'=>'registered']);

        $p2 = $mkPatient(['family_name'=>'Garcia','first_name'=>'Juan','middle_name'=>'Santos','birthday'=>'1985-03-14','sex'=>'Male','address'=>'Brgy. Poblacion, Agoo, La Union','contact_number'=>'09171234562','occupation'=>'Farmer','civil_status'=>'Married']);
        $mkVisit($p2,$clerkOpd,['type'=>'OPD','days_ago'=>0,'time'=>'08:30','cc'=>'Fever for 3 days, cough, difficulty breathing','status'=>'vitals_done','vitals'=>['temp'=>38.5,'pr'=>92,'rr'=>22,'bp'=>'130/80','o2'=>96,'wt'=>65.0,'pain'=>4]]);

        $p3 = $mkPatient(['family_name'=>'Santos','first_name'=>'Ligaya','birthday'=>'2018-04-17','sex'=>'Female','address'=>'Brgy. Catbangen, San Fernando, La Union','is_pedia'=>true]);
        $mkVisit($p3,$clerkOpd,['type'=>'OPD','days_ago'=>0,'time'=>'10:00','cc'=>'High grade fever 39°C, fine macular rashes, vomiting x3','status'=>'vitals_done','vitals'=>['temp'=>39.2,'pr'=>132,'rr'=>28,'bp'=>null,'o2'=>97,'wt'=>14.5,'pain'=>6]]);

        $p4 = $mkPatient(['family_name'=>'Navarro','first_name'=>'Rosario','middle_name'=>'Vera','birthday'=>'1945-02-25','sex'=>'Female','address'=>'Brgy. Dalumpinas Oeste, San Fernando, La Union','occupation'=>'Retired','civil_status'=>'Widowed']);
        $mkVisit($p4,$clerkEr,['type'=>'ER','days_ago'=>0,'time'=>'07:20','cc'=>'Sudden loss of consciousness, facial drooping, slurred speech','brought_by'=>'Family','condition'=>'Poor','status'=>'vitals_done','vitals'=>['temp'=>36.9,'pr'=>98,'rr'=>22,'bp'=>'185/110','o2'=>91,'wt'=>58.0,'pain'=>3]]);

        // ADMITTED PATIENTS — these get doctors orders, lab requests, nurses notes
        $p5 = $mkPatient(['family_name'=>'Dela Cruz','first_name'=>'Carlos','middle_name'=>'Bautista','birthday'=>'1990-11-05','sex'=>'Male','address'=>'Brgy. Lingsat, San Fernando, La Union','occupation'=>'Driver','civil_status'=>'Single']);
        $v5 = $mkVisit($p5,$clerkEr,['type'=>'ER','days_ago'=>0,'time'=>'06:45','cc'=>'MVA — chest pain, severe respiratory distress','brought_by'=>'Ambulance','condition'=>'Fair','status'=>'admitted','vitals'=>['temp'=>37.2,'pr'=>118,'rr'=>26,'bp'=>'88/60','o2'=>89,'wt'=>68.0,'pain'=>9],'assessment'=>['diagnosis'=>'Blunt chest trauma; Tension pneumothorax, right','hpi'=>'Patient was in a motorcycle accident. Progressive SOB after impact.','pmh'=>'No known prior illness','allergies'=>'NKDA','pe_lungs'=>'Absent breath sounds on the right, deviated trachea to left','pe_cvs'=>'Tachycardic, muffled heart sounds','pe_abd'=>'Soft, non-tender','impression'=>'Tension pneumothorax R side','ddx'=>'Hemothorax, cardiac tamponade','plan'=>'Emergency needle decompression → chest tube insertion; IVF PNSS 1L fast drip; O2 via NRM; Surgical consult','disposition'=>'Admitted','payment_class'=>'Charity','service'=>'Surgery']],$drDelaCruz);

        $p6 = $mkPatient(['family_name'=>'Reyes','first_name'=>'Maria','middle_name'=>'Cruz','birthday'=>'1975-07-22','sex'=>'Female','address'=>'Brgy. San Agustin, San Fernando, La Union','occupation'=>'Teacher','civil_status'=>'Married']);
        $mkVisit($p6,$clerkOpd,['type'=>'OPD','days_ago'=>0,'time'=>'09:15','cc'=>'Hypertension follow-up, persistent headache, blurring of vision','status'=>'discharged','vitals'=>['temp'=>36.8,'pr'=>78,'rr'=>16,'bp'=>'165/105','o2'=>98,'wt'=>72.0,'pain'=>3],'assessment'=>['diagnosis'=>'Hypertension Stage 2, uncontrolled','hpi'=>'Headache for 2 days. BP poorly controlled despite amlodipine 5mg OD.','pmh'=>'Known hypertensive x 5 years','allergies'=>'NKDA','plan'=>'Upstep amlodipine to 10mg OD; add losartan 50mg OD; low-salt diet; return in 2 weeks','disposition'=>'Discharged']],$drSantos);

        $p7 = $mkPatient(['family_name'=>'Aquino','first_name'=>'Rosa','middle_name'=>'Lim','birthday'=>'1995-01-20','sex'=>'Female','address'=>'Brgy. Pagdalagan Sur, Bauang, La Union','occupation'=>'Nurse Aide','civil_status'=>'Single']);
        $v7 = $mkVisit($p7,$clerkEr,['type'=>'ER','days_ago'=>1,'time'=>'14:00','cc'=>'Severe RLQ abdominal pain, nausea, vomiting, anorexia','brought_by'=>'Family','condition'=>'Fair','status'=>'admitted','vitals'=>['temp'=>38.2,'pr'=>104,'rr'=>18,'bp'=>'108/72','o2'=>99,'wt'=>52.0,'pain'=>8],'assessment'=>['diagnosis'=>'Acute appendicitis (Alvarado score 8/10)','hpi'=>"Periumbilical pain migrated to RLQ over 12 hours. McBurney's positive. Rovsing's sign positive.",'pmh'=>'No prior surgeries','allergies'=>'Penicillin (rash)','pe_abd'=>'Guarding and rigidity at RLQ; rebound tenderness positive','impression'=>'Acute appendicitis, non-perforated','ddx'=>'Ovarian cyst torsion, ectopic pregnancy','plan'=>'NPO; IVF D5LR 1L x 8h; Cefuroxime 1.5g IV q8h; Metronidazole 500mg IV q8h; Prep for laparoscopic appendectomy','disposition'=>'Admitted','payment_class'=>'Private','service'=>'Surgery','doctor'=>$drDelaCruz]],$drDelaCruz);

        $p8 = $mkPatient(['family_name'=>'Bautista','first_name'=>'Eduardo','middle_name'=>'Navarro','birthday'=>'1970-05-10','sex'=>'Male','address'=>'Brgy. Central, Bauang, La Union','occupation'=>'Fisherman','civil_status'=>'Married']);
        $mkVisit($p8,$clerkOpd,['type'=>'OPD','days_ago'=>1,'time'=>'09:00','cc'=>'Diabetes follow-up — polyuria, polydipsia, weight loss','status'=>'discharged','vitals'=>['temp'=>36.5,'pr'=>74,'rr'=>15,'bp'=>'126/82','o2'=>98,'wt'=>78.0,'pain'=>1],'assessment'=>['diagnosis'=>'Diabetes Mellitus Type 2, poorly controlled (FBS 15.2 mmol/L)','hpi'=>'Known T2DM x 3 years, non-compliant with metformin.','pmh'=>'DM Type 2 x 3 years','allergies'=>'NKDA','plan'=>'Metformin 1g BID (reinstate); add glimepiride 2mg OD; ADA diet counseling; HbA1c; return in 1 month','disposition'=>'Discharged']],$drSantos);

        $p9 = $mkPatient(['family_name'=>'Cruz','first_name'=>'Marites','middle_name'=>'Pascual','birthday'=>'1952-12-03','sex'=>'Female','address'=>'Brgy. Tococ, Agoo, La Union','occupation'=>'Housewife','civil_status'=>'Married']);
        $mkVisit($p9,$clerkOpd,['type'=>'OPD','days_ago'=>7,'time'=>'10:30','cc'=>'Productive cough x 3 weeks, night sweats, 8 lbs weight loss','status'=>'referred','vitals'=>['temp'=>37.8,'pr'=>88,'rr'=>20,'bp'=>'120/78','o2'=>93,'wt'=>42.0,'pain'=>2],'assessment'=>['diagnosis'=>'Pulmonary Tuberculosis, Bacteriologically Confirmed, Category I','hpi'=>'Cough for 3 weeks productive of yellowish sputum. Night sweats. Weight loss 8 lbs.','pmh'=>'No prior TB treatment','allergies'=>'NKDA','pe_lungs'=>'Coarse rales bilateral upper lobes','impression'=>'PTB Cat I','plan'=>'GeneXpert sputum; refer to DOTS center; RIPE regimen to start','disposition'=>'Referred']],$drSantos);

        $p10 = $mkPatient(['family_name'=>'Pascual','first_name'=>'Miguel','middle_name'=>'Aguilar','birthday'=>'2005-09-08','sex'=>'Male','address'=>'Brgy. Biday, San Fernando, La Union','occupation'=>'Student','civil_status'=>'Single']);
        $mkVisit($p10,$clerkEr,['type'=>'ER','days_ago'=>3,'time'=>'21:15','cc'=>'Generalized tonic-clonic seizure, 3 minutes duration — first episode','brought_by'=>'Family','condition'=>'Fair','status'=>'discharged','vitals'=>['temp'=>37.0,'pr'=>92,'rr'=>16,'bp'=>'118/76','o2'=>98,'wt'=>55.0,'pain'=>2],'assessment'=>['diagnosis'=>'New onset seizure, etiology to be determined','hpi'=>'Witnessed GTC seizure lasting 3 minutes at home. No prior episodes.','pmh'=>'No prior neurological illness','allergies'=>'NKDA','impression'=>'New onset seizure — r/o epilepsy','ddx'=>'Epilepsy, intracranial mass, metabolic disturbance','plan'=>'EEG; MRI brain with contrast; Phenytoin loading; neurology follow-up','disposition'=>'Discharged']],$drCastillo);

        $p11 = $mkPatient(['family_name'=>'Flores','first_name'=>'Andres','birthday'=>'2021-06-15','sex'=>'Male','address'=>'Brgy. Sevilla, San Fernando, La Union','is_pedia'=>true]);
        $v11 = $mkVisit($p11,$clerkOpd,['type'=>'OPD','days_ago'=>2,'time'=>'08:00','cc'=>'High fever, difficulty breathing, decreased feeding x 2 days','status'=>'admitted','vitals'=>['temp'=>39.6,'pr'=>148,'rr'=>52,'bp'=>null,'o2'=>93,'wt'=>10.2,'pain'=>7],'assessment'=>['diagnosis'=>'Severe Pneumonia (CAP-HR), 3-year-old male','hpi'=>'Fever 39°C for 2 days. Progressive difficulty breathing. Poor feeding. No rashes.','pmh'=>'No prior admissions. Vaccinations complete.','allergies'=>'NKDA','pe_lungs'=>'Subcostal and intercostal retractions; bilateral crackles on auscultation','impression'=>'CAP-HR (SARI)','plan'=>'Admit; O2 via face mask 2-3 LPM; IVF D5 0.3NaCl 900mL/day; Ampicillin 200mg/kg/day q6h','disposition'=>'Admitted','payment_class'=>'Charity','service'=>'Pediatrics']],$drReyes);

        $p12 = $mkPatient(['family_name'=>'Castillo','first_name'=>'Lourdes','middle_name'=>'Briones','birthday'=>'1993-03-22','sex'=>'Female','address'=>'Brgy. Poro, San Fernando, La Union','occupation'=>'Accountant','civil_status'=>'Married']);
        $v12 = $mkVisit($p12,$clerkOpd,['type'=>'OPD','days_ago'=>1,'time'=>'15:30','cc'=>'G2P1 34 weeks AOG — severe headache, blurring of vision, epigastric pain','status'=>'admitted','vitals'=>['temp'=>36.7,'pr'=>88,'rr'=>16,'bp'=>'160/110','o2'=>99,'wt'=>68.0,'pain'=>7],'assessment'=>['diagnosis'=>'Severe Pre-eclampsia, G2P1, 34 weeks AOG','hpi'=>'Severe headache, scotomata, epigastric pain. BP 160/110 on recumbent.','pmh'=>'No prior hypertension','allergies'=>'NKDA','pe_abd'=>'FH 34 cm; FHT 148 bpm; cephalic; no uterine contractions','impression'=>'Severe Pre-eclampsia','ddx'=>'Gestational hypertension, HELLP syndrome','plan'=>'Admit; Magnesium sulfate 4g IVP then 1g/hr; Hydralazine 10mg IVP PRN; NST; Betamethasone 12mg IM q24h x 2','disposition'=>'Admitted','payment_class'=>'Private','service'=>'OB-Gynecology','doctor'=>$drVillanueva]],$drVillanueva);

        // ── 8. Doctor's Orders for admitted patients ──────────────────────────
        // v5 — Chest trauma (Surgery, Dr. Dela Cruz)
        $orders5 = [
            'IVF PNSS 1L to run fast → then 1L x 8hr',
            'O2 4-6 LPM via non-rebreather mask; titrate to O2 sat >94%',
            'Morphine sulfate 2mg IV q4hr PRN severe pain; hold if RR <12',
            'Ketorolac 30mg IV q6hr (analgesic)',
            'Chest tube insertion right side — DONE; drainage system patent',
            'NPO x 8 hours — aspiration precaution',
            'Repeat CXR portable in 2 hours post chest tube insertion',
            'Surgical consult STAT',
            'CBC, ABG, chest X-ray, type & crossmatch NOW',
        ];
        $at5 = now()->subDays(0)->setTimeFromTimeString('08:00');
        foreach ($orders5 as $i => $text) {
            DoctorsOrder::create(['visit_id'=>$v5->id,'doctor_id'=>$drDelaCruz->id,'order_text'=>$text,'status'=> $i < 4 ? 'carried' : 'pending','order_date'=>$at5->copy()->addMinutes($i * 2),'is_completed'=> $i < 4,'completed_by'=> $i < 4 ? $nurseMendoza->id : null,'completed_at'=> $i < 4 ? $at5->copy()->addHours(1) : null]);
        }

        // v7 — Acute appendicitis (Surgery, Dr. Dela Cruz)
        $orders7 = [
            'IVF D5LR 1L x 8 hours',
            'NPO strict — patient for OR',
            'Cefuroxime 1.5g IV q8h (start now)',
            'Metronidazole 500mg IV q8h (start now)',
            'Mefenamic acid 500mg cap q8h PRN mild pain only',
            'Paracetamol 1g IV q6h for temperature >38.5°C',
            'Monitor BP, PR, RR, temp q4h',
            'Insert Foley catheter — pre-op',
            'CBC, serum electrolytes, urinalysis, PT-PTT, blood typing STAT',
            'Consent for laparoscopic appendectomy — SIGNED',
        ];
        $at7 = now()->subDays(1)->setTimeFromTimeString('15:00');
        foreach ($orders7 as $i => $text) {
            DoctorsOrder::create(['visit_id'=>$v7->id,'doctor_id'=>$drDelaCruz->id,'order_text'=>$text,'status'=> $i < 6 ? 'carried' : 'pending','order_date'=>$at7->copy()->addMinutes($i * 3),'is_completed'=> $i < 6,'completed_by'=> $i < 6 ? $nurseRamos->id : null,'completed_at'=> $i < 6 ? $at7->copy()->addHours(1) : null]);
        }

        // v11 — Pedia pneumonia (Peds, Dr. Reyes)
        $orders11 = [
            'IVF D5 0.3NaCl at 37.5 mL/hr (maintenance per Holliday-Segar)',
            'O2 via face mask 2 LPM; maintain O2 sat >94%',
            'Ampicillin 500mg IV q6h (200mg/kg/day)',
            'Chloramphenicol 250mg IV q6h (75mg/kg/day)',
            'Paracetamol 130mg (10mg/kg/dose) q6h PRN temp >38°C',
            'Monitor temp, PR, RR, O2 sat q2h',
            'Weigh daily — record I&O strictly',
            'Chest X-ray PA portable daily x 3 days',
            'CBC with differential, Blood culture (aerobic) NOW',
        ];
        $at11 = now()->subDays(2)->setTimeFromTimeString('09:00');
        foreach ($orders11 as $i => $text) {
            DoctorsOrder::create(['visit_id'=>$v11->id,'doctor_id'=>$drReyes->id,'order_text'=>$text,'status'=> $i < 5 ? 'carried' : 'pending','order_date'=>$at11->copy()->addMinutes($i * 2),'is_completed'=> $i < 5,'completed_by'=> $i < 5 ? $nurseMendoza->id : null,'completed_at'=> $i < 5 ? $at11->copy()->addHours(1) : null]);
        }

        // v12 — Severe pre-eclampsia (OB, Dr. Villanueva)
        $orders12 = [
            'IVF D5LR 1L x 12h (basal fluid)',
            'MgSO4 4g in 250mL PNSS to run in 15 min (loading) — DONE',
            'MgSO4 maintenance: 1g/hr via infusion pump — RUNNING',
            'Hydralazine 10mg IV push PRN for BP >160/110 — give if BP spikes',
            'Betamethasone 12mg IM now (1st dose) — DONE',
            'Betamethasone 12mg IM after 24h (2nd dose) — DUE',
            'Monitor BP q15min x 1hr, then q30min',
            'Insert Foley catheter; measure urine output hourly (minimum 30 mL/hr)',
            'NST now and q6h',
            'CBC, LFT, creatinine, uric acid, UA with microscopy STAT',
            'OB ultrasound with BPP TODAY',
            'Nothing by mouth — for possible CS',
        ];
        $at12 = now()->subDays(1)->setTimeFromTimeString('16:00');
        foreach ($orders12 as $i => $text) {
            DoctorsOrder::create(['visit_id'=>$v12->id,'doctor_id'=>$drVillanueva->id,'order_text'=>$text,'status'=> $i < 7 ? 'carried' : 'pending','order_date'=>$at12->copy()->addMinutes($i * 3),'is_completed'=> $i < 7,'completed_by'=> $i < 7 ? $nurseRamos->id : null,'completed_at'=> $i < 7 ? $at12->copy()->addHours(2) : null]);
        }

        // ── 9. Nurses' Notes (SOAP) ───────────────────────────────────────────
        // v5 — Chest trauma
        NursesNote::create(['visit_id'=>$v5->id,'nurse_id'=>$nurseMendoza->id,'subjective'=>"Patient c/o 9/10 chest pain on the right side. States pain worsens with breathing. Very anxious and restless.",'objective'=>"BP 88/60 mmHg, PR 118 bpm, RR 26/min, Temp 37.2°C, O2 sat 89% on room air. Chest tube draining serosanguineous fluid, 280 mL since insertion. Absent breath sounds right side.",'assessment'=>"Severe acute pain related to chest trauma and pneumothorax. Impaired gas exchange related to altered lung expansion.",'plan'=>"Morphine 2mg IV administered as ordered. Repositioned to semi-Fowler's. O2 NRM maintained. Chest tube drainage monitored and documented. Notified Dr. Dela Cruz of O2 saturation. Safety rails up.",'noted_at'=>now()->subDays(0)->setTimeFromTimeString('09:30')]);

        NursesNote::create(['visit_id'=>$v5->id,'nurse_id'=>$nurseRamos->id,'subjective'=>"Patient reports pain reduced to 5/10 after morphine. Less anxious. Still complaining of difficulty breathing.",'objective'=>"BP 102/68, PR 94, RR 20/min, O2 sat 95% on NRM 6 LPM. Chest tube draining well — 380 mL total output. Repeat CXR shows re-expansion of right lung.",'assessment'=>"Improving gas exchange. Pain partially controlled. Hemodynamically improving.",'plan'=>"Continue current orders. O2 titrated to 4 LPM (O2 sat 95%). Scheduled for CBC repeat at 2pm. Endorsed to oncoming shift.",'noted_at'=>now()->subDays(0)->setTimeFromTimeString('14:00')]);

        // v7 — Appendicitis
        NursesNote::create(['visit_id'=>$v7->id,'nurse_id'=>$nurseRamos->id,'subjective'=>"Patient c/o 8/10 abdominal pain, RLQ. Nauseated. Refused to eat or drink. Very worried about the surgery.",'objective'=>"BP 108/72, PR 104 bpm, Temp 38.2°C, RR 18, O2 sat 99%. Abdomen rigid at RLQ with guarding. IVF D5LR infusing at 125 mL/hr. Cefuroxime and metronidazole started.",'assessment'=>"Acute pain related to appendiceal inflammation. Anxiety related to impending surgery. Risk for infection.",'plan'=>"Mefenamic acid deferred (pain >5/10 — scheduled OR soon). NPO reinforced. Consent verification completed. Surgical team notified patient is ready for OR prep. IV antibiotics on schedule.",'noted_at'=>now()->subDays(1)->setTimeFromTimeString('16:30')]);

        // v11 — Pedia pneumonia
        NursesNote::create(['visit_id'=>$v11->id,'nurse_id'=>$nurseMendoza->id,'subjective'=>"Mother reports child is still febrile, not eating, and very tired. Child is crying but consolable. Cough persistent.",'objective'=>"Temp 39.1°C, PR 148, RR 52/min, O2 sat 93% on 2 LPM O2. Subcostal retractions noted. Bilateral crackles audible on auscultation. Weight 10.2 kg. IVF running at 37.5 mL/hr. 1st dose Ampicillin and Chloramphenicol given.",'assessment'=>"Impaired gas exchange related to alveolar consolidation. Hyperthermia related to infectious process. Imbalanced nutrition: less than body requirements.",'plan'=>"Paracetamol 130mg given at 38.5°C — temp decreased to 38.1°C after 45 min. O2 maintained. Fluid intake and output strictly monitored (input 425 mL / output 180 mL urine). Blood culture drawn. Mother educated on isolation precautions.",'noted_at'=>now()->subDays(2)->setTimeFromTimeString('10:00')]);

        // v12 — Pre-eclampsia
        NursesNote::create(['visit_id'=>$v12->id,'nurse_id'=>$nurseRamos->id,'subjective'=>"Patient c/o severe headache 7/10, blurring of vision, and epigastric pain. States she is scared for her baby.",'objective'=>"BP 162/108 mmHg, PR 88 bpm, RR 16/min, O2 sat 99%. Urine output 45 mL/hr (adequate). MgSO4 loading completed; maintenance infusion at 1g/hr — running via infusion pump. Reflexes 2+ (normal). No clonus. FHT 148 bpm on NST.",'assessment'=>"Risk for maternal seizure related to pre-eclampsia. Anxiety related to preterm risk. Altered peripheral tissue perfusion.",'plan'=>"MgSO4 maintenance per order. BP remains at 160/108 — Hydralazine 10mg IVP administered as per PRN order. Repeat BP in 15 min. Betamethasone 1st dose given. Continuous EFM ongoing. OB ultrasound ordered for today.",'noted_at'=>now()->subDays(1)->setTimeFromTimeString('17:00')]);

        // ── 10. Lab Requests ──────────────────────────────────────────────────
        $labReq5 = LabRequest::create([
            'request_no'           => LabRequest::generateRequestNo(),
            'visit_id'             => $v5->id,
            'patient_id'           => $p5->id,
            'doctor_id'            => $drDelaCruz->id,
            'submitted_by'         => $drDelaCruz->id,
            'ward'                 => 'Surgery / ICU',
            'request_type'         => 'stat',
            'clinical_diagnosis'   => 'Tension pneumothorax; Blunt chest trauma',
            'requesting_physician' => 'Dr. Jose Dela Cruz',
            'tests'                => ['Complete Blood Count (CBC)', 'ABG (Arterial Blood Gas)', 'Prothrombin Time (PT-PA)', 'Blood Typing', 'Crossmatching', 'Serum Electrolytes'],
            'date_requested'       => today(),
            'status'               => 'pending',
        ]);

        $labReq7 = LabRequest::create([
            'request_no'           => LabRequest::generateRequestNo(),
            'visit_id'             => $v7->id,
            'patient_id'           => $p7->id,
            'doctor_id'            => $drDelaCruz->id,
            'submitted_by'         => $drDelaCruz->id,
            'ward'                 => 'Surgical Ward Rm 204',
            'request_type'         => 'routine',
            'clinical_diagnosis'   => 'Acute appendicitis (Alvarado score 8/10)',
            'requesting_physician' => 'Dr. Jose Dela Cruz',
            'tests'                => ['Complete Blood Count (CBC)', 'Routine Urinalysis', 'Total Protein', 'Albumin', 'Prothrombin Time (PT-PA)', 'APTT', 'Blood Typing'],
            'date_requested'       => today()->subDay(),
            'status'               => 'pending',
        ]);

        $labReq11 = LabRequest::create([
            'request_no'           => LabRequest::generateRequestNo(),
            'visit_id'             => $v11->id,
            'patient_id'           => $p11->id,
            'doctor_id'            => $drReyes->id,
            'submitted_by'         => $drReyes->id,
            'ward'                 => 'Pedia Ward',
            'request_type'         => 'stat',
            'clinical_diagnosis'   => 'Severe Pneumonia (CAP-HR)',
            'requesting_physician' => 'Dr. Maria Reyes',
            'tests'                => ['Complete Blood Count (CBC)', 'C-Reactive Protein — Qualitative', 'Blood Typing'],
            'specimen'             => 'Blood (EDTA and plain) · Sputum (blood culture)',
            'date_requested'       => today()->subDays(2),
            'status'               => 'pending',
        ]);

        $labReq12 = LabRequest::create([
            'request_no'           => LabRequest::generateRequestNo(),
            'visit_id'             => $v12->id,
            'patient_id'           => $p12->id,
            'doctor_id'            => $drVillanueva->id,
            'submitted_by'         => $drVillanueva->id,
            'ward'                 => 'OB Ward Rm 312',
            'request_type'         => 'stat',
            'clinical_diagnosis'   => 'Severe Pre-eclampsia, G2P1, 34 wks AOG',
            'requesting_physician' => 'Dr. Ana Villanueva',
            'tests'                => ['Complete Blood Count (CBC)', 'AST / SGOT', 'ALT / SGPT', 'Total Bilirubin', 'Creatinine', 'Uric Acid', 'Routine Urinalysis', 'Urine Protein (semi-quantitative)'],
            'date_requested'       => today()->subDay(),
            'status'               => 'pending',
        ]);

        // ── 11. Radiology Requests ────────────────────────────────────────────
        $radReq5 = RadiologyRequest::create([
            'request_no'          => RadiologyRequest::generateRequestNo(),
            'visit_id'            => $v5->id,
            'patient_id'          => $p5->id,
            'doctor_id'           => $drDelaCruz->id,
            'submitted_by'        => $drDelaCruz->id,
            'modality'            => 'X-RAY',
            'source'              => 'ER',
            'ward'                => 'Surgery / ICU',
            'examination_desired' => 'Chest X-Ray Portable AP (post chest tube insertion)',
            'clinical_diagnosis'  => 'Tension pneumothorax, right; post needle decompression and chest tube insertion',
            'clinical_findings'   => 'Absent breath sounds right side. Tracheal deviation to left. Chest tube in situ right side.',
            'requesting_physician'=> 'Dr. Jose Dela Cruz',
            'date_requested'      => today(),
            'status'              => 'pending',
        ]);

        $radReq11 = RadiologyRequest::create([
            'request_no'          => RadiologyRequest::generateRequestNo(),
            'visit_id'            => $v11->id,
            'patient_id'          => $p11->id,
            'doctor_id'           => $drReyes->id,
            'submitted_by'        => $drReyes->id,
            'modality'            => 'X-RAY',
            'source'              => 'OPD',
            'ward'                => 'Pedia Ward',
            'examination_desired' => 'Chest X-Ray PA (portable)',
            'clinical_diagnosis'  => 'Severe Pneumonia (CAP-HR), 3y/o male',
            'clinical_findings'   => 'Subcostal and intercostal retractions. Bilateral crackles on auscultation. Fever 39.6°C, RR 52/min, O2 sat 93% on 2 LPM O2.',
            'requesting_physician'=> 'Dr. Maria Reyes',
            'date_requested'      => today()->subDays(2),
            'status'              => 'pending',
        ]);

        $radReq12 = RadiologyRequest::create([
            'request_no'          => RadiologyRequest::generateRequestNo(),
            'visit_id'            => $v12->id,
            'patient_id'          => $p12->id,
            'doctor_id'           => $drVillanueva->id,
            'submitted_by'        => $drVillanueva->id,
            'modality'            => 'ULTRASOUND',
            'source'              => 'OPD',
            'ward'                => 'OB Ward Rm 312',
            'examination_desired' => 'Obstetric Ultrasound with Biophysical Profile (BPP)',
            'clinical_diagnosis'  => 'Severe Pre-eclampsia, G2P1, 34 weeks AOG',
            'clinical_findings'   => 'FHT 148 bpm. FH 34 cm. Cephalic presentation. No uterine contractions. Patient on MgSO4 therapy.',
            'requesting_physician'=> 'Dr. Ana Villanueva',
            'date_requested'      => today()->subDay(),
            'status'              => 'pending',
        ]);

        // ── 12. Patient portal users ──────────────────────────────────────────
        $userAli = User::create(['name'=>'Maria Reyes','email'=>'juan@lumc.gov.ph','password'=>Hash::make('password'),'panel'=>'patient','is_active'=>true,'patient_id'=>$p6->id]);
        $userAli->assignRole('patient');

        $userPatient = User::create(['name'=>'Eduardo Bautista','email'=>'patient@lumc.gov.ph','password'=>Hash::make('password'),'panel'=>'patient','is_active'=>true,'patient_id'=>$p8->id]);
        $userPatient->assignRole('patient');

        // ── Console output ────────────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('✅  LUMC database seeded successfully!');
        $this->command->info('');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',                    'admin@lumc.gov.ph',       'password'],
                ['Doctor (Internal Med)',    'doctor@lumc.gov.ph',      'password'],
                ['Doctor (Internal Med)',    'rsantos@lumc.gov.ph',     'password'],
                ['Doctor (Pediatrics)',      'mreyes@lumc.gov.ph',      'password'],
                ['Doctor (Surgery)',         'jdelacruz@lumc.gov.ph',   'password'],
                ['Doctor (OB-Gyne)',         'avillanueva@lumc.gov.ph', 'password'],
                ['Doctor (Neurology)',       'rcastillo@lumc.gov.ph',   'password'],
                ['Nurse',                    'nurse@lumc.gov.ph',       'password'],
                ['Nurse (Gloria Mendoza)',   'gmendoza@lumc.gov.ph',    'password'],
                ['Clerk OPD',                'clerk@lumc.gov.ph',       'password'],
                ['Clerk ER',                 'clerk-er@lumc.gov.ph',    'password'],
                ['MedTech',                  'medtech@lumc.gov.ph',     'password'],
                ['RadTech',                  'radtech@lumc.gov.ph',     'password'],
                ['Tech (generic)',           'tech@lumc.gov.ph',        'password'],
                ['Patient (Maria Reyes)',    'juan@lumc.gov.ph',        'password'],
                ['Patient (E. Bautista)',    'patient@lumc.gov.ph',     'password'],
            ]
        );
        $this->command->info('  4 admitted patients · 4 lab requests · 3 radiology requests · SOAP notes seeded');
        $this->command->info('');
    }
}