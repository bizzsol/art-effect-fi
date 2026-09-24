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
        padding-left: 5px !important;
        padding-right: 5px !important;
      }
      .table-bordered {
        border-collapse: collapse;
      }
      .table-bordered td {
        border: 1px solid #000000;
        padding: 4px;
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
      <h2 style="padding-top: 115px"><strong>{{ $title }}</strong></h2>
    </htmlpageheader>

    <htmlpagefooter name="page-footer">
      <table class="table-bordered">
        <tbody>
          <tr>
            <td colspan="2" style="text-align: center;border: none !important">
                {{ $title }} Printed by <strong>{{ auth()->user()->name  }}</strong>
            </td>
          </tr>
          <tr style="border: none !important">
            <td style="height: 50px; !important;border: none !important;border-right: none !important"></td>
            <td style="height: 50px; !important;border: none !important;border-left: none !important"></td>
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
          <td colspan="4" class="text-center" style="padding: 8px !important"><h5><strong>SUMMARY &ndash; ALL BANK LEDGERS</strong></h5></td>
        </tr>
        <tr>
          <td style="width: 25%;padding: 8px !important" class="text-right"><strong>Total Opening</strong></td>
          <td style="width: 25%;padding: 8px !important" class="text-right"><strong>Total Receipts</strong></td>
          <td style="width: 25%;padding: 8px !important" class="text-right"><strong>Total Payments</strong></td>
          <td style="width: 25%;padding: 8px !important" class="text-right"><strong>Total Closing</strong></td>
        </tr>
        <tr>
          <td class="text-right">{{ systemMoneyFormat($totalOpening) }}</td>
          <td class="text-right">{{ systemMoneyFormat($totalReceipts) }}</td>
          <td class="text-right">{{ systemMoneyFormat($totalPayments) }}</td>
          <td class="text-right">{{ systemMoneyFormat($totalClosing) }}</td>
        </tr>
      </table>

      <h5><strong>LEDGER-WISE / TRANSACTION-WISE DETAIL</strong></h5>
      <table class="table table-bordered">
        <tr>
          <td style="width: 14%"><strong>Bank Ledger Code</strong></td>
          <td style="width: 9%"><strong>Transaction Date</strong></td>
          <td style="width: 9%"><strong>Voucher Ref</strong></td>
          <td style="width: 9%"><strong>Type</strong></td>
          <td style="width: 6%" class="text-center"><strong>Currency</strong></td>
          <td style="width: 9%" class="text-right"><strong>Opening</strong></td>
          <td style="width: 9%" class="text-right"><strong>Receipts</strong></td>
          <td style="width: 9%" class="text-right"><strong>Payments</strong></td>
          <td style="width: 10%" class="text-right"><strong>Balance / Closing</strong></td>
          <td style="width: 16%"><strong>Voucher Narration</strong></td>
        </tr>
        @forelse($ledgers as $ledger)
            @php $ledgerLabel = $ledger['account']->code . ' - ' . $ledger['account']->name; @endphp
            <tr>
                <td>{{ $ledgerLabel }}</td>
                <td></td>
                <td></td>
                <td>Opening Balance</td>
                <td class="text-center">{{ $currencyCode }}</td>
                <td class="text-right">{{ systemMoneyFormat($ledger['opening']) }}</td>
                <td></td>
                <td></td>
                <td class="text-right">{{ systemMoneyFormat($ledger['opening']) }}</td>
                <td></td>
            </tr>
            @foreach($ledger['rows'] as $row)
                <tr>
                    <td>{{ $ledgerLabel }}</td>
                    <td>{{ date('d-M-Y', strtotime($row['date'])) }}</td>
                    <td>{{ $row['voucher'] }}</td>
                    <td>{{ $row['type'] }}</td>
                    <td class="text-center">{{ $currencyCode }}</td>
                    <td></td>
                    <td class="text-right">{{ $row['receipts'] > 0 ? systemMoneyFormat($row['receipts']) : '' }}</td>
                    <td class="text-right">{{ $row['payments'] > 0 ? systemMoneyFormat($row['payments']) : '' }}</td>
                    <td class="text-right">{{ systemMoneyFormat($row['balance']) }}</td>
                    <td>{{ $row['narration'] }}</td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="10" class="text-center">No bank/cash ledgers found for this company.</td>
            </tr>
        @endforelse
        <tr>
          <td colspan="5" class="text-right"><strong>GRAND TOTAL &ndash; ALL BANK LEDGERS</strong></td>
          <td class="text-right"><strong>{{ systemMoneyFormat($totalOpening) }}</strong></td>
          <td class="text-right"><strong>{{ systemMoneyFormat($totalReceipts) }}</strong></td>
          <td class="text-right"><strong>{{ systemMoneyFormat($totalPayments) }}</strong></td>
          <td class="text-right"><strong>{{ systemMoneyFormat($totalClosing) }}</strong></td>
          <td></td>
        </tr>
      </table>
    </div>
  </body>
</html>
