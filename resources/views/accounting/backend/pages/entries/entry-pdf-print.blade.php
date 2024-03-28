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

            background: url('assets/idcard/letterhead/{{ getUnitCode(isset($entry->purchaseOrder->purchase_order_id) ? $entry->purchaseOrder->purchase_order_id : 0) }}.png') no-repeat 0 0;
            background-image-resize: 6;
        }

        html, body, p {
            font-size: 10px !important;
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

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
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
                Entry Issued by <strong>{{ \App\User::find($entry->created_by)->name }}</strong>
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
            @if(isset($entry->purchaseOrder->id))
                {{-- <td style="width:  20%;border-top: none !important">Supplier:</td> --}}
                <td style="width:  50%;border-top: none !important" colspan="3">Purchase Order:</td>
            @endif
            <td style="width:  30%;border-top: none !important">Type:</td>
            <td style="width:  20%;border-top: none !important">
                @if($entry->is_advance == 1)
                    Advance Category:
                @endif
            </td>
        </tr>
        <tr>
            @if(isset($entry->purchaseOrder->id))
                {{-- <td style="width:  20%;border-top: none !important">
                    <strong>{{ $entry->purchaseOrder->purchaseOrder->relQuotation->relSuppliers->name.' ('.$entry->purchaseOrder->purchaseOrder->relQuotation->relSuppliers->phone.')' }}</strong>
                </td> --}}
                <td style="width:  50%;border-top: none !important" colspan="3">
                    <strong>{{ $entry->purchaseOrder->purchaseOrder->reference_no.' ('.date('Y-m-d', strtotime($entry->purchaseOrder->purchaseOrder->po_date)).')' }}</strong>
                </td>
            @endif
            <td style="width:  30%;border-top: none !important">
                <strong>{{ $entry->entryType ? $entry->entryType->name : '' }} @if(isset($entry->purchaseOrder->type))
                        ({{ ucwords(str_replace('-', ' ', $entry->purchaseOrder->type)) }})
                    @endif</strong>
            </td>
            <td style="width:  20%;border-top: none !important">
                @if($entry->is_advance == 1)
                    <strong>{{ $entry->advanceCategory ? '['.$entry->advanceCategory->code.'] '.$entry->advanceCategory->name : '' }}</strong>
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    <table class="table">
        <tbody>
        <tr>
            <td style="width: 15%;border-top: none !important">Code:</td>
            @if($entry->number != $entry->code)
                <td style="width: 15%;border-top: none !important">Reference:</td>
            @endif
            <td style="width: 10%;border-top: none !important">Date:</td>
            <td style="width: 15%;border-top: none !important">Company:</td>
            <td style="width: 30%;border-top: none !important">Fiscal Year:</td>
            <td style="width: 20%;border-top: none !important">Status:</td>
        </tr>
        <tr>
            <td style="width: 15%;border-top: none !important">
                <strong>{{ $entry->code }}</strong>
            </td>
            @if($entry->number != $entry->code)
                <td style="width: 15%;border-top: none !important">
                    <strong>{{ $entry->number }}</strong>
                </td>
            @endif
            <td style="width: 10%;border-top: none !important">
                <strong>{{ $entry->date }}</strong>
            </td>
            <td style="width: 15%;border-top: none !important">
                <strong>{{ entryCompanies($entry) }}</strong>
            </td>
            <td style="width: 30%;border-top: none !important">
                <strong>{{ isset($entry->fiscalYear->title)?$entry->fiscalYear->title:'' }}
                    &nbsp;|&nbsp;{{ date('d-M-y', strtotime($entry->fiscalYear->start)).' to '.date('d-M-y', strtotime($entry->fiscalYear->end)) }}
                    )</strong>
            </td>
            <td style="width: 20%;border-top: none !important">
                <strong>
                    @include('accounting.backend.pages.entry-approval-stage',[
                        'object' => $entry
                    ])
                </strong>
            </td>
        </tr>
        </tbody>
    </table>

    <br>

    <table class="table table-bordered">
        <tbody>
        <tr>
            <td style="width: 12.5%"><strong>Cost Centre</strong></td>
            <td style="width: 12.5%"><strong>Group</strong></td>
            <td style="width: 20%"><strong>Ledger</strong></td>
            <td style="width: 5%"><strong>Currency</strong></td>
            @if(!$same)
                <td style="width: 10%"><strong>Rate</strong></td>
                <td style="width: 10%"><strong>Debit</strong></td>
                <td style="width: 10%"><strong>Credit</strong></td>
            @endif
            <td style="width: 10%"><strong>Debit ({{ $systemCurrency->code }})</strong></td>
            <td style="width: 10%"><strong>Credit ({{ $systemCurrency->code }})</strong></td>
            <td style="width: 10%"><strong>Narration</strong></td>
        </tr>
        @if($entry->items->count() > 0)
            @foreach($entry->items as $key => $item)
                <tr>
                    <td>{{ $item->costCentre ? '['.$item->costCentre->code.'] '.$item->costCentre->name : '' }}</td>
                    <td>{{ $item->chartOfAccount ? '['.$item->chartOfAccount->accountGroup->code.'] '.$item->chartOfAccount->accountGroup->name : '' }}</td>
                    <td>
                        {{ $item->chartOfAccount ? '['.$item->chartOfAccount->code.'] '.$item->chartOfAccount->name : '' }} {!! transactionVendor($item) ? transactionVendor($item) : '' !!}
                    </td>
                    <td class="text-center">{{ $currency }}</td>
                    @if(!$same)
                        <td class="text-right">{{ systemMoneyFormat($exchangeRate) }}</td>
                        <td class="text-right">{{ $item->debit_credit == "D" ? systemMoneyFormat($item->amount) : '' }}</td>
                        <td class="text-right">{{ $item->debit_credit == "C" ? systemMoneyFormat($item->amount) : '' }}</td>
                    @endif
                    <td class="text-right">{{ $item->debit_credit == "D" ? systemMoneyFormat($item->amount*$exchangeRate) : '' }}</td>
                    <td class="text-right">{{ $item->debit_credit == "C" ? systemMoneyFormat($item->amount*$exchangeRate) : '' }}</td>
                    <td class="text-center">{{ $item->narration }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
        @php
            $total_debit = $entry->items->where('debit_credit', 'D')->sum('amount');
            $total_credit = $entry->items->where('debit_credit', 'C')->sum('amount');
            $d_deference = $total_credit > $total_debit ? ($total_credit-$total_debit) : 0;
            $c_deference = $total_debit > $total_credit ? ($total_debit-$total_credit) : 0;
        @endphp
        <tfoot>
        <tr>
            <td colspan="{{ !$same ? 5 : 4 }}">
                <h5><strong>Total</strong></h5>
            </td>
            @if(!$same)
                <td style="font-weight: bold;"
                    class="text-right total-debit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                    {{ systemMoneyFormat($total_debit) }}
                </td>
                <td style="font-weight: bold;"
                    class="text-right total-credit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                    {{ systemMoneyFormat($total_credit) }}
                </td>
            @endif
            <td style="font-weight: bold;"
                class="text-right total-debit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                {{ systemMoneyFormat($total_debit*$exchangeRate) }}
            </td>
            <td style="font-weight: bold;"
                class="text-right total-credit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                {{ systemMoneyFormat($total_credit*$exchangeRate) }}
            </td>
            <td></td>
        </tr>
        @if($d_deference > 0 || $c_deference > 0)
            <tr>
                <td colspan="{{ !$same ? 5 : 4 }}">
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

    <br>

    <table>
        <tbody>
        <tr>
            <td style="padding-left: 0px !important">
                <p>In words ({{ $entry->exchangeRate->currency->code }}):
                    <strong>{{ inWordBn($entry->debit, true, $entry->exchangeRate->currency->name, $entry->exchangeRate->currency->hundreds) }}
                        only.</strong></p>

                @if(!$same)
                    <p>In words ({{ $systemCurrency->code }}):
                        <strong>{{ inWordBn($entry->debit*$exchangeRate, true, $systemCurrency->name, $systemCurrency->hundreds) }}
                            only.</strong></p>
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    <table>
        <tbody>
        <tr>
            <td style="padding-left: 0px !important"><strong>Narration:</strong></td>
        </tr>
        <tr>
            <td style="padding-left: 0px !important">
                <br>
                <p>{{ $entry->notes }}</p>
            </td>
        </tr>
        </tbody>
    </table>

    @if($entry->attachments->count() > 0)
    <table>
        <tbody>
        <tr>
            <td style="padding-left: 0px !important"><strong>Attachments:</strong></td>
        </tr>
        <tr>
            <td style="padding-left: 0px !important">
                <br>
                <ol>
                @foreach($entry->attachments as $attachment)
                <li>
                    <a href="{{ asset($attachment->path) }}" target="_blank" style="text-decoration: none">{{ $attachment->name }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ $attachment->type}}&nbsp;&nbsp;|&nbsp;&nbsp;{{ formatBytes($attachment->size) }}</a>
                </li>
                @endforeach
                </ol>
            </td>
        </tr>
        </tbody>
    </table>
    @endif

    <table style="margin-top: 50px">
        <tbody>
            @include('accounting.backend.pages.entries.authors', [
                'entry' => $entry,
            ])
        </tbody>
    </table>
</div>
</body>
</html>                                                                                                                                                                                                                             