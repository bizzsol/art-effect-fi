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
                <li class="active">{{__($title)}}</li>

                <li class="top-nav-btn">
                    <a href="{{ url('accounting/currencies/create') }}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Currency"> <i class="las la-plus"></i>Add Currency</i></a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-2">
                @include('yajra.datatable')
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
@endsection