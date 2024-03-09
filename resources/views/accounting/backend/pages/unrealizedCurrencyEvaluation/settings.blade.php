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
                    <form action="{{ route('accounting.unrealized-currency-settings.store') }}" method="post" accept-charset="utf-8">
                    @csrf
                        <div class="row pr-3 pt-3">
                            <div class="col-md-12">
                                <label for="asset_ledgers"><strong>Asset Ledgers:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="asset_ledgers[]" id="asset_ledgers" class="form-control rounded" multiple>
                                        @if($ledgers->whereIn('accountGroup.account_class_id', [1])->count() > 0)
                                        @foreach($ledgers->whereIn('accountGroup.account_class_id', [1]) as $key => $ledger)
                                            <option value="{{ $ledger->id }}" {{ in_array($ledger->id, $unrealizedCurrencyEventSettings['asset_ledgers']) ? 'selected' : '' }}>[{{ $ledger->code }}]&nbsp;{{ $ledger->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="liability_ledgers"><strong>Liability Ledgers:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="liability_ledgers[]" id="liability_ledgers" class="form-control rounded" multiple>
                                        @if($ledgers->whereIn('accountGroup.account_class_id', [2, 3])->count() > 0)
                                        @foreach($ledgers->whereIn('accountGroup.account_class_id', [2, 3]) as $key => $ledger)
                                            <option value="{{ $ledger->id }}" {{ in_array($ledger->id, $unrealizedCurrencyEventSettings['liability_ledgers']) ? 'selected' : '' }}>[{{ $ledger->code }}]&nbsp;{{ $ledger->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="pnl_asset_ledger_id"><strong>P&L Posting for Currnecy Gain/Loss (For Asset GLs:):<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="pnl_asset_ledger_id" id="pnl_asset_ledger_id" class="form-control rounded">
                                        @if($ledgers->count() > 0)
                                        @foreach($ledgers as $key => $ledger)
                                            <option value="{{ $ledger->id }}" {{ $ledger->id == $unrealizedCurrencyEventSettings['pnl_asset_ledger_id'] ? 'selected' : '' }}>[{{ $ledger->code }}]&nbsp;{{ $ledger->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="pnl_liability_ledger_id"><strong>P&L Posting for Currnecy Gain/Loss (For Liability GLs:):<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="pnl_liability_ledger_id" id="pnl_liability_ledger_id" class="form-control rounded">
                                        @if($ledgers->count() > 0)
                                        @foreach($ledgers as $key => $ledger)
                                            <option value="{{ $ledger->id }}" {{ $ledger->id == $unrealizedCurrencyEventSettings['pnl_liability_ledger_id'] ? 'selected' : '' }}>[{{ $ledger->code }}]&nbsp;{{ $ledger->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="provision_asset_ledger_id"><strong>Provision Posting for Currnecy Gain/Loss (For Asset GLs:):<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="provision_asset_ledger_id" id="provision_asset_ledger_id" class="form-control rounded">
                                        @if($ledgers->whereIn('accountGroup.account_class_id', [1])->count() > 0)
                                        @foreach($ledgers->whereIn('accountGroup.account_class_id', [1]) as $key => $ledger)
                                            <option value="{{ $ledger->id }}" {{ $ledger->id == $unrealizedCurrencyEventSettings['provision_asset_ledger_id'] ? 'selected' : '' }}>[{{ $ledger->code }}]&nbsp;{{ $ledger->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="provision_liability_ledger_id"><strong>Provision Posting for Currnecy Gain/Loss (For Liability GLs:):<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="provision_liability_ledger_id" id="provision_liability_ledger_id" class="form-control rounded">
                                        @if($ledgers->whereIn('accountGroup.account_class_id', [2, 3])->count() > 0)
                                        @foreach($ledgers->whereIn('accountGroup.account_class_id', [2, 3]) as $key => $ledger)
                                            <option value="{{ $ledger->id }}" {{ $ledger->id == $unrealizedCurrencyEventSettings['provision_liability_ledger_id'] ? 'selected' : '' }}>[{{ $ledger->code }}]&nbsp;{{ $ledger->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-success btn-lg pull-right btn-block"><i class="la la-save"></i>&nbsp;Update Unrealized Currency Evaluation Settings</button>
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