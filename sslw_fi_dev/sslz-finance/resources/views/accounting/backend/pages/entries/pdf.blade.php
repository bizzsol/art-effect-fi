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

                background: url('assets/idcard/letterhead/{{ getUnitCode(0) }}.png') no-repeat 0 0;
                background-image-resize: 6;
            }
            
            html, body, p  {
                font-size:  10px !important;
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
                text-align:  right;
            }           
        </style>    
    </head>
    
    <body>
        <htmlpageheader name="page-header">
            <div class="row mb-3 print-header">
                <div class="col-md-12" style="width: 100%;float:left;padding-top: 135px">
                    <h2><strong>{{ $title }}</strong></h2>
                </div>
            </div>
        </htmlpageheader>

        <htmlpagefooter name="page-footer">

            <table class="table-bordered">
                <tbody>
                    <tr>
                        <td colspan="2" style="text-align: center;border: none !important">
                            Entry List Printed by <strong>{{ auth()->user()->name }}</strong>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td colspan="2" style="border: none !important">
                            <small>(Note: This Entry List doesn’t require signature as it is automatically generated from MBM Group’s ERP)</small>
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
                        <td colspan="2" style="text-align: right;border: none !important">
                            <small>Page {PAGENO} of {nb}</small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </htmlpagefooter>
        
        <div class="container">
          <table class="table table-bordered" cellspacing="0" width="100%">
           <tbody>
            <tr>
               <td style="width: 8%"><strong>Date</strong></td>
               <td style="width: 12%"><strong>Number</strong></td>
               <td style="width: 17.5%"><strong>Ledger</strong></td>
               <td style="width: 10%;text-align: center"><strong>Type</strong></td>
               <td style="width: 14.5%"><strong>Source</strong></td>
               <td style="width: 8%"><strong>Currency</strong></td>
               <td style="width: 9%"><strong>Debit</strong></td>
               <td style="width: 9%"><strong>Credit</strong></td>
               <td style="width: 12.5%;text-align: center"><strong>Status</strong></td>
             </tr>
            @if(isset($entries[0]))
            @foreach($entries as $key => $entry)
            <tr>
                <td style="text-align: center">{{ $entry->date }}</td>
                <td style="text-align: center">{{ $entry->number }}</td>
                <td>
                    <p>Debit: {{ $entry->items->where('debit_credit', 'D')->pluck('chartOfAccount.code')->implode(', ') }}</p>
                    <p>Credit: {{ $entry->items->where('debit_credit', 'C')->pluck('chartOfAccount.code')->implode(', ') }}</p>
                </td>
                <td style="text-align: center">{{ $entry->entryType ? $entry->entryType->name : '' }}</td>
                <td style="text-align: center">
                    {{ $entry->purchaseOrder ? ucwords(str_replace('-', ' ', $entry->purchaseOrder->type)) : $entry->notes }}
                </td>
                <td style="text-align: center">{{ $entry->exchangeRate ? $entry->exchangeRate->currency->code : '' }}</td>
                <td style="text-align: right">{{ $entry->debit }}</td>
                <td style="text-align: right">{{ $entry->credit }}</td>
                <td style="text-align: center">
                  @include('accounting.backend.pages.approval-stage',[
                    'object' => $entry
                  ])
                </td>
            </tr>
            @endforeach
            @endif
            {{-- <tr>
             <td colspan="6" style="width: 65%;text-align: right"><strong>Total</strong></td>
             <td style="width: 10%;text-align: right"><strong>{{ $entries->sum('debit') }}</strong></td>
             <td style="width: 10%;text-align: right"><strong>{{ $entries->sum('credit') }}</strong></td>
             <td style="width: 15%;text-align: center"></td>
            </tr> --}}
           </tbody>
        </table>
        </div>
    </body>
</html> 