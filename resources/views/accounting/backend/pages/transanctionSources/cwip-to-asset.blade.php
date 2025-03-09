<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 25%">GRN</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->goodsReceivedItemsStockIn->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Asset</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->name }} {{ getProductAttributesFaster($model->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Batch</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->batch }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Asset Codes</td>
                <td style="width: 5%" class="text-center">:</td>
                <td>
                    @if($model->items->count() > 0) 
                    <ul>
                        @foreach($model->items as $key => $item)
                        <li><strong>{{ $item->asset_code }}</strong></li>
                        @endforeach
                    </ul>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 25%">Depreciation Method</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->depreciationMethod->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Depreciation Start Date</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->depreciation_start_date }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>