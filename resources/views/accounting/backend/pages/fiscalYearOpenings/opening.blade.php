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
                            <h5 class="mb-2"><strong>Transactions</strong></h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Company</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Reference</th>
                                        <th class="text-center">Debit Ledgers</th>
                                        <th class="text-center">Credit Ledgers</th>
                                        <th class="text-center">Source</th>
                                        <th class="text-center">Currency</th>
                                        <th class="text-center">Debit</th>
                                        <th class="text-center">Credit</th>
                                        <th class="text-center">Datetime</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($entries[0]))
                                    @foreach($entries as $key => $entry)
                                    <tr>
                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td class="text-center">{{ entryCompanies($entry) }}</td>
                                        <td class="text-center">{{ $entry->date }}</td>
                                        <td class="text-center">{{ $entry->number }}</td>
                                        <td class="text-center">
                                            <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->id }}" data-entry-type="{{ $entry->entryType->name }}" data-code="{{ $entry->code }}">
                                                <p style="width: 150px;white-space: normal">{{ implode(', ', array_unique($entry->items->where('debit_credit', 'D')->pluck('chartOfAccount.code')->toArray())) }}</p>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->id }}" data-entry-type="{{ $entry->entryType->name }}" data-code="{{ $entry->code }}">
                                                <p style="width: 150px;white-space: normal">{{ implode(', ', array_unique($entry->items->where('debit_credit', 'C')->pluck('chartOfAccount.code')->toArray())) }}</p>
                                            </a>
                                        </td>
                                        <td class="text-center">{{ $entry->purchaseOrder ? ucwords(str_replace('-', ' ', $entry->purchaseOrder->type)) : $entry->notes }}</td>
                                        <td class="text-center">{{ $entry->exchangeRate ? $entry->exchangeRate->currency->code : '' }}</td>
                                        <td class="text-center">{{ $entry->debit }}</td>
                                        <td class="text-center">{{ $entry->credit }}</td>
                                        <td class="text-center">{{ date('M jS, y g:i a', strtotime($entry->created_at)) }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                            {!! $entries->render('pagination::bootstrap-4') !!}
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
    function getShortDetails(element) {
        $.dialog({
            title: (element.attr('data-entry-type'))+" Voucher #"+(element.attr('data-code')),
            content: "url:{{ url('accounting/entries') }}/"+(element.attr('data-id'))+"?short-details",
            animation: 'scale',
            columnClass: 'col-md-12',
            closeAnimation: 'scale',
            backgroundDismiss: true
        });
    }
</script>
@endsection