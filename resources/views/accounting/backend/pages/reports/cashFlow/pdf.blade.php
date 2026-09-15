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
          <td style="width: 20%;padding: 10px 10px 10px 10px !important">
            <h5 style="font-size: 16px;"><strong>Codes</strong></h5>
          </td>
          <td style="width: 50%;padding: 10px 10px 10px 10px !important">
            <h5 style="font-size: 16px;"><strong>Cash Flow from Operating Activities</strong></h5>
          </td>
          <td style="width: 30%;padding: 10px 10px 10px 10px !important" class="text-right">
            <h5 style="font-size: 16px;"><strong>Amount ({{ $currency->code }})</strong></h5>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><strong>Net Profit for the Period</strong></td>
          <td class="text-right"><strong>{{ systemMoneyFormat($netProfit) }}</strong></td>
        </tr>
        <tr>
          <td colspan="3"><strong>Adjustments for Changes in Working Capital:</strong></td>
        </tr>
        {!! $adjustments !!}
        <tr>
          <td colspan="2"><strong>Net Cash Generated from/(Used in) Operating Activities:</strong></td>
          <td class="text-right"><strong>{{ systemMoneyFormat($netCashFromOperating) }}</strong></td>
        </tr>
      </table>

      <table class="table table-bordered">
        <tr>
          <td style="width: 75%"><strong>Net Increase/(Decrease) in Cash and Cash Equivalents</strong></td>
          <td style="width: 25%" class="text-right"><strong>{{ systemMoneyFormat($netCashFromOperating) }}</strong></td>
        </tr>
        <tr>
          <td>Cash and Cash Equivalents at the Beginning of the Period</td>
          <td class="text-right">{{ systemMoneyFormat($openingCash) }}</td>
        </tr>
        <tr>
          <td><strong>Cash and Cash Equivalents at the End of the Period</strong></td>
          <td class="text-right"><strong>{{ systemMoneyFormat($closingCash) }}</strong></td>
        </tr>
      </table>

      <table class="table table-bordered">
        <tr>
          <td style="width: 15%;padding: 10px !important"><h5 style="font-size: 16px;"><strong>Code</strong></h5></td>
          <td style="width: 45%;padding: 10px !important"><h5 style="font-size: 16px;"><strong>Cash & Bank Ledger</strong></h5></td>
          <td style="width: 20%;padding: 10px !important" class="text-right"><h5 style="font-size: 16px;"><strong>Opening Balance</strong></h5></td>
          <td style="width: 20%;padding: 10px !important" class="text-right"><h5 style="font-size: 16px;"><strong>Closing Balance</strong></h5></td>
        </tr>
        {!! $cashLedgers !!}
      </table>
    </div>
  </body>
</html>
