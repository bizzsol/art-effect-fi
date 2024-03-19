@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
    .select2-container--default .select2-results__option[aria-disabled=true]{
        color: black !important;
        font-weight: bold !important;
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
                    <form action="{{ route('accounting.advance-categories.update', $advanceCategory->id) }}" method="post" accept-charset="utf-8">
                    @csrf
                    @method('PUT')
                        <div class="row pr-3">
                            <div class="col-md-2">
                                <label for="code"><strong>{{ __('Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="code" id="label" value="{{ old('code', $advanceCategory->code) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="name"><strong>{{ __('Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name', $advanceCategory->name) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="chart_of_account_id"><strong>{{ __('Choose Ledger') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="chart_of_account_id" id="chart_of_account_id" class="form-control rounded select2" required>
                                        {!! chartOfAccountsOptions([], $advanceCategory->chart_of_account_id, 0, getAllGroupAndLedgers()) !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row pr-3">
                            <div class="col-md-12">
                                <label for="description"><strong>{{ __('Description') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="description" id="description" value="{{ old('description', $advanceCategory->description) }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/advance-categories') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Update Entry Type</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection