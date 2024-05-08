<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
      @page {
        margin-top: 1.85in;
        margin-bottom: 1.25in;
        header: page-header;
        footer: page-footer;

        background: url({{ getCompanyPad(auth()->user()->costCentre->profitCentre->company) }}) no-repeat 0 0;
        background-image-resize: 6;
      }

      html, body, p  {
        font-size:  11px !important;
        color: #000000;
      }

      table {
        width: 100% !important;
        border-spacing: 0px !important;
        margin-top: 10px !important;
        margin-bottom: 15px !important;
      }
      table caption {
        color: #000000 !important;
      }
      table td {
        padding-top: 1px !important;
        padding-bottom: 1px !important;
        padding-left: 7px !important;
        padding-right: 7px !important;
      }
      .table-non-bordered {
        padding-left: 0px !important;
      }
      .table-bordered {
        border-collapse: collapse;
      }
      .table-bordered td {
        border: 1px solid #000000;
        padding: 5px;
      }
      .table-bordered tr:first-child td {
        border-top: 0;
      }
      .table-bordered tr td:first-child {
        border-left: 0;
      }
      .table-bordered tr:last-child td {
        border-bottom: 0;
      }
      .table-bordered tr td:last-child {
        border-right: 0;
      }
      .mt-0 {
        margin-top: 0; 
      }
      .mb-0 {
        margin-bottom: 0; 
      }
      .image-space {
        white-space: wrap !important;
        padding-top: 45px !important;
      }
      .break-before {
        page-break-before: always;
        break-before: always;
      }
      .break-after {
        break-after: always;
      }
      .break-inside {
        page-break-inside: avoid;
        break-inside: avoid;
      }
      .break-inside-auto { 
        page-break-inside: auto;
        break-inside: auto;
      }
      .space-top {
        margin-top: 10px;
      }
      .space-bottom {
        margin-bottom: 10px;
      }

      .text-right{
        text-align:  right !important;
      }
      .text-center{
        text-align: center !important;
      }
      .text-left{
        text-align: left !important;
      }     
    </style>  
  </head>
  
  <body>
    <htmlpageheader name="page-header">
      <div class="row mb-3 print-header">
        <div class="col-md-6" style="width: 50%;float:left;padding-top: 135px">
          <h2><strong>{{ $title }}</strong></h2>
        </div>
        {{-- <div class="col-md-6 text-right" style="width: 50%;float:left;padding-top: 50px">
          @if(!empty($purchaseOrder->Unit->hr_unit_logo) && file_exists(public_path($purchaseOrder->Unit->hr_unit_logo)))
            <img src="{{ str_replace('/assets','assets', $purchaseOrder->Unit->hr_unit_logo) }}" alt="logo" style="float: right !important;height: 15mm; width:  35mm; margin: 0;" />
          @endif
        </div> --}}
      </div>
    </htmlpageheader>

    <htmlpagefooter name="page-footer">
      <table class="table-bordered">
        <tbody>
          <tr>
            <td colspan="2" style="text-align: center;border: none !important">
                {{ $title }} Printed by <strong>{{ auth()->user()->name  }}</strong>
            </td>
          </tr>
          {{-- <tr>
            <td colspan="2" style="border: none !important">
              <small>(Note: This {{ $directPurchase ? 'Internal Job Order' : 'Purchase Order' }} doesn’t require signature as it is automatically generated from MBM Group’s ERP)</small>
            </td>
          </tr> --}}
          {{-- <tr>
            <td style="border-left: none !important;border-bottom: none !important">
              <small>
                Factory: M-19 & M-14, Section-14, Mirpur, Dhaka-1206
                <br>
                Phone: +8809678-411412, Mail: info@mbm.group
              </small>
            </td>
            <td style="padding-left: 25px;border-right: none !important;border-bottom: none !important">
              <small>
                Corporate Office: Plot: 1358, Road: 50 (Old), 9 (New)
                <br>
                Avenue: 11, DOHS, Mirpur-12, Dhaka-1216
                <br>
                Website: www.mbm.group
              </small>
            </td>
          </tr> --}}
          <tr style="border: none !important">
            <td style="height: 50px; !important;border: none !important;border-right: none !important">
              
            </td>
            <td style="height: 50px; !important;border: none !important;border-left: none !important">
              
            </td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: right;border: none !important;">
              <small>Page {PAGENO} of {nb}</small>
            </td>
          </tr>
        </tbody>
      </table>
    </htmlpagefooter>
    
    <div class="container">
        <h5>Ledger statement for <strong>[{{ $account->code }}] {{ $account->name }}</strong> from <strong>{{ date('d-M-Y', strtotime($from)) }}</strong> to <strong>{{ date('d-M-Y', strtotime($to)) }}</strong></h5>

        <table class="table-bordered">
          <tbody>
            <tr>
                <td style="width: 20%"><strong>Group</strong></td>
                <td style="width: 35%"><strong>Ledger</strong></td>
                <td style="width: 10%"><strong>From</strong></td>
                <td style="width: 10%"><strong>To</strong></td>
                <td style="width: 10%"><strong>Currency</strong></td>
                <td style="width: 25%"><strong>Opening Balance</strong></td>
            </tr>
            <tr>
                <td>{{ '['.$account->accountGroup->code.'] '.$account->accountGroup->name }}</td>
                <td>{{ '['.$account->code.'] '.$account->name }}</td>
                <td class="text-center">{{ $from }}</td>
                <td class="text-center">{{ $to }}</td>
                <td class="text-center">
                  {{ $currency->code }}
                </td>
                <td class="text-right">
                  {{ $opening_balance['balance'] }}
                </td>

            </tr>
          </tbody>
        </table>

        <table class="table table-bordered">
            <tr>
              <td style="width: 7%"><strong>Date</strong></td>
              <td style="width: 8%"><strong>Reference</strong></td>
              <td style="width: 10%"><strong>Ledger</strong></td>
              <td style="width: 10%"><strong>Supplier</strong></td>
              <td style="width: 8%"><strong>Type</strong></td>
              <td style="width: 7%" class="text-right"><strong>Currency</strong></td>
              <td style="width: 9%" class="text-right"><strong>Debit</strong></td>
              <td style="width: 9%" class="text-right"><strong>Credit</strong></td>
              <td style="width: 9%" class="text-right"><strong>Debit ({{ $currency->code }})</strong></td>
              <td style="width: 9%" class="text-right"><strong>Credit ({{ $currency->code }})</strong></td>
              <td style="width: 14%" class="text-right"><strong>Closing Balance ({{ $currency->code }})</strong></td>
                <td style="width: 14%" class="text-left"><strong>Narration</strong></td>
           </tr>
          @php
              $total_debit = 0;
              $total_credit = 0;
              $closing_balance = $opening_balance['balance'];
          @endphp
          @if(isset($entries[0]))
          @foreach($entries as $key => $entry)
          @php
              $debit = 0;
              $credit = 0;
              if($entry->items->where('chart_of_account_id', $chart_of_account_id)->count() > 0){
                  foreach($entry->items->where('chart_of_account_id', $chart_of_account_id) as $key => $item){
                      $debit += ($item->debit_credit == "D" ? $item->amount : 0);
                      $credit += ($item->debit_credit == "C" ? $item->amount : 0);
                  }
              }

              $exchangeRate = 1;
              if($entry->exchangeRate->currency_id != $currency->id){
                  $exchangeRate = json_decode($entry->exchangeRate->rates, true)[$currency->id]['rate'];
              }

              $total_debit += ($debit > 0 ? $debit*$exchangeRate : 0);
              $total_credit += ($credit > 0 ? $credit*$exchangeRate : 0);

              $closing_balance = ($closing_balance+(($debit > 0 ? $debit*$exchangeRate : 0)-($credit > 0 ? $credit*$exchangeRate : 0)));
          @endphp
          <tr>
              <td>{{ $entry->date }}</td>
              <td>{{ $entry->number }}</td>
              <td>
                  <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->id }}" data-entry-type="{{ $entry->entryType->name }}" data-code="{{ $entry->code }}">
                      <p>Debit: {{ $entry->items->where('debit_credit', 'D')->pluck('chartOfAccount.code')->implode(', ') }}</p>
                      <p>Credit: {{ $entry->items->where('debit_credit', 'C')->pluck('chartOfAccount.code')->implode(', ') }}</p>
                  </a>
              </td>
              <td>
                  {{ getEntryVendor($entry) }}
              </td>
              <td>{{ $entry->entryType ? $entry->entryType->name : '' }}</td>
              <td class="text-center">{{ $entry->exchangeRate->currency->code }}</td>
              <td class="text-right">{{ $debit > 0 ? systemMoneyFormat($debit) : '' }}</td>
              <td class="text-right">{{ $credit > 0 ? systemMoneyFormat($credit) : '' }}</td>
              <td class="text-right">{{ $debit > 0 ? systemMoneyFormat($debit*$exchangeRate) : '' }}</td>
              <td class="text-right">{{ $credit > 0 ? systemMoneyFormat($credit*$exchangeRate) : '' }}</td>
              <td class="text-right">
                  {{ systemMoneyFormat($closing_balance) }}
              </td>
              <td>{{ $entry->notes }}</td>
          </tr>
          @endforeach
          @endif

          <tr>
              <td colspan="8" class="text-right"><strong>Balance: ({{ $currency->code }})</strong></td>
              <td class="text-right"><strong>{{ $total_debit > 0 ? systemMoneyFormat($total_debit) : '' }}</strong></td>
              <td class="text-right"><strong>{{ $total_credit > 0 ? systemMoneyFormat($total_credit) : '' }}</strong></td>
              <td class="text-right"><strong>{{ systemMoneyFormat($closing_balance) }}</strong></td>
              <td></td>
          </tr>
        </table>
    </div>
  </body>
</html>                                                                                                               