@php
    $assets = \App\Models\FixedAssets\FixedAssetBatchItem::where('asset_code', $model->asset_code)->where('is_disposed', 1)->get();
    $depreciations = 0;
    if($assets->count() > 0){
        foreach($assets as $key => $asset){
            $depreciations += $asset->depreciations->sum('amount');
        }
    }

    $gain_loss = 'equal';
    $due = $assets->sum('asset_value')-($depreciations+$assets->sum('disposal_amount'));
    if($due > 0){
        $gain_loss = 'loss';
    }else{
        $gain_loss = 'gain';
        $due = $due*(-1);
    }
@endphp
<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
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
                <td style="width: 35%">Disposal Type</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->disposal_type }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Cost</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($assets->sum('asset_value')) }}</strong></td>
            </tr>
    
            <tr>
                <td style="width: 35%">Depreciations</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($depreciations) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Disposal Amount</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($assets->sum('disposal_amount')) }}</strong></td>
            </tr>
            @if($gain_loss == 'gain')
            <tr>
                <td style="width: 35%">Gain</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong class="text-{{ $gain_loss == 'gain' ? 'success' : 'danger' }}">{{ $due }}</strong></td>
            </tr>
            @endif

            @if($gain_loss == 'loss')
            <tr>
                <td style="width: 35%">Loss</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong class="text-{{ $gain_loss == 'gain' ? 'success' : 'danger' }}">{{ $due }}</strong></td>
            </tr>
            @endif
    
            <tr>
                <td style="width: 35%">Disposal Remarks</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->disposal_remarks }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Disposed At</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ date('Y-m-d g:i a', strtotime($model->disposed_at)) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Disposed By</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ \App\User::find($model->disposed_by)->name }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>