@php
    $amount = \App\Models\FixedAssets\FixedAssetBatchItemDepreciation::whereHas('batchItem', function($query) use($model){
        return $query->where('asset_code', $model->asset_code);
    })
    ->where('from', $sources['from'])
    ->where('to', $sources['to'])
    ->sum('amount');

    $depreciation = \App\Models\FixedAssets\FixedAssetBatchItemDepreciation::whereHas('batchItem', function($query) use($model){
        return $query->where('asset_code', $model->asset_code);
    })
    ->where('from', $sources['from'])
    ->where('to', $sources['to'])
    ->first();
@endphp

<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 35%">GRN</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Asset</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->finalAsset->name }} {{ getProductAttributesFaster($model->finalAsset) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Asset Code</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->asset_code }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Depreciation Method</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->batch->depreciationMethod->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Depreciation Start Date</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->batch->depreciation_start_date }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Depreciation Date</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $depreciation->date }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Depreciation Duration</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $depreciation->from.' to '.$depreciation->to }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Depreciation Amount</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $amount }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Depreciation Remarks</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $depreciation->remarks }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>