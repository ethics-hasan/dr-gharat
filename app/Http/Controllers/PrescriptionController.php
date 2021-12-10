<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medicine;
use App\User;
use App\Patient;
use App\Prescription;
use App\PrescriptionMedicine;
use App\PrescriptionTreatment;
use App\Treatment;
use Redirect;
use PDF;
class PrescriptionController extends Controller{
    

    public function __construct(){
        $this->middleware('auth');
    }


    public function create(){
    	$medicines = Medicine::all();
        $patients = User::where('role','patient')->get();
        $treatments = Treatment::all();
    	return view('prescription.create',['medicines' => $medicines, 'patients' => $patients, 'treatments' => $treatments]);
    }

    public function store(Request $request){

	    	 $validatedData = $request->validate([
	        	'patient_id' => ['required','exists:users,id'],
	        	'medicine.*' => 'required',
	        	'medicine_description.*' => 'required',
	        	'treatment.*' => 'required',
	        	'treatment_description.*' => 'required',

	    	]);

            

    	$prescription = new Prescription;

        $prescription->user_id = $request->patient_id;
        $prescription->reference = 'p'.rand(10000,99999);

        $prescription->save();

     
    if(isset($request->medicine)):
  	   	$i = count($request->medicine);

  	   	for ($x = 0; $x < $i; $x++) {
		  
		  echo $request->medicine[$x];

            $add_medicine = new PrescriptionMedicine;

            $add_medicine->description = $request->medicine_description[$x];
            $add_medicine->prescription_id = $prescription->id;
            $add_medicine->medicine_id = $request->medicine[$x];

            $add_medicine->save();
        }
    endif;

    if(isset($request->treatment)):

        $y = count($request->treatment);

        for ($x = 0; $x < $y; $x++) {

            $add_treatment = new PrescriptionTreatment;

            $add_treatment->treatment_id = $request->treatment[$x];
            $add_treatment->prescription_id = $prescription->id;
            $add_treatment->description = $request->treatment_description[$x];

            $add_treatment->save();

		}

    endif;

		return Redirect::route('prescription.all')->with('success', 'Prescription Created Successfully!');;



    }

    public function all(){
    	$prescriptions = Prescription::all();
    	return view('prescription.all',['prescriptions' => $prescriptions]);
    }

    public function view($id){

    	$prescription = Prescription::findOrfail($id);
        $prescription_medicines = PrescriptionMedicine::where('prescription_id' ,$id)->get();
        $prescription_treatments = PrescriptionTreatment::where('prescription_id' ,$id)->get();
    	
    	return view('prescription.view',['prescription' => $prescription, 'prescription_medicines' => $prescription_medicines, 'prescription_treatments' => $prescription_treatments]);
    }

    public function pdf($id){
    	
    	$prescription = Prescription::find($id);
    	$prescription_medicines = PrescriptionMedicine::where('prescription_id' ,$id)->get();
    	
    	 view()->share(['prescription' => $prescription, 'prescription_medicines' => $prescription_medicines]);
      $pdf = PDF::loadView('prescription.pdf_view', ['prescription' => $prescription, 'prescription_medicines' => $prescription_medicines]);

      // download PDF file with download method
      return $pdf->download($prescription->User->name.'_pdf.pdf');
    }


    public function destroy($id){

        Prescription::destroy($id);
        return Redirect::route('prescription.all')->with('success', 'Prescription Deleted Successfully!');;

    }
}
