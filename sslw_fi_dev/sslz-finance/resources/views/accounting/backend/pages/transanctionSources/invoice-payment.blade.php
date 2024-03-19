<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 25%">Sales Order</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->code }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Customer</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->customer->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Reference</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->reference }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Datetime</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ date('Y-m-d g:i a', strtotime($model->datetime)) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Sales Payment Type</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->salesPaymentType->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Sales Type</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->salesType->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Currency</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->exchangeRate->currency->name }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Comments</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->comments }}</strong></td>
            </tr>
            <tr>
                <td style="width: 25%">Bill Amount</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($model->bills->sum('amount')) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>