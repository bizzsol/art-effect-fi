@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
    label{
        cursor: pointer !important;
    }

    input[type="checkbox"]{
        cursor: pointer !important;
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
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info mt-2 p-2">
                        <form action="{{ url('accounting/trial-balance') }}" method="get" accept-charset="utf-8" id="report-form">
                            <input type="hidden" name="report_type" id="report_type" value="report">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label for="company_id"><strong>Company</strong></label>
                                            <select name="company_id" id="company_id" class="form-control" onchange="getProfitCentres()">
                                                @if(isset($companies[0]))
                                                @foreach($companies as $key => $company)
                                                    <option value="{{ $company->id }}">[{{ $company->code }}] {{ $company->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="profit_centre_id"><strong>Profit Centre</strong></label>
                                            <select name="profit_centre_id" id="profit_centre_id" class="form-control" onchange="getCostCentres()">
                                                <option value="0">All Profit Centres</option>
                                                @if(isset($profitCentres[0]))
                                                @foreach($profitCentres as $key => $profitCentre)
                                                    <option value="{{ $profitCentre->id }}">[{{ $profitCentre->code }}] {{ $profitCentre->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="cost_centre_id"><strong>Cost Centre</strong></label>
                                            <select name="cost_centre_id" id="cost_centre_id" class="form-control">
                                                <option value="0">All Cost Centres</option>
                                                {!! getCostCentres(true, $companies->first()->id, true) !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="fiscal_year_id"><strong>Fiscal Year</strong></label>
                                            <select name="fiscal_year_id" id="fiscal_year_id" class="form-control" onchange="getDates()">
                                                @if(isset($fiscalYears[0]))
                                                @foreach($fiscalYears as $key => $fiscalYear)
                                                    <option value="{{ $fiscalYear->id }}" data-start="{{ date('Y-m-d', strtotime($fiscalYear->start)) }}" data-end="{{ date('Y-m-d', strtotime($fiscalYear->end)) }}">{{ $fiscalYear->title }}&nbsp;|&nbsp;{{ date('d-M-y', strtotime($fiscalYear->start)).' to '.date('d-M-y', strtotime($fiscalYear->end)) }})</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="from"><strong>From</strong></label>
                                                    <input type="date" name="from" id="from" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="to"><strong>To</strong></label>
                                                    <input type="date" name="to" id="to" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="currency_id"><strong>Reporting Currency</strong></label>
                                            <select name="currency_id" id="currency_id" class="form-control">
                                                @if(isset($currencyTypes[0]))
                                                @foreach($currencyTypes as $key => $currencyType)
                                                <optgroup label="{{ $currencyType->name }}">
                                                    @if($currencyType->currencies->count() > 0)
                                                    @foreach($currencyType->currencies as $key => $currency)
                                                        <option value="{{ $currency->id }}" {{ $accountDefaultSettings['currency_id'] == $currency->id ? 'selected' : '' }}>&nbsp;&nbsp;{{ $currency->name }} ({{ $currency->code }}&nbsp;|&nbsp;{{ $currency->symbol }})</option>
                                                    @endforeach
                                                    @endif
                                                </optgroup>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="group_wise"><strong>Group Wise</strong></label>
                                            <select name="group_wise" id="group_wise" class="form-control">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12 mt-2">
                                                    <div class="form-group text-center">
                                                        <label>
                                                            <input type="checkbox" onchange="checkColumns($(this))" class="opening_balance" value="1" checked>&nbsp;Opening Balance
                                                        </label>
                                                        &nbsp;&nbsp;
                                                        <label>
                                                            <input type="checkbox" onchange="checkColumns($(this))" class="debit" value="1" checked>&nbsp;Debit
                                                        </label>
                                                        &nbsp;&nbsp;
                                                        <label>
                                                            <input type="checkbox" onchange="checkColumns($(this))" class="credit" value="1" checked>&nbsp;Credit
                                                        </label>
                                                        &nbsp;&nbsp;
                                                        <label>
                                                            <input type="checkbox" onchange="checkColumns($(this))" class="closing_balance" value="1" checked>&nbsp;Closing Balance
                                                        </label>
                                                        &nbsp;&nbsp;
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    @can('trial-balance-excel')
                                        @include('accounting.backend.pages.reports.buttons', [
                                            'title' => "Trial Balance",
                                            'url' => url('accounting/trial-balance'),
                                        ])
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel panel-info mt-2 p-2">
                        <div class="row">
                            <div class="col-md-12 report-search mb-2" style="display: none">
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="text" placeholder="Search Ledger Code and Names here..." class="form-control search-report" onchange="searchReport($(this))" onkeyup="searchReport($(this))">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="zero_balance" id="zero_balance" class="form-control" onchange="zeroBalanceFilter()">
                                            <option value="1">Show Zero Balance</option>
                                            <option value="0">Hide Zero Balance</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 report-view export-table" style="display: none">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
    function checkColumns(element) {
        $.each(element.parent().parent().find('input'), function(index, val) {
            if($(this).is(':checked')){
                $('.'+$(this).attr('class')+'_column').show();
            }else{
                $('.'+$(this).attr('class')+'_column').hide();
            }
        });
    }
</script>
@endsection