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
            'phone' => ['required'],
            'gender' => ['required'],
            'doctor_id' => ['required','exists:doctors,id']
        ]);


        $patient = Patient::where('id', $request->id)
                     ->update([
                        'name' => $request->name,
                        'doctor_id' => $request->doctor_id,
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

        return Redirect::route('patient.all')->with('success', __('sentence.Patient Created Successfully'));
    }


    public function view($id)
    {
        $patient = Patient::findOrfail($id);
        $appointments = Appointment::where('patient_id', $id)->OrderBy('id', 'Desc')->get();
        $invoices = Billing::where('patient_id', $id)->OrderBy('id', 'Desc')->get();
        return view('patient.view', ['patient' => $patient, 'appointments' => $appointments, 'invoices' => $invoices]);
    }
}
