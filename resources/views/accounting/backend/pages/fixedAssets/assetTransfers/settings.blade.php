@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
    .select2-container--default .select2-results__option[aria-disabled=true] {
        color: #000 !important;
        font-weight:  bold !important;
    }

    .select2-container{
        width:  100% !important;
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
                    <form action="{{ route('accounting.asset-re-scheduling-settings.store') }}" method="post" accept-charset="utf-8">
                    @csrf
                        <div class="row pr-3 pt-3">
                            @if(isset($companies[0]))
                            @foreach($companies as $company)
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="overhouling_expense_ledger_id_{{ $company->id }}"><strong>{{ $company->code }} Overhouling Expense Ledger:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="overhouling_expense_ledger_id[{{ $company->id }}]" id="overhouling_expense_ledger_id_{{ $company->id }}" class="form-control rounded select-me">
                                                {!! chartOfAccountsOptions([], $company->overhouling_expense_ledger_id, 0, $all, false, '', false, [$company->id], true, getLedgerBalances($all, $company->id, getActiveFiscalYear($company->id)->id)) !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="accidental_loss_ledger_id_{{ $company->id }}"><strong>{{ $company->code }} Accidental Loss Ledger:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="accidental_loss_ledger_id[{{ $company->id }}]" id="accidental_loss_ledger_id_{{ $company->id }}" class="form-control rounded select-me">
                                                {!! chartOfAccountsOptions([], $company->accidental_loss_ledger_id, 0, $all, false, '', false, [$company->id], true, getLedgerBalances($all, $company->id, getActiveFiscalYear($company->id)->id)) !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                                            
                            <div class="col-md-12 mb-2 mt-2">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-success btn-lg pull-right"><i class="la la-save"></i>&nbsp;Save Settings</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection