<table class="table table-bordered mb-0">
    <thead>
        <tr>
            <th>Date</th>
            <th colspan="2">Transaction Currency</th>
            <th>Exchange Rate</th>
            <th colspan="2">Reporting Currency</th>
            <th>Run Date Rate</th>
            <th colspan="2">UnRealized Amount</th>
            <th colspan="2">Gain</th>
            <th colspan="2">Loss</th>
        </tr>
    </thead>
    <tbody>
        @php
            $run_rate = isset(json_decode($run_date['rate']->rates, true)[$systemCurrency->id]['rate']) ? json_decode($run_date['rate']->rates, true)[$systemCurrency->id]['rate'] : 1;
            $transactions = $entries->where('entry.exchangeRate.currency_id', $currency->id)->where('chart_of_account_id', $ledger->id);
        @endphp
        @if($transactions->count() > 0)
        @foreach($transactions as $key => $transaction)
        @php
            $rate = exchangeRate($transaction->entry->exchangeRate, $systemCurrency->id);
            $transaction_currency = $transaction->amount*($transaction->debit_credit == 'D' ? 1 : (-1));
            $reporting_currency = $transaction_currency*$rate;

            $unrealized_amount = $transaction_currency*$run_rate;
            $gain = $reporting_currency > $unrealized_amount ? $reporting_currency-$unrealized_amount : 0;
            $loss = $reporting_currency < $unrealized_amount ? $unrealized_amount-$reporting_currency : 0;
        @endphp
        <tr>
            <td>{{ $transaction->entry->date }}</td>
            <td>{{ $currency->symbol }}</td>
            <td class="text-right">{{ $transaction_currency }}</td>
            <td class="text-center">{{ systemDoubleValue($rate, 2) }}</td>
            <td>{{ $systemCurrency->symbol }}</td>
            <td class="text-right">{{ $reporting_currency }}</td>
            <td class="text-center">{{ $run_rate }}</td>
            <td>{{ $systemCurrency->symbol }}</td>
            <td class="text-right">{{ $unrealized_amount }}</td>
            <td>{{ $systemCurrency->symbol }}</td>
            <td class="text-success text-right">{{ $gain }}</td>
            <td>{{ $systemCurrency->symbol }}</td>
            <td class="text-danger text-right">{{ $loss }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>