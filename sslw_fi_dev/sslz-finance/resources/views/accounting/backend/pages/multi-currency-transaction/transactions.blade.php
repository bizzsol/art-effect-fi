@php
    $systemCurrency = systemCurrency();
@endphp
@if($entries->count() > 0)
    @foreach($entries as $key => $entry)
        @php
            $currency = $entry->exchangeRate->currency->code;
            $same = ($entry->exchangeRate->currency_id == $systemCurrency->id ? true : false);
            $exchangeRate = exchangeRate($entry->exchangeRate, $systemCurrency->id);
        @endphp
        @include('accounting.backend.pages.entries.entry-details', [
            'title' => $entry->entryType->name . ' Voucher #' . $entry->code,
            'currency' => $currency,
            'entry' => $entry,
            'systemCurrency' => $systemCurrency,
            'same' => $same,
            'exchangeRate' => $exchangeRate,
            'differentView' => true
        ])
        <hr style="border-top: 2px dashed #ccc">
    @endforeach
@endif