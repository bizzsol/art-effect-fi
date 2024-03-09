<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 25%">Sales Order</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->salesOrderDelivery->salesOrder->code }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Sales Order Delivery</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->salesOrderDelivery->code }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Product</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->product->name }} {{ getProductAttributesFaster($model->product) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Warehouse</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->warehouse->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Quantity</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->quantity }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>