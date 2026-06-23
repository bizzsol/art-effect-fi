<form action="{{ route('accounting.exchange-rates.update', $exchangeRate->id) }}" method="post" accept-charset="utf-8" id="edit-exchange-rate-form" novalidate>
    @csrf
    @method('PUT')
    <input type="hidden" name="currency_id" value="{{ $exchangeRate->currency_id }}">
    <div class="row pr-3">
        <div class="col-md-3">
            <label><strong>{{ __('Currency') }}:</strong></label>
            <div class="input-group input-group-md mb-3">
                <input type="text" class="form-control rounded bg-white" value="{{ $exchangeRate->currency->name }} ({{ $exchangeRate->currency->code }} | {{ $exchangeRate->currency->symbol }})" readonly>
            </div>
        </div>
        <div class="col-md-2">
            <label for="edit_date"><strong>{{ __('Date') }}:<span class="text-danger">&nbsp;*</span></strong></label>
            <div class="input-group input-group-md mb-3">
                <input type="date" name="date" id="edit_date" value="{{ date('Y-m-d', strtotime($exchangeRate->datetime)) }}" class="form-control rounded">
            </div>
        </div>
        <div class="col-md-2">
            <label for="edit_time"><strong>{{ __('Time') }}:<span class="text-danger">&nbsp;*</span></strong></label>
            <div class="input-group input-group-md mb-3">
                <input type="time" name="time" id="edit_time" value="{{ date('H:i:s', strtotime($exchangeRate->datetime)) }}" class="form-control rounded">
            </div>
        </div>
        <div class="col-md-2">
            <label for="edit_reference"><strong>{{ __('Reference') }}:</strong></label>
            <div class="input-group input-group-md mb-3">
                <input type="text" name="reference" id="edit_reference" value="{{ $exchangeRate->reference }}" class="form-control rounded bg-white" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <label for="edit_desc"><strong>{{ __('Description') }}:</strong></label>
            <div class="input-group input-group-md mb-3">
                <input type="text" name="desc" id="edit_desc" value="{{ $exchangeRate->desc }}" class="form-control rounded">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('accounting.backend.pages.currency.exchangeRates.rates', [
                'currencyTypes' => $currencyTypes,
                'currency_id'   => $exchangeRate->currency_id,
                'existingRates' => $existingRates,
            ])
        </div>
    </div>
</form>
