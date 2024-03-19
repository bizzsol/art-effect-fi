<form action="{{ url('accounting/receivable-clearings') }}" method="post" accept-charset="utf-8">
@csrf
<input type="hidden" name="date" value="{{ $date }}">
    <div class="row">
        <div class="col-md-5">
            <h5><strong>Debits</strong></h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Debit</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sl = 0;
                    @endphp
                    @if($entries->where('debit_credit', 'D')->count() > 0)
                    @foreach($entries->where('debit_credit', 'D') as $entry_key => $entry)
                    @php
                        $rate = isset(json_decode($entry->entry->exchangeRate->rates, true)[$systemCurrency->id]['rate']) ? json_decode($entry->entry->exchangeRate->rates, true)[$systemCurrency->id]['rate'] : 1;
                        $sl++;
                    @endphp
                    <tr>
                        <td class="text-center">
                            D-{{ $sl }}
                            <input type="hidden" name="ledgers[]" value="{{ $entry->chart_of_account_id }}">
                        </td>
                        <td>{{ $entry->entry->date }}</td>
                        <td>
                            <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->entry->id }}" data-entry-type="{{ $entry->entry->entryType->name }}" data-code="{{ $entry->entry->code }}">{{ $entry->entry->number }}</a>
                        </td>
                        <td class="text-right">{{ $entry->entry->exchangeRate->currency->symbol }} {{ systemMoneyFormat($entry->amount) }}</td>
                        <td class="text-right">{{ systemMoneyFormat($rate) }}</td>
                        <td class="text-right" id="debit-amount-D-{{ $sl }}" data-amount="{{ $entry->amount*$rate }}">{{ systemMoneyFormat($entry->amount*$rate) }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-7">
            <h5><strong>Credits</strong></h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Credit</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Crearing With</th>
                        <th>Gain/Loss</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sl = 0;
                    @endphp
                    @if($entries->where('debit_credit', 'C')->count() > 0)
                    @foreach($entries->where('debit_credit', 'C') as $key => $entry)
                    @php
                        $rate = isset(json_decode($entry->entry->exchangeRate->rates, true)[$systemCurrency->id]['rate']) ? json_decode($entry->entry->exchangeRate->rates, true)[$systemCurrency->id]['rate'] : 1;
                        $sl++;
                    @endphp
                    <tr>
                        <td class="text-center">
                            C-{{ $sl }}
                            <input type="hidden" name="ledgers[]" value="{{ $entry->chart_of_account_id }}">
                        </td>
                        <td>{{ $entry->entry->date }}</td>
                        <td>
                            <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->entry->id }}" data-entry-type="{{ $entry->entry->entryType->name }}" data-code="{{ $entry->entry->code }}">{{ $entry->entry->number }}</a>
                        </td>
                        <td class="text-right">{{ $entry->entry->exchangeRate->currency->symbol }} {{ systemMoneyFormat($entry->amount) }}</td>
                        <td class="text-right">{{ systemMoneyFormat($rate) }}</td>
                        <td class="text-right credit-amount" data-amount="{{ $entry->amount*$rate }}">{{ systemMoneyFormat($entry->amount*$rate) }}</td>
                        <td>
                            <select name="debits[{{ $entry->id }}]" class="form-control" style="height: 20px !important;padding: 0px !important" onchange="calculateGainLoss($(this))">
                                <option value="0">N/A</option>
                                @for($i = 1;$i <= $entries->where('debit_credit', 'D')->count();$i++)
                                <option>D-{{ $i }}</option>
                                @endfor
                            </select>
                        </td>
                        <td class="text-right gain-loss">
                            
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-md btn-success text-white"><i class="la la-check"></i>&nbsp;Proceed</button>
        </div>
    </div>
</form>

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

    function calculateGainLoss(element){
        var gain_loss = 0;
        var debit = parseFloat($('#debit-amount-'+(element.find(':selected').val())).attr('data-amount'));
        var credit = parseFloat(element.parent().parent().find('.credit-amount').attr('data-amount'));
        if(debit > 0 && credit > 0){
            gain_loss = credit - debit;
        }

        if(gain_loss == 0){
            element.parent().parent().find('.gain-loss').html('0.00');
        }else if(gain_loss > 0){
            element.parent().parent().find('.gain-loss').html('<span class="text-success">'+(parseFloat(gain_loss).toFixed(2))+'</span>');
        }else{
            element.parent().parent().find('.gain-loss').html('<span class="text-danger">'+(parseFloat(gain_loss).toFixed(2))+'</span>');
        }
    }
</script>