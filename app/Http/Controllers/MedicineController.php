<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medicine;
use Redirect;

class MedicineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    //
    public function create()
    {
        return view('medicine.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $medicine = Medicine::updateOrCreate(
            ['name' => $request->name, 'description' => $request->description]
        );

        return Redirect::back()->with('success', __('sentence.Medicine Added Successfully'));
    }

    public function all()
    {
        $medicines = Medicine::all();

        return view('medicine.all', ['medicines' => $medicines]);
    }


    public function edit($id)
    {
        $medicine = Medicine::find($id);
        return view('medicine.edit', ['medicine' => $medicine]);
    }

    public function store_edit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $medicine = Medicine::find($request->medicine_id);

        $medicine->name = $request->name;
        $medicine->description = $request->description;

        $medicine->save();

        return Redirect::route('medicine.all')->with('success', __('sentence.Medicine Edited Successfully'));
    }

    public function destroy($id)
    {
        Medicine::destroy($id);
        return Redirect::route('medicine.all')->with('success', __('sentence.Medicine Deleted Successfully'));
    }
}
