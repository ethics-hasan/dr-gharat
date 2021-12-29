<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Billing;
use App\Billing_item;
use Redirect;
use PDF;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function create()
    {
        $patients = Patient::all();

        return view('billing.create', ['patients' => $patients]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
                'patient_id' => ['required','exists:patients,id'],
                'payment_mode' => 'required',
                'payment_status' => 'required',
                'invoice_title.*' => 'required',
                'invoice_amount.*' => ['required','numeric'],
                'invoice_status.*' => 'required'
            ]);


        $billing = new Billing();

        $billing->patient_id = $request->patient_id;
        $billing->payment_mode = $request->payment_mode;
        $billing->payment_status = $request->payment_status;
        $billing->reference = 'b'.rand(10000, 99999);

        $billing->save();


        $i = count($request->invoice_title);

        for ($x = 0; $x < $i; $x++) {
            echo $request->invoice_title[$x];



            $invoice_item = new Billing_item();

            $invoice_item->invoice_title = $request->invoice_title[$x];
            $invoice_item->invoice_amount = $request->invoice_amount[$x];
            $invoice_item->invoice_status = $request->invoice_status[$x];
            $invoice_item->billing_id = $billing->id;

            $invoice_item->save();
        }

        return Redirect::route('billing.create')->with('success', 'Invoice Created Successfully!');
        ;
    }

    public function store_edit(Request $request)
    {
        $validatedData = $request->validate([
         'patient_id' => ['required','exists:patients,id'],
         'payment_mode' => 'required',
         'payment_status' => 'required',
         'invoice_title.*' => 'required',
         'invoice_amount.*' => ['required','numeric'],
         'invoice_status.*' => 'required'
     ]);


        $billing = new Billing();

        $billing->patient_id = $request->patient_id;
        $billing->payment_mode = $request->payment_mode;
        $billing->payment_status = $request->payment_status;
        $billing->reference = 'b'.rand(10000, 99999);

        $billing->save();



        $i = count($request->invoice_title);

        for ($x = 0; $x < $i; $x++) {
            echo $request->invoice_title[$x];



            $invoice_item = new Billing_item();

            $invoice_item->invoice_title = $request->invoice_title[$x];
            $invoice_item->invoice_amount = $request->invoice_amount[$x];
            $invoice_item->invoice_status = $request->invoice_status[$x];
            $invoice_item->billing_id = $billing->id;

            $invoice_item->save();
        }

        return Redirect::route('billing.create')->with('success', 'Invoice Created Successfully!');
    }

    public function all()
    {
        $invoices = Billing::all();
        return view('billing.all', ['invoices' => $invoices]);
    }


    public function view($id)
    {
        $billing = Billing::findOrfail($id);
        $billing_items = Billing_item::where('billing_id', $id)->get();
        $amount_paid = Billing_item::where('billing_id', $id)->where('invoice_status', 'Paid')->sum('invoice_amount');
        $amount_balance = Billing_item::where('billing_id', $id)->where('invoice_status', 'Balance')->sum('invoice_amount');

        return view('billing.view', [
          'billing' => $billing,
          'billing_items' => $billing_items,
          'amount_paid' => $amount_paid,
          'amount_balance' => $amount_balance
        ]);
    }

    public function pdf($id)
    {
        $billing = Billing::findOrfail($id);
        $billing_items = Billing_item::where('billing_id', $id)->get();

        view()->share(['billing' => $billing, 'billing_items' => $billing_items]);
        $pdf = PDF::loadView('billing.pdf_view', ['billing' => $billing, 'billing_items' => $billing_items]);

        // download PDF file with download method
        return $pdf->download($billing->Patient->name.'_invoice.pdf');
    }
}
