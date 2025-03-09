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
                    <form action="{{ route('accounting.companies.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="code"><strong>{{ __('Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="code" id="code" value="{{ $code }}" readonly class="form-control rounded">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name"><strong>{{ __('Organization Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control rounded">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="owner_name"><strong>{{ __('Owner Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}" class="form-control rounded">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="phone"><strong>{{ __('Phone') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="email"><strong>{{ __('Email') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row pr-3">
                            <div class="col-md-7">
                                <label for="address"><strong>{{ __('Address') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <textarea name="address" id="address" class="form-control rounded" style="min-height: 130px">{{ old('address') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="logo_file"><strong>{{ __('Logo') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="file" name="logo_file" id="logo_file" class="form-control rounded"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="banner_file"><strong>{{ __('Banner') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="file" name="banner_file" id="banner_file" class="form-control rounded"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/companies') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Save Company</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection