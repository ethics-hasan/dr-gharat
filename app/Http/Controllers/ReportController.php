<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Doctor;
use App\Xray;
use App\Sonography;
use App\BloodTest;
use App\PatientXray;
use App\PatientSonography;
use App\PatientBloodTest;

use Illuminate\Http\Request;
use Redirect;
use DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            $patients = Patient::where('doctor_id', $doctor->id)->get();
            $doctor->patient_count = $patients->count();

            $doctor->xray_count = 0;
            $doctor->sonography_count = 0;
            $doctor->blood_test_count = 0;

            $doctor->total_amount = 0;

            foreach ($patients as $patient) {
                $doctor->xray_count += PatientXray::where('patient_id', $patient->id)->count();
                $doctor->sonography_count += PatientSonography::where('patient_id', $patient->id)->count();
                $doctor->blood_test_count += PatientBloodTest::where('patient_id', $patient->id)->count();


                $doctor->total_amount += DB::table('patient_xrays')
                                        ->select('xrays.amount')
                                        ->join('xrays', 'xrays.id', '=', 'patient_xrays.xray_id')
                                        ->where('patient_xrays.patient_id', $patient->id)
                                        ->sum('xrays.amount');
                            
                $doctor->total_amount += DB::table('patient_sonographies')
                                        ->select('sonographies.amount')
                                        ->join('sonographies', 'sonographies.id', '=', 'patient_sonographies.sonography_id')
                                        ->where('patient_sonographies.patient_id', $patient->id)
                                        ->sum('sonographies.amount');
                
                $doctor->total_amount += DB::table('patient_blood_tests')
                                        ->select('blood_tests.amount')
                                        ->join('blood_tests', 'blood_tests.id', '=', 'patient_blood_tests.blood_test_id')
                                        ->where('patient_blood_tests.patient_id', $patient->id)
                                        ->sum('blood_tests.amount');
            }
        }

        return view('report.all', ['doctors' => $doctors]);
    }


    
}
