<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 25%">Sales Order</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->salesOrder->code }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Datetime</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ date('Y-m-d g:i a', strtotime($model->datetime)) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Reference</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->reference }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Amount</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($model->amount) }} {{ $model->salesOrder->exchangeRate->currency->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Type</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ ucwords($model->type) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Status</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ ucwords($model->status) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Comments</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->comments }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>