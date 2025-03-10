<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin-top: 1.25in;
            margin-bottom: 1.25in;
            header: page-header;
            footer: page-footer;

            background: url({{ getUnitPad($lease->costCentre->profitCentre->company->units->first()) }}) no-repeat 0 0;
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
            <td colspan="2" style="text-align: right;border: none !important;">
                <small>{PAGENO} of {nb}</small>
            </td>
        </tr>
        </tbody>
    </table>
</htmlpagefooter>

<div class="container">
   <table class="table table-bordered">
        <tr>
            <td style="width: 33%;">
                Company: <strong>{{ '['.$lease->costCentre->profitCentre->company->code.'] '.$lease->costCentre->profitCentre->company->name }}</strong>
            </td>

            <td style="width: 33%;">
                Profit Centre: <strong>{{ '['.$lease->costCentre->profitCentre->code.'] '.$lease->costCentre->profitCentre->name }}</strong>
            </td>

            <td style="width: 33%;">
                Cost Centre: <strong>{{ '['.$lease->costCentre->code.'] '.$lease->costCentre->name }}</strong>
            </td>
        </tr>
        <tr>
            <td style="width: 67%;" colspan="2">
                Vendor: <strong>{{ '['.$lease->supplier->code.'] '.$lease->supplier->name }}</strong>, Contract ID: <strong>{{ $lease->contract_id }}</strong>, Reference: <strong>{{ $lease->contract_reference }}</strong>
            </td>
            <td style="width: 33%;">
                Interest Rate: <strong>{{ $lease->rate.'%' }}</strong> for <strong>{{ $lease->year }}</strong> years, <strong>{{ ucwords($lease->pay_interval) }}</strong> Installments
            </td>
        </tr>
        <tr>
            <td style="width: 33%;">
                Lease Amount: <strong>{{ $lease->exchangeRate->currency->code.' '.systemMoneyFormat($lease->amount) }}</strong>
            </td>

            <td style="width: 33%;">
                Depreciated Amount: <strong>{{ $lease->exchangeRate->currency->code.' '.systemMoneyFormat($lease->depreciations->where('status', 'depreciated')->sum('amount')) }}</strong>
            </td>

            <td style="width: 33%;">
                Lease Book Value: <strong>{{ $lease->exchangeRate->currency->code.' '.systemMoneyFormat($lease->amount-$lease->depreciations->where('status', 'depreciated')->sum('amount')) }}</strong>
            </td>
        </tr>
    </table>

    <h5 class="mt-3 mb-1"><strong>Lease Depreciations</strong></h5>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <td style="width: 15%" class="text-center"><strong>Date</strong></td>
                <td style="width: 15%" class="text-center"><strong>From</strong></td>
                <td style="width: 15%" class="text-center"><strong>To</strong></td>
                <td style="width: 15%" class="text-center"><strong>Amount</strong></td>
                <td style="width: 40%" class="text-center"><strong>Status</strong></td>
            </tr>
        </thead>
        <tbody>
            @if($lease->depreciations->count() > 0)
            @foreach($lease->depreciations as $key => $depreciation)
            <tr>
                <td class="text-center">{{ $depreciation->date }}</td>
                <td class="text-center">{{ $depreciation->from }}</td>
                <td class="text-center">{{ $depreciation->to }}</td>
                <td class="text-center">{{ systemMoneyFormat($depreciation->amount) }}</td>
                <td class="text-center">
                    @if($depreciation->status == 'pending')
                        <a class="btn btn-xs btn-warning">Pending</a>
                    @elseif($depreciation->status == 'depreciated')
                        <a class="btn btn-xs btn-success">Depreciated</a>
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
</body>
</html>   