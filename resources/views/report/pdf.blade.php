<head>
    <title>{{$doctor->name}}</title>
</head>

<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        padding: 5px;
    }
</style>
<body>
    
    <h5>Report generated on : {{ Carbon\Carbon::now()->format('d M Y - H:i:s') }}</h5>
    <h1 style="text-align: center;">{{$doctor->name}}</h1>
    
    <h3>Xrays</h3>
    <table width="100%">
        <tr>
          <th width="10%">#</th>
          <th width="30%">{{ __('sentence.Patient Name') }}</th>
          <th width="30%">{{ __('sentence.Xray Name') }}</th>
          <th width="15%">{{ __('sentence.Date') }}</th>
          <th width="15%">{{ __('sentence.Amount') }}</th>
        </tr>
        @php
            $x = 0;
        @endphp
        @foreach($doctor_xrays as $xray)
        <tr>
          <td>{{ ++$x }}</td>
          <td>{{ $xray->patient_name }}</td>
          <td>{{ $xray->xray_name }}</td>
          <td>{{ Carbon\Carbon::parse($xray->date)->format('d M Y') }}</td>
          <td>{{ $xray->amount }}</td>
        </tr>
        @endforeach
        
        @if (!$doctor_xrays->count())
        <tr>
          <td colspan="5" align="center">{{ __('sentence.No Xrays Available') }}</td>
        </tr>
        @endif
    </table>
    
    <h3>Sonography</h3>
    <table width="100%">
        <tr>
          <th width="10%">#</th>
          <th width="30%">{{ __('sentence.Patient Name') }}</th>
          <th width="30%">{{ __('sentence.Sonography Name') }}</th>
          <th width="15%">{{ __('sentence.Date') }}</th>
          <th width="15%">{{ __('sentence.Amount') }}</th>
        </tr>
        @php
            $y = 0;
        @endphp
        @foreach($doctor_sonography as $sonography)
        <tr>
          <td>{{ ++$y }}</td>
          <td>{{ $sonography->patient_name }}</td>
          <td>{{ $sonography->sonography_name }}</td>
          <td>{{ Carbon\Carbon::parse($sonography->date)->format('d M Y') }}</td>
          <td>{{ $sonography->amount }}</td>
        </tr>
        @endforeach
        
        @if (!$doctor_sonography->count())
        <tr>
          <td colspan="5" align="center">{{ __('sentence.No Sonography Available') }}</td>
        </tr>
        @endif
    </table>
    
    {{-- <h3>Blood Tests</h3>
    <table width="100%">
        <tr>
          <th width="10%">#</th>
          <th width="30%">{{ __('sentence.Patient Name') }}</th>
          <th width="30%">{{ __('sentence.Blood Test Name') }}</th>
          <th width="15%">{{ __('sentence.Date') }}</th>
          <th width="15%">{{ __('sentence.Amount') }}</th>
        </tr>
        @php
            $z = 0;
        @endphp
        @foreach($doctor_blood_tests as $blood_test)
        <tr>
          <td>{{ ++$z }}</td>
          <td>{{ $blood_test->patient_name }}</td>
          <td>{{ $blood_test->blood_test_name }}</td>
          <td>{{ Carbon\Carbon::parse($blood_test->date)->format('d M Y') }}</td>
          <td>{{ $blood_test->amount }}</td>
        </tr>
        @endforeach

        @if (!$doctor_blood_tests->count())
        <tr>
          <td colspan="5" align="center">{{ __('sentence.No Blood Tests Available') }}</td>
        </tr>
        @endif
        
    </table> --}}

    <h3 style="margin-left: 85%">Total - {{$doctor->total_amount}}</h3>
</body>