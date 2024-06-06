@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
</style>
@include('yajra.css')
@endsection
@section('main-content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('pms.dashboard') }}">Home</a>
                </li>
                <li><a href="#">PMS</a></li>
                <li class="active">Accounts</li>
                <li class="active">{{ $title }}</li>

                <li class="top-nav-btn">
                    <a href="{{ url('accounting/leases') }}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="View Leases"> <i class="las la-eye"></i>&nbsp;View Leases</i></a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-2">
                <form action="{{ url('accounting/leases/create') }}" method="get">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="company_id"><strong>Company <span class="text-danger">*</span></strong></label>
                                <select name="company_id" id="company_id" class="form-control" onchange="getCostCentres()">
                                    @if(isset($companies[0]))
                                    @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request()->get('company_id') == $company->id ? 'selected' : '' }}>{{ $company->code }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cost_centre_id"><strong>Cost Centre <span class="text-danger">*</span></strong></label>
                                <select name="cost_centre_id" id="cost_centre_id" class="form-control" data-selected="{{ request()->get('cost_centre_id') }}">
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier_id"><strong>Vendor <span class="text-danger">*</span></strong></label>
                                <select name="supplier_id" id="supplier_id" class="form-control">
                                    @if(isset($suppliers[0]))
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request()->get('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="currency_id"><strong>Currency <span class="text-danger">*</span></strong></label>
                                <select name="currency_id" id="currency_id" class="form-control">
                                    @if(isset($currencies[0]))
                                    @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ request()->get('currency_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contract_id"><strong>Contract ID <span class="text-danger">*</span></strong></label>
                                <input type="text" name="contract_id" id="contract_id" class="form-control" value="{{ request()->get('contract_id') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contract_reference"><strong>Contract Reference <span class="text-danger">*</span></strong></label>
                                <input type="text" name="contract_reference" id="contract_reference" class="form-control" value="{{ request()->get('contract_id') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date"><strong>Lease Start Date<span class="text-danger">*</span></strong></label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request()->has('start_date') ? request()->get('start_date') : date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pay_interval"><strong>Installment Type<span class="text-danger">*</span></strong></label>
                                <select name="pay_interval" id="pay_interval" class="form-control">
                                    @foreach(leasePayIntervals() as $key => $value)
                                    <option value="{{ $key }}" {{ request()->get('pay_interval') == $key ? 'selected' : '' }}>{{ ucwords($key) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="amount"><strong>Lease Amount <span class="text-danger">*</span></strong></label>
                                <input type="number" step="any" name="amount" id="amount" class="form-control" value="{{ request()->has('amount') ? request()->get('amount') : 1 }}" required min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rate"><strong>Interest Rate(%)<span class="text-danger">*</span></strong></label>
                                <input type="number" step="any" name="rate" id="rate" class="form-control" value="{{ request()->has('rate') ? request()->get('rate') : 1 }}" required min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year"><strong>Years<span class="text-danger">*</span></strong></label>
                                <input type="number" name="year" id="year" class="form-control" value="{{ request()->has('year') ? request()->get('year') : 1 }}" required min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group pt-4">
                                <button class="btn btn-md btn-success btn-block mt-2" type="submit"><i class="las la-search"></i>&nbsp;Amortization Schedule</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if(request()->has('cost_centre_id') && request()->has('supplier_id') && request()->has('currency_id') && request()->get('amount') > 0 && request()->get('rate') > 0 && request()->get('year') > 0)
            @php
               $pmt = calculatePMT((int)(request()->get('amount')), (float)(request()->get('rate')), (int)(request()->get('year')));
               $currency = $currencies->where('id', request()->get('currency_id'))->first();
            @endphp
            <div class="panel panel-info mt-2 p-2">
                <form action="{{ route('accounting.leases.store') }}" method="post" id="lease-form">
                @csrf
                <input type="hidden" name="company_id" value="{{ request()->get('company_id') }}">
                <input type="hidden" name="cost_centre_id" value="{{ request()->get('cost_centre_id') }}">
                <input type="hidden" name="supplier_id" value="{{ request()->get('supplier_id') }}">
                <input type="hidden" name="currency_id" value="{{ request()->get('currency_id') }}">
                <input type="hidden" name="contract_id" value="{{ request()->get('contract_id') }}">
                <input type="hidden" name="contract_reference" value="{{ request()->get('contract_reference') }}">
                <input type="hidden" name="amount" value="{{ request()->get('amount') }}">
                <input type="hidden" name="rate" value="{{ request()->get('rate') }}">
                <input type="hidden" name="year" value="{{ request()->get('year') }}">
                <input type="hidden" name="start_date" value="{{ request()->get('start_date') }}">
                <input type="hidden" name="pay_interval" value="{{ request()->get('pay_interval') }}">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td style="width: 50%">
                                            {{ ucwords(request()->get('pay_interval')) }} Installment Amount: <strong>{{ $currency->symbol }}{{ systemMoneyFormat($pmt['installment']) }}</strong>
                                        </td>
                                        <td style="width: 50%">
                                            Total Lease Payable Amount: <strong>{{ $currency->symbol }}{{ systemMoneyFormat($pmt['total']) }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <h5 class="mt-3 mb-1"><strong>{{ ucwords(request()->get('pay_interval')) }} Amortization Schedule</strong></h5>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10%" class="text-center">{{ ucwords(str_replace('ly', '', request()->get('pay_interval'))) }}</th>
                                        <th style="width: 10%" class="text-center">Date</th>
                                        <th style="width: 16%" class="text-right">Beginning</th>
                                        <th style="width: 16%" class="text-right">PMT</th>
                                        <th style="width: 16%" class="text-right">Interest</th>
                                        <th style="width: 16%" class="text-right">Principal</th>
                                        <th style="width: 16%" class="text-right">Ending balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $balance = (int)(request()->get('amount'));
                                        $count = 0;
                                        $date = date('Y-m-01', strtotime(request()->get('start_date').' '.leasePayIntervals()[request()->get('pay_interval')]['interval']));
                                    @endphp
                                    
                                    <tr>
                                        <td class="text-right" colspan="7">{{ systemMoneyFormat($balance) }}</td>
                                    </tr>

                                    @while($balance > 0)
                                    @php
                                        $begin = $balance;
                                        $interest = ($balance*((float)(request()->get('rate'))/100))/leasePayIntervals()[request()->get('pay_interval')]['divisor'];
                                        $principle = $pmt['installment']-$interest;
                                        $balance = $begin-$principle;
                                        $count++;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $count }}</td>
                                        <td class="text-center">{{ $date }}</td>
                                        <td class="text-right">{{ systemMoneyFormat($begin) }}</td>
                                        <td class="text-right">{{ systemMoneyFormat($pmt['installment']) }}</td>
                                        <td class="text-right">{{ systemMoneyFormat($interest) }}</td>
                                        <td class="text-right">{{ systemMoneyFormat($principle) }}</td>
                                        <td class="text-right">{{ systemMoneyFormat($balance) }}</td>
                                    </tr>

                                    @php
                                        $date = date('Y-m-01', strtotime($date.' '.leasePayIntervals()[request()->get('pay_interval')]['interval']));
                                    @endphp
                                    @endwhile
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-md btn-success text-white lease-button"><i class="las la-check"></i>&nbsp;Proceed Lease Creation</button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')

<script type="text/javascript">
    $(document).ready(function() {
        var form = $('#lease-form');
        var button = $('.lease-button');
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
                    window.open("{{ url('accounting/leases') }}", "_parent");
                }else{
                    toastr.error(response.message);
                }
                button.prop('disabled', false).html(content);
            })
            .fail(function(response) {
                var errors = '<ul class="pl-3">';
                $.each(response.responseJSON.errors, function(index, val) {
                    errors += '<li>'+val[0]+'</li>';
                });
                errors += '</ul>';
                toastr.error(errors);

                button.prop('disabled', false).html(content);
            });
        });
    });

    getCostCentres();
    function getCostCentres() {
        $('#cost_centre_id').html('<option value="{{ null }}">Please wait...</option>');

        $.ajax({
            url: "{{ url('accounting/leases/create?get-cost-centres') }}&company_id="+$('#company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            if($('#cost_centre_id').attr('data-selected') != ""){
                $('#cost_centre_id').html(response).select2().val($('#cost_centre_id').attr('data-selected')).trigger("change");
            }else{
                $('#cost_centre_id').html(response).select2().trigger("change");
            }
        });
    }
</script>
@endsection