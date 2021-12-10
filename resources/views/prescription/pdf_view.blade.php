<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- CSRF Token -->
      <meta name="csrf-token" content="zXbiSv6MysbCo84DXZ4JSrdGP6dkFJbqvwo0wgSS">
      <title>Doctorino - {{ __('sentence.View Prescription') }} 
      </title>
      <!-- Custom styles for this template-->
      <link href="{{ asset('css/app.css') }}" rel="stylesheet">

   </head>
   <body id="page-top">
      <div id="app">
         <!-- Page Wrapper -->
         <div id="wrapper">
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
               <!-- Main Content -->
               <div id="content">
                  <!-- Begin Page Content -->
                  <div class="container-fluid">
                     <div class="row justify-content-center">
                        <div class="col">
                           <div class="card shadow mb-4">
                              <div class="card-body">
                                 <!-- ROW : Doctor informations -->
                                 <div class="row">
                                    <div class="col-md-9">
                                       {!! clean(App\Setting::get_option('header_left')) !!}
                                    </div>
                                    <div class="col-md-3">
                                       <p class="float-right">Alger, {{ __('sentence.On') }} {{ $prescription->created_at->format('d-m-Y') }}</p>
                                    </div>
                                 </div>
                                 <!-- END ROW : Doctor informations -->
                                 <!-- ROW : Patient informations -->
                                 <div class="row">
                                    <div class="col">
                                       <hr>
                                       <p>
                                          <b>{{ __('sentence.Patient Name') }} :</b> {{ $prescription->User->name }}
                                          @isset($prescription->User->Patient->birthday)
                                          - <b>{{ __('sentence.Age') }} :</b> {{ $prescription->User->Patient->birthday }} ({{ \Carbon\Carbon::parse($prescription->User->Patient->birthday)->age }} {{ __('sentence.Years') }})
                                          @endisset
                                          @isset($prescription->User->Patient->gender)
                                          - <b>{{ __('sentence.Gender') }} :</b> {{ __('sentence.'.$prescription->User->Patient->gender) }}
                                          @endisset
                                          @isset($prescription->User->Patient->history)
                                          - <b>{{ __('sentence.History') }} :</b> {{ $prescription->User->Patient->history }}
                                          @endisset
                                          @isset($prescription->User->Patient->reason)
                                          - <b>{{ __('sentence.Reason') }} :</b> {{ $prescription->User->Patient->reason }}
                                          @endisset
                                       </p>
                                       <hr>
                                       <h5 class="text-center"><b>{{ __('sentence.Prescription') }}</b></h5>
                                       <hr>
                                    </div>
                                 </div>
                                 <!-- END ROW : Patient informations -->
                                 <!-- ROW : Medicines List -->
                                 <div class="row justify-content-center">
                                    <div class="col">
                                       @forelse ($prescription_medicines as $medicine)
                                       <li>{{ $medicine->Medicine->name }} - {{ $medicine->description }}</li>
                                       @empty
                                       <p>{{ __('sentence.No Medicines') }}</p>
                                       @endforelse
                                    </div>
                                 </div>
                                 <!-- END ROW : Medicines List -->
                                 <!-- ROW : Footer informations -->
                                 <footer>
                                    <hr>
                                    <div class="row">
                                       <div class="col-6">
                                          <p class="font-size-12">{!! clean(App\Setting::get_option('footer_left')) !!}</p>
                                       </div>
                                       <div class="col-6">
                                          <p class="float-right font-size-12">{!! clean(App\Setting::get_option('footer_right')) !!}</p>
                                       </div>
                                    </div>
                                    <!-- END ROW : Footer informations -->
                                 </footer>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Page Heading -->
                  </div>
                  <!-- /.container-fluid -->
               </div>
               <!-- End of Main Content -->
            </div>
            <!-- End of Content Wrapper -->
         </div>
         <!-- End of Page Wrapper -->
      </div>
   </body>
</html>