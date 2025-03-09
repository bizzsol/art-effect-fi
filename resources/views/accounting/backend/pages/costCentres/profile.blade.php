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
                        <div class="col-md-4">
                            <div class="col-md-12 text-center">
                                <img src="{{ asset($costCentre->logo) }}" style="width:75%">
                                <h3 class="mb-3">{{ $costCentre->name }}</h3>
                                <h5><i class="lar la-building"></i>&nbsp;<a href="{{ url('accounting/companies/'.$costCentre->profitCentre->company_id) }}">[{{ $costCentre->profitCentre->company->code }}] {{ $costCentre->profitCentre->company->name }}</a></h5>
                                <h5><i class="la la-phone-alt"></i>&nbsp;{{ $costCentre->phone }}</h5>
                                <h5><i class="la la-envelope"></i>&nbsp;{{ $costCentre->email }}</h5>
                                <h5><i class="lar la-map"></i>&nbsp;{{ $costCentre->address }}</h5>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-8 offset-md-2 text-center">
                                    <img src="{{ asset($costCentre->banner) }}" style="width:100%">
                                </div>
                            </div>
                        </div>
                    </div>
                    @can('company-edit')
                        <a class="btn btn-md btn-primary" href="{{ url('accounting/cost-centres/'.$costCentre->id.'/edit') }}"><i class="la la-edit">&nbsp;Edit Cost Centre</i></a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection