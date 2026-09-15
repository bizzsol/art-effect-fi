<span style="display: none" id="export-title">{{ $title }}</span>
<table style="width: 100%">
    <tbody>
        <tr>
            <td>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 25%">
                                <h5><strong>Codes</strong></h5>
                            </th>
                            <th style="width: 50%">
                                <h5><strong>Cash Flow from Operating Activities</strong></h5>
                            </th>
                            <th style="width: 25%" class="text-right">
                                <h5><strong>Amount ({{ $currency->code }})</strong></h5>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="report-tbody">
                        <tr>
                            <td></td>
                            <td><strong>Net Profit for the Period</strong></td>
                            <td class="closing_balance_column" style="text-align: right"><strong>{{ systemMoneyFormat($netProfit) }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>Adjustments for Changes in Working Capital:</strong></td>
                        </tr>
                        {!! $adjustments !!}
                        <tr>
                            <td colspan="2"><strong>Net Cash Generated from/(Used in) Operating Activities:</strong></td>
                            <td class="closing_balance_column" style="text-align: right"><strong>{{ systemMoneyFormat($netCashFromOperating) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="table table-bordered table-striped table-hover">
                    <tbody>
                        <tr>
                            <td style="width: 75%"><strong>Net Increase/(Decrease) in Cash and Cash Equivalents</strong></td>
                            <td style="width: 25%; text-align: right"><strong>{{ systemMoneyFormat($netCashFromOperating) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Cash and Cash Equivalents at the Beginning of the Period</td>
                            <td style="text-align: right">{{ systemMoneyFormat($openingCash) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cash and Cash Equivalents at the End of the Period</strong></td>
                            <td style="text-align: right"><strong>{{ systemMoneyFormat($closingCash) }}</strong></td>
                        </tr>
                        @if($difference != 0)
                        <tr>
                            <td colspan="2" style="color: #c0392b">
                                <strong>Note:</strong> Operating activities total differs from the actual change in cash/bank ledgers by {{ systemMoneyFormat($difference) }}. This usually points to a ledger posted outside the selected company/profit centre scope, or a ledger not yet mapped correctly - see the "(unmapped)" rows above.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 15%"><h5><strong>Code</strong></h5></th>
                            <th style="width: 45%"><h5><strong>Cash & Bank Ledger</strong></h5></th>
                            <th style="width: 20%" class="text-right"><h5><strong>Opening Balance</strong></h5></th>
                            <th style="width: 20%" class="text-right"><h5><strong>Closing Balance</strong></h5></th>
                        </tr>
                    </thead>
                    <tbody class="report-tbody">
                        {!! $cashLedgers !!}
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
