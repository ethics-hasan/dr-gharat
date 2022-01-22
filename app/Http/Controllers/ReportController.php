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
use PDF;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getReportData($year = NULL, $month = NULL, $date = NULL)
    {
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            $patients_query = Patient::where('doctor_id', $doctor->id);
            if ($year) {
                $patients_query->whereYear('created_at', $year);
            }
            if ($month) {
                $patients_query->whereMonth('created_at', $month);
            }
            if ($date) {
                $patients_query->whereDate('created_at', $date);
            }

            $patients = $patients_query->get();                 
            $doctor->patient_count = $patients->count();

            $doctor->xray_count = 0;
            $doctor->sonography_count = 0;
            $doctor->blood_test_count = 0;

            $doctor->total_amount = 0;

            $all_patients = Patient::where('doctor_id', $doctor->id)->get();

            foreach ($all_patients as $patient) {
                $doctor_xray_count_query = PatientXray::where('patient_id', $patient->id);
                $doctor_sonography_count_query = PatientSonography::where('patient_id', $patient->id);
                $doctor_blood_test_count_query = PatientBloodTest::where('patient_id', $patient->id);

                $xray_total_amount_query = DB::table('patient_xrays')
                                        ->select('xrays.amount')
                                        ->join('xrays', 'xrays.id', '=', 'patient_xrays.xray_id')
                                        ->where('patient_xrays.patient_id', $patient->id);
                            
                $sonography_total_amount_query = DB::table('patient_sonographies')
                                        ->select('sonographies.amount')
                                        ->join('sonographies', 'sonographies.id', '=', 'patient_sonographies.sonography_id')
                                        ->where('patient_sonographies.patient_id', $patient->id);
                
                $blood_test_total_amount_query = DB::table('patient_blood_tests')
                                        ->select('blood_tests.amount')
                                        ->join('blood_tests', 'blood_tests.id', '=', 'patient_blood_tests.blood_test_id')
                                        ->where('patient_blood_tests.patient_id', $patient->id);

                if ($year) {
                    $doctor_xray_count_query->whereYear('created_at', $year);
                    $doctor_sonography_count_query->whereYear('created_at', $year);
                    $doctor_blood_test_count_query->whereYear('created_at', $year);
                    $xray_total_amount_query->whereYear('patient_xrays.created_at', $year);
                    $sonography_total_amount_query->whereYear('patient_sonographies.created_at', $year);
                    $blood_test_total_amount_query->whereYear('patient_blood_tests.created_at', $year);
                }
                if ($month) {
                    $doctor_xray_count_query->whereMonth('created_at', $month);
                    $doctor_sonography_count_query->whereMonth('created_at', $month);
                    $doctor_blood_test_count_query->whereMonth('created_at', $month);
                    $xray_total_amount_query->whereMonth('patient_xrays.created_at', $month);
                    $sonography_total_amount_query->whereMonth('patient_sonographies.created_at', $month);
                    $blood_test_total_amount_query->whereMonth('patient_blood_tests.created_at', $month);
                }
                if ($date) {
                    $doctor_xray_count_query->whereDate('created_at', $date);
                    $doctor_sonography_count_query->whereDate('created_at', $date);
                    $doctor_blood_test_count_query->whereDate('created_at', $date);
                    $xray_total_amount_query->whereDate('patient_xrays.created_at', $date);
                    $sonography_total_amount_query->whereDate('patient_sonographies.created_at', $date);
                    $blood_test_total_amount_query->whereDate('patient_blood_tests.created_at', $date);
                }
                            
                $doctor->xray_count += $doctor_xray_count_query->count();
                $doctor->sonography_count += $doctor_sonography_count_query->count();
                $doctor->blood_test_count += $doctor_blood_test_count_query->count();
                        
                $doctor->total_amount += $xray_total_amount_query->sum('xrays.amount');
                $doctor->total_amount += $sonography_total_amount_query->sum('sonographies.amount');
                $doctor->total_amount += $blood_test_total_amount_query->sum('blood_tests.amount');
            }
        }

        return [
            'doctors' => $doctors,
            'year' => $year,
            'month' => $month,
            'date' => $date
        ];
    }

    public function all()
    {
        $report_data = $this->getReportData(date("Y"), date("m"));
        return view('report.all', [
            'doctors' => $report_data['doctors'],
            'year' => $report_data['year'],
            'month' => $report_data['month'],
            'date' => $report_data['date']
        ]);
    }

    public function filter(Request $request)
    {
        $report_data = $this->getReportData($request->year, $request->month, $request->date);
        return view('report.all', [
            'doctors' => $report_data['doctors'],
            'year' => $report_data['year'],
            'month' => $report_data['month'],
            'date' => $report_data['date']
        ]);
    }
    
    public function pdf($id)
    {
        $doctor = Doctor::findOrfail($id);
        
        $doctor_xrays = DB::table('xrays')
                        ->select('*', 'xrays.name as xray_name', 'patients.name as patient_name')
                        ->join('patient_xrays', 'xrays.id', '=', 'patient_xrays.xray_id')
                        ->join('patients', 'patients.id', '=', 'patient_xrays.patient_id')
                        ->where('patients.doctor_id', $doctor->id)
                        ->get();
                        
        $doctor_sonography = DB::table('sonographies')
                        ->select('*', 'sonographies.name as sonography_name', 'patients.name as patient_name')
                        ->join('patient_sonographies', 'sonographies.id', '=', 'patient_sonographies.sonography_id')
                        ->join('patients', 'patients.id', '=', 'patient_sonographies.patient_id')
                        ->where('patients.doctor_id', $doctor->id)
                        ->get();
        
        $doctor_blood_tests = DB::table('blood_tests')
                        ->select('*', 'blood_tests.name as blood_test_name', 'patients.name as patient_name')
                        ->join('patient_blood_tests', 'blood_tests.id', '=', 'patient_blood_tests.blood_test_id')
                        ->join('patients', 'patients.id', '=', 'patient_blood_tests.patient_id')
                        ->where('patients.doctor_id', $doctor->id)
                        ->get();
        
        $doctor->total_amount = 0;
        foreach ($doctor_xrays as $xray) {
            $doctor->total_amount += $xray->amount;
        }
        foreach ($doctor_sonography as $sonography) {
            $doctor->total_amount += $sonography->amount;
        }
        foreach ($doctor_blood_tests as $blood_test) {
            $doctor->total_amount += $blood_test->amount;
        }

        // To view pdf template
        // return view('report.pdf', [
        //     'doctor' => $doctor, 
        //     'doctor_xrays' => $doctor_xrays, 
        //     'doctor_sonography' => $doctor_sonography,
        //     'doctor_blood_tests' => $doctor_blood_tests,
        // ]);

        $pdf = PDF::loadView('report.pdf', [
            'doctor' => $doctor, 
            'doctor_xrays' => $doctor_xrays, 
            'doctor_sonography' => $doctor_sonography,
            'doctor_blood_tests' => $doctor_blood_tests,
        ]);

        return $pdf->download($doctor->name.'.pdf');
    }
}
