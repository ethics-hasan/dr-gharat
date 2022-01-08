<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Prescription;
use App\Appointment;
use App\Billing;
use App\Doctor;
use App\Xray;
use App\Sonography;
use App\BloodTest;
use App\PatientTreatment;

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
        return view('patient.edit', ['patient' => $patient]);
    }

    public function store_edit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required'],
            'gender' => ['required'],

        ]);


        $patient = Patient::where('id', $request->id)
                     ->update([
                        'name' => $request->name,
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
            'phone' => ['required'],
            'gender' => ['required'],
            'doctor_id' => ['required','exists:doctors,id']
        ]);


        $patient = new Patient();
        $patient->name = $request->name;
        $patient->doctor_id = $request->doctor_id;
        $patient->birthday = $request->birthday;
        $patient->phone = $request->phone;
        $patient->gender = $request->gender;
        $patient->marital_status = $request->marital_status;
        $patient->blood = $request->blood;
        $patient->address = $request->address;
        $patient->history = $request->history;
        $patient->reason = $request->reason;
        $patient->save();

        $i = 0;
        if ($request->xray_id) {
            $i = count($request->xray_id);
        }

        for ($x = 0; $x < $i; $x++) {
            $patient_treatment = new PatientTreatment();

            $patient_treatment->xray_id = $request->xray_id[$x] ? $request->xray_id[$x] :  NULL;
            $patient_treatment->sonography_id = $request->sonography_id[$x] ? $request->sonography_id[$x] : NULL;
            $patient_treatment->blood_test_id = $request->blood_test_id[$x] ? $request->blood_test_id[$x] : NULL;
            $patient_treatment->patient_id = $patient->id;

            $patient_treatment->save();
        }

        return Redirect::route('patient.all')->with('success', __('sentence.Patient Created Successfully'));
    }


    public function view($id)
    {
        $patient = Patient::findOrfail($id);
        $prescriptions = Prescription::where('patient_id', $id)->OrderBy('id', 'Desc')->get();
        $appointments = Appointment::where('patient_id', $id)->OrderBy('id', 'Desc')->get();
        $invoices = Billing::where('patient_id', $id)->OrderBy('id', 'Desc')->get();
        return view('patient.view', ['patient' => $patient, 'prescriptions' => $prescriptions, 'appointments' => $appointments, 'invoices' => $invoices]);
    }
}
