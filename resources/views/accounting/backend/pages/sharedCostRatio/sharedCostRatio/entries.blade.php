@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
</style>
@include('yajra.css')
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
                <li class="active"><a href="{{ url('accounting/cost-centre-allocations') }}">{{__($title)}}</a></li>
            </ul>
        </div>
    </div>
    <div class="panel panel-info mt-2 p-2">
        <div class="row mb-3">
            <div class="col-md-12">
                <h5 class="mb-3"><strong>{{ $sharedCostRatio->costCentreAllocation->name }}</strong></h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 5%;">SL</th>
                            <th style="width: 10%;">Type</th>
                            <th style="width: 20%;">Company</th>
                            <th style="width: 25%;">Cost Centre</th>
                            <th style="width: 30%;">Chart of Account</th>
                            <th style="width: 10%;">Allocation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1.</td>
                            <td class="text-center"><strong>Source</strong></td>
                            <td>[{{ $sharedCostRatio->costCentreAllocation->costCentre->profitCentre->company->code }}] {{ $sharedCostRatio->costCentreAllocation->costCentre->profitCentre->company->name }}</td>
                            <td>[{{ $sharedCostRatio->costCentreAllocation->costCentre->code }}] {{ $sharedCostRatio->costCentreAllocation->costCentre->name }}</td>
                            <td>[{{ $sharedCostRatio->costCentreAllocation->chartOfAccount->code }}] {{ $sharedCostRatio->costCentreAllocation->chartOfAccount->name }}</td>
                            <td class="text-right"><strong>{{ $sharedCostRatio->costCentreAllocation->allocation }}%</strong></td>
                        </tr>

                        @if(isset($sharedCostRatio->costCentreAllocation->targets[0]))
                        @foreach($sharedCostRatio->costCentreAllocation->targets as $key => $target)
                        <tr>
                            <td class="text-center">{{ $key+2 }}.</td>
                            <td class="text-center"><strong>Destination</strong></td>
                            <td>[{{ $target->costCentre->profitCentre->company->code }}] {{ $target->costCentre->profitCentre->company->name }}</td>
                            <td>[{{ $target->costCentre->code }}] {{ $target->costCentre->name }}</td>
                            <td>[{{ $target->chartOfAccount->code }}] {{ $target->chartOfAccount->name }}</td>
                            <td class="text-right"><strong>{{ $target->allocation }}%</strong></td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h5 class="mb-3"><strong>Transactions</strong></h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 2%">SL</th>
                            <th style="width: 15%">Company</th>
                            <th style="width: 7.5%">Fiscal Year</th>
                            <th style="width: 7.5%">Date</th>
                            <th style="width: 10%">Code</th>
                            <th style="width: 10%">Reference</th>
                            <th style="width: 5%">Currency</th>
                            <th style="width: 7.5%">Debit</th>
                            <th style="width: 7.5%">Credit</th>
                            <th style="width: 23%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($sharedCostRatio->entries->count() > 0)
                        @foreach($sharedCostRatio->entries as $key => $entry)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ entryCompanies($entry->entry) }}</td>
                            <td class="text-center">{{ $entry->entry->fiscalYear ? $entry->entry->fiscalYear->title : '' }}</td>
                            <td class="text-center">{{ $entry->entry->date }}</td>
                            <td class="text-center">
                                <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->entry_id }}" data-entry-type="{{ $entry->entry->entryType->name }}" data-code="{{ $entry->entry->code }}">{{ $entry->entry->code }}</a>
                            </td>
                            <td class="text-center">
                                <a class="text-primary" onclick="getShortDetails($(this))" data-id="{{ $entry->entry_id }}" data-entry-type="{{ $entry->entry->entryType->name }}" data-code="{{ $entry->entry->code }}">{{ $entry->entry->number }}</a></td>
                            <td class="text-center">{{ $entry->entry->exchangeRate ? $entry->entry->exchangeRate->currency->code : '' }}</td>
                            <td class="text-right">{{ systemMoneyFormat($entry->entry->debit) }}</td>
                            <td class="text-right">{{ systemMoneyFormat($entry->entry->credit) }}</td>
                            <td>
                                @if($entry->entry->is_approved == 'approved')
                                    <a class="btn btn-xs btn-success">Approved</a>
                                @else
                                    @if($entry->entry->approvals->count() > 0)
                                    @foreach($entry->entry->approvals as $key => $value)
                                        <a class="btn btn-xs btn-{{ approvalLevelClass()[$value->status] }} mb-1">{{ $value->approvalLevel->name }} {{ ucwords($value->status) }}</a>
                                    @endforeach
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
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