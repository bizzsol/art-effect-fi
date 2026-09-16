<span style="display: none" id="export-title">{{ $title }}</span>

<table class="table table-bordered mb-2 export-table">
    <thead>
        <tr>
            <th colspan="4" class="text-center"><h5 class="mb-0"><strong>SUMMARY &ndash; ALL BANK LEDGERS</strong></h5></th>
        </tr>
        <tr>
            <th class="text-right" style="width: 25%">Total Opening</th>
            <th class="text-right" style="width: 25%">Total Receipts</th>
            <th class="text-right" style="width: 25%">Total Payments</th>
            <th class="text-right" style="width: 25%">Total Closing</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-right">{{ systemMoneyFormat($totalOpening) }}</td>
            <td class="text-right">{{ systemMoneyFormat($totalReceipts) }}</td>
            <td class="text-right">{{ systemMoneyFormat($totalPayments) }}</td>
            <td class="text-right">{{ systemMoneyFormat($totalClosing) }}</td>
        </tr>
    </tbody>
</table>

<h5 class="mb-1"><strong>LEDGER-WISE / TRANSACTION-WISE DETAIL</strong></h5>
<table class="table table-bordered export-table">
    <thead>
        <tr>
            <th style="width: 14%">Bank Ledger Code</th>
            <th style="width: 9%">Transaction Date</th>
            <th style="width: 9%">Voucher Ref</th>
            <th style="width: 9%">Type</th>
            <th style="width: 6%" class="text-center">Currency</th>
            <th style="width: 9%" class="text-right">Opening</th>
            <th style="width: 9%" class="text-right">Receipts</th>
            <th style="width: 9%" class="text-right">Payments</th>
            <th style="width: 10%" class="text-right">Balance / Closing</th>
            <th style="width: 16%">Voucher Narration</th>
        </tr>
    </thead>
    <tbody class="report-tbody">
        @forelse($ledgers as $ledger)
            @php
                $ledgerLabel = $ledger['account']->code . ' - ' . $ledger['account']->name;
                $ledgerGroup = 'ledger-group-' . $ledger['account']->id;
                $ledgerIsZero = round($ledger['opening'], 2) == 0 && round($ledger['closing'], 2) == 0;
            @endphp
            <tr class="{{ $ledgerGroup }} cash-flow-ledger-row" data-ledger-zero="{{ $ledgerIsZero ? 1 : 0 }}">
                <td>{{ $ledgerLabel }}</td>
                <td></td>
                <td></td>
                <td>Opening Balance</td>
                <td class="text-center">{{ $currencyCode }}</td>
                <td class="text-right">{{ systemMoneyFormat($ledger['opening']) }}</td>
                <td></td>
                <td></td>
                <td class="text-right">{{ systemMoneyFormat($ledger['opening']) }}</td>
                <td></td>
            </tr>
            @foreach($ledger['rows'] as $row)
                <tr class="{{ $ledgerGroup }} cash-flow-ledger-row" data-ledger-zero="{{ $ledgerIsZero ? 1 : 0 }}">
                    <td>{{ $ledgerLabel }}</td>
                    <td>{{ date('d-M-Y', strtotime($row['date'])) }}</td>
                    <td>
                        <a class="text-primary" href="javascript:void(0)" onclick="getShortDetails($(this))" data-id="{{ $row['entry_id'] }}" data-entry-type="{{ $row['entry_type'] }}" data-code="{{ $row['voucher'] }}">{{ $row['voucher'] }}</a>
                    </td>
                    <td>{{ $row['type'] }}</td>
                    <td class="text-center">{{ $currencyCode }}</td>
                    <td></td>
                    <td class="text-right">{{ $row['receipts'] > 0 ? systemMoneyFormat($row['receipts']) : '' }}</td>
                    <td class="text-right">{{ $row['payments'] > 0 ? systemMoneyFormat($row['payments']) : '' }}</td>
                    <td class="text-right">{{ systemMoneyFormat($row['balance']) }}</td>
                    <td>{{ $row['narration'] }}</td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="10" class="text-center">No bank/cash ledgers found for this company.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="background-color: #fdf3d9">
            <td colspan="5" class="text-right"><strong>GRAND TOTAL &ndash; ALL BANK LEDGERS</strong></td>
            <td class="text-right"><strong>{{ systemMoneyFormat($totalOpening) }}</strong></td>
            <td class="text-right"><strong>{{ systemMoneyFormat($totalReceipts) }}</strong></td>
            <td class="text-right"><strong>{{ systemMoneyFormat($totalPayments) }}</strong></td>
            <td class="text-right"><strong>{{ systemMoneyFormat($totalClosing) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>

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

    // Overrides the generic zeroBalanceFilter() from reports/buttons.blade.php: that version hides a
    // row based on that row's own closing-balance cell, which fits a one-row-per-ledger report but not
    // this one - here every row is a single voucher carrying a running balance that can legitimately
    // pass through zero mid-statement. "Zero Balance" instead has to mean the whole ledger (opening
    // balance and every one of its transactions) nets to zero, so the filter toggles by ledger, not
    // by row, using the data-ledger-zero flag stamped on every row for that ledger.
    function zeroBalanceFilter() {
        var hideZero = $('#zero_balance').val() == 0;
        $('.cash-flow-ledger-row').each(function () {
            var isZero = $(this).attr('data-ledger-zero') == '1';
            $(this).toggle(!(hideZero && isZero));
        });
    }
</script>
