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
                    <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3">
                    <div class="row pr-3">
                        <div class="col-md-12">
                            <form action="{{ url('accounting/category-wise-asset-register') }}" method="get" accept-charset="utf-8" target="_blank">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="category_id"><strong>Category</strong></label>
                                            <div class="select-search-group input-group input-group-md mb-3 d-">
                                                <select name="category_id" id="category_id" class="form-control">
                                                    @if(isset($categories[0]))
                                                    @foreach($categories as $key => $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->code }})</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="from"><strong>From</strong></label>
                                            <input type="date" name="from" id="from" value="{{ $from }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="to"><strong>To</strong></label>
                                            <input type="date" name="to" id="to" value="{{ $to }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group pt-4">
                                            <button type="submit" class="btn btn-success btn-block btn-md mt-2"><i class="la la-search"></i>&nbsp;Get Register</button>
                                        </div>
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