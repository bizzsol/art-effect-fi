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
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="panel panel-info mt-2 p-2">
                        <form action="{{ url('accounting/trial-balance-with-balance') }}" method="get" accept-charset="utf-8" id="report-form">
                            <input type="hidden" name="report_type" id="report_type" value="report">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="company_id"><strong>Company</strong></label>
                                            <select name="company_id" id="company_id" class="form-control">
                                                @if(isset($companies[0]))
                                                @foreach($companies as $key => $company)
                                                    <option value="{{ $company->id }}">[{{ $company->code }}] {{ $company->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fiscal_year_id"><strong>Fiscal Year</strong></label>
                                            <select name="fiscal_year_id" id="fiscal_year_id" class="form-control">
                                                @if(isset($fiscalYears[0]))
                                                @foreach($fiscalYears as $key => $fiscalYear)
                                                    <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->title }}&nbsp;|&nbsp;{{ date('d-M-y', strtotime($fiscalYear->start)).' to '.date('d-M-y', strtotime($fiscalYear->end)) }})</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
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
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    @can('trial-balance-excel')
                                        @include('accounting.backend.pages.reports.buttons', [
                                            'title' => "Trial Balance (Balance) from ".date('M jS, y', strtotime($fiscalYear->start))." to ".date('M jS, y', strtotime($fiscalYear->end)),
                                            'url' => url('accounting/trial-balance-with-balance'),
                                        ])
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel panel-info mt-2 p-2">
                        <div class="row">
                            <div class="col-md-12 report-view export-table" style="display: none">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection