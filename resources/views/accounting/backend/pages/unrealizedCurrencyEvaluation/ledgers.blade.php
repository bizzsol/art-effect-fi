<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Code</th>
            <th>Ledger</th>
            <th>Transaction Amount</th>
            <th>Transaction Exchange Rate</th>
            <th>Reporting Amount</th>
            <th>Run Date Exchange Rate</th>
            <th>Unrealized Amount</th>
            <th>Gain</th>
            <th>Loss</th>
        </tr>
    </thead>
    <tbody>
        @if($event->ledgers->count() > 0)
        @foreach($event->ledgers as $key => $ledger)
        <tr>
            <td>{{ $ledger->chartOfAccount->code }}</td>
            <td>{{ $ledger->chartOfAccount->name }}</td>
            <td>{{ $ledger->transactionCurrency->symbol }} {{ $ledger->transaction_amount }}</td>
            <td>{{ $ledger->transaction_exchange_rate }}</td>
            <td>{{ $ledger->reportingCurrency->symbol }} {{ $ledger->reporting_amount }}</td>
            <td>{{ $ledger->run_date_exchange_rate }}</td>
            <td>{{ $ledger->reportingCurrency->symbol }} {{ $ledger->unrealized_amount }}</td>
            <td>{{ $ledger->gain }}</td>
            <td>{{ $ledger->loss }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>