<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 35%">Purchase Order</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->relPurchaseOrder->reference_no }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Warehouse</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->relWarehouse->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Reference No</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->reference_no }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">GRN Reference No</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Product</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->relGoodsReceivedItems->relProduct->name }} {{ getProductAttributesFaster($model->relGoodsReceivedItems->relProduct) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Unit Amount</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($model->unit_amount) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Received QTY</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->received_qty }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Sub Total</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($model->sub_total) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Discount</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($model->discount) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Vat</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($model->vat) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Total Amount</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($model->total_amount) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">GRN</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->is_grn_complete }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
