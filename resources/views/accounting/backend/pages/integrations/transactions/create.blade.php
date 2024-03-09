@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
</style>
@endsection
@section('main-content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li><a href="#">PMS</a></li>
                <li class="active">Accounts</li>
                <li class="active">{{__($title)}}</li>
                <li class="top-nav-btn">
                    <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3">
                    <form action="{{ route('accounting.transactions.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="transaction-form">
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-4">
                                <label for="company_id"><strong>{{ __('Company') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="company_id" id="company_id" class="form-control" onchange="getCostCentres()">
                                        @if(isset($companies[0]))
                                        @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->code }} | {{ $company->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="cost_centre_id"><strong>{{ __('Cost Centre') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="cost_centre_id" id="cost_centre_id" class="form-control">
                                       
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="currency_id"><strong>{{ __('Currency') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="currency_id" id="currency_id" class="form-control">
                                        @if(isset($currencies[0]))
                                        @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="entry_type_id"><strong>{{ __('Entry Type') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="entry_type_id" id="entry_type_id" class="form-control">
                                        @if(isset($entryTypes[0]))
                                        @foreach($entryTypes as $entryType)
                                        <option value="{{ $entryType->id }}">{{ $entryType->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="sub_process_id"><strong>{{ __('Sub Process') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="sub_process_id" id="sub_process_id" class="form-control" onchange="getLedgers()">
                                        @if(isset($processes[0]))
                                        @foreach($processes as $process)
                                        <optgroup label="{{ $process->code }} | {{ $process->name }}">
                                            @if(isset($process->subProcesses[0]))
                                            @foreach($process->subProcesses as $subProcess)
                                            <option value="{{ $subProcess->id }}">&nbsp;&nbsp;&nbsp;{{ $subProcess->code }} | {{ $subProcess->name }}</option>
                                            @endforeach
                                            @endif
                                        </optgroup>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="datetime"><strong>{{ __('Datetime') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="datetime-local" name="datetime" id="datetime" value="{{ old('datetime', date('Y-m-d H:i:s')) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="amount"><strong>{{ __('Amount') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="number" name="amount" id="amount" value="{{ old('amount', 0) }}" class="form-control rounded" onchange="calculate()" onkeyup="calculate()">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="number"><strong>{{ __('Reference') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="number" id="number" value="{{ old('number') }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row ledgers mt-4">
                            
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="notes"><strong>{{ __('Narration') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <textarea name="notes" id="notes" rows="2" class="form-control" style="resize: none;">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                            
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/transactions') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md transaction-button"><i class="la la-save"></i>&nbsp;Save Transaction</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
    getCostCentres();
    function getCostCentres() {
        $('#cost_centre_id').html('<option value="">Please wait...<option>');
        $.ajax({
            url: "{{ url('accounting/transactions/create') }}?get-cost-centres&company_id="+$('#company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(costCentres) {
            var data = '';
            $.each(costCentres, function(index, costCentre) {
                data += '<option value="'+costCentre.id+'">'+costCentre.name+'</option>';
            });
            $('#cost_centre_id').html(data);
        });
    }

    getLedgers();
    function getLedgers() {
        $('.ledgers').html('<div class="col-md-12"><h4 class="text-center"><i class="las la-spinner la-spin"></i>&nbsp;Please wait...</h4></div>');
        $.ajax({
            url: "{{ url('accounting/transactions/create') }}?get-ledgers&sub_process_id="+$('#sub_process_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('.ledgers').html(response);
            $('#amount').val(0);
        });
    }

    function calculate() {
        var amount = parseInt($('#amount').val());
        $.each($('.ledger'), function(index, val) {
            $(this).val(amount > 0 && parseInt($(this).attr('data-percentage')) > 0 ? Math.floor(amount*(parseInt($(this).attr('data-percentage'))/100)) : 0);
        });
    }

    $(document).ready(function() {
        var form = $('#transaction-form');
        var button = $('.transaction-button');
        var content = button.html();

        form.submit(function(event) {
            event.preventDefault();
            button.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                data: form.serializeArray(),
            })
            .done(function(response) {
                if(response.success){
                    form[0].reset();
                    $.dialog({
                        title: response.entry_type+" Voucher #"+response.code,
                        content: "url:{{ url('accounting/entries') }}/"+response.id+"?short-details",
                        animation: 'scale',
                        columnClass: 'col-md-12',
                        closeAnimation: 'scale',
                        backgroundDismiss: true
                    });
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
</script>
@endsection