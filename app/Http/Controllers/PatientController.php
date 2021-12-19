<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Prescription;
use App\Appointment;
use App\Billing;
use Hash;
use Redirect;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{

	public function __construct(){
        $this->middleware('auth');
    }


    public function all(){

    	$patients = Patient::all();

    	return view('patient.all', ['patients' => $patients]);

    }

    public function create(){
    	return view('patient.create');
    }

    public function edit($id){
    	$patient = Patient::find($id);
    	return view('patient.edit',['patient' => $patient]);
    }

    public function store_edit(Request $request){

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

    public function store(Request $request){

    	$validatedData = $request->validate([
        	'name' => ['required', 'string', 'max:255'],
            'phone' => ['required'],
            'gender' => ['required'],
    	]);


		$patient = new Patient();
		$patient->name = $request->name;
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


    public function view($id){

    	$patient = Patient::findOrfail($id);
        $prescriptions = Prescription::where('patient_id' ,$id)->OrderBy('id','Desc')->get();
        $appointments = Appointment::where('patient_id' ,$id)->OrderBy('id','Desc')->get();
        $invoices = Billing::where('patient_id' ,$id)->OrderBy('id','Desc')->get();
    	return view('patient.view', ['patient' => $patient, 'prescriptions' => $prescriptions, 'appointments' => $appointments, 'invoices' => $invoices]);

    }


}
