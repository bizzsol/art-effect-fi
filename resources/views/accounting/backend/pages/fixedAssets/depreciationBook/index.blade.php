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
                    <form action="{{ url('accounting/depreciation-book') }}" method="get" accept-charset="utf-8">
                        <div class="row pr-3">
                            <div class="col-md-10 col-sm-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <label for="company_id"><strong>Company</strong></label>
                                        <select name="company_id" id="company_id" class="form-control">
                                            <option value="{{ null }}">All Companies</option>
                                            @if(isset($companies[0]))
                                            @foreach($companies as $key => $company)
                                            <option value="{{ $company->id }}" {{ request()->get('company_id') == $company->id ? 'selected' : '' }}>[{{ $company->code }}] {{ $company->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label for="year"><strong>Year:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="year" id="year" class="form-control rounded">
                                                <option value="0">All Years</option>
                                                @if(isset($years[0]))
                                                @foreach($years as $key => $year)
                                                <option {{ $year == request()->get('year') ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label for="month"><strong>Month:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="month" id="month" class="form-control rounded">
                                                <option value="0">All Months</option>
                                                @for($i=1;$i<=12;$i++)
                                                @php $mo = $i < 10 ? '0'.$i : $i; @endphp
                                                <option value="{{ $mo }}" {{ $mo == request()->get('month') ? 'selected' : '' }}>{{ date('F', strtotime(date('Y-').($i < 10 ? '0'.$i : $i))) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 pt-4">
                                <button type="submit" class="mt-2 btn btn-success btn-md btn-block"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="row pr-3">
                        <div class="col-md-12 p-3">
                            @include('yajra.datatable')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
@endsection