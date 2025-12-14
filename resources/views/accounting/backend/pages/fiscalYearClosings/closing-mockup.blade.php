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
                    <form action="{{ route('accounting.fiscal-year-closings.store') }}" method="post" accept-charset="utf-8">
                    @csrf
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
                        </div>

                        @if(isset($company->id))
                        <div class="row pr-3 mb-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="text-white">Closing Fiscal Year</h5>
                                    </div>
                                    <div class="card-body" style="border: 1px solid #ccc">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="from_fiscal_year_id"><strong>Closing Fiscal Year</strong></label>
                                                    <select name="from_fiscal_year_id" id="from_fiscal_year_id" class="form-control">
                                                        <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->title }}&nbsp;|&nbsp;{{ date('d-M-y', strtotime($fiscalYear->start)).' to '.date('d-M-y', strtotime($fiscalYear->end)) }})</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4 table-responsive">
                                                <h5 class="mb-2"><strong>#Balance Sheet Items</strong></h5>
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 15%">Code</th>
                                                            <th style="width: 25%">Ledger</th>
                                                            <th style="width: 15%" class="text-right">Closing Balance</th>
                                                            <th style="width: 15%" class="text-right">Carry Forwarded</th>
                                                            <th style="width: 15%" class="text-right">Mock Balance ({{ $systemCurrency->code }})</th>
                                                            <th style="width: 15%" class="text-right">Difference</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $balanceSheetBalance = 0;
                                                            $total_difference = 0;
                                                        @endphp
                                                        @if($mockBalanceSheetLedgers->count() > 0)
                                                        @foreach($mockBalanceSheetLedgers as $key => $ledger)
                                                        @php
                                                            $balance = ledgerClosingBalance($ledger->accountGroup, $ledger, ['D' => $debitTransactions, 'C' => $creditTransactions], $getAllGroupAndLedgers, $carryForwarded)['balance'];

                                                            $balanceSheetBalance += $balance;

                                                            $difference = $balance - ($balanceSheetLedgers->where('chart_of_account_id', $ledger->id)->sum('previous_balance'));
                                                            $total_difference += $difference;
                                                        @endphp
                                                            <tr>
                                                                <td>{{ $ledger->code }}</td>
                                                                <td>{{ $ledger->name }}</td>
                                                                <td>
                                                                    {{ systemMoneyFormat($balanceSheetLedgers->where('chart_of_account_id', $ledger->id)->sum('previous_balance')) }}
                                                                </td>
                                                                <td>
                                                                    {{ systemMoneyFormat($balanceSheetLedgers->where('chart_of_account_id', $ledger->id)->sum('carry_forwarding_amount')) }}
                                                                </td>
                                                                <td class="text-right">{{ systemMoneyFormat($balance) }}</td>
                                                                <td class="text-right">{{ systemMoneyFormat($difference) }}</td>
                                                            </tr>
                                                        @endforeach
                                                        @endif

                                                        <tr>
                                                            <td colspan="2" class="text-right">
                                                                <strong>Balance:</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($balanceSheetLedgers->sum('previous_balance')) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($balanceSheetLedgers->sum('carry_forwarding_amount')) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($balanceSheetBalance) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($total_difference) }}</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-md-12 table-responsive">
                                                <h5 class="mb-2"><strong>#Profit Loss Items</strong></h5>
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 15%">Code</th>
                                                            <th style="width: 25%">Ledger</th>
                                                            <th style="width: 15%" class="text-right">Closing Balance</th>
                                                            <th style="width: 15%" class="text-right">Carry Forwarded</th>
                                                            <th style="width: 15%" class="text-right">Mock Balance ({{ $systemCurrency->code }})</th>
                                                            <th style="width: 15%" class="text-right">Difference</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $profitLossBalance = 0;
                                                            $total_difference = 0;
                                                        @endphp
                                                        @if($mockProfitLossLedgers->count() > 0)
                                                        @foreach($mockProfitLossLedgers as $key => $ledger)
                                                        @php
                                                            $balance = ledgerClosingBalance($ledger->accountGroup, $ledger, ['D' => $debitTransactions, 'C' => $creditTransactions], $getAllGroupAndLedgers, $carryForwarded)['balance'];

                                                            $profitLossBalance += $balance;

                                                            $difference = $balance - ($profitLossLedgers->where('chart_of_account_id', $ledger->id)->sum('previous_balance'));
                                                            $total_difference += $difference;
                                                        @endphp
                                                            <tr>
                                                                <td>{{ $ledger->code }}</td>
                                                                <td>{{ $ledger->name }}</td>
                                                                <td>
                                                                    {{ systemMoneyFormat($profitLossLedgers->where('chart_of_account_id', $ledger->id)->sum('previous_balance')) }}
                                                                </td>
                                                                <td>
                                                                    {{ systemMoneyFormat($profitLossLedgers->where('chart_of_account_id', $ledger->id)->sum('carry_forwarding_amount')) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ systemMoneyFormat($balance) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ systemMoneyFormat($difference) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @endif

                                                        <tr>
                                                            <td colspan="2" class="text-right">
                                                                <strong>Balance:</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($profitLossLedgers->sum('previous_balance')) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($profitLossLedgers->sum('carry_forwarding_amount')) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($profitLossBalance) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($total_difference) }}</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-success">
                                        <h5 class="text-white">Closing to Fiscal Year</h5>
                                    </div>
                                    <div class="card-body" style="border: 1px solid #ccc">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="to_fiscal_year_id"><strong>Closing to Fiscal Year</strong></label>
                                                    <select name="to_fiscal_year_id" id="to_fiscal_year_id" class="form-control">
                                                    @if($fiscalYears->count() > 0)
                                                    @foreach($fiscalYears as $key => $year)
                                                        <option value="{{ $year->id }}">{{ $year->title }}&nbsp;|&nbsp;{{ date('d-M-y', strtotime($year->start)).' to '.date('d-M-y', strtotime($year->end)) }})</option>
                                                    @endforeach
                                                    @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4 table-responsive">
                                                <h5 class="mb-2"><strong>#Balance Sheet Items</strong></h5>
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 15%">Code</th>
                                                            <th style="width: 25%">Ledger</th>
                                                            <th style="width: 15%" class="text-right">Closing Balance</th>
                                                            <th style="width: 15%" class="text-right">Carry Forwarded</th>
                                                            <th style="width: 15%" class="text-right">Mock Balance ({{ $systemCurrency->code }})</th>
                                                            <th style="width: 15%" class="text-right">Difference</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $total_p_l_balance = 0;
                                                            $balanceSheetBalance = 0;
                                                            $total_difference = 0;
                                                        @endphp
                                                        @if($mockBalanceSheetLedgers->count() > 0)
                                                        @foreach($mockBalanceSheetLedgers as $key => $ledger)
                                                        @php
                                                            $balance = ledgerClosingBalance($ledger->accountGroup, $ledger, ['D' => $debitTransactions, 'C' => $creditTransactions], $getAllGroupAndLedgers, $carryForwarded)['balance'];
                                                            
                                                            $p_l_balance = 0;
                                                            if($accountDefaultSettings['retained_earnings'] == $ledger->id){
                                                                $balance += $profitLossBalance;

                                                                $p_l_balance = $profitLossLedgers->where('chart_of_account_id', $ledger->id)->sum('previous_balance');
                                                                $total_p_l_balance += $p_l_balance;
                                                            }

                                                            $balanceSheetBalance += $balance;

                                                            $difference = $balance - ($balanceSheetLedgers->where('chart_of_account_id', $ledger->id)->sum('previous_balance')+$p_l_balance);
                                                            $total_difference += $difference;
                                                        @endphp
                                                            <tr>
                                                                <td>{{ $ledger->code }}</td>
                                                                <td>{{ $ledger->name }}</td>
                                                                <td>
                                                                    {{ systemMoneyFormat($balanceSheetLedgers->where('chart_of_account_id', $ledger->id)->sum('previous_balance')+$p_l_balance) }}
                                                                </td>
                                                                <td>
                                                                    {{ systemMoneyFormat($balanceSheetLedgers->where('chart_of_account_id', $ledger->id)->sum('carry_forwarding_amount')+$p_l_balance) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ systemMoneyFormat($balance) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ systemMoneyFormat($difference) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @endif

                                                        <tr>
                                                            <td colspan="2" class="text-right">
                                                                <strong>Balance:</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($balanceSheetLedgers->sum('previous_balance')+$total_p_l_balance) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($balanceSheetLedgers->sum('carry_forwarding_amount')+$total_p_l_balance) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($balanceSheetBalance) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($total_difference) }}</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-md-12 table-responsive">
                                                <h5 class="mb-2"><strong>#Profit Loss Items</strong></h5>
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 15%">Code</th>
                                                            <th style="width: 25%">Ledger</th>
                                                            <th style="width: 15%" class="text-right">Closing Balance</th>
                                                            <th style="width: 15%" class="text-right">Carry Forwarded</th>
                                                            <th style="width: 15%" class="text-right">Mock Balance ({{ $systemCurrency->code }})</th>
                                                            <th style="width: 15%" class="text-right">Difference</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($mockProfitLossLedgers->count() > 0)
                                                        @foreach($mockProfitLossLedgers as $key => $ledger)
                                                            <tr>
                                                                <td>{{ $ledger->code }}</td>
                                                                <td>{{ $ledger->name }}</td>
                                                                <td>
                                                                    {{ systemMoneyFormat($profitLossLedgers->where('chart_of_account_id', $ledger->id)->sum('previous_balance')) }}
                                                                </td>
                                                                <td>
                                                                    {{ systemMoneyFormat($profitLossLedgers->where('chart_of_account_id', $ledger->id)->sum('carry_forwarding_amount')) }}
                                                                </td>
                                                                <td class="text-right">0.00</td>
                                                                <td class="text-right">0.00</td>
                                                            </tr>
                                                        @endforeach
                                                        @endif

                                                        <tr>
                                                            <td colspan="2" class="text-right">
                                                                <strong>Balance:</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($profitLossLedgers->sum('previous_balance')) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>{{ systemMoneyFormat($profitLossLedgers->sum('carry_forwarding_amount')) }}</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>0.00</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>0.00</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Run Fiscal Year Closing</button>
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/fiscal-year-closings') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                            </div>
                        </div> --}}
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection