<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th rowspan="2" class="text-center" style="vertical-align: middle !important;width: 25% !important">Currency Type</th>
            <th colspan="3" class="text-center" style="width: 35% !important">Currency</th>
            <th rowspan="2" class="text-center" style="vertical-align: middle !important;width: 10% !important">Exchange Rate</th>
            <th rowspan="2" class="text-center" style="vertical-align: middle !important;width: 30% !important">Remarks</th>
        </tr>
        <tr>
            <th class="text-center" style="width: 10% !important">Code</th>
            <th class="text-center" style="width: 15% !important">Name</th>
            <th class="text-center" style="width: 10% !important">Symbol</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($currencyTypes[0]))
        @foreach($currencyTypes as $key => $currencyType)
            @php
                $currencies = \App\Models\PmsModels\Accounts\Currency::where('currency_type_id', $currencyType->id)->whereNotIn('id', [$currency_id])->get();
            @endphp
            @if(isset($currencies[0]))
            @foreach($currencies as $c_key => $currency)
            <tr>
                @if($c_key == 0)
                <td rowspan="{{ $currencies->count() }}" class="text-center">
                    <strong>{{ $currencyType->name }}</strong>
                </td>
                @endif

                <td class="text-center">{{ $currency->code }}</td>
                <td class="text-center">{{ $currency->name }}</td>
                <td class="text-center">{{ $currency->symbol }}</td>

                <td>
                    <input type="number" name="exchange_rates[{{ $currency->id }}][rate]" min="0" value="1" class="form-control text-center" step="any" style="font-weight: bold">
                </td>
                <td>
                    <input type="text" name="exchange_rates[{{ $currency->id }}][description]" class="form-control">
                </td>
            </tr>
            @endforeach
            @endif
        @endforeach
        @endif
    </tbody>
</table>