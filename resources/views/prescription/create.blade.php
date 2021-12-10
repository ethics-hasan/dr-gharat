@extends('layouts.master')
@section('title')
{{ __('sentence.New Prescription') }}
@endsection
@section('content')
<form method="post" action="{{ route('prescription.store') }}">
   <div class="row">
      <div class="col">
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
      </div>
   </div>
   <div class="row justify-content-center">
      <div class="col-md-4">
         <div class="card shadow mb-4">
            <div class="card-header py-3">
               <h6 class="m-0 font-weight-bold text-primary">{{ __('sentence.Patient informations') }}</h6>
            </div>
            <div class="card-body">
               <div class="form-group">
                  <label for="exampleInputEmail1">{{ __('sentence.Patient') }} :</label>
                  <select class="form-control select2 select2-hidden-accessible" id="medicine" tabindex="-1" name="patient_id" aria-hidden="true">
                     <option>{{ __('sentence.Select Patient') }}</option>
                     @foreach($patients as $patient)
                     <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                     @endforeach
                  </select>
                  {{ csrf_field() }}
               </div>
               <div class="form-group text-center">
                  <img src="{{ asset('img/patient-icon.png') }}" class="img-profile rounded-circle img-fluid">
               </div>
               <div class="form-group">
                  <input type="submit" value="{{ __('sentence.Create Prescription') }}" class="btn btn-warning" align="center">
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-8">
         <div class="card shadow mb-4">
            <div class="card-header py-3">
               <h6 class="m-0 font-weight-bold text-primary">{{ __('sentence.Medicines list') }}</h6>
            </div>
            <div class="card-body">
               <fieldset class="todos_labels">
                  <div class="repeatable"></div>
                  <div class="form-group">
                     <a type="button" class="btn btn-success add text-white" align="center"><i class='fa fa-plus'></i> {{ __('sentence.Add Medicine') }}</a>
                  </div>
               </fieldset>
            </div>
         </div>
         <div class="card shadow mb-4">
            <div class="card-header py-3">
               <h6 class="m-0 font-weight-bold text-primary">{{ __('sentence.Treatments list') }}</h6>
            </div>
            <div class="card-body">
               <fieldset class="test_labels">
                  <div class="repeatable"></div>
                  <div class="form-group">
                     <a type="button" class="btn btn-success add text-white" align="center"><i class='fa fa-plus'></i> {{ __('sentence.Add Treatment') }}</a>
                  </div>
               </fieldset>
            </div>
         </div>
      </div>
   </div>
</form>
@endsection

@section('footer')
<script type="text/template" id="todos_labels">
   <section>
                         <div class="row">
                             <div class="col-md-6">
                                 <select class="form-control select2 select2-hidden-accessible" name="medicine[]" id="medicine" tabindex="-1" aria-hidden="true">
                                   <option value="">{{ __('sentence.Select Medicine') }}...</option>
                                   @foreach($medicines as $medicine)
                                       <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                   @endforeach
                                 </select>
                             </div>
                            
                             <div class="col-md-6">
                                 <div class="form-group-custom">
                                     <input type="text" id="description" name="medicine_description[]" class="form-control" placeholder="{{ __('sentence.Description') }}">
                                 </div>
                             </div>
                         </div>
   
                 </section>
   <hr color="#a1f1d4">
</script>
<script type="text/template" id="test_labels">
   <section>
                         <div class="row">
                            
                             <div class="col-md-6">
                                 <select class="form-control select2 select2-hidden-accessible" name="treatment[]" id="treatment" tabindex="-1" aria-hidden="true">
                                   <option value="">{{ __('sentence.Select Treatment') }}...</option>
                                   @foreach($treatments as $treatment)
                                       <option value="{{ $treatment->id }}">{{ $treatment->name }}</option>
                                   @endforeach
                                 </select>
                             </div>
                            
                             <div class="col-md-6">
                                 <div class="form-group-custom">
                                     <input type="text" id="description" name="treatment_description[]"  class="form-control" placeholder="{{ __('sentence.Description') }}">
                                 </div>
                             </div>
                         </div>
   
                      
                 </section>
   <hr color="#a1f1d4">
</script>
@endsection