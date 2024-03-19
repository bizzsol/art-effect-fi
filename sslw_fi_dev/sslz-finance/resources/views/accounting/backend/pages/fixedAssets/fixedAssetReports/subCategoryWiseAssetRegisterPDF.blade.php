<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>
        <style>
            @page {
                margin-top: .75in;
                margin-bottom: .75in;
                header: page-header;
                footer: page-footer;
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
                <div class="col-md-6" style="width: 100%;float:left;padding-top: 15px">
                    <h2><strong>{{ $title }}</strong></h2>
                </div>
            </div>
        </htmlpageheader>

        <htmlpagefooter name="page-footer">
            <table class="table-bordered">
                <tbody>
                    <tr>
                        <td style="border: none !important;width: 85%">
                            {{ $title }} printed by <strong>{{ auth()->user()->name }}, Time & Date: {{ date('g.i a, d.m.Y') }}</strong>
                        </td>
                        <td style="text-align: right;border: none !important;width: 15%">
                            <small>Page {PAGENO} of {nb}</small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </htmlpagefooter>
        
        <div class="container">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                       <td><strong>#</strong></td> 
                       <td><strong>Asset Name</strong></td> 
                       <td><strong>Identification Mark</strong></td> 
                       <td><strong>Capitalization Date</strong></td> 
                       <td><strong>Quantity</strong></td>
                       <td><strong>Opening Asset</strong></td> 
                       <td><strong>Addition</strong></td> 
                       <td><strong>Disposal</strong></td> 
                       <td><strong>Cost</strong></td> 
                       <td><strong>Depreciation Rate</strong></td> 
                       <td><strong>Opening Depreciation</strong></td> 
                       <td><strong>Depreciation for Period</strong></td> 
                       <td><strong>Accumulated Depreciation</strong></td> 
                       <td><strong>Book Value</strong></td> 
                       <td><strong>Location</strong></td> 
                       <td><strong>Status</strong></td>  
                    </tr>
                    @php
                        $total_opening_asset = 0;
                        $total_addition = 0;
                        $total_disposal = 0;
                        $total_cost = 0;
                        $total_opening_depreciation = 0;
                        $total_period_depreciation = 0;
                        $total_accumulated_depreciation = 0;
                        $total_book_value = 0;
                    @endphp
                    @if(isset($items[0]))
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

                        $opening_asset = ($opening_addition+$opening_disposal)-$opening_depreciated;

                        $addition = $allBatchItems->where('asset_code', $item->asset_code)
                        ->where('created_at', '>=', $from)
                        ->where('created_at', '<=', $to)
                        ->sum('asset_value');

                        $disposal = $allBatchItems->where('asset_code', $item->asset_code)
                        ->where('is_disposed', 1)
                        ->where('disposed_at', '>=', $from)
                        ->where('disposed_at', '<=', $to)
                        ->sum('asset_value');

                        $cost = (($opening_addition+$opening_disposal)-$opening_depreciated)+$addition-$disposal;

                        $total_opening_asset += $opening_asset;
                        $total_addition += $addition;
                        $total_disposal += $disposal;
                        $total_cost += $cost;

                        $assetValue = $allBatchItems->where('asset_code', $item->asset_code)
                        ->where('is_disposed', 0)
                        ->sum('asset_value');
                        
                        $opening_depreciation = $allDepreciations->where('to', '<', $from)
                        ->where('batchItem.asset_code', $item->asset_code)
                        ->where('batchItem.is_disposed', 0)
                        ->sum('amount');
                        $total_opening_depreciation += $opening_depreciation;

                        $accumulated_depreciation = $allDepreciations->where('to', '<=', $to)
                        ->where('batchItem.asset_code', $item->asset_code)
                        ->where('batchItem.is_disposed', 0)
                        ->sum('amount');
                        $total_accumulated_depreciation += $accumulated_depreciation;
                        
                        $period_depreciation = ($accumulated_depreciation-$opening_depreciation);
                        $total_period_depreciation += $period_depreciation;
                        
                        $book_value = ($assetValue-$accumulated_depreciation);
                        $total_book_value += $book_value;
                    @endphp
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>
                            {{ isset($item->finalAsset->name) ? $item->finalAsset->name.' '.getProductAttributesFaster($item->finalAsset) : '' }}
                        </td>
                        <td>{{ $item->asset_code }}</td>
                        <td>{{ date('Y-m-d', strtotime($item->batch->created_at)) }}</td>
                        <td>
                            {{ '1 '.(isset($item->finalAsset->productUnit->unit_name) ? $item->finalAsset->productUnit->unit_name : '') }}
                        </td>
                        <td style="text-align: right">
                            {{ systemMoneyFormat($opening_asset) }}
                        </td>
                        <td style="text-align: right">
                            {{ systemMoneyFormat($addition) }}
                        </td>
                        <td style="text-align: right">
                            {{ systemMoneyFormat($disposal) }}
                        </td>
                        <td style="text-align: right">
                            {{ systemMoneyFormat($cost) }}
                        </td>
                        <td style="text-align: right">{{ $item->batch->depreciation_rate.'%' }}</td>
                        <td style="text-align: right">
                            {{ systemMoneyFormat($opening_depreciation) }}
                        </td>
                        <td style="text-align: right">
                            {{ systemMoneyFormat($period_depreciation) }}
                        </td>
                        <td style="text-align: right">
                            {{ systemMoneyFormat($accumulated_depreciation) }}
                        </td>
                        <td style="text-align: right">{{ systemMoneyFormat($book_value) }}</td>
                        <td>{{ $item->currentUser ? $item->currentUser->fixedAssetLocation->name : 'Not Distributed' }}</td>
                        <td>{{ $item->is_disposed == 0 ? 'Active' : ucwords($item->disposal_type) }}</td>
                    </tr>
                    @endforeach
                    @endif

                    <tr>
                        <td colspan="5"><strong>Total:</strong></td>
                        <td style="text-align: right">
                            <strong>{{ systemMoneyFormat($total_opening_asset) }}</strong>
                        </td>
                        <td style="text-align: right">
                            <strong>{{ systemMoneyFormat($total_addition) }}</strong>
                        </td>
                        <td style="text-align: right">
                            <strong>{{ systemMoneyFormat($total_disposal) }}</strong>
                        </td>
                        <td style="text-align: right">
                            <strong>{{ systemMoneyFormat($total_cost) }}</strong>
                        </td>
                        <td style="text-align: right"></td>
                        <td style="text-align: right">
                            <strong>{{ systemMoneyFormat($total_opening_depreciation) }}</strong>
                        </td>
                        <td style="text-align: right">
                            <strong>{{ systemMoneyFormat($total_period_depreciation) }}</strong>
                        </td>
                        <td style="text-align: right">
                            <strong>{{ systemMoneyFormat($total_accumulated_depreciation) }}</strong>
                        </td>
                        <td style="text-align: right">
                            <strong>{{ systemMoneyFormat($total_book_value) }}</strong>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>                                                                                                                                                                                                                             