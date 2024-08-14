<table class="table table-bordered export-table">
    <tbody>
    <tr>
        <td colspan="12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 55%">Ledger</th>
                        <th style="width: 10%">From</th>
                        <th style="width: 10%">To</th>
                        <th style="width: 25%">Opening Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <ul>
                                @if(isset($searchAccounts[0]))
                                @foreach($searchAccounts as $searchAccount)
                                <li>{{ '['.$searchAccount->code.'] '.$searchAccount->name }} ({{ '['.$searchAccount->accountGroup->code.'] '.$searchAccount->accountGroup->name }})</li>
                                @endforeach
                                @endif
                            </ul>
                        </td>
                        <td>{{ $from }}</td>
                        <td>{{ $to }}</td>
                        <td class="text-right">
                            {{ $currency->symbol }} {{ $opening_balance }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 7%"><strong>Date</strong></td>
        <td style="width: 8%"><strong>Reference</strong></td>
        <td style="width: 10%"><strong>Ledger</strong></td>
        <td style="width: 10%"><strong>Supplier</strong></td>
        <td style="width: 8%"><strong>Type</strong></td>
        <td style="width: 7%" class="text-right"><strong>Currency</strong></td>
        <td style="width: 9%" class="text-right"><strong>Debit</strong></td>
        <td style="width: 9%" class="text-right"><strong>Credit</strong></td>
        <td style="width: 9%" class="text-right"><strong>Debit ({{ $currency->code }})</strong></td>
        <td style="width: 9%" class="text-right"><strong>Credit ({{ $currency->code }})</strong></td>
        <td style="width: 14%" class="text-right"><strong>Closing Balance ({{ $currency->code }})</strong></td>
        <td style="width: 14%" class="text-left"><strong>Narration</strong></td>
    </tr>
    @php
        $total_debit = 0;
        $total_credit = 0;
        $closing_balance = $opening_balance;
    @endphp
    @if(isset($entries[0]))
        @foreach($entries as $key => $entry)
            @php
                $debit = 0;
                $credit = 0;
                if($entry->items->whereIn('chart_of_account_id', $searchAccounts->pluck('id')->toArray())->count() > 0){
                    foreach($entry->items->whereIn('chart_of_account_id', $searchAccounts->pluck('id')->toArray()) as $key => $item){
                        $debit += ($item->debit_credit == "D" ? $item->amount : 0);
                        $credit += ($item->debit_credit == "C" ? $item->amount : 0);
                    }
                }

                $exchangeRate = 1;
                if($entry->exchangeRate->currency_id != $currency->id){
                    $exchangeRate = json_decode($entry->exchangeRate->rates, true)[$currency->id]['rate'];
                }

                $total_debit += ($debit > 0 ? $debit*$exchangeRate : 0);
                $total_credit += ($credit > 0 ? $credit*$exchangeRate : 0);

                $closing_balance = ($closing_balance+(($debit > 0 ? $debit*$exchangeRate : 0)-($credit > 0 ? $credit*$exchangeRate : 0)));
            @endphp
            <tr>
                <td>{{ $entry->date }}</td>
                <td>{{ $entry->number }}</td>
                <td>
                    <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->id }}"
                       data-entry-type="{{ $entry->entryType->name }}" data-code="{{ $entry->code }}">
                        <p>
                            Debit: {{ $entry->items->where('debit_credit', 'D')->pluck('chartOfAccount.code')->implode(', ') }}</p>
                        <p>
                            Credit: {{ $entry->items->where('debit_credit', 'C')->pluck('chartOfAccount.code')->implode(', ') }}</p>
                    </a>
                </td>
                <td>
                    {{ getEntryVendor($entry) }}
                </td>
                <td>{{ $entry->entryType ? $entry->entryType->name : '' }}</td>
                <td class="text-center">{{ $entry->exchangeRate->currency->code }}</td>
                <td class="text-right">{{ $debit > 0 ? systemMoneyFormat($debit) : '' }}</td>
                <td class="text-right">{{ $credit > 0 ? systemMoneyFormat($credit) : '' }}</td>
                <td class="text-right">{{ $debit > 0 ? systemMoneyFormat($debit*$exchangeRate) : '' }}</td>
                <td class="text-right">{{ $credit > 0 ? systemMoneyFormat($credit*$exchangeRate) : '' }}</td>
                <td class="text-right">
                    {{ systemMoneyFormat($closing_balance) }}
                </td>
                <td>{{ $entry->notes }}</td>
            </tr>
        @endforeach
    @endif

    <tr>
        <td colspan="8" class="text-right"><strong>Balance: ({{ $currency->code }})</strong></td>
        <td class="text-right"><strong>{{ $total_debit > 0 ? systemMoneyFormat($total_debit) : '' }}</strong></td>
        <td class="text-right"><strong>{{ $total_credit > 0 ? systemMoneyFormat($total_credit) : '' }}</strong></td>
        <td class="text-right"><strong>{{ systemMoneyFormat($closing_balance) }}</strong></td>
    </tr>
    </tbody>
</table>

<div class="row">
  <div class="col-md-12">
    {!! $entries ? $entries->render('pagination::bootstrap-4') : '' !!}
  </div>
</div>

<script type="text/javascript">
    function getShortDetails(element) {
        $.dialog({
            title: (element.attr('data-entry-type')) + " Voucher #" + (element.attr('data-code')),
            content: "url:{{ url('accounting/entries') }}/" + (element.attr('data-id')) + "?short-details",
            animation: 'scale',
            columnClass: 'col-md-12',
            closeAnimation: 'scale',
            backgroundDismiss: true
        });
    }

    $(document).ready(function() {
        $('.page-link').click(function(event) {
          event.preventDefault();
          var element = $(this);
          var content = element.html();
          var link = element.attr('href');
          element.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>');
          $.ajax({
            url: link,
            type: 'GET',
            data: {},
          })
          .done(function(report) {
            $('.report-view').html(report);
            element.prop('disabled', false).html(content);
          });
        });
      });
</script>