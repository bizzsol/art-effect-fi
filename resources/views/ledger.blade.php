<div class="card">
    <div class="card-body bordered">
        <h5 class="floating-title">{{ $text }} {{ isset($account->code) ? '('.$account->code.')' : '' }}</h5>
        <div class="row">
            <div class="col-md-12">
                <label for="{{ $field }}_name"><strong>{{ __('Ledger Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                <div class="input-group input-group-md mb-3 d-">
                    <input type="text" name="ledger_name[{{ $field }}]" id="{{ $field }}_name" value="{{ old('ledger_name.'.$field, (isset($account->name) ? $account->name : '')) }}" class="form-control rounded">
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <label for="{{ $field }}_type"><small><strong>{{ __('Opening Balance Type') }}:<span class="text-danger">&nbsp;*</span></strong></small></label>
                        <div class="input-group input-group-md mb-3 d-">
                            <select name="ledger_type[{{ $field }}]" id="{{ $field }}_type" class="form-control rounded">
                                <option value="D" {{ isset($account->type) && $account->type == "D" ? 'selected' : '' }}>Debit</option>
                                <option value="C" {{ isset($account->type) && $account->type == "C" ? 'selected' : '' }}>Credit</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="{{ $field }}_opening_balance"><strong>Opening Balance</strong></label>
                        <div class="input-group input-group-md mb-3 d-">
                            <input type="number" step="0.01" name="opening_balance[{{ $field }}]" id="{{ $field }}_opening_balance" value="{{ old('opening_balance.'.$field, (isset($account->opening_balance) ? $account->opening_balance : 0)) }}" class="form-control text-right rounded">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>