<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 25%">Vendor</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->supplier ? $model->supplier->name.' ('.$model->supplier->code.')' : ($model->purchaseOrder->relQuotation->relSuppliers->name.' ('.$model->purchaseOrder->relQuotation->relSuppliers->code.')') }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Purchase Order</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->purchaseOrder->reference_no }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">GRN</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->goodsReceivedItemsStockIn->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Product</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->name }} {{ getProductAttributesFaster($model->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Date & Time</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ date('Y-m-d g:i a', strtotime($model->date.' '.$model->time)) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Cost</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($model->cost) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Details</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->details }}</strong></td>
            </tr>
        </tbody>
    </table> 
</div>
