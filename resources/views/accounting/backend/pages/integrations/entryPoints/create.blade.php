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
                    <form action="{{ route('accounting.entry-points.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-2">
                                <label for="code"><strong>{{ __('Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="code" id="code" value="{{ $code }}" readonly class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="short_name"><strong>{{ __('Short Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="short_name" id="short_name" value="{{ old('short_name') }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="name"><strong>{{ __('Entry Point Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="description"><strong>{{ __('Description') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label for="debit_ledgers"><strong>{{ __('Debit Ledgers') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="debit_ledgers[]" class="form-control debit_ledgers select2" multiple data-placeholder="Choose Debit Ledgers...">
                                        {!! $chartOfAccountsOptions !!}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label for="credit_ledgers"><strong>{{ __('Debit Ledgers') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="credit_ledgers[]" class="form-control credit_ledgers select2" multiple data-placeholder="Choose Credit Ledgers...">
                                        {!! $chartOfAccountsOptions !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                            
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/entry-points') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Save Entry Point</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection