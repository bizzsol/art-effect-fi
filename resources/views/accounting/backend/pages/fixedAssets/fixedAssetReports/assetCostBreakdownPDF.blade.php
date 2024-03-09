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
          <h2><strong>{{ $title }}</strong></h2>
        </div>
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
      <table class="table table-bordered table-striped table-hover">
        <tbody>
            <tr>
              <td style="width: 27.5%"><strong>Asset Name</strong></td>
              <td style="width: 10%"><strong>Asset Code</strong></td>
              <td style="width: 12.5%"><strong>Opening Asset</strong></td>
              <td style="width: 12.5%"><strong>Addition</strong></td>
              <td style="width: 12.5%"><strong>Depreciation</strong></td>
              <td style="width: 12.5%"><strong>Disposal</strong></td>
              <td style="width: 12.5%"><strong>Closing Asset</strong></td>
            </tr>
            @php
              $total_opening_asset = 0;
              $total_addition = 0;
              $total_depreciated = 0;
              $total_disposal = 0;
              $total_closing_asset = 0;
            @endphp

            @if($items->count() > 0)
            @foreach($items as $key => $item)
            @php
                $opening_addition = $allBatchItems->where('asset_code', $item->asset_code)
                ->where('created_at', '<', $from)
                ->sum('asset_value');
                $opening_depreciated = $allDepreciations->where('to', '<', $from)
                ->where('batchItem.asset_code', $item->asset_code)
                ->where('batchItem.is_disposed', 0)
                ->sum('amount');
                $opening_disposal = $allBatchItems->where('asset_code', $item->asset_code)
                ->where('is_disposed', 1)
                ->where('disposed_at', '<', $from)
                ->sum('asset_value');

                $opening_asset = ($opening_addition-$opening_disposal)-$opening_depreciated;
                $total_opening_asset += $opening_asset;
                
                $addition = $allBatchItems->where('asset_code', $item->asset_code)
                ->where('created_at', '>=', $from)
                ->where('created_at', '<=', $to)
                ->sum('asset_value');
                $total_addition += $addition;

                $opening_depreciation = $allDepreciations->where('to', '<', $from)
                ->where('batchItem.asset_code', $item->asset_code)
                ->where('batchItem.is_disposed', 0)
                ->sum('amount');
                $accumulated_depreciation = $allDepreciations->where('to', '<=', $to)
                ->where('batchItem.asset_code', $item->asset_code)
                ->where('batchItem.is_disposed', 0)
                ->sum('amount');
                $depreciated = $accumulated_depreciation-$opening_depreciation;
                $total_depreciated += $depreciated;

                $disposal = $allBatchItems->where('asset_code', $item->asset_code)
                  ->where('is_disposed', 1)
                  ->where('disposed_at', '>=', $from)
                  ->where('disposed_at', '<=', $to)
                  ->sum('asset_value');
                $total_disposal += $disposal;

                $closing_asset = ($opening_asset+$addition-$disposal)-$depreciated;
                $total_closing_asset += $closing_asset;
            @endphp
            <tr>
              <td>{{ isset($item->finalAsset->name) ? $item->finalAsset->name.' '.getProductAttributesFaster($item->finalAsset) : '' }}</td>
              <td class="text-center">{{ $item->asset_code }}</td>
              <td class="text-right">
                  {{ systemMoneyFormat($opening_asset) }}
              </td>
              <td class="text-right">
                  {{ systemMoneyFormat($addition) }}
              </td>
              <td class="text-right">
                  {{ systemMoneyFormat($depreciated) }}
              </td>
              <td class="text-right">
                  {{ systemMoneyFormat($disposal) }}
              </td>
              <td class="text-right">
                  {{ systemMoneyFormat($closing_asset) }}
              </td>
            </tr>
            @endforeach
            @endif

            <tr>
              <td colspan="2" class="text-right">
                  <strong>Total:</strong>
              </td>
              <td class="text-right">
                  <strong>{{ systemMoneyFormat($total_opening_asset) }}</strong>
              </td>
              <td class="text-right">
                  <strong>{{ systemMoneyFormat($total_addition) }}</strong>
              </td>
              <td class="text-right">
                  <strong>{{ systemMoneyFormat($total_depreciated) }}</strong>
              </td>
              <td class="text-right">
                  <strong>{{ systemMoneyFormat($total_disposal) }}</strong>
              </td>
              <td class="text-right">
                  <strong>{{ systemMoneyFormat($total_closing_asset) }}</strong>
              </td>
            </tr>
        </tbody>
    </table>
    </div>
  </body>
</html>                                                                                                               