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

    .bordered{
        border: 1px #ccc solid
    }

    .floating-title{
        position: absolute;
        top: -18px;
        left: 15px;
        padding: 5px 20px 5px 5px;
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .card{
        margin-top: 35px !important;
    }
    .card-body{
        padding-top: 25px !important;
        padding-bottom: 0px !important;
    }

    .label{
        font-weight:  bold !important;
    }

    .tab-pane{
        padding-top: 15px;
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
                    <form action="{{ route('accounting.accounts-default-settings.store') }}" method="post" accept-charset="utf-8">
                    @csrf
                        <div class="row pr-3 pt-3">
                            <div class="col-md-6 mb-4">
                                <div class="card mt-4">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title"><i class="las la-user-secret"></i>&nbsp;Supplier Ledgers</h5>
                                        <div class="row pr-3">
                                            <div class="col-md-12">
                                                <label for="grir_account"><strong>{{ __('GR/IR Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="grir_account" id="grir_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['grir_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="supplier_payable_account"><strong>{{ __('Supplier Payable Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="supplier_payable_account" id="supplier_payable_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['supplier_payable_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="supplier_advance_account"><strong>{{ __('Supplier Advance Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="supplier_advance_account" id="supplier_advance_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['supplier_advance_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title"><i class="las la-cogs"></i>&nbsp;Inventory Ledgers</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="inventory_account"><strong>{{ __('Inventory Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="inventory_account" id="inventory_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['inventory_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="cogs_account"><strong>{{ __('Consumption Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="cogs_account" id="cogs_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['cogs_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title"><i class="las la-shopping-cart"></i>&nbsp;Fixed Asset Ledgers</h5>
                                        <div class="row pr-3">
                                            <div class="col-md-6">
                                                <label for="cwip_account"><strong>{{ __('CWIP Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="cwip_account" id="cwip_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['cwip_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="cwip_asset_account"><strong>{{ __('Capitalization Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="cwip_asset_account" id="cwip_asset_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['cwip_asset_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="fixed_asset_account"><strong>{{ __('Fixed Assets Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="fixed_asset_account" id="fixed_asset_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['fixed_asset_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="depreciation_cost_account"><strong>{{ __('Depreciation Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="depreciation_cost_account" id="depreciation_cost_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['depreciation_cost_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="inventory_adjustments_account"><strong>{{ __('Accumulated Depreciation Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="inventory_adjustments_account" id="inventory_adjustments_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['inventory_adjustments_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="depreciation_disposal_account"><strong>{{ __('Disposal Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="depreciation_disposal_account" id="depreciation_disposal_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['depreciation_disposal_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title"><i class="las la-shopping-cart"></i>&nbsp;Sales Ledgers</h5>
                                        <div class="row pr-3">
                                            <div class="col-md-6">
                                                <label for="sales_account"><strong>{{ __('Sales Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="sales_account" id="sales_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['sales_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="sales_discount_account"><strong>{{ __('Sales Discount Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="sales_discount_account" id="sales_discount_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['sales_discount_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="receivables_account"><strong>{{ __('Receivables Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="receivables_account" id="receivables_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['receivables_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="payment_discount_account"><strong>{{ __('Payment Discount Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="payment_discount_account" id="payment_discount_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['payment_discount_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title"><i class="las la-university"></i>&nbsp;Bank Settings</h5>
                                        <div class="row pr-3">
                                            <div class="col-md-6">
                                                <label for="bank_account_group"><strong>{{ __('Bank Ledger Group') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="bank_account_group" id="bank_account_group" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['bank_account_group'] }}">
                                                        {!! $accountGroupOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="bank_account"><strong>{{ __('Bank Default Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="bank_account" id="bank_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['bank_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="bank_interest_account"><strong>{{ __('Bank Interest Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="bank_interest_account" id="bank_interest_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['bank_interest_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="bank_charges_account"><strong>{{ __('Bank Charges Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="bank_charges_account" id="bank_charges_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['bank_charges_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title"><i class="las la-hand-holding-usd"></i>&nbsp;Cash Ledgers</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="petty_cash_account"><strong>{{ __('Petty Cash Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="petty_cash_account" id="petty_cash_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['petty_cash_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="cash_in_hand_account"><strong>{{ __('Cash in hand Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="cash_in_hand_account" id="cash_in_hand_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['cash_in_hand_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title"><i class="las la-money-check-alt"></i>&nbsp;VAT/TAX Ledgers</h5>
                                        <div class="row pr-3">
                                            <div class="col-md-6">
                                                <label for="vat_payable"><strong>{{ __('VAT Payable') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="vat_payable" id="vat_payable" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['vat_payable'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="tax_payable"><strong>{{ __('TAX Payable') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="tax_payable" id="tax_payable" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['tax_payable'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title"><i class="las la-chart-bar"></i>&nbsp;Finance Settings</h5>
                                        <div class="row pr-3">
                                            <div class="col-md-6">
                                                <label for="balance_sheet_items"><strong>{{ __('Balance Sheet Items') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="balance_sheet_items[]" id="balance_sheet_items" class="form-control rounded" multiple data-placeholder="Chosse Balance Sheet Items...">
                                                        @if($classes->count() > 0)
                                                        @foreach($classes as $key => $class)
                                                            <option value="{{ $class->id }}" {{ in_array($class->id, $balanceSheetItems) ? 'selected' : '' }}>{{ $class->name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="profit_loss_items"><strong>{{ __('Profit Loss Items') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="profit_loss_items[]" id="profit_loss_items" class="form-control rounded" multiple data-placeholder="Chosse Profit Loss Items...">
                                                        @if($classes->count() > 0)
                                                        @foreach($classes as $key => $class)
                                                            <option value="{{ $class->id }}" {{ in_array($class->id, $profitLossItems) ? 'selected' : '' }}>{{ $class->name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="currency_carry_account"><strong>{{ __('Currency Clearing') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="currency_carry_account" id="currency_carry_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['currency_carry_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="currency_gain_loss_account"><strong>{{ __('Currency Gain/Loss') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="currency_gain_loss_account" id="currency_gain_loss_account" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['currency_gain_loss_account'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="currency_id"><strong>{{ __('Reporting Currency') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="currency_id" id="currency_id" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['currency_id'] }}">
                                                        @if(isset($currencyTypes[0]))
                                                        @foreach($currencyTypes as $key => $currencyType)
                                                        <optgroup label="{{ $currencyType->name }}">
                                                            @if($currencyType->currencies->count() > 0)
                                                            @foreach($currencyType->currencies as $key => $currency)
                                                                <option value="{{ $currency->id }}">&nbsp;&nbsp;{{ $currency->name }} ({{ $currency->code }}&nbsp;|&nbsp;{{ $currency->symbol }})</option>
                                                            @endforeach
                                                            @endif
                                                        </optgroup>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="retained_earnings"><strong>{{ __('Retained Earnings') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="retained_earnings" id="retained_earnings" class="form-control rounded select-me" data-selected="{{ $accountDefaultSettings['retained_earnings'] }}">
                                                        {!! $chartOfAccountsOptions !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2 mt-2">
                                                <div class="row">
                                                    <div class="col-md-12 text-right">
                                                        <button type="submit" class="btn btn-success btn-lg pull-right"><i class="la la-save"></i>&nbsp;Save Default Settings</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {
        $.each($('.select-me'), function(index, val) {
            $(this).select2().val($(this).attr('data-selected')).trigger("change");
        });
    });
</script>
@endsection