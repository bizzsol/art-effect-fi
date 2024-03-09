<div class="col-md-6">
    <div class="row">
        @if($entryPointLedgers->where('type', 'D')->count() > 0)
        @foreach($entryPointLedgers->where('type', 'D') as $ledger)
        <div class="col-md-12 mb-3">
            <div class="form-group">
                <label for="ledger-{{ $ledger->id }}"><strong>[{{ $ledger->chartOfAccount->code }}] {{ $ledger->chartOfAccount->name }} ({{ $ledger->type == 'D' ? 'Dr.' : 'Cr.' }}) (%)</strong></label>
                <input type="number" name="debit_percentages[{{ $ledger->chart_of_account_id }}]" value="0" class="form-control">
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>

<div class="col-md-6">
    <div class="row">
        @if($entryPointLedgers->where('type', 'C')->count() > 0)
        @foreach($entryPointLedgers->where('type', 'C') as $ledger)
        <div class="col-md-12 mb-3">
            <div class="form-group">
                <label for="ledger-{{ $ledger->id }}"><strong>[{{ $ledger->chartOfAccount->code }}] {{ $ledger->chartOfAccount->name }} ({{ $ledger->type == 'D' ? 'Dr.' : 'Cr.' }}) (%)</strong></label>
                <input type="number" name="credit_percentages[{{ $ledger->chart_of_account_id }}]" value="0" class="form-control">
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>