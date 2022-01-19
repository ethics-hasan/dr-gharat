<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;

use App\Patient;
use App\Xray;
use App\Sonography;
use App\BloodTest;
use App\PatientXray;
use App\PatientSonography;
use App\PatientBloodTest;

class TreatmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function create()
    {
        $patients = Patient::all();
        $xrays = Xray::all();
        $sonographies = Sonography::all();
        $blood_tests = BloodTest::all();

        return view('treatment.create', [
            'patients' => $patients,
            'xrays' => $xrays,
            'sonographies' => $sonographies,
            'blood_tests' => $blood_tests
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => ['required','exists:patients,id'],
        ]);

        // Add Patient Xray
        $xray_count = 0;
        if ($request->xray_id) {
            $xray_count = count($request->xray_id);
        }
        for ($x = 0; $x < $xray_count; $x++) {
            $patient_xray = new PatientXray();

            $patient_xray->patient_id = $request->patient_id;
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

            $patient_sonography->patient_id = $request->patient_id;
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

            $patient_blood_test->patient_id = $request->patient_id;
            $patient_blood_test->blood_test_id = $request->blood_test_id[$x];

            $patient_blood_test->save();
        }

        return Redirect::route('treatment.all')->with('success', __('sentence.Treatment Created Successfully'));
    }

    public function all()
    {
        $patients = Patient::all();

        foreach ($patients as $patient) {
            $patient->xray_count = PatientXray::where('patient_id', $patient->id)->count();
            $patient->sonography_count = PatientSonography::where('patient_id', $patient->id)->count();
            $patient->blood_test_count = PatientBloodTest::where('patient_id', $patient->id)->count();
        }

        return view('treatment.all', [
            'patients' => $patients
        ]);
    }

    public function destroy($id)
    {
        Treatment::destroy($id);
        return Redirect::route('treatment.all')->with('success', __('sentence.Treatment Deleted Successfully'));
    }
}
