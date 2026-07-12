<table class="table table-bordered export-table">
    <tbody>
        <tr>
            <td colspan="13" class="p-0">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th style="width: 40%">Ledger</th>
                            <th style="width: 20%">Sub-Ledger</th>
                            <th style="width: 10%">From</th>
                            <th style="width: 10%">To</th>
                            <th style="width: 10%">Opening Balance (b/f)</th>
                            <th style="width: 10%">Closing Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <ul class="pl-2">
                                    @if(isset($searchAccounts[0]))
                                        @foreach($searchAccounts as $searchAccount)
                                            <li>{{ '[' . $searchAccount->code . '] ' . $searchAccount->name }}
                                                ({{ '[' . $searchAccount->accountGroup->code . '] ' . $searchAccount->accountGroup->name }})
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </td>
                            <td>
                                <ul class="pl-2">
                                    @if(isset($subLedgers[0]))
                                        @foreach($subLedgers as $subLedger)
                                            <li>{{ '[' . $subLedger->code . '] ' . $subLedger->name }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </td>
                            <td>{{ $from }}</td>
                            <td>{{ $to }}</td>
                            <td class="text-right">
                                {{ $currency->symbol }} {{ systemMoneyFormat($opening_balance) }}
                            </td>
                            <td class="text-right">
                                {{ $currency->symbol }} {{ systemMoneyFormat($closingBalance) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 7%"><strong>Date</strong></td>
            <td style="width: 7%"><strong>Reference</strong></td>
            <td style="width: 10%"><strong>Ledger</strong></td>
            <td style="width: 10%"><strong>Sub-Ledger</strong></td>
            <td style="width: 10%"><strong>Supplier</strong></td>
            <td style="width: 6%"><strong>Type</strong></td>
            <td style="width: 5%" class="text-right"><strong>Currency</strong></td>
            <td style="width: 7%" class="text-right"><strong>Debit</strong></td>
            <td style="width: 7%" class="text-right"><strong>Credit</strong></td>
            <td style="width: 7%" class="text-right"><strong>Debit ({{ $currency->code }})</strong></td>
            <td style="width: 7%" class="text-right"><strong>Credit ({{ $currency->code }})</strong></td>
            <td style="width: 7%" class="text-right"><strong>Closing Balance ({{ $currency->code }})</strong></td>
            <td style="width: 10%" class="text-left"><strong>Narration</strong></td>
        </tr>
        @php
            $total_debit = 0;
            $total_credit = 0;
            $closing_balance = $opening_balance;
            $currency_id = $currency->id;
        @endphp
        @if(isset($entries[0]))
            @foreach($entries as $key => $entry)
                @php
                    $debit = 0;
                    $credit = 0;

                    foreach ($entry->items as $item) {
                        if (!in_array($item->chart_of_account_id, $searchAccountIds))
                            continue;
                        if (isset($sub_ledger_id[0]) && !in_array($item->sub_ledger_id, $sub_ledger_id))
                            continue;
                        if ($cost_centre_id > 0 && $item->cost_centre_id != $cost_centre_id)
                            continue;
                        if ($profit_centre_id > 0 && $item->costCentre && $item->costCentre->profit_centre_id != $profit_centre_id)
                            continue;
                        if ($company_id > 0 && $item->costCentre && $item->costCentre->profitCentre && $item->costCentre->profitCentre->company_id != $company_id)
                            continue;

                        if ($item->debit_credit == "D") {
                            $debit += $item->amount;
                        } else {
                            $credit += $item->amount;
                        }
                    }
                @endphp
                @if($debit > 0 || $credit > 0)
                    @php

                        $exchangeRate = 1;
                        $rates = [];
                        if ($entry->exchangeRate) {
                            $rates = json_decode($entry->exchangeRate->rates, true);
                            $exchangeRate = isset($rates[$currency_id]['rate']) ? $rates[$currency_id]['rate'] : 1;
                        }


                        $reportingDebit = $debit > 0 ? $debit * $exchangeRate : 0;
                        $reportingCredit = $credit > 0 ? $credit * $exchangeRate : 0;

                        $total_debit += $reportingDebit;
                        $total_credit += $reportingCredit;

                        $closing_balance = $closing_balance + ($reportingDebit - $reportingCredit);
                    @endphp
                    <tr>
                        <td>{{ $entry->date }}</td>
                        <td>{{ $entry->number }}</td>
                        <td>
                            <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->id }}"
                                data-entry-type="{{ $entry->entryType->name }}" data-code="{{ $entry->code }}">
                                <p>
                                    Debit:
                                    {{ $entry->items->where('debit_credit', 'D')->pluck('chartOfAccount.code')->implode(', ') }}
                                </p>
                                <p>
                                    Credit:
                                    {{ $entry->items->where('debit_credit', 'C')->pluck('chartOfAccount.code')->implode(', ') }}
                                </p>
                            </a>
                        </td>
                        <td>
                            <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->id }}"
                                data-entry-type="{{ $entry->entryType->name }}" data-code="{{ $entry->code }}">
                                @if($entry->items->where('debit_credit', 'D')->whereNotNull('sub_ledger_id')->count() > 0)
                                    <p>
                                        Debit: {{ $entry->items->where('debit_credit', 'D')->pluck('subLedger.name')->implode(', ') }}
                                    </p>
                                @endif
                                @if($entry->items->where('debit_credit', 'C')->whereNotNull('sub_ledger_id')->count() > 0)
                                    <p>
                                        Credit: {{ $entry->items->where('debit_credit', 'C')->pluck('subLedger.name')->implode(', ') }}
                                    </p>
                                @endif
                            </a>
                        </td>
                        <td>
                            {{ getEntryVendor($entry) }}
                        </td>
                        <td>{{ $entry->entryType ? $entry->entryType->name : '' }}</td>
                        <td class="text-center">{{ $entry->exchangeRate->currency->code }}</td>
                        <td class="text-right">{{ $debit > 0 ? systemMoneyFormat($debit) : '' }}</td>
                        <td class="text-right">{{ $credit > 0 ? systemMoneyFormat($credit) : '' }}</td>
                        <td class="text-right">{{ $reportingDebit > 0 ? systemMoneyFormat($reportingDebit) : '' }}</td>
                        <td class="text-right">{{ $reportingCredit > 0 ? systemMoneyFormat($reportingCredit) : '' }}</td>
                        <td class="text-right">
                            {{ systemMoneyFormat($closing_balance) }}
                        </td>
                        <td>{{ $entry->notes }}</td>
                    </tr>
                @endif
            @endforeach
        @endif

        <tr>
            <td colspan="9" class="text-right"><strong>Balance: ({{ $currency->code }})</strong></td>
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

    $(document).ready(function () {
        $('.page-link').click(function (event) {
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
                .done(function (report) {
                    $('.report-view').html(report);
                    element.prop('disabled', false).html(content);
                });
        });
    });
</script>