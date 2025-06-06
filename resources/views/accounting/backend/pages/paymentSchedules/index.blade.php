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
                <li class="active">{{__($title)}} </li>
            </ul>
        </div>
    </div>
    <div class="panel panel-info mt-2 p-2">
        <div class="row mb-3">
            <div class="col-md-12 text-right">
                @can('payment-schedules-create')
                <a class="btn btn-sm btn-success pull-right" href="{{ url('accounting/payment-schedules/create') }}" style="float: right"><i class="la la-plus"></i>&nbsp;New Payment Schedule</a>
                @endcan
            </div>
        </div>
       @include('yajra.datatable')
   </div>
</div>
</div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
@endsection