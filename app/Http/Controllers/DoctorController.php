<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use App\Doctor;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    //
    public function create()
    {
        return view('doctor.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required'
        ]);

        $doctor = Doctor::updateOrCreate(
            [
                'name' => $request->name,
                'clinic_name' => $request->clinic_name,
                'phone' => $request->phone,
                'address' => $request->address
            ]
        );

        return Redirect::route('doctor.all')->with('success', __('sentence.Doctor Added Successfully'));
    }

    public function all()
    {
        $doctors = Doctor::all();

        return view('doctor.all', ['doctors' => $doctors]);
    }


    public function edit($id)
    {
        $doctor = Doctor::find($id);
        return view('doctor.edit', ['doctor' => $doctor]);
    }

    public function store_edit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $doctor = Doctor::find($request->doctor_id);

        $doctor->name = $request->name;
        $doctor->phone = $request->phone;
        $doctor->clinic_name = $request->clinic_name;
        $doctor->address = $request->address;

        $doctor->save();

        return Redirect::route('doctor.all')->with('success', __('sentence.Doctor Edited Successfully'));
    }

    public function destroy($id)
    {
        Doctor::destroy($id);
        return Redirect::route('doctor.all')->with('success', __('sentence.Doctor Deleted Successfully'));
    }
}
