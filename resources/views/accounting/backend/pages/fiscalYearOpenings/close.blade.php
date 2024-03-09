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
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="mb-2"><strong>Fiscal Year Opening</strong></h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Reason</th>
                                        <th>Company</th>
                                        <th>Fiscal Year</th>
                                        <th>Approval</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $fiscalYearOpening->reference }}</td>
                                        <td>{{ date('M jS, y', strtotime($fiscalYearOpening->date)) }}</td>
                                        <td>{{ date('g:i a', strtotime($fiscalYearOpening->time)) }}</td>
                                        <td>{{ $fiscalYearOpening->reason }}</td>
                                        <td>{{ '['.$fiscalYearOpening->company->name.'] '.$fiscalYearOpening->company->name }}</td>
                                        <td>{{ $fiscalYearOpening->fiscalYear ? $fiscalYearOpening->fiscalYear->title.' | '.date('d-M-y', strtotime($fiscalYearOpening->fiscalYear->start)).' to '.date('d-M-y', strtotime($fiscalYearOpening->fiscalYear->end)) : '' }}</td>
                                        <td>{{ $fiscalYearOpening->is_approved }}</td>
                                        <td>{{ $fiscalYearOpening->status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="mb-2"><strong>User Access</strong></h5>
                            <ul>
                                @if($fiscalYearOpening->users->count() > 0)
                                @foreach($fiscalYearOpening->users as $user)
                                <li>{{ $user->user->name.' ('.$user->user->associate_id.')' }}</li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('accounting.fiscal-year-openings.destroy', $fiscalYearOpening->id) }}" method="post" accept-charset="utf-8">
                            @csrf
                            @method('DELETE')
                                <div class="row pr-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-primary">
                                                <h5 class="text-white">Closing Fiscal Year ({{ $fiscalYearOpening->fiscalYear->title }})</h5>
                                            </div>
                                            <div class="card-body" style="border: 1px solid #ccc">
                                                <div class="row">
                                                    <div class="col-md-12 mb-4">
                                                        <div class="form-group">
                                                            <label><strong>Closing Fiscal Year</strong></label>
                                                            <h5><strong>{{ $fiscalYearOpening->fiscalYear ? $fiscalYearOpening->fiscalYear->title.' | '.date('d-M-y', strtotime($fiscalYearOpening->fiscalYear->start)).' to '.date('d-M-y', strtotime($fiscalYearOpening->fiscalYear->end)) : '' }}</strong></h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-4">
                                                        <h5 class="mb-2"><strong>#Balance Sheet Items</strong></h5>
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 15%">Code</th>
                                                                    <th style="width: 60%">Ledger</th>
                                                                    <th style="width: 25%" class="text-right">Balance ({{ $systemCurrency->code }})</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $balanceSheetBalance = 0;
                                                                @endphp
                                                                @if($balanceSheetLedgers->count() > 0)
                                                                @foreach($balanceSheetLedgers as $key => $ledger)
                                                                @php
                                                                    $balance = ledgerClosingBalance($ledger->accountGroup, $ledger, ['D' => $debitTransactions, 'C' => $creditTransactions], $getAllGroupAndLedgers)['balance'];

                                                                    $balanceSheetBalance += $balance;
                                                                @endphp
                                                                    <tr>
                                                                        <td>{{ $ledger->code }}</td>
                                                                        <td>{{ $ledger->name }}</td>
                                                                        <td class="text-right">{{ systemMoneyFormat($balance) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                @endif

                                                                <tr>
                                                                    <td colspan="2" class="text-right">
                                                                        <strong>Balance:</strong>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <strong>{{ systemMoneyFormat($balanceSheetBalance) }}</strong>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <h5 class="mb-2"><strong>#Profit Loss Items</strong></h5>
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 15%">Code</th>
                                                                    <th style="width: 60%">Ledger</th>
                                                                    <th style="width: 25%" class="text-right">Balance ({{ $systemCurrency->code }})</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $profitLossBalance = 0;
                                                                @endphp
                                                                @if($profitLossLedgers->count() > 0)
                                                                @foreach($profitLossLedgers as $key => $ledger)
                                                                @php
                                                                    $balance = ledgerClosingBalance($ledger->accountGroup, $ledger, ['D' => $debitTransactions, 'C' => $creditTransactions], $getAllGroupAndLedgers)['balance'];

                                                                    $profitLossBalance += $balance;
                                                                @endphp
                                                                    <tr>
                                                                        <td>{{ $ledger->code }}</td>
                                                                        <td>{{ $ledger->name }}</td>
                                                                        <td class="text-right">
                                                                            {{ systemMoneyFormat($balance) }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                @endif

                                                                <tr>
                                                                    <td colspan="2" class="text-right">
                                                                        <strong>Balance:</strong>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <strong>{{ systemMoneyFormat($profitLossBalance) }}</strong>
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
                                                <h5 class="text-white">Carry Forwarding Balances</h5>
                                            </div>
                                            <div class="card-body" style="border: 1px solid #ccc">
                                                <div class="row">
                                                    <div class="col-md-12 mb-4">
                                                        <div class="form-group">
                                                            <label for="to_fiscal_year_id"><strong>Carry Forwarding to</strong></label>
                                                            <select name="to_fiscal_year_id" id="to_fiscal_year_id" class="form-control">
                                                            @if($fiscalYears->count() > 0)
                                                            @foreach($fiscalYears as $key => $year)
                                                                <option value="{{ $year->id }}">{{ $year->title }}&nbsp;|&nbsp;{{ date('d-M-y', strtotime($year->start)).' to '.date('d-M-y', strtotime($year->end)) }})</option>
                                                            @endforeach
                                                            @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-4">
                                                        <h5 class="mb-2"><strong>#Balance Sheet Items</strong></h5>
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 15%">Code</th>
                                                                    <th style="width: 60%">Ledger</th>
                                                                    <th style="width: 25%" class="text-right">Balance ({{ $systemCurrency->code }})</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $balanceSheetBalance = 0;
                                                                @endphp
                                                                @if($balanceSheetLedgers->count() > 0)
                                                                @foreach($balanceSheetLedgers as $key => $ledger)
                                                                @php
                                                                    $balance = ledgerClosingBalance($ledger->accountGroup, $ledger, ['D' => $debitTransactions, 'C' => $creditTransactions], $getAllGroupAndLedgers)['balance'];
                                                                    
                                                                    if($accountDefaultSettings['retained_earnings'] == $ledger->id){
                                                                        $balance += $profitLossBalance;
                                                                    }

                                                                    $balanceSheetBalance += $balance;
                                                                @endphp
                                                                    <tr>
                                                                        <td>{{ $ledger->code }}</td>
                                                                        <td>{{ $ledger->name }}</td>
                                                                        <td class="text-right">
                                                                            {{ systemMoneyFormat($balance) }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                @endif

                                                                <tr>
                                                                    <td colspan="2" class="text-right">
                                                                        <strong>Balance:</strong>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <strong>{{ systemMoneyFormat($balanceSheetBalance) }}</strong>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <h5 class="mb-2"><strong>#Profit Loss Items</strong></h5>
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 15%">Code</th>
                                                                    <th style="width: 60%">Ledger</th>
                                                                    <th style="width: 25%" class="text-right">Balance ({{ $systemCurrency->code }})</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if($profitLossLedgers->count() > 0)
                                                                @foreach($profitLossLedgers as $key => $ledger)
                                                                    <tr>
                                                                        <td>{{ $ledger->code }}</td>
                                                                        <td>{{ $ledger->name }}</td>
                                                                        <td class="text-right">0.00</td>
                                                                    </tr>
                                                                @endforeach
                                                                @endif

                                                                <tr>
                                                                    <td colspan="2" class="text-right">
                                                                        <strong>Balance:</strong>
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Close Fiscal Year</button>
                                        <a class="btn btn-dark btn-md" href="{{ url('accounting/fiscal-year-openings') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
 
</script>
@endsection