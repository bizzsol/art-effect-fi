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
            <div class="panel panel-info mt-2 p-3">
                <form action="{{ url('accounting/reconciliation') }}" method="get" accept-charset="utf-8">
                    <div class="row">
                        <div class="col-md-5">
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
                        <div class="col-md-3 pt-4">
                            <div class="row pt-2">
                                <div class="col-md-6 pt-1">
                                    <button class="btn btn-sm btn-block btn-success" type="submit"><i class="la la-search"></i>&nbsp;Search</button>
                                </div>
                                <div class="col-md-6 pt-1">
                                    <a class="btn btn-sm btn-block btn-danger" href="{{ url('accounting/reconciliation') }}"><i class="la la-times"></i>&nbsp;Clear</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection