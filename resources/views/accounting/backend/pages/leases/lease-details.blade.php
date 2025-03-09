<hr class="pt-0 mt-0">

<table class="table table-bordered">
    <tr>
        <td style="width: 35%">Company</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ '['.$lease->costCentre->profitCentre->company->code.'] '.$lease->costCentre->profitCentre->company->name }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Profit Centre</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ '['.$lease->costCentre->profitCentre->code.'] '.$lease->costCentre->profitCentre->name }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Cost Centre</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ '['.$lease->costCentre->code.'] '.$lease->costCentre->name }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Vendor</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ '['.$lease->supplier->code.'] '.$lease->supplier->name }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Contract ID</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ $lease->contract_id }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Reference</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ $lease->contract_reference }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Interest Rate</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ $lease->rate.'%' }}</strong> for <strong>{{ $lease->year }}</strong> years, <strong>{{ ucwords($lease->pay_interval) }}</strong> Installments
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Lease Amount</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ $lease->exchangeRate->currency->symbol.' '.systemMoneyFormat($lease->amount) }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Installment Amount</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ $lease->exchangeRate->currency->symbol.' '.systemMoneyFormat($lease->installment_amount) }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Payable Amount</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ $lease->exchangeRate->currency->symbol.' '.systemMoneyFormat($lease->total_payable_amount) }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Posted Amount</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ $lease->exchangeRate->currency->symbol.' '.systemMoneyFormat($lease->schedules->whereNotIn('status', ['planned'])->sum('principle')+$lease->schedules->whereNotIn('status', ['planned'])->sum('interest')) }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Paid Amount</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ $lease->exchangeRate->currency->symbol.' '.systemMoneyFormat($lease->schedules->where('status', 'paid')->sum('principle')+$lease->schedules->where('status', 'paid')->sum('interest')) }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Due Amount</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ $lease->exchangeRate->currency->symbol.' '.systemMoneyFormat($lease->schedules->whereNotIn('status', ['paid'])->sum('principle')+$lease->schedules->whereNotIn('status', ['paid'])->sum('interest')) }}</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 35%">Depreciated Amount</td>
        <td style="width: 5%" class="text-center">:</td>
        <td style="width: 60%">
            <strong>{{ $lease->exchangeRate->currency->symbol.' '.systemMoneyFormat($lease->depreciations->where('status', 'depreciated')->sum('amount')) }}</strong>
        </td>
    </tr>
</table>