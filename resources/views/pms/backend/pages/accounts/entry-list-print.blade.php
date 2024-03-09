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

                background: url('assets/idcard/letterhead/{{ getUnitCode(isset($purchaseOrder->id) ? $purchaseOrder->id : 0) }}.png') no-repeat 0 0;
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

            .text-center{
                text-align:  center;
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
                            Transactions Printed by <strong>{{ auth()->user()->name }}</strong> at <strong>{{ date('Y-m-d g:i a') }}</strong>
                        </td>
                    </tr>
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
            <br>

            <table class="table">
                <tbody>
                    <tr>
                        <td style="width:  50%;border-top: none !important;padding-left: 0px !important">Supplier:</td>
                        <td style="width:  50%;border-top: none !important" colspan="2">Purchase Order:</td>
                    </tr>
                    <tr>
                        <td style="width:  50%;border-top: none !important;padding-left: 0px !important">
                            <strong>{{ $purchaseOrder->relQuotation->relSuppliers->name.' ('.$purchaseOrder->relQuotation->relSuppliers->phone.')' }}</strong>
                        </td>
                        <td style="width:  50%;border-top: none !important" colspan="2">
                            <strong>{{ $purchaseOrder->reference_no.' ('.date('Y-m-d', strtotime($purchaseOrder->po_date)).')' }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr style="border: 1px dashed black !important;margin-bottom: 25px">

            @if(isset($purchaseOrder->purchaseOrderEntries[0]))
            @foreach($purchaseOrder->purchaseOrderEntries as $key => $purchaseOrderEntry)
            @php
                $currency = $purchaseOrderEntry->entry->exchangeRate->currency->code;
                $same = ($purchaseOrderEntry->entry->exchangeRate->currency_id == $systemCurrency->id ? true : false);
                $exchangeRate = exchangeRate($purchaseOrderEntry->entry->exchangeRate, $systemCurrency->id);
            @endphp
            <table class="table">
                <tbody>
                    <tr>
                        <td style="width: 15%;border-top: none !important;padding-left: 0px !important">Type:</td>
                        <td style="width: 15%;border-top: none !important;padding-left: 0px !important">Reference:</td>
                        <td style="width: 20%;border-top: none !important">Company:</td>
                        <td style="width: 15%;border-top: none !important">Date:</td>
                        <td style="width: 15%;border-top: none !important">Fiscal Year:</td>
                        <td style="width: 20%;border-top: none !important">Status:</td>
                    </tr>
                    <tr>
                        <td style="width: 15%;border-top: none !important;padding-left: 0px !important">
                            <strong>{{ $purchaseOrderEntry->entry->entryType->name }} ({{ $purchaseOrderEntry->type }})</strong>
                        </td>
                        <td style="width: 15%;border-top: none !important">
                            <strong>{{ $purchaseOrderEntry->entry->number }}</strong>
                        </td>
                        <td style="width: 20%;border-top: none !important;padding-left: 0px !important">
                            <strong>{{ entryCompanies($purchaseOrderEntry->entry) }}</strong>
                        </td>
                        
                        <td style="width: 15%;border-top: none !important">
                            <strong>{{ $purchaseOrderEntry->entry->date }}</strong>
                        </td>
                        <td style="width: 15%;border-top: none !important">
                            <strong>{{ isset($purchaseOrderEntry->entry->fiscalYear->title)?$purchaseOrderEntry->entry->fiscalYear->title:'' }}</strong>
                        </td>
                        <td style="width: 20%;border-top: none !important">
                            <strong>
                                @include('accounting.backend.pages.approval-stage',[
                                    'object' => $purchaseOrderEntry->entry
                                ])
                            </strong>
                        </td>   
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 15%"><strong>Cost Centre</strong></td>
                        <td style="width: 15%"><strong>Group</strong></td>
                        <td style="width: 20%"><strong>Ledger</strong></td>
                        <td style="width: 10%"><strong>Currency</strong></td>
                        @if(!$same)
                        <td style="width: 10%"><strong>Debit</strong></td>
                        <td style="width: 10%"><strong>Credit</strong></td>
                        @endif
                        <td style="width: 10%"><strong>Debit ({{ $systemCurrency->code }})</strong></td>
                        <td style="width: 10%"><strong>Credit ({{ $systemCurrency->code }})</strong></td>
                    </tr>
                    @if($purchaseOrderEntry->entry->items->count() > 0)
                    @foreach($purchaseOrderEntry->entry->items as $key => $item)
                    <tr>
                        <td>{{ $item->costCentre ? '['.$item->costCentre->code.'] '.$item->costCentre->name : '' }}</td>
                        <td>{{ $item->chartOfAccount ? '['.$item->chartOfAccount->accountGroup->code.'] '.$item->chartOfAccount->accountGroup->name : '' }}</td>
                        <td>
                            {{ $item->chartOfAccount ? '['.$item->chartOfAccount->code.'] '.$item->chartOfAccount->name : '' }} {!! transactionVendor($item) ? transactionVendor($item) : '' !!}
                        </td>
                        <td class="text-center">{{ $currency }}</td>
                        @if(!$same)
                        <td class="text-right">{{ $item->debit_credit == "D" ? systemMoneyFormat($item->amount) : '' }}</td>
                        <td class="text-right">{{ $item->debit_credit == "C" ? systemMoneyFormat($item->amount) : '' }}</td>
                        @endif
                        <td class="text-right">{{ $item->debit_credit == "D" ? systemMoneyFormat($item->amount*$exchangeRate) : '' }}</td>
                        <td class="text-right">{{ $item->debit_credit == "C" ? systemMoneyFormat($item->amount*$exchangeRate) : '' }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                @php
                    $total_debit = $purchaseOrderEntry->entry->items->where('debit_credit', 'D')->sum('amount');
                    $total_credit = $purchaseOrderEntry->entry->items->where('debit_credit', 'C')->sum('amount');
                    $d_deference = $total_credit > $total_debit ? ($total_credit-$total_debit) : 0;
                    $c_deference = $total_debit > $total_credit ? ($total_debit-$total_credit) : 0;
                @endphp
                <tfoot>
                    <tr>
                        <td colspan="4">
                            <h5><strong>Total</strong></h5>
                        </td>
                        @if(!$same)
                        <td style="font-weight: bold;" class="text-right total-debit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                            {{ systemMoneyFormat($total_debit) }}
                        </td>
                        <td style="font-weight: bold;" class="text-right total-credit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                            {{ systemMoneyFormat($total_credit) }}
                        </td>
                        @endif
                        <td style="font-weight: bold;" class="text-right total-debit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                            {{ systemMoneyFormat($total_debit*$exchangeRate) }}
                        </td>
                        <td style="font-weight: bold;" class="text-right total-credit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                            {{ systemMoneyFormat($total_credit*$exchangeRate) }}
                        </td>
                    </tr>
                    @if($d_deference > 0 || $c_deference > 0)
                    <tr>
                        <td colspan="4">
                            <h5><strong>Difference</strong></h5>
                        </td>
                        @if(!$same)
                        <td style="font-weight: bold;" class="text-right debit-difference">
                            {{ $d_deference > 0 ? systemMoneyFormat($d_deference) : '' }}
                        </td>
                        <td style="font-weight: bold;" class="text-right credit-difference">
                            {{ $c_deference > 0 ? systemMoneyFormat($c_deference) : '' }}
                        </td>
                        @endif
                        <td style="font-weight: bold;" class="text-right debit-difference">
                            {{ $d_deference > 0 ? systemMoneyFormat($d_deference*$exchangeRate) : '' }}
                        </td>
                        <td style="font-weight: bold;" class="text-right credit-difference">
                            {{ $c_deference > 0 ? systemMoneyFormat($c_deference*$exchangeRate) : '' }}
                        </td>
                    </tr>
                    @endif
                </tfoot>
            </table>

            <table>
                <tbody>
                    <tr>
                        <td style="padding-left: 0px !important">
                            <p>In words: <strong>{{ inWordBn($purchaseOrderEntry->entry->debit, $purchaseOrderEntry->entry->exchangeRate->currency->name, $purchaseOrderEntry->entry->exchangeRate->currency->hundreds) }} only.</strong></p>
                        </td>
                    </tr>
                    @if(!$same)
                    <tr>
                        <td style="padding-left: 0px !important">
                            <p>In words: <strong>{{ inWordBn($purchaseOrderEntry->entry->debit*$exchangeRate, $systemCurrency->name, $systemCurrency->hundreds) }} only.</strong></p>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding-left: 0px !important"><strong>Narration:</strong></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0px !important">
                            <br>
                            <p>{{ $purchaseOrderEntry->entry->notes }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="margin-top: 50px">
                <tbody>
                    <tr>
                        <td style="width: 33%;border-top: none !important" class="text-center pl-4 pr-4">
                            <strong>{!! $purchaseOrderEntry->entry->preparedBy ? $purchaseOrderEntry->entry->preparedBy->name : '&nbsp;' !!}</strong>
                            <hr class="mt-1 pt-0 mb-1 pb-0" style="border-top: 2px solid black">
                            Prepared by
                        </td>
                        <td style="width: 33%;border-top: none !important" class="text-center pl-4 pr-4">
                            <strong>{!! $purchaseOrderEntry->entry->reviewedBy ? $purchaseOrderEntry->entry->reviewedBy->name : '&nbsp;' !!}</strong>
                            <hr class="mt-1 pt-0 mb-1 pb-0" style="border-top: 2px solid black">
                            Reviewed by
                        </td>
                        {{-- <td style="width: 25%;border-top: none !important" class="text-center pl-4 pr-4">
                            <strong>{!! $purchaseOrderEntry->entry->assessedBy ? $purchaseOrderEntry->entry->assessedBy->name : '&nbsp;' !!}</strong>
                            <hr class="mt-1 pt-0 mb-1 pb-0" style="border-top: 2px solid black">
                            Assessed by
                        </td> --}}
                        <td style="width: 33%;border-top: none !important" class="text-center pl-4 pr-4">
                            <strong>{!! $purchaseOrderEntry->entry->approvedBy ? $purchaseOrderEntry->entry->approvedBy->name : '&nbsp;' !!}</strong>
                            <hr class="mt-1 pt-0 mb-1 pb-0" style="border-top: 2px solid black">
                            Approved by
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagebreak></pagebreak>
            @endforeach
            @endif
        </div>
    </body>
</html>                                                                                                                                                                                                                             