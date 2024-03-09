<table class="export-table" style="width: 100%">
    <tbody>
        <tr>
            <td colspan="3" class="pt-3 pb-3">
                <h5>Ledger statement for <strong>[{{ $account->code }}] {{ $account->name }}</strong> from <strong>{{ date('d-M-Y', strtotime($from)) }}</strong> to <strong>{{ date('d-M-Y', strtotime($to)) }}</strong></h5>
            </td>
        </tr>

        {{--
        <tr>
            <td style="width: 50%" class="pr-3">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td style="width: 50% !important">Bank or cash account</td>
                            <td style="width: 50% !important">{{ $account->bank_or_cash == 1 ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <td style="width: 50% !important">Notes</td>
                            <td style="width: 50% !important">{{ $account->notes }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        --}}

        <tr>
            <td colspan="2">
                <table class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 8%">Date</th>
                            <th style="width: 7%">Number</th>
                            <th style="width: 10%">Ledger</th>
                            <th style="width: 10%">Supplier</th>
                            <th style="width: 8%">Type</th>
                            <th style="width: 7%" class="text-right">Currency</th>
                            <th style="width: 10%" class="text-right">Debit</th>
                            <th style="width: 10%" class="text-right">Credit</th>
                            <th style="width: 10%" class="text-right">Debit ({{ $currency->code }})</th>
                            <th style="width: 10%" class="text-right">Credit ({{ $currency->code }})</th>
                         </tr>
                   </thead>
                   <tbody>
                    @php
                        $total_debit = 0;
                        $total_credit = 0;
                    @endphp
                    @if(isset($entries[0]))
                    @foreach($entries as $key => $entry)
                    @php
                        $debit = 0;
                        $credit = 0;
                        if($entry->items->where('chart_of_account_id', $chart_of_account_id)->count() > 0){
                            foreach($entry->items->where('chart_of_account_id', $chart_of_account_id) as $key => $item){
                                $debit += ($item->debit_credit == "D" ? $item->amount : 0);
                                $credit += ($item->debit_credit == "C" ? $item->amount : 0);
                            }
                        }

                        $exchangeRate = 1;
                        if($entry->exchangeRate->currency_id != $currency->id){
                            $exchangeRate = json_decode($entry->exchangeRate->rates, true)[$currency->id]['rate'];
                        }

                        $total_debit += $debit;
                        $total_credit += $credit;
                    @endphp
                    <tr>
                        <td>{{ $entry->date }}</td>
                        <td>{{ $entry->number }}</td>
                        <td>
                            <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->id }}" data-entry-type="{{ $entry->entryType->name }}" data-code="{{ $entry->code }}">
                                <p>Debit: {{ $entry->items->where('debit_credit', 'D')->pluck('chartOfAccount.code')->implode(', ') }}</p>
                                <p>Credit: {{ $entry->items->where('debit_credit', 'C')->pluck('chartOfAccount.code')->implode(', ') }}</p>
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
                    </tr>
                    @endforeach
                    @endif

                    <tr>
                        <td colspan="8" class="text-right"><strong>Total: ({{ $currency->code }})</strong></td>
                        <td class="text-right"><strong>{{ $total_debit > 0 ? systemMoneyFormat($total_debit) : '' }}</strong></td>
                        <td class="text-right"><strong>{{ $total_credit > 0 ? systemMoneyFormat($total_credit) : '' }}</strong></td>
                    </tr>

                    <tr>
                        <td colspan="8" class="text-right"><strong>Balance: ({{ $currency->code }})</strong></td>
                        <td class="text-right"><strong>{{ $total_debit-$total_credit > 0 ? systemMoneyFormat($total_debit-$total_credit) : '' }}</strong></td>
                        <td class="text-right"><strong>{{ $total_debit-$total_credit < 0 ? systemMoneyFormat(($total_debit-$total_credit)*-1) : '' }}</strong></td>
                    </tr>
                   </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<script type="text/javascript">
    function getShortDetails(element) {
        $.dialog({
            title: (element.attr('data-entry-type'))+" Voucher #"+(element.attr('data-code')),
            content: "url:{{ url('accounting/entries') }}/"+(element.attr('data-id'))+"?short-details",
            animation: 'scale',
            columnClass: 'col-md-12',
            closeAnimation: 'scale',
            backgroundDismiss: true
        });
    }
</script>