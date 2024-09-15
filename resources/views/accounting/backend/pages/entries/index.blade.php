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
                    <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li><a href="#">PMS</a></li>
                <li class="active">Accounts</li>
                <li class="active">{{__($title)}}</li>
                <li class="top-nav-btn">
                    @can('entry-create')
                        @if(isset($entryTypes[0]))
                        @foreach($entryTypes as $key => $entryType)
                            <a class="btn btn-sm btn-success pull-right ml-2" style="float: right" @if($companies->count() == 1) href="{{ url('accounting/entries/create?type='.$entryType->label.'&company='.$companies->first()->id) }}" @else data-toggle="modal" data-target="#companyModal" onclick="$('#type').val('{{ $entryType->label }}')" @endif><i class="las la-plus-circle"></i>&nbsp;{{ $entryType->name }}</a>
                        @endforeach
                        @endif
                    @endcan
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-3">
                <div class="panel-body">
                    <form action="{{ url('accounting/entries') }}" method="get">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="company_id_"><strong>Company</strong></label>
                                    <select name="company_id" id="company_id_" class="form-control" onchange="getLedgers()">
                                        <option value="{{ null }}">All Companies</option>
                                        @if(isset($companies[0]))
                                        @foreach($companies as $key => $company)
                                        <option value="{{ $company->id }}" {{ $company_id == $company->id ? 'selected' : '' }}>[{{ $company->code }}] {{ $company->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="entry_type_id_"><strong>Entry Type</strong></label>
                                    <select name="entry_type_id" id="entry_type_id_" class="form-control">
                                        <option value="{{ null }}">All Entry Types</option>
                                        @if(isset($entryTypes[0]))
                                        @foreach($entryTypes as $key => $entryType)
                                        <option value="{{ $entryType->id }}" {{ $entry_type_id == $entryType->id ? 'selected' : '' }}>{{ $entryType->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fiscal_year_id_"><strong>Fiscal Year</strong></label>
                                    <select name="fiscal_year_id" id="fiscal_year_id_" class="form-control">
                                        <option value="{{ null }}">All Fiscal Years</option>
                                        @if(isset($fiscalYears[0]))
                                        @foreach($fiscalYears as $key => $fiscalYear)
                                        <option value="{{ $fiscalYear->id }}" {{ $fiscal_year_id == $fiscalYear->id ? 'selected' : '' }}>{{ $fiscalYear->title }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="currency_id_"><strong>Currency</strong></label>
                                    <select name="currency_id" id="currency_id_" class="form-control">
                                        <option value="{{ null }}">All Currencies</option>
                                        @if(isset($currencies[0]))
                                        @foreach($currencies as $key => $currency)
                                        <option value="{{ $currency->id }}" {{ $currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->code }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="from_"><strong>Date From</strong></label>
                                    <input type="date" name="from" id="from_" class="form-control" value="{{ $from }}" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="to_"><strong>Date To</strong></label>
                                    <input type="date" name="to" id="to_" class="form-control" value="{{ $to }}" />
                                </div>
                            </div>
                            <div class="col-md-4 ledger-parent">
                                <div class="form-group ledger">
                                    <label for="debit_ledger_id_"><strong>Debit Ledger</strong></label>
                                    <select name="debit_ledger_id" id="debit_ledger_id_" class="form-control select-me" data-selected="{{ $debit_ledger_id }}" onchange="getSubLedgers($(this))">
                                        
                                    </select>
                                </div>
                                <div class="form-group sub-ledger mt-2" style="display: none">
                                    <select name="debit_sub_ledger_id" class="form-control sub-ledger-select2" data-selected="{{ $debit_sub_ledger_id }}">
                                        <option value="{{ null }}">Without Sub-Ledger</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 ledger-parent">
                                <div class="form-group ledger">
                                    <label for="credit_ledger_id_"><strong>Credit Ledger</strong></label>
                                    <select name="credit_ledger_id" id="credit_ledger_id_" class="form-control select-me" data-selected="{{ $credit_ledger_id }}" onchange="getSubLedgers($(this))">
                                        
                                    </select>
                                </div>
                                <div class="form-group sub-ledger mt-2" style="display: none">
                                    <select name="credit_sub_ledger_id" class="form-control sub-ledger-select2" data-selected="{{ $credit_sub_ledger_id }}">
                                        <option value="{{ null }}">Without Sub-Ledger</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status_"><strong>Status</strong></label>
                                    <select name="status" id="status_" class="form-control">
                                        <option value="{{ null }}">All Status</option>
                                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        @if(isset($approvalLevels[0]))
                                        @foreach($approvalLevels as $approvalLevel)
                                            <option value="{{ $approvalLevel->name }}-approved" {{ $status == $approvalLevel->name.'-approved' ? 'selected' : '' }}>{{ $approvalLevel->name }} Approved</option>
                                            <option value="{{ $approvalLevel->name }}-pending" {{ $status == $approvalLevel->name.'-pending' ? 'selected' : '' }}>{{ $approvalLevel->name }} Pending</option>
                                            <option value="{{ $approvalLevel->name }}-denied" {{ $status == $approvalLevel->name.'-denied' ? 'selected' : '' }}>{{ $approvalLevel->name }} Denied</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 pt-4">
                                <div class="btn-group mt-2" style="width: 100%">
                                    <button type="submit" class="btn btn-success btn-sm" style="width: 50%"><i class="las la-search"></i>&nbsp;Search</button>
                                    <a href="{{ url('accounting/entries') }}" class="btn btn-danger btn-sm" style="width: 50%"><i class="las la-times"></i>&nbsp;Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="companyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <input type="hidden" name="type" id="type" value="">
                <div class="form-group">
                    <label for="company_id"><strong>Choose Company</strong></label>
                    <select name="company_id" id="company_id" class="form-control">
                        @if($companies->count() > 0)
                        @foreach($companies as $key => $company)
                            <option value="{{ $company->id }}">[{{ $company->code }}] {{ $company->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="la la-times"></i>&nbsp;Close</button>
                    <button type="button" class="btn btn-success" onclick="window.open('{{ url('accounting/entries/create') }}?type='+$('#type').val()+'&company='+$('#company_id').val(), '_parent')">Proceed&nbsp;<i class="las la-arrow-alt-circle-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
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

    function getSubLedgers(element, div) {
        var subLedgers = '<option value="{{ null }}">Without Sub-Ledger</option>';
        element.parent().parent().find('.sub-ledger-select2').html(subLedgers);
        element.parent().parent().find('.sub-ledger').hide();

        $.ajax({
            url: "{{ url('accounting/entries/create?get-sub-ledgers') }}&chart_of_account_id="+element.val(),
            type: 'GET',
            dataType: 'json',
            data: {},
        })
        .done(function(response) {
            if(response.count > 0){
                $.each(response.sub_ledgers, function(index, sub_ledger) {
                    subLedgers += '<option value="'+sub_ledger.id+'" '+(element.parent().parent().find('.sub-ledger-select2').attr('data-selected') == sub_ledger.id ? 'selected' : '')+'>['+sub_ledger.code+'] '+sub_ledger.name+'</option>';
                });

                element.parent().parent().find('.sub-ledger-select2').html(subLedgers);
                element.parent().parent().find('.sub-ledger').show();
            }
        });
    }

    getLedgers();
    function getLedgers() {
         $('#debit_ledger_id_').html('<option value="{{ null }}">Please wait...</option>');
        $('#credit_ledger_id_').html('<option value="{{ null }}">Please wait...</option>');
        $.ajax({
            url: "{{ url('accounting/entries') }}?get-ledgers&company_id="+$('#company_id_').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#debit_ledger_id_').html('<option value="{{ null }}">All Debit Ledgers</option>'+response);
            $('#credit_ledger_id_').html('<option value="{{ null }}">All Credit Ledgers</option>'+response);

            $.each($('.select-me'), function(index, val) {
                $(this).select2().val($(this).attr('data-selected')).trigger("change");
            });
        });
    }

    $(document).ready(function() {
        $.each($('.select-me'), function(index, val) {
            $(this).select2().val($(this).attr('data-selected')).trigger("change");
        });
    });
</script>
@include('accounting.backend.pages.approval-scripts')
@endsection