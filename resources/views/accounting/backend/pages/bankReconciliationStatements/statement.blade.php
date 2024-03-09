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

            background: url('assets/idcard/letterhead/{{ getUnitCode(0) }}.png) no-repeat 0 0; background-image-resize: 6; } html, body, p { font-size: 12px !important; color: #000000; } table { width: 100% !important; border-spacing: 0px !important; margin-top: 10px !important; margin-bottom: 15px !important; } table caption { color: #000000 !important; } table td { padding-top: 1px !important; padding-bottom: 1px !important; padding-left: 7px !important; padding-right: 7px !important; } .table-non-bordered { padding-left: 0px !important; } .table-bordered { border-collapse: collapse; } .table-bordered td { border: 1px solid #000000; padding: 5px; } .table-bordered tr:first-child td { border-top: 0; } .table-bordered tr td:first-child { border-left: 0; } .table-bordered tr:last-child td { border-bottom: 0; } .table-bordered tr td:last-child { border-right: 0; } .mt-0 { margin-top: 0; } .mb-0 { margin-bottom: 0; } .image-space { white-space: wrap !important; padding-top: 45px !important; } .break-before { page-break-before: always; break-before: always; } .break-after { break-after: always; } .break-inside { page-break-inside: avoid; break-inside: avoid; } .break-inside-auto { page-break-inside: auto; break-inside: auto; } .space-top { margin-top: 10px; } .space-bottom { margin-bottom: 10px; } .text-right{ text-align: right; } .text-center{ text-align: center; }
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
                Statement Printed by <strong>{{ auth()->user()->name  }}</strong>
            </td>
        </tr>
        {{-- <tr>
            <td colspan="2" style="border: none !important">
                <small>(Note: This {{ $title }} doesn’t require signature as it is automatically generated from MBM Group’s ERP)</small>
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

<div class="container" style="padding-top: 15px">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <td colspan="3" style="text-align: center">
                <h3><strong>Bank Reconciliation Statement for {{ $statement->bankAccount->name }}</strong></h3>
                <h3><strong>As on {{ date('F j, Y', strtotime($statement->date)) }}</strong></h3>
            </td>
        </tr>
        <tr>
            <td><strong>Description</strong></td>
            <td><strong>Currency</strong></td>
            <td><strong>Amount</strong></td>
        </tr>
        @php
            $entries = \App\Models\PmsModels\Accounts\Entry::whereIn('id', json_decode($statement->pending_entries, true))->get();
        @endphp
        <tr>
            <td style="width: 70%">Balance as per Bank Book, {{ date('F j, Y', strtotime($statement->date)) }}</td>
            <td style="width: 10%" class="text-center"
                rowspan="{{ $entries->count() > 0 ? 12+$entries->count() : 10 }}">{{ $statement->bankAccount->currency->code }}</td>
            <td style="width: 20%" class="text-right">{{ systemMoneyFormat($statement->book_balance) }}</td>
        </tr>

        <tr>
            <td style="width: 70%">Reconciling Amount</td>
            <td style="width: 20%" class="text-right">
                {{ systemMoneyFormat($statement->reconciling_amount) }}
            </td>
        </tr>

        <tr>
            <td style="width: 70%">Interest Earned</td>
            <td style="width: 20%" class="text-right">{{ systemMoneyFormat($statement->bank_interest_earned) }}</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-right">
                <strong>{{ systemMoneyFormat($statement->bank_interest_earned+$statement->book_balance) }}</strong>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 70%">Deduction:</td>
            <td style="width: 20%" class="text-right"></td>
        </tr>
        @if(isset($entries[0]))
            <tr>
                <td style="width: 70%">Outstanding Checks :</td>
                <td style="width: 20%" class="text-right"></td>
            </tr>
            @php
                $total_debit = 0;
                $total_credit = 0;
            @endphp
            @foreach($entries as $key => $entry)
                @php
                    $debit = $entry->items->whereIn('chart_of_account_id', $accounts)->where('debit_credit', 'D')->whereNull('reconciliation_date')->sum('amount');
                    $credit = $entry->items->whereIn('chart_of_account_id', $accounts)->where('debit_credit', 'C')->whereNull('reconciliation_date')->sum('amount');

                    $total_debit += $debit;
                    $total_credit += $credit;
                @endphp
                <tr>
                    <td style="width: 70%">{{ $entry->number }}</td>
                    <td style="width: 20%" class="text-right">{{ systemMoneyFormat($debit-($credit*-1)) }}</td>
                </tr>
            @endforeach

            <tr>
                <td style="width: 70%" style="text-align: right"><strong>Sub Total</strong></td>
                <td style="width: 20%" class="text-right">
                    <strong>{{ systemMoneyFormat($total_debit-($total_credit*-1)) }}</strong>
                </td>
            </tr>
        @endif
        <tr>
            <td style="width: 70%">Service Charges</td>
            <td style="width: 20%" class="text-right">{{ systemMoneyFormat($statement->bank_charges) }}</td>
        </tr>
        <tr>
            <td style="width: 70%">Error on Cheque</td>
            <td style="width: 20%" class="text-right"></td>
        </tr>
        <tr>
            <td></td>
            <td class="text-right">
                <strong>{{ systemMoneyFormat($statement->bank_charges) }}</strong>
            </td>
        </tr>
        <tr>
            <td style="width: 70%"><strong>Closing Bank Balance</strong></td>
            <td style="width: 20%" class="text-right">
                <strong>{{ systemMoneyFormat($statement->bank_balance) }}</strong>
            </td>
        </tr>
        </tbody>
    </table>

    @php
        $entries = \App\Models\PmsModels\Accounts\Entry::whereIn('id', json_decode($statement->reconciling_entries, true))->get();
        $total_debit = 0;
        $total_credit = 0;
    @endphp
    @if(isset($entries[0]))
        <h3>Reconciled Transactions</h3>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td><strong>Description</strong></td>
                <td><strong>Currency</strong></td>
                <td><strong>Debit</strong></td>
                <td><strong>Credit</strong></td>
            </tr>

            @foreach($entries as $key => $entry)
                @php
                    $debit = $entry->items->whereIn('chart_of_account_id', $accounts)->where('debit_credit', 'D')->whereNotNull('reconciliation_date')->sum('amount');
                    $credit = $entry->items->whereIn('chart_of_account_id', $accounts)->where('debit_credit', 'C')->whereNotNull('reconciliation_date')->sum('amount');
                    $total_debit += $debit;
                    $total_credit += $credit;
                @endphp
                <tr>
                    <td style="width: 60%">
                        {{ $entry->number }}
                        &nbsp;|&nbsp;
                        {{ $entry->entryType ? $entry->entryType->name : '' }}
                        &nbsp;|&nbsp;
                        {{ $entry->purchaseOrder ? ucwords(str_replace('-', ' ', $entry->purchaseOrder->type)) : $entry->notes }}
                    </td>
                    <td style="width: 10%" class="text-center">
                        {{ $entry->exchangeRate->currency->code }}
                    </td>
                    <td style="width: 15%" class="text-right">
                        {{ $debit > 0 ? $debit : '' }}
                    </td>
                    <td style="width: 15%" class="text-right">
                        {{ $credit > 0 ? $credit : '' }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @php
        $entries = \App\Models\PmsModels\Accounts\Entry::whereIn('id', json_decode($statement->pending_entries, true))->get();
        $total_debit = 0;
        $total_credit = 0;
    @endphp
    @if(isset($entries[0]))
        <h3>Pending Transactions</h3>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td><strong>Description</strong></td>
                <td><strong>Currency</strong></td>
                <td><strong>Debit</strong></td>
                <td><strong>Credit</strong></td>
            </tr>

            @foreach($entries as $key => $entry)
                @php
                    $debit = $entry->items->whereIn('chart_of_account_id', $accounts)->where('debit_credit', 'D')->whereNull('reconciliation_date')->sum('amount');
                    $credit = $entry->items->whereIn('chart_of_account_id', $accounts)->where('debit_credit', 'C')->whereNull('reconciliation_date')->sum('amount');
                    $total_debit += $debit;
                    $total_credit += $credit;
                @endphp
                <tr>
                    <td style="width: 60%">
                        {{ $entry->number }}
                        &nbsp;|&nbsp;
                        {{ $entry->entryType ? $entry->entryType->name : '' }}
                        &nbsp;|&nbsp;
                        {{ $entry->purchaseOrder ? ucwords(str_replace('-', ' ', $entry->purchaseOrder->type)) : $entry->notes }}
                    </td>
                    <td style="width: 10%" class="text-center">
                        {{ $entry->exchangeRate->currency->code }}
                    </td>
                    <td style="width: 15%" class="text-right">
                        {{ $debit > 0 ? $debit : '' }}
                    </td>
                    <td style="width: 15%" class="text-right">
                        {{ $credit > 0 ? $credit : '' }}
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    @endif

</div>
</body>
</html>                                                                                                                                                                                                                             