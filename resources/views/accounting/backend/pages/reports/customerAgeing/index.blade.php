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
                    <form action="{{ url('accounting/customer-ageing') }}" method="get" accept-charset="utf-8" id="report-form">
                    <input type="hidden" name="report_type" id="report_type" value="report">
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label for="company_id"><strong>Company</strong></label>
                                <select name="company_id" id="company_id" class="form-control" onchange="getChartOfAccounts();getProfitCentres();">
                                    @if(isset($companies[0]))
                                    @foreach($companies as $key => $company)
                                        <option value="{{ $company->id }}" {{ request()->get('company_id') == $company->id ? 'selected' : '' }}>[{{ $company->code }}] {{ $company->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="profit_centre_id"><strong>Profit Centre</strong></label>
                                <select name="profit_centre_id" id="profit_centre_id" class="form-control" onchange="getCompanies()">
                                    <option value="0">All Profit Centres</option>
                                    @if(isset($profitCentres[0]))
                                    @foreach($profitCentres as $key => $profitCentre)
                                        <option value="{{ $profitCentre->id }}">[{{ $profitCentre->code }}] {{ $profitCentre->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_code"><strong>Customer Code</strong></label>
                                    <select name="customer_code" id="customer_code" class="form-control">
                                        <option value="{{ null }}">All Customers</option>
                                        @if(isset($customers[0]))
                                        @foreach($customers as $key => $customer)
                                        @if(isset($customer['code']) && !empty($customer['code']))
                                            <option value="{{ $customer['code'] }}">{{ $customer['code'] }} :: {{ $customer['name'] }}</option>
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="chart_of_account_id"><strong>Ledger Accounts</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="chart_of_account_id[]" id="chart_of_account_id" class="form-control" multiple data-placeholder="Choose Ledger Accounts...">
                                            {!! $chartOfAccountsOptions !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="narration"><strong>Narrations</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="narration" id="narration" class="form-control" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                    <label for="date"><strong>Date</strong></label>
                                    <input type="date" name="date" id="date" value="{{ date('Y-m-d', strtotime($date)) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8 pt-4">
                                @include('accounting.backend.pages.reports.buttons', [
                                    'url' => url('accounting/customer-ageing?chart_of_account_id='.request()->get('chart_of_account_id').'&from='.request()->get('date')),
                                ])
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info mt-2 p-2 report-view export-table">
        
    </div>
</div>
</div>
</div>
@endsection
