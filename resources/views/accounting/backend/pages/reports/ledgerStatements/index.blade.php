@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
    .select2-container{
        width: 100% !important;
    }

    .select2-container--default .select2-results__option[aria-disabled=true]{
        color: black !important;
        font-weight:  bold !important;
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
                    <a href="{{ route('pms.dashboard') }}">Home</a>
                </li>
                <li><a href="#">PMS</a></li>
                <li class="active">Accounts</li>
                <li class="active">{{ $title }}</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-2">
             <div id="accordion">
              <div class="card">
                <div class="card-header bg-primary p-0" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#filter" aria-expanded="true" aria-controls="filter">
                          <h5 class="text-white"><strong><i class="las la-chevron-circle-right"></i>&nbsp;Filters</strong></h5>
                      </button>
                   </h5>
                </div>

                <div id="filter" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                  <div class="card-body">
                    <form action="{{ url('accounting/ledger-statement') }}" method="get" accept-charset="utf-8" id="report-form">
                    <input type="hidden" name="report_type" id="report_type" value="report">
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label for="company_id"><strong>Company</strong></label>
                                <select name="company_id" id="company_id" class="form-control" onchange="getProfitCentres(),getChartOfAccounts();">
                                    @if(isset($companies[0]))
                                    @foreach($companies as $key => $company)
                                        <option value="{{ $company->id }}" {{ request()->get('company_id') == $company->id ? 'selected' : '' }}>[{{ $company->code }}] {{ $company->name }}</option>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="chart_of_account_id"><strong>Ledger Account</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="chart_of_account_id[]" id="chart_of_account_id" class="form-control" multiple data-placeholder="Choose Ledger Accounts..." onchange="getSubLedgers($(this))">
                                            {!! chartOfAccountsOptions([], request()->get('chart_of_account_id'), 0, getAllGroupAndLedgers(false, true)) !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_ledger_id"><strong>Sub Ledger</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="sub_ledger_id[]" id="sub_ledger_id" class="form-control" multiple data-placeholder="Choose Sub Ledgers...">
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="narration"><strong>Narration</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="narration" id="narration" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="currency_id"><strong>Currency</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
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
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="from"><strong>Start Date</strong></label>
                                    <input type="date" name="from" id="from" value="{{ date('Y-m-d', strtotime($from)) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="to"><strong>End Date</strong></label>
                                    <input type="date" name="to" id="to" value="{{ date('Y-m-d', strtotime($to)) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                @can('ledger-statement-excel')
                                    @include('accounting.backend.pages.reports.buttons', [
                                        'url' => url('accounting/ledger-statement?chart_of_account_id='.request()->get('chart_of_account_id').'&from='.request()->get('from').'&to='.request()->get('to')),
                                        'normalExcel' => true
                                    ])
                                @endcan
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info mt-2 p-2 report-view">
        
    </div>
</div>
</div>
</div>
@endsection