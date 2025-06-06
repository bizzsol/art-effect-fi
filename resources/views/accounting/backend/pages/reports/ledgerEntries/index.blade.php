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
                    <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li><a href="#">PMS</a></li>
                <li class="active">Accounts</li>
                <li class="active">{{__($title)}}</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-2">
               <div id="accordion">
                  <div class="card">
                    <div class="card-header bg-primary p-0" id="headingOne">
                      <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#filter" aria-expanded="true" aria-controls="filter">
                          <h5 class="text-white"><strong><i class="las la-chevron-circle-right la-spin"></i>&nbsp;Filters</strong></h5>
                        </button>
                      </h5>
                    </div>

                    <div id="filter" class="collapse {{ !request()->has('from') ? 'show' : '' }}" aria-labelledby="headingOne" data-parent="#accordion">
                      <div class="card-body">
                        <form action="{{ url('accounting/ledger-entries') }}" method="get" accept-charset="utf-8" id="report-form">
                        <input type="hidden" name="report_type" id="report_type" value="report">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="chart_of_account_id"><strong>Ledger Account</strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="chart_of_account_id" id="chart_of_account_id" class="form-control">
                                                {!! chartOfAccountsOptions([], $chart_of_account_id, 0, getAllGroupAndLedgers()) !!}
                                            </select>
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
                                                        <option value="{{ $currency->id }}" {{ accountDefaultSettings()['currency_id'] == $currency->id ? 'selected' : '' }}>&nbsp;&nbsp;{{ $currency->name }} ({{ $currency->code }}&nbsp;|&nbsp;{{ $currency->symbol }})</option>
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
                                        <input type="date" name="from" id="from" value="{{ $from }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="to"><strong>End Date</strong></label>
                                        <input type="date" name="to" id="to" value="{{ $to }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @can('ledger-entries-excel')
                                    @include('accounting.backend.pages.reports.buttons', [
                                        'url' => url('accounting/ledger-entries?chart_of_account_id='.request()->get('chart_of_account_id').'&from='.request()->get('from').'&to='.request()->get('to')),
                                        'title' => $title
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
            <div class="panel panel-info mt-2 p-2 report-view" style="display: none">
                
            </div>
        </div>
    </div>
</div>
@endsection