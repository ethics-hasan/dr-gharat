<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Appointment;
use App\Billing;
use App\Doctor;
use App\Xray;
use App\Sonography;
use App\BloodTest;
use App\PatientXray;
use App\PatientSonography;
use App\PatientBloodTest;

use Hash;
use Redirect;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function all()
    {
        $patients = Patient::all();

        return view('patient.all', ['patients' => $patients]);
    }

    public function create()
    {
        $doctors = Doctor::all();
        $xrays = Xray::all();
        $sonographies = Sonography::all();
        $blood_tests = BloodTest::all();
        
        return view('patient.create', [
            'doctors' => $doctors,
            'xrays' => $xrays,
            'sonographies' => $sonographies,
            'blood_tests' => $blood_tests
        ]);
    }

    public function edit($id)
    {
        $patient = Patient::find($id);
        $doctors = Doctor::all();

        return view('patient.edit', ['patient' => $patient, 'doctors' => $doctors]);
    }

    public function store_edit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'min:10'],
            'gender' => ['required'],
            'doctor_id' => ['required']
        ]);


        $patient = Patient::where('id', $request->id)
                     ->update([
                        'name' => $request->name,
                        'doctor_id' => $request->doctor_id ? $request->doctor_id : NULL,
                        'birthday' => $request->birthday,
                        'phone' => $request->phone,
                        'gender' => $request->gender,
                        'marital_status' => $request->marital_status,
                        'blood' => $request->blood,
                        'address' => $request->address,
                        'history' => $request->history,
                        'reason' => $request->reason
                    ]);




        return Redirect::back()->with('success', __('sentence.Patient Updated Successfully'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'min:10'],
            'gender' => ['required'],
            'doctor_id' => ['required']
        ]);


        $patient = new Patient();
        $patient->name = $request->name;
        $patient->doctor_id = $request->doctor_id ? $request->doctor_id : NULL;
        $patient->birthday = $request->birthday;
        $patient->phone = $request->phone;
        $patient->gender = $request->gender;
        $patient->marital_status = $request->marital_status;
        $patient->blood = $request->blood;
        $patient->address = $request->address;
        $patient->history = $request->history;
        $patient->reason = $request->reason;
        $patient->save();

        // Add Patient Xray
        $xray_count = 0;
        if ($request->xray_id) {
            $xray_count = count($request->xray_id);
        }
        for ($x = 0; $x < $xray_count; $x++) {
            $patient_xray = new PatientXray();
        
            $patient_xray->patient_id = $patient->id;
            $patient_xray->xray_id = $request->xray_id[$x];
        
            $patient_xray->save();
        }
        
        // Add Patient Sonography
        $sonography_count = 0;
        if ($request->sonography_id) {
            $sonography_count = count($request->sonography_id);
        }
        for ($x = 0; $x < $sonography_count; $x++) {
            $patient_sonography = new PatientSonography();
        
            $patient_sonography->patient_id = $patient->id;
            $patient_sonography->sonography_id = $request->sonography_id[$x];
        
            $patient_sonography->save();
        }
        
        // Add Patient Blood Test
        $blood_test_count = 0;
        if ($request->blood_test_id) {
            $blood_test_count = count($request->blood_test_id);
        }
        for ($x = 0; $x < $blood_test_count; $x++) {
            $patient_blood_test = new PatientBloodTest();
        
            $patient_blood_test->patient_id = $patient->id;
            $patient_blood_test->blood_test_id = $request->blood_test_id[$x];
        
            $patient_blood_test->save();
        }
        
        return Redirect::route('patient.all')->with('success', __('sentence.Patient Created Successfully'));
    }


    public function view($id)
    {
        $patient = Patient::findOrfail($id);
        $appointments = Appointment::where('patient_id', $id)->OrderBy('id', 'Desc')->get();
        $invoices = Billing::where('patient_id', $id)->OrderBy('id', 'Desc')->get();

        if ($patient->doctor_id) {
            $doctor = Doctor::findOrfail($patient->doctor_id);
            $patient->referred_doctor = $doctor->name;
        }

        return view('patient.view', ['patient' => $patient, 'appointments' => $appointments, 'invoices' => $invoices]);
    }
}
