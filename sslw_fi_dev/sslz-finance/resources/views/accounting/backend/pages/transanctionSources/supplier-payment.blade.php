<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 35%">Supplier</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->relSupplier->name }} [{{ $model->relSupplier->code }}]</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Purchase Order</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->relPurchaseOrder->reference_no }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Transection Date</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ date('Y-m-d', strtotime($model->transection_date)) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Transection Type</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->transection_type }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Bill Type</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->bill_type }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Bill Amount</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($model->bill_type == 'po-advance' ? $model->pay_amount : $model->bill_amount) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
