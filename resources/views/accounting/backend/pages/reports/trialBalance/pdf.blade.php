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
        background: url({{ getCompanyPad($companies->where('id', request()->get('company_id'))->first()) }}) no-repeat 0 0;
        background-image-resize: 6;
      }

      html, body, p  {
        font-size:  12px !important;
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
        font-size:  10px !important;
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
        <div class="col-md-6" style="width: 100%;float:left;padding-top: 135px">
          <h2><strong>{{ $title }} ({{ request()->get('from') }} to {{ request()->get('to') }}) ({{ $currency->code }})</strong></h2>
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
      <table class="table table-bordered">
         <tr>
            <td style="width: 10%;font-size: 12px !important"><strong>Code</strong></td>
            <td style="width: 30%;font-size: 12px !important"><strong>Ledger</strong></td>
            <td style="width: 15%;font-size: 12px !important" class="text-right"><strong>Opening</strong></td>
            <td style="width: 15%;font-size: 12px !important" class="text-right"><strong>Debit</strong></td>
            <td style="width: 15%;font-size: 12px !important" class="text-right"><strong>Credit</strong></td>
            <td style="width: 15%;font-size: 12px !important" class="text-right"><strong>Closing</strong></td>
         </tr>
         {!! $trialBalance !!}
      </table>
    </div>
  </body>
</html>                                                                                                               