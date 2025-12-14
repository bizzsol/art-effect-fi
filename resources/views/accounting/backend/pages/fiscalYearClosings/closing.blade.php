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
                    <div class="row pr-3 mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h5 class="text-white">{{ $title }}</h5>
                                </div>
                                <div class="card-body" style="border: 1px solid #ccc">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Company</th>
                                                        <th>Date & Time</th>
                                                        <th>Closing Fiscal Year</th>
                                                        <th>Closing to Fiscal Year</th>
                                                        <th>Currency</th>
                                                        <th>Balance</th>
                                                        <th>Profit Loss</th>
                                                        <th>Retained Earnings</th>
                                                        <th>Processed By</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $closing->company ? '['.$closing->company->code.'] '.$closing->company->name : '' }}</td>
                                                        <td>{{ date('Y-m-d', strtotime($closing->date)) }} {{ date('g:i a', strtotime($closing->time)) }}</td>
                                                        <td>
                                                            {{ $closing->fromFiscalYear->title }}&nbsp;|&nbsp;{{ date('d-M-y', strtotime($closing->fromFiscalYear->start)).' to '.date('d-M-y', strtotime($closing->fromFiscalYear->end)) }}
                                                        </td>
                                                        <td>
                                                            {{ $closing->toFiscalYear->title }}&nbsp;|&nbsp;{{ date('d-M-y', strtotime($closing->toFiscalYear->start)).' to '.date('d-M-y', strtotime($closing->toFiscalYear->end)) }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $closing->exchangeRate->currency->code }}
                                                        </td>
                                                        <td class="text-right">{{ systemMoneyFormat($closing->balance) }}</td>
                                                        <td class="text-right">{{ systemMoneyFormat($closing->profit_loss) }}</td>
                                                        <td class="text-right">{{ systemMoneyFormat($closing->retained_earnings) }}</td>
                                                        <td>{{ $closing->creator ? $closing->creator->name : '' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <h5 class="mb-2"><strong>#Balance Sheet Items</strong></h5>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%">Code</th>
                                                        <th style="width: 40%">Ledger</th>
                                                        <th style="width: 25%" class="text-right">Closing Balance</th>
                                                        <th style="width: 25%" class="text-right">Carry Forwarded</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {!! $balanceSheets !!}
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <h5 class="mb-2"><strong>#Profit Loss Items</strong></h5>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%">Code</th>
                                                        <th style="width: 40%">Ledger</th>
                                                        <th style="width: 25%" class="text-right">Closing Balance</th>
                                                        <th style="width: 25%" class="text-right">Carry Forwarded</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {!! $profitLosses !!}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection