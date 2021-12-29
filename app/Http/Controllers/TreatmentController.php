<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;

use App\Treatment;

class TreatmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function create()
    {
        return view('treatment.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
                'name' => 'required',
            ]);

        $treatment = new Treatment();

        $treatment->name = $request->name;
        $treatment->description = $request->description;

        $treatment->save();

        return Redirect::route('treatment.all')->with('success', __('sentence.Treatment Created Successfully'));
    }

    public function all()
    {
        $treatments = Treatment::all();
        return view('treatment.all', ['treatments' => $treatments]);
    }

    public function edit($id)
    {
        $treatment = Treatment::find($id);
        return view('treatment.edit', ['treatment' => $treatment]);
    }

    public function store_edit(Request $request)
    {
        $validatedData = $request->validate([
                'name' => 'required',
            ]);

        $treatment = Treatment::find($request->treatment_id);

        $treatment->name = $request->name;
        $treatment->description = $request->description;

        $treatment->save();

        return Redirect::route('treatment.all')->with('success', __('sentence.Treatment Edited Successfully'));
    }

    public function destroy($id)
    {
        Treatment::destroy($id);
        return Redirect::route('treatment.all')->with('success', __('sentence.Treatment Deleted Successfully'));
    }
}
