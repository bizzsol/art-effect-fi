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
                    <form action="{{ route('accounting.payment-schedules.update', $schedule->id) }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="schedule-form">
                    @csrf
                    @method('PUT')
                        <div class="row pr-3">
                            <div class="col-md-3">
                                <label for="company_id"><strong>Company:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="company_id" id="company_id" class="form-control rounded">
                                        @foreach($companies as $key => $company)
                                            <option value="{{ $company->id }}" {{ $schedule->company_id == $company->id ? 'selected' : '' }}>[{{ $company->code }}] {{ $company->name }}</option>
                                       @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="name"><strong>{{ __('Payment Schedule Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name', $schedule->name) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="day"><strong>Day:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="day" id="day" class="form-control rounded">
                                        @for($i = 1;$i <= 31; $i++)
                                        <option {{ ($i < 10 ? '0'.$i : $i) == $company->day ? 'selected' : '' }}>{{ $i < 10 ? '0'.$i : $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="amount"><strong>Amount:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="number" name="amount" id="amount" value="{{ old('amount', $schedule->amount) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row pr-3">
                            <div class="col-md-12">
                                <label for="description"><strong>{{ __('Description') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <textarea name="description" id="description" class="form-control rounded" style="min-height: 130px">{{ old('description', $schedule->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <h4 class="text-white">Debit Ledgers</h4>
                                            </div>
                                            <div class="col-md-3">
                                                <a class="btn btn-sm btn-success btn-block" onclick="addDebitLedger()"><i class="la la-plus"></i>&nbsp;Add Ledger</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body debit-ledgers" style="border: 1px solid #ccc;">
                                        @if($schedule->ledgers->where('type', 'D')->count() > 0)
                                        @foreach($schedule->ledgers->where('type', 'D') as $key => $ledger)
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="debit_ledgers"><strong>Ledger</strong></label>
                                                    <select name="debit_ledgers[]" class="form-control choose-me" data-selected="{{ $ledger->chart_of_account_id }}">{!! $chartOfAccountsOptions !!}</select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="debit_ledger_amounts"><strong>Amount</strong></label>
                                                    <input type="number" name="debit_ledger_amounts[]" id="debit_ledger_amounts" value="{{ $ledger->amount }}" class="debit_ledger_amounts form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-1 pt-4 pl-0 pr-0">
                                                <a class="btn btn-md btn-danger btn-block mt-2" onclick="remove($(this))"><i class="la la-trash"></i></a>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <h4 class="text-white">Credit Ledgers</h4>
                                            </div>
                                            <div class="col-md-3">
                                                <a class="btn btn-sm btn-success btn-block" onclick="addCreditLedger()"><i class="la la-plus"></i>&nbsp;Add Ledger</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body credit-ledgers" style="border: 1px solid #ccc;">
                                        @if($schedule->ledgers->where('type', 'C')->count() > 0)
                                        @foreach($schedule->ledgers->where('type', 'C') as $key => $ledger)
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="credit_ledgers"><strong>Ledger</strong></label>
                                                    <select name="credit_ledgers[]" class="form-control choose-me" data-selected="{{ $ledger->chart_of_account_id }}">{!! $chartOfAccountsOptions !!}</select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="credit_ledger_amounts"><strong>Amount</strong></label>
                                                    <input type="number" name="credit_ledger_amounts[]" id="credit_ledger_amounts" value="{{ $ledger->amount }}" class="credit_ledger_amounts form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-1 pt-4 pl-0 pr-0">
                                                <a class="btn btn-md btn-danger btn-block mt-2" onclick="remove($(this))"><i class="la la-trash"></i></a>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/payment-schedules') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md schedule-button"><i class="la la-save"></i>&nbsp;Save Payment Schedule</button>
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
    function addDebitLedger(select2 = true) {
        $('.debit-ledgers').append('<div class="row">'+
                                        '<div class="col-md-8">'+
                                            '<div class="form-group">'+
                                                '<label for="debit_ledgers"><strong>Ledger</strong></label>'+
                                                '<select name="debit_ledgers[]" class="form-control">{!! $chartOfAccountsOptions !!}</select>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-3">'+
                                            '<div class="form-group">'+
                                                '<label for="debit_ledger_amounts"><strong>Percentage</strong></label>'+
                                                '<input type="number" name="debit_ledger_amounts[]" id="debit_ledger_amounts" value="0" class="debit_ledger_amounts form-control">'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-1 pt-4 pl-0 pr-0">'+
                                            '<a class="btn btn-md btn-danger btn-block mt-2" onclick="remove($(this))"><i class="la la-trash"></i></a>'+
                                        '</div>'+
                                    '</div>');
        if(select2){
            $('select').select2();
        }
    }

    function addCreditLedger(select2 = true) {
        $('.credit-ledgers').append('<div class="row">'+
                                        '<div class="col-md-8">'+
                                            '<div class="form-group">'+
                                                '<label for="credit_ledgers"><strong>Ledger</strong></label>'+
                                                '<select name="credit_ledgers[]" class="form-control">{!! $chartOfAccountsOptions !!}</select>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-3">'+
                                            '<div class="form-group">'+
                                                '<label for="credit_ledger_amounts"><strong>Percentage</strong></label>'+
                                                '<input type="number" name="credit_ledger_amounts[]" id="credit_ledger_amounts" value="0" class="credit_ledger_amounts form-control">'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-1 pt-4 pl-0 pr-0">'+
                                            '<a class="btn btn-md btn-danger btn-block mt-2" onclick="remove($(this))"><i class="la la-trash"></i></a>'+
                                        '</div>'+
                                    '</div>');
        if(select2){
            $('select').select2();
        }
    }

    function remove(element) {
        element.parent().parent().remove();
    }


    $(document).ready(function() {
        $.each($('.choose-me'), function(index, val) {
            $(this).select2().val($(this).attr('data-selected')).trigger("change");
        });

        var form = $('#schedule-form');
        var button = $('.schedule-button');
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
                button.html(content).prop('disabled', false);
                if(response.success){
                    location.reload();
                }else{
                    toastr.error(response.message);
                }
            })
            .fail(function(response) {
                var errors = '<ul class="pl-2">';
                $.each(response.responseJSON.errors, function(index, val) {
                    errors += "<li class='text-white'>"+val[0]+"</li>";
                });
                errors += '</ul>';
                toastr.error(errors);

                button.html(content).prop('disabled', false);
            });
        });
    });
</script>
@endsection