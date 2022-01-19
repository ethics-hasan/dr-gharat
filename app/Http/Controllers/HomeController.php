<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Appointment;
use App\Billing;
use App\Billing_item;
use App;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_patients = Patient::all()->count();
        $total_patients_today = Patient::wheredate('created_at', Today())->count();
        $total_appointments = Appointment::all()->count();
        $total_appointments_today = Appointment::wheredate('date', Today())->get();
        $upcoming_appointments = Appointment::wheredate('date', '<=', date('Y-m-d', strtotime('+3 day')))
                                                ->wheredate('date', '>', date('Y-m-d'))
                                                ->where('visited', 0)
                                                ->get();
        $total_payments = Billing::all()->count();
        $total_payments = Billing::all()->count();
        $total_payments_month = Billing_item::where('invoice_status', 'Paid')->whereMonth('created_at', date('m'))->sum('invoice_amount');
        $total_payments_year = Billing_item::where('invoice_status', 'Paid')->whereYear('created_at', date('Y'))->sum('invoice_amount');

        return view('home', [
            'total_patients' => $total_patients,
            'total_patients_today' => $total_patients_today,
            'total_appointments' => $total_appointments,
            'total_appointments_today' => $total_appointments_today,
            'upcoming_appointments' => $upcoming_appointments,
            'total_payments' => $total_payments,
            'total_payments_month' => $total_payments_month,
            'total_payments_year' => $total_payments_year
        ]);
    }


    public function lang($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}
