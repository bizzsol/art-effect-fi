<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 35%">Requisition</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->relRequisitionDelivery->relRequisition->reference_no }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Requisition By</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->relRequisitionDelivery->relRequisition->relUsersList->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Product</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->product->name }} {{ getProductAttributesFaster($model->product) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Warehouse</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->warehouse->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Delivery QTY</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->delivery_qty }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
