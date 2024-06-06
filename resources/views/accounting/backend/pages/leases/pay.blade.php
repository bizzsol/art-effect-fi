<div style="overflow: hidden;">
    <hr class="mt-0 pt-0">
    <form action="{{ route('accounting.leases.update', $schedule->id) }}" method="post" id="pay-form">
    @csrf
    @method('PUT')
        <div class="row mb-4">
            <div class="col-md-12 mb-3">
                @include('payment', [
                    'currency_id' => $schedule->lease->exchangeRate->currency_id,
                    'company_id' => $schedule->lease->costCentre->profitCentre->company_id,
                    'supplier_id' => $schedule->lease->supplier_id,
                    'select2' => true
                ])
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="datetime"><strong>Datetime</strong></label>
                    <input type="datetime-local" name="datetime" id="datetime" value="{{ date('Y-m-d H:i:s') }}" class="form-control">
                </div>
            </div>
            <div class="col-md-4 pt-4">
                <button class="btn btn-block mt-2 btn-md btn-success pay-button"><i class="las la-check"></i>&nbsp;Process Payment</button>
            </div>
            <div class="col-md-2 pt-4">
                <button class="btn btn-block mt-2 btn-md btn-dark" onclick="$('.jconfirm').remove()"><i class="las la-times"></i>&nbsp;Cancel</button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="mb-2"><strong>Posted Installment Information</strong></h5>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%" class="text-center">{{ ucwords(str_replace('ly', '', $schedule->lease->pay_interval)) }}</th>
                        <th style="width: 10%" class="text-center">Date</th>
                        <th style="width: 14%" class="text-right">Beginning</th>
                        <th style="width: 14%" class="text-right">PMT</th>
                        <th style="width: 14%" class="text-right">Interest</th>
                        <th style="width: 14%" class="text-right">Principal</th>
                        <th style="width: 14%" class="text-right">Ending balance</th>
                        <th style="width: 10%" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">{{ $schedule->serial }}</td>
                        <td class="text-center">{{ $schedule->date }}</td>
                        <td class="text-right">{{ systemMoneyFormat($schedule->balance) }}</td>
                        <td class="text-right">{{ systemMoneyFormat($schedule->principle+$schedule->interest) }}</td>
                        <td class="text-right">{{ systemMoneyFormat($schedule->interest) }}</td>
                        <td class="text-right">{{ systemMoneyFormat($schedule->principle) }}</td>
                        <td class="text-right">{{ systemMoneyFormat($schedule->balance-$schedule->principle) }}</td>
                        <td class="text-center">{{ ucwords($schedule->status) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h5 class="mb-2"><strong>Lease Information</strong></h5>
            <table class="table table-bordered">
                <tr>
                    <td style="width: 33%;">
                        Company: <strong>{{ '['.$schedule->lease->costCentre->profitCentre->company->code.'] '.$schedule->lease->costCentre->profitCentre->company->name }}</strong>
                    </td>

                    <td style="width: 33%;">
                        Profit Centre: <strong>{{ '['.$schedule->lease->costCentre->profitCentre->code.'] '.$schedule->lease->costCentre->profitCentre->name }}</strong>
                    </td>

                    <td style="width: 33%;">
                        Cost Centre: <strong>{{ '['.$schedule->lease->costCentre->code.'] '.$schedule->lease->costCentre->name }}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="width: 67%;" colspan="2">
                        Vendor: <strong>{{ '['.$schedule->lease->supplier->code.'] '.$schedule->lease->supplier->name }}</strong>, Contract ID: <strong>{{ $schedule->lease->contract_id }}</strong>, Reference: <strong>{{ $schedule->lease->contract_reference }}</strong>
                    </td>
                    <td style="width: 33%;">
                        Interest Rate: <strong>{{ $schedule->lease->rate.'%' }}</strong> for <strong>{{ $schedule->lease->year }}</strong> years, <strong>{{ ucwords($schedule->lease->pay_interval) }}</strong> Installments
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%;">
                        Lease Amount: <strong>{{ $schedule->lease->exchangeRate->currency->symbol.systemMoneyFormat($schedule->lease->amount) }}</strong>
                    </td>

                    <td style="width: 33%;">
                        Monthly Installment Amount: <strong>{{ $schedule->lease->exchangeRate->currency->symbol.systemMoneyFormat($schedule->lease->installment_amount) }}</strong>
                    </td>

                    <td style="width: 33%;">
                        Total Lease Payable Amount: <strong>{{ $schedule->lease->exchangeRate->currency->symbol.systemMoneyFormat($schedule->lease->total_payable_amount) }}</strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var pay_form = $('#pay-form');
        var pay_button = $('.pay-button');
        var pay_content = pay_button.html();

        pay_form.submit(function(event) {
           event.preventDefault();
           pay_button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);

           $.ajax({
               url: pay_form.attr('action'),
               type: pay_form.attr('method'),
               dataType: 'json',
               data: pay_form.serializeArray(),
           })
           .done(function(response) {
               if(response.success){
                    location.reload();
               }else{
                    toastr.error(response.message);
                    pay_button.prop('disabled', false).html(pay_content);
               }
           })
           .fail(function(response) {
                var errors = '<ul class="pl-3">';
                $.each(response.responseJSON.errors, function(index, val) {
                    errors += '<li>'+val[0]+'</li>';
                });
                errors += '</ul>';
                toastr.error(errors);

                pay_button.prop('disabled', false).html(pay_content);
           });
        });
    });
</script>