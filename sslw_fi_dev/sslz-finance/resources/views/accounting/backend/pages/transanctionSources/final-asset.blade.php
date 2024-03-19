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
        </tbody>
    </table>
</div>
