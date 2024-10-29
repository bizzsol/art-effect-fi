<table>
    <tr>
        <td><strong>SL</strong></td>
        <td><strong>Code</strong></td>
        <td><strong>Name</strong></td>
        <td><strong>Company</strong></td>
        <td><strong>Bank Accounts</strong></td>
    </tr>

    @if(isset($ledgers[0]))
    @foreach($ledgers as $key => $ledger)
    <tr>
        <td>{{ $key+1 }}</td>
        <td>{{ $ledger->code }}</td>
        <td>{{ $ledger->name }}</td>
        <td>{{ $ledger->companies->pluck('company.code')->implode(', ') }}</td>
        <td>{{ $ledger->bankAccounts->pluck('number')->implode(', ') }}</td>
    </tr>
    @endforeach
    @endif
</table>