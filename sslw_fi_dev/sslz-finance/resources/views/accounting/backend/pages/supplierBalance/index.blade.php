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
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-2">
                <div class="panel-body pt-0">
                    <form action="{{ url('accounting/supplier-balance') }}" method="get" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label for="date"><strong>As On:</strong></label>
                                    <input type="date" name="date" id="date" value="{{ $date }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0 pt-4">
                                    <button type="submit" class="mt-2 btn btn-success btn-md btn-block"><i class="la la-search"></i>&nbsp;Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
@endsection