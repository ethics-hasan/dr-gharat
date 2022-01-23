<head>
   <title>Doctorino - Invoice</title>
</head>

<style>
   table, th, td {
       border: 1px solid black;
       border-collapse: collapse;
       text-align: center;
       padding: 5px 10px;
   }
</style>

<body>
   <p>
      <b>{{ __('sentence.Date') }} :</b> {{ $billing->created_at->format('d-m-Y') }}<br>
      <b>{{ __('sentence.Reference') }} :</b> {{ $billing->reference }}<br>
      <b>{{ __('sentence.OPD Number') }} :</b> {{ $billing->opd_no }}<br>
      <b>{{ __('sentence.Patient Name') }} :</b> {{ $billing->Patient->name }}
   </p>

   <h1 style="text-align: center">{{ __('sentence.Invoice') }}</h1>

   <table width="100%">
      <tr>
         <th width="10%">#</th>
         <th width="50%">{{ __('sentence.Item') }}</th>
         <th width="20%">Paid/Balance</th>
         <th width="20%">{{ __('sentence.Amount') }}</th>
      </tr>
      @forelse ($billing_items as $key => $billing_item)
      <tr>
         <td>{{ $key+1 }}</td>
         <td>{{ $billing_item->invoice_title }}</td>
         <td>{{ $billing_item->invoice_status }}</td>
         <td>{{ $billing_item->invoice_amount }}</td>
      </tr>
      @empty
      <tr>
         <td colspan="3">{{ __('sentence.Empty Invoice') }}</td>
      </tr>
      @endforelse
      @empty(!$billing_item)
      <tr>
         <td colspan="3"><strong>{{ __('sentence.Total') }}</strong></td>
         <td colspan="1"><strong>{{ $billing_items->sum('invoice_amount') }}</strong></td>
      </tr>
      <tr>
         <td colspan="3"><strong>Paid</strong></td>
         <td colspan="1"><strong>{{ $amount_paid }}</strong></td>
      </tr>
      <tr>
         <td colspan="3"><strong>Balance</strong></td>
         <td colspan="1"><strong>{{ $amount_balance }}</strong></td>
      </tr>
      @endempty
   </table>
</body>
