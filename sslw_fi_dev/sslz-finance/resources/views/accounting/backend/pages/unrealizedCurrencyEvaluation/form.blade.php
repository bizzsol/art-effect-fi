<form action="{{ route('accounting.unrealized-currency-evaluation.store') }}" method="post" accept-charset="utf-8" id="evaluation-form">
    @csrf
    <input type="hidden" name="run_date" value="{{ $run_date }}" @if(isset($lastEvaluated->id)) min="{{ $lastEvaluated->run_date }}" @endif>
    <input type="hidden" name="payables" value="{{ $payables }}">
    <input type="hidden" name="receivables" value="{{ $receivables }}">
    <input type="hidden" name="asset_ledgers" value="{{ implode(',', $asset_ledgers) }}">
    <input type="hidden" name="liability_ledgers" value="{{ implode(',', $liability_ledgers) }}">
    <div class="row pr-3">
        <div class="col-md-2">
            <label for="reversal_date"><strong>Reversal Date:<span class="text-danger">&nbsp;*</span></strong></label>
            <input type="date" name="reversal_date" id="reversal_date" value="{{ date('Y-m-01', strtotime($run_date.' +1 months')) }}" class="form-control" @if($from) min="{{ $from }}" @endif>
        </div>
    </div>

    <div class="row mt-3">
        @if($payables == 1)
        <div class="col-md-12 mb-2">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 10%">
                            <h5 class="text-center"><strong>Payables</strong></h5>
                        </td>
                        <td style="width: 90%" class="p-0">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 7.5%">Code</th>
                                        <th style="width: 20%">Ledger</th>
                                        <th colspan="2">Trans. Currency</th>
                                        <th colspan="2">Reporting Currency</th>
                                        <th style="width: 7.5%">Avg Rate</th>
                                        <th style="width: 7.5%">Run Rate</th>
                                        <th colspan="2">UnRealized Amount</th>
                                        <th colspan="2">UnRealized Gain</th>
                                        <th colspan="2">UnRealized Loss</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($ledgers->whereIn('id', $supplierPayables)->count() > 0)
                                    @foreach($ledgers->whereIn('id', $supplierPayables)->sortBy('code') as $key => $ledger)
                                        @php
                                            $sl = 0;
                                        @endphp
                                        @if($currencies->count() > 0)
                                        @foreach($currencies as $currency_key => $currency)
                                        @php
                                            $transactions = $orderEntries->where('entry.exchangeRate.currency_id', $currency->id)->where('chart_of_account_id', $ledger->id);

                                            $transaction_currency = 0;
                                            $rates = [];
                                            $average_rate = 0;
                                            $reporting_currency = 0;
                                            $run_rate = isset(json_decode($runRates[$currency->id]['rate']->rates, true)[$systemCurrency->id]['rate']) ? json_decode($runRates[$currency->id]['rate']->rates, true)[$systemCurrency->id]['rate'] : 1;
                                            
                                            if($transactions->count() > 0){
                                                foreach($transactions as $key => $transaction){
                                                    $rate = exchangeRate($transaction->entry->exchangeRate, $systemCurrency->id);
                                                    $transaction_currency += $transaction->amount*($transaction->debit_credit == 'D' ? 1 : (-1));
                                                    $reporting_currency += $transaction->amount*($transaction->debit_credit == 'D' ? 1 : (-1))*$rate;

                                                }

                                                $average_rate = $reporting_currency != 0 && $transaction_currency != 0 ? $reporting_currency/$transaction_currency : 0;
                                            }

                                            $unrealized_amount = $transaction_currency*$run_rate;
                                            $gain = $unrealized_amount > $reporting_currency ? $unrealized_amount-$reporting_currency : 0;
                                            $loss = $unrealized_amount < $reporting_currency ? $reporting_currency-$unrealized_amount : 0;
                                        @endphp
                                        @if($transactions->count() > 0 && $transaction_currency != 0)
                                        @php
                                            $sl++;
                                        @endphp
                                        <tr>
                                            <td>{{ $sl == 1 ? $ledger->code : '' }}</td>
                                            <td>{{ $sl == 1 ? $ledger->name : '' }}</td>
                                            <td style="width: 2%">{{ $currency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $transaction_currency }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $reporting_currency }}</td>
                                            <td class="text-center">{{ systemDoubleValue($average_rate, 2) }}</td>
                                            <td class="text-center">{{ $run_rate }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $unrealized_amount }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-success text-right">{{ $gain }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-danger text-right">{{ $loss }}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endif
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        @if($receivables == 1)
        <div class="col-md-12 mb-2">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 10%">
                            <h5 class="text-center"><strong>Receivables</strong></h5>
                        </td>
                        <td style="width: 90%" class="p-0">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 7.5%">Code</th>
                                        <th style="width: 20%">Ledger</th>
                                        <th colspan="2">Trans. Currency</th>
                                        <th colspan="2">Reporting Currency</th>
                                        <th style="width: 7.5%">Avg Rate</th>
                                        <th style="width: 7.5%">Run Rate</th>
                                        <th colspan="2">UnRealized Amount</th>
                                        <th colspan="2">UnRealized Gain</th>
                                        <th colspan="2">UnRealized Loss</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($ledgers->whereIn('id', $customerReceivables)->count() > 0)
                                    @foreach($ledgers->whereIn('id', $customerReceivables)->sortBy('code') as $key => $ledger)
                                        @php
                                            $sl = 0;
                                        @endphp
                                        @if($currencies->count() > 0)
                                        @foreach($currencies as $currency_key => $currency)
                                        @php
                                            $transactions = $orderEntries->where('entry.exchangeRate.currency_id', $currency->id)->where('chart_of_account_id', $ledger->id);

                                            $transaction_currency = 0;
                                            $rates = [];
                                            $average_rate = 0;
                                            $reporting_currency = 0;
                                            $run_rate = isset(json_decode($runRates[$currency->id]['rate']->rates, true)[$systemCurrency->id]['rate']) ? json_decode($runRates[$currency->id]['rate']->rates, true)[$systemCurrency->id]['rate'] : 1;
                                            
                                            if($transactions->count() > 0){
                                                foreach($transactions as $key => $transaction){
                                                    $rate = exchangeRate($transaction->entry->exchangeRate, $systemCurrency->id);
                                                    $transaction_currency += $transaction->amount*($transaction->debit_credit == 'D' ? 1 : (-1));
                                                    $reporting_currency += $transaction->amount*($transaction->debit_credit == 'D' ? 1 : (-1))*$rate;

                                                }

                                                $average_rate = $reporting_currency != 0 && $transaction_currency != 0 ? $reporting_currency/$transaction_currency : 0;
                                            }

                                            $unrealized_amount = $transaction_currency*$run_rate;
                                            $gain = $reporting_currency < $unrealized_amount ? $unrealized_amount-$reporting_currency : 0;
                                            $loss = $reporting_currency > $unrealized_amount ? $reporting_currency-$unrealized_amount : 0;
                                        @endphp
                                        @if($transactions->count() > 0 && $transaction_currency != 0)
                                        @php
                                            $sl++;
                                        @endphp
                                        <tr>
                                            <td>{{ $sl == 1 ? $ledger->code : '' }}</td>
                                            <td>{{ $sl == 1 ? $ledger->name : '' }}</td>
                                            <td style="width: 2%">{{ $currency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $transaction_currency }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $reporting_currency }}</td>
                                            <td class="text-center">{{ systemDoubleValue($average_rate, 2) }}</td>
                                            <td class="text-center">{{ $run_rate }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $unrealized_amount }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-success text-right">{{ $gain }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-danger text-right">{{ $loss }}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endif
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        <div class="col-md-12 mb-2">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 10%">
                            <h5 class="text-center"><strong>Assets</strong></h5>
                        </td>
                        <td style="width: 90%" class="p-0">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 7.5%">Code</th>
                                        <th style="width: 20%">Ledger</th>
                                        <th colspan="2">Trans. Currency</th>
                                        <th colspan="2">Reporting Currency</th>
                                        <th style="width: 7.5%">Avg Rate</th>
                                        <th style="width: 7.5%">Run Rate</th>
                                        <th colspan="2">UnRealized Amount</th>
                                        <th colspan="2">UnRealized Gain</th>
                                        <th colspan="2">UnRealized Loss</th>
                                        <th style="width: 3%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($ledgers->whereIn('id', $asset_ledgers)->count() > 0)
                                    @foreach($ledgers->whereIn('id', $asset_ledgers)->sortBy('code') as $key => $ledger)
                                        @php
                                            $sl = 0;
                                        @endphp
                                        @if($currencies->count() > 0)
                                        @foreach($currencies as $currency_key => $currency)
                                        @php
                                            $transactions = $entries->where('entry.exchangeRate.currency_id', $currency->id)->where('chart_of_account_id', $ledger->id);

                                            $transaction_currency = 0;
                                            $rates = [];
                                            $average_rate = 0;
                                            $reporting_currency = 0;
                                            $run_rate = isset(json_decode($runRates[$currency->id]['rate']->rates, true)[$systemCurrency->id]['rate']) ? json_decode($runRates[$currency->id]['rate']->rates, true)[$systemCurrency->id]['rate'] : 1;
                                            if($transactions->count() > 0){
                                                foreach($transactions as $key => $transaction){
                                                    $rate = exchangeRate($transaction->entry->exchangeRate, $systemCurrency->id);
                                                    $transaction_currency += $transaction->amount*($transaction->debit_credit == 'D' ? 1 : (-1));
                                                    $reporting_currency += $transaction->amount*($transaction->debit_credit == 'D' ? 1 : (-1))*$rate;

                                                }

                                                $average_rate = $reporting_currency != 0 && $transaction_currency != 0 ? $reporting_currency/$transaction_currency : 0;
                                            }

                                            $unrealized_amount = $transaction_currency*$run_rate;
                                            $gain = $reporting_currency < $unrealized_amount ? $unrealized_amount-$reporting_currency : 0;
                                            $loss = $reporting_currency > $unrealized_amount ? $reporting_currency-$unrealized_amount : 0;
                                        @endphp
                                        @if($transactions->count() > 0 && $transaction_currency != 0)
                                        @php
                                            $sl++;
                                        @endphp
                                        <tr>
                                            <td>{{ $sl == 1 ? $ledger->code : '' }}</td>
                                            <td>{{ $sl == 1 ? $ledger->name : '' }}</td>
                                            <td style="width: 2%">{{ $currency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $transaction_currency }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $reporting_currency }}</td>
                                            <td class="text-center">{{ systemDoubleValue($average_rate, 2) }}</td>
                                            <td class="text-center">{{ $run_rate }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $unrealized_amount }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-success text-right">{{ $gain }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-danger text-right">{{ $loss }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-xs btn-primary" onclick="showDetails($(this), '{{ $currency->id }}', '{{ $ledger->id }}', 'assets')"><i class="la la-tasks"></i></a>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endif
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-12 mb-2">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 10%">
                            <h5 class="text-center"><strong>Liabilities</strong></h5>
                        </td>
                        <td style="width: 90%" class="p-0">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 7.5%">Code</th>
                                        <th style="width: 20%">Ledger</th>
                                        <th colspan="2">Trans. Currency</th>
                                        <th colspan="2">Reporting Currency</th>
                                        <th style="width: 7.5%">Avg Rate</th>
                                        <th style="width: 7.5%">Run Rate</th>
                                        <th colspan="2">UnRealized Amount</th>
                                        <th colspan="2">UnRealized Gain</th>
                                        <th colspan="2">UnRealized Loss</th>
                                        <th style="width: 3%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($ledgers->whereIn('id', $liability_ledgers)->count() > 0)
                                    @foreach($ledgers->whereIn('id', $liability_ledgers)->sortBy('code') as $key => $ledger)
                                        @php
                                            $sl = 0;
                                        @endphp
                                        @if($currencies->count() > 0)
                                        @foreach($currencies as $currency_key => $currency)
                                        @php
                                            $transactions = $entries->where('entry.exchangeRate.currency_id', $currency->id)->where('chart_of_account_id', $ledger->id);

                                            $transaction_currency = 0;
                                            $rates = [];
                                            $average_rate = 0;
                                            $reporting_currency = 0;
                                            $run_rate = isset(json_decode($runRates[$currency->id]['rate']->rates, true)[$systemCurrency->id]['rate']) ? json_decode($runRates[$currency->id]['rate']->rates, true)[$systemCurrency->id]['rate'] : 1;
                                            
                                            if($transactions->count() > 0){
                                                foreach($transactions as $key => $transaction){
                                                    $rate = exchangeRate($transaction->entry->exchangeRate, $systemCurrency->id);
                                                    $transaction_currency += $transaction->amount*($transaction->debit_credit == 'D' ? 1 : (-1));
                                                    $reporting_currency += $transaction->amount*($transaction->debit_credit == 'D' ? 1 : (-1))*$rate;

                                                }

                                                $average_rate = $reporting_currency != 0 && $transaction_currency != 0 ? $reporting_currency/$transaction_currency : 0;
                                            }

                                            $unrealized_amount = $transaction_currency*$run_rate;
                                            $gain = $unrealized_amount > $reporting_currency ? $unrealized_amount-$reporting_currency : 0;
                                            $loss = $unrealized_amount < $reporting_currency ? $reporting_currency-$unrealized_amount : 0;
                                        @endphp
                                        @if($transactions->count() > 0 && $transaction_currency != 0)
                                        @php
                                            $sl++;
                                        @endphp
                                        <tr>
                                            <td>{{ $sl == 1 ? $ledger->code : '' }}</td>
                                            <td>{{ $sl == 1 ? $ledger->name : '' }}</td>
                                            <td style="width: 2%">{{ $currency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $transaction_currency }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $reporting_currency }}</td>
                                            <td class="text-center">{{ systemDoubleValue($average_rate, 2) }}</td>
                                            <td class="text-center">{{ $run_rate }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-right">{{ $unrealized_amount }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-success text-right">{{ $gain }}</td>
                                            <td style="width: 2%">{{ $systemCurrency->symbol }}</td>
                                            <td style="width: 8%" class="text-danger text-right">{{ $loss }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-xs btn-primary" onclick="showDetails($(this), '{{ $currency->id }}', '{{ $ledger->id }}', 'liabilities')"><i class="la la-tasks"></i></a>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endif
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 offset-md-8">
            <button type="submit" class="btn btn-success btn-lg pull-right btn-block mt-2" id="evaluation-button"><i class="la la-save"></i>&nbsp;Post {{ $title }}</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    function showDetails(element, currency_id, ledger_id, type){
        var content = element.html();
        element.html('<i class="las la-spinner la-spin"></i>').prop('disabled', true);

        $.ajax({
            url: "{{ url('accounting/unrealized-currency-evaluation') }}/"+ledger_id+"?currency_id="+currency_id+"&type="+type+"&run_date={{ request()->get('run_date') }}",
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $.dialog({
                title: response.title,
                content: response.content,
                animation: 'scale',
                columnClass: 'col-md-12'
            });
            element.html(content).prop('disabled', false);    
        });
    }

    $(document).ready(function() {
        var form = $('#evaluation-form');
        var button = $('#evaluation-button');
        var content = button.html();

        form.submit(function(event) {
            event.preventDefault();

            button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                data: form.serializeArray(),
            })
            .done(function(response) {
                if(response.success){
                   window.open("{{ url('accounting/unrealized-currency-evaluation') }}", "_parent");
                }else{
                    toastr.error(response.message);
                    button.html(content).prop('disabled', false);
                }
            })
            .fail(function(response) {
                button.html(content).prop('disabled', false);
                $.each(response.responseJSON.errors, function(index, val) {
                    toastr.error(val[0]);
                });
            });
        });
    });
</script>