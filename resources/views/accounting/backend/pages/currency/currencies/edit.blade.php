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
                    <form action="{{ route('accounting.currencies.update', $currency->id) }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        <div class="row pr-3">
                            <div class="col-md-3">
                                <label for="currency_type_id"><strong>{{ __('Currency Type') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="currency_type_id" id="currency_type_id" class="form-control rounded">
                                        @if(isset($currencyTypes[0]))
                                        @foreach($currencyTypes as $key => $currencyType)
                                        <option value="{{ $currencyType->id }}" {{ $currency->currency_type_id == $currencyType->id ? 'selected' : '' }}>{{ $currencyType->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="code"><strong>{{ __('Currency Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="code" id="code" value="{{ old('code', $currency->code) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="name"><strong>{{ __('Currency Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name', $currency->name) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="symbol"><strong>{{ __('Currency Symbol') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="symbol" id="symbol" value="{{ old('symbol', $currency->symbol) }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label for="hundreds"><strong>{{ __('Currency Hundreds') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="hundreds" id="hundreds" value="{{ old('hundreds', $currency->hundreds) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="country"><strong>{{ __('Currency Country') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="country" id="country" value="{{ old('country', $currency->country) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label for="desc"><strong>{{ __('Description') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="desc" id="desc" value="{{ old('desc', $currency->desc) }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/currencies') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Update Currency</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection