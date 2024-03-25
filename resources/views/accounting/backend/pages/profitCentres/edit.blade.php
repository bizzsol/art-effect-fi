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
                    <form action="{{ route('accounting.profit-centres.update', $profitCentre->id) }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        <div class="row pr-3">
                            <div class="col-md-2">
                                <label for="company_id"><strong>{{ __('Company') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="company_id" id="company_id" class="form-control rounded">
                                        @foreach($companies as $key => $company)
                                        <option value="{{ $company->id }}" {{ $company->id == $profitCentre->company_id ? 'selected' : '' }}>[{{ $company->code }}] {{ $company->name }}</option>
                                       @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="code"><strong>{{ __('Profit Centre Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="code" id="code" value="{{ old('code', $profitCentre->code) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="name"><strong>{{ __('Profit Centre Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name', $profitCentre->name) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="phone"><strong>{{ __('Phone') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $profitCentre->phone) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="email"><strong>{{ __('Email') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="email" name="email" id="email" value="{{ old('email', $profitCentre->email) }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row pr-3">
                            <div class="col-md-7">
                                <label for="address"><strong>{{ __('Address') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <textarea name="address" id="address" class="form-control rounded" style="min-height: 130px">{{ old('address', $profitCentre->address) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="logo_file"><strong>{{ __('Logo') }}:</strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="file" name="logo_file" id="logo_file" class="form-control rounded"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="banner_file"><strong>{{ __('Banner') }}:</strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="file" name="banner_file" id="banner_file" class="form-control rounded"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/profit-centres') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Update Profit Centre</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection