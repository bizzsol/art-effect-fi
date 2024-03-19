<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin-top: 0.25in;
            margin-bottom: 0.25in;
            header: page-header;
            footer: page-footer;

            background: url('assets/cheque.png') no-repeat 0 0;
            background-image-resize: 6;
        }

        html, body, p {
            font-size: 12px !important;
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

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }
    </style>
</head>

<body>
<htmlpageheader name="page-header">

</htmlpageheader>

<htmlpagefooter name="page-footer">
    <table class="table-bordered">
        <tbody>
        <tr>
            <td style="border: none !important">
                <strong>{{ $title }}</strong> issued by <strong>{{ $cheque->creator->name }}, Time &
                    Date: {{ date('g.i a, d.m.Y', strtotime($cheque->datetime)) }}</strong>
            </td>
            <td style="text-align: right;border: none !important;">
                <small>Page {PAGENO} of {nb}</small>
            </td>
        </tr>
        </tbody>
    </table>
</htmlpagefooter>

<div class="container">
    <table class="table">
        <tbody>
        <tr>
            <td style="width: 100%;text-align: center;padding-top: 85px" colspan="2">
                <h1><strong>{{ $cheque->bankAccount->bank_name }}</strong></h1>
                <h2><strong>{{ $cheque->bankAccount->name }} [{{ $cheque->bankAccount->number }}]</strong></h2>
            </td>
        </tr>
        <tr>
            <td style="width: 60%;">

            </td>
            <td style="width: 40%;text-align: right;padding-top: 40px">
                <h2>
                    <strong>{{ date('d-m-Y', strtotime($cheque->datetime)) }}</strong>
                </h2>
            </td>
        </tr>
        <tr>
            <td style="width: 60%;text-align: left;padding-top: 70px;padding-left: 135px">
                <h2>
                    @if(request()->has('cash'))
                        <strong>Cash</strong>
                    @else
                        @if ($cheque->payee_name)
                            <strong>{{$cheque->payee_name}}</strong>
                        @else
                            <strong>{{ isset($cheque->entry->purchaseOrder->purchaseOrder->relQuotation->relSuppliers->name) ? $cheque->entry->purchaseOrder->purchaseOrder->relQuotation->relSuppliers->name : '' }}
                            ({{ isset($cheque->entry->purchaseOrder->purchaseOrder->relQuotation->relSuppliers->code) ? $cheque->entry->purchaseOrder->purchaseOrder->relQuotation->relSuppliers->code : '' }}
                            )</strong>
                        @endif
                        
                    @endif
                </h2>
            </td>
            <td style="width: 40%;text-align: right;padding-top: 70px;padding-right: 15px">
                <h2>
                    <strong>{{ isset($cheque->entry->exchangeRate->currency->code) ? $cheque->entry->exchangeRate->currency->code : '' }} {{ systemMoneyFormat($amount) }}</strong>
                </h2>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;text-align: center;padding-top: 75px" colspan="2">
                <h3><strong>In word</strong>:
                    <strong>{{ inWordBn(systemDoubleValue($amount, 2), true, isset($cheque->entry->exchangeRate->currency->name) ? $cheque->entry->exchangeRate->currency->name : false, isset($cheque->entry->exchangeRate->currency->hundreds) ? $cheque->entry->exchangeRate->currency->hundreds : false) }}
                        only</strong></h3>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;text-align: center;padding-top: 50px" colspan="2">
                <h2><strong><i style="letter-spacing: 10px">{{ $cheque->cheque_number }}</i></strong></h2>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>																																																								