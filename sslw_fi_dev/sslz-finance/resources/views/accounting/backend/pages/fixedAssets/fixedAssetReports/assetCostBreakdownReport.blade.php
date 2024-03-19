<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Asset Name</th>
            <th>Sub Category</th>
            <th>Category</th>
            <th>Identification Mark</th>
            <th>Capitalization Date</th>
            <th>Quantity</th>
            <th>Opening Asset</th>
            <th>Addition</th>
            <th>Depreciation</th>
            <th>Disposal</th>
            <th>Closing Asset</th>
        </tr>
    </thead>
    <tbody>
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
            <td>{{ $key+1 }}</td>
            <td>{{ isset($item->finalAsset->name) ? $item->finalAsset->name.' '.getProductAttributesFaster($item->finalAsset) : '' }}</td>
            <td>{{ isset($item->finalAsset->category->name) ? $item->finalAsset->category->name : '' }}</td>
            <td>{{ isset($item->finalAsset->category->category->name) ? $item->finalAsset->category->category->name : '' }}</td>
            <td class="text-center">{{ $item->asset_code }}</td>
            <td class="text-center">{{ date('Y-m-d', strtotime($item->batch->created_at)) }}</td>
            <td class="text-center">{{ '1 '.(isset($item->finalAsset->productUnit->unit_name) ? $item->finalAsset->productUnit->unit_name : '') }}</td>
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
            <td colspan="7" class="text-right">
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