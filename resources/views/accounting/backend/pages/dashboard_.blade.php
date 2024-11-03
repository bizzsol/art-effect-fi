@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style>
.iq-card .iq-card-header {
    min-height: 45px !important;
}

.charts {
    margin-top: -35px;
}

.bar-charts {
    margin-top: -10px;
}
</style>
@endsection
@section('main-content')
<div class="row pt-4">

    {{-- <div class="col-lg-3 pr-0">
        <div class="iq-card p-3" style="padding-top: 5px !important;">
            <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                <div class="iq-header-title">
                    <h4 class="card-title text-primary border-left-heading">Balance Summery</h4>
                </div>
                <!--<a class="open-button" style="float: right;margin-top: 7px !important;margin-right: -55px;"><i class="las la-plus-circle" style="font-size: 24px"></i></a>
                <a class="close-button" style="float: right;margin-top: 25px !important;margin-right: 60px;display: none"><i class="las la-minus-circle" style="font-size: 24px"></i></a>-->
            </div>
            <div class="iq-card-body p-0">
                <canvas class="charts" data-data="{{ implode(',', array_values($balances)) }}" data-labels="{{ implode(',', array_keys($balances)) }}" data-chart="doughnut" data-legend-position="top" data-title-text="" width="200" height="200"></canvas>
            </div>
        </div>
    </div> --}}

    <div class="col-lg-4 pr-0">
        <div class="iq-card p-3" style="padding-top: 5px !important;">
            <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                <div class="iq-header-title">
                    <h4 class="card-title text-primary border-left-heading">Overall Entries</h4>
                </div>
                <!--<a class="open-button" style="float: right;margin-top: 7px !important;margin-right: -55px;"><i class="las la-plus-circle" style="font-size: 24px"></i></a>
                <a class="close-button" style="float: right;margin-top: 25px !important;margin-right: 60px;display: none"><i class="las la-minus-circle" style="font-size: 24px"></i></a>-->
            </div>
            <div class="iq-card-body p-0">
                <canvas class="charts" data-data="{{ implode(',', array_values($typeWiseEntries['overall'])) }}" data-labels="{{ implode(',', array_keys($typeWiseEntries['overall'])) }}" data-chart="doughnut" data-legend-position="top" data-title-text="" width="200" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4 pr-0">
        <div class="iq-card p-3" style="padding-top: 5px !important;">
            <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                <div class="iq-header-title">
                    <h4 class="card-title text-primary border-left-heading">This Month Entries</h4>
                </div>
                <!--<a class="open-button" style="float: right;margin-top: 7px !important;margin-right: -55px;"><i class="las la-plus-circle" style="font-size: 24px"></i></a>
                <a class="close-button" style="float: right;margin-top: 25px !important;margin-right: 60px;display: none"><i class="las la-minus-circle" style="font-size: 24px"></i></a>-->
            </div>
            <div class="iq-card-body p-0">
                <canvas class="charts" data-data="{{ implode(',', array_values($typeWiseEntries['this-month'])) }}" data-labels="{{ implode(',', array_keys($typeWiseEntries['this-month'])) }}" data-chart="doughnut" data-legend-position="top" data-title-text="" width="200" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="iq-card p-3" style="padding-top: 5px !important;">
            <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                <div class="iq-header-title">
                    <h4 class="card-title text-primary border-left-heading">
                        Fiscal Year Entries
                    </h4>
                </div>
                <!--<a class="open-button" style="float: right;margin-top: 7px !important;margin-right: -55px;"><i class="las la-plus-circle" style="font-size: 24px"></i></a>
                <a class="close-button" style="float: right;margin-top: 25px !important;margin-right: 60px;display: none"><i class="las la-minus-circle" style="font-size: 24px"></i></a>-->
            </div>
            <div class="iq-card-body p-0">
                <canvas class="charts" data-data="{{ implode(',', array_values($typeWiseEntries['current-fiscal-year'])) }}" data-labels="{{ implode(',', array_keys($typeWiseEntries['current-fiscal-year'])) }}" data-chart="doughnut" data-legend-position="top" data-title-text="" width="200" height="210"></canvas>
            </div>
        </div>
    </div>

    {{-- @php
        $chartData = implode(',', array_values(getDateWiseTotalTransactions(date('Y-m-d', strtotime('-30 days')), date('Y-m-d'))));

        if(isset($entryTypes[0])){
            foreach($entryTypes as $key => $entryType){
                $chartData .= '|'.implode(',', array_values(getDateWiseTotalTransactions(date('Y-m-d', strtotime('-30 days')), date('Y-m-d'), $entryType->id)));
            }
        }
    @endphp

    <div class="col-lg-12 mb-3">
        <div class="iq-card p-3" style="padding-top: 5px !important;">
            <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                <div class="iq-header-title">
                    <h4 class="card-title text-primary border-left-heading">Last 30 days Transactions ({{ date('d-M-y', strtotime('-30 days')) }} to {{ date('d-M-y') }})</h4>
                </div>
            </div>
            <div class="iq-card-body p-0">
                <canvas class="bar-charts" id="30-days-transactions" data-data="{{ $chartData }}" data-labels="{{ implode(',', array_values(dateRange(date('Y-m-d', strtotime('-30 days')), date('Y-m-d'), 'd-M')))  }}" data-legend-position="top" data-title-text="Total,{{ $entryTypes->pluck('name')->implode(',') }}" width="200" height="65"></canvas>
            </div>
        </div>
    </div> --}}

    {{-- <div class="col-lg-12 mb-3">
        <div class="iq-card p-3" style="padding-top: 5px !important;">
            <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                <div class="iq-header-title">
                    <h4 class="card-title text-primary border-left-heading">Income & Expense ({{ date('F Y') }})</h4>
                </div>
            </div>
            <div class="iq-card-body p-0">
                <canvas class="bar-charts" id="income-expense" data-data="{{ implode(',', array_values($incomes)) }}|{{ implode(',', array_values($expenses)) }}" data-labels="{{ implode(',', array_values(dateRange(date('Y-m-01'), date('Y-m-t'), 'd-M')))  }}" data-legend-position="top" data-title-text="Income,Expense" width="200" height="65"></canvas>
            </div>
        </div>
    </div> --}}
</div>
@endsection
@include('accounting.backend.pages.scripts')