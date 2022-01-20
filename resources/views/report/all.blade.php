@extends('layouts.master')

@section('title')
{{ __('sentence.Report Generation') }}
@endsection

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
   <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif
@if (session('success'))
<div class="alert alert-success">
   {{ session('success') }}
</div>
@endif
<!-- DataTales Example -->
<div class="card shadow mb-4">
   <div class="card-header py-3">
      <div class="row">
         <div class="col-8">
            <h6 class="m-0 font-weight-bold text-primary w-75 p-2">{{ __('sentence.Report Generation') }}</h6>
         </div>
      </div>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
               <tr>
                  <th>#</th>
                  <th>{{ __('sentence.Doctor') }}</th>
                  <th>{{ __('sentence.Total Patients') }}</th>
                  <th>{{ __('sentence.Total Xrays') }}</th>
                  <th>{{ __('sentence.Total Sonography') }}</th>
                  <th>{{ __('sentence.Total Blood Tests') }}</th>
                  <th>{{ __('sentence.Total Amount') }}</th>
               </tr>
            </thead>
            <tbody>
               @php
                  $no = 0;
               @endphp
               
               @foreach($doctors as $doctor)
               <tr>
                  <td>{{ ++$no }}</td>
                  <td>{{ $doctor->name }}</td>
                  <td>{{ $doctor->patient_count }}</td>
                  <td>{{ $doctor->xray_count }}</td>
                  <td>{{ $doctor->sonography_count }}</td>
                  <td>{{ $doctor->blood_test_count }}</td>
                  <td>{{ $doctor->total_amount }}</td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection