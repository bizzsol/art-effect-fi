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
                <h5 class="mb-3"><strong>{{ $allocation->name }}</strong></h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 5%;">SL</th>
                            <th style="width: 10%;">Type</th>
                            <th style="width: 15%;">Company</th>
                            <th style="width: 25%;">Cost Centre</th>
                            <th style="width: 35%;">Chart of Account</th>
                            <th style="width: 10%;">Allocation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1.</td>
                            <td class="text-center"><strong>Source</strong></td>
                            <td>[{{ $allocation->costCentre->profitCentre->company->code }}] {{ $allocation->costCentre->profitCentre->company->name }}</td>
                            <td>[{{ $allocation->costCentre->code }}] {{ $allocation->costCentre->name }}</td>
                            <td>[{{ $allocation->chartOfAccount->code }}] {{ $allocation->chartOfAccount->name }}</td>
                            <td class="text-right"><strong>{{ $allocation->allocation }}%</strong></td>
                        </tr>

                        @if(isset($allocation->targets[0]))
                        @foreach($allocation->targets as $key => $target)
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
   </div>
</div>
@endsection