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
                    <a href="{{ url('accounting/sub-ledgers/'.$account->id) }}" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3">
                    <form action="{{ route('accounting.sub-ledgers.store') }}?chart_of_account_id={{ $account->id }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-4">
                                <label for="code"><strong>{{ __('Sub Ledger Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <input type="text" name="code" id="code" class="form-control" required>
                            </div>
                            <div class="col-md-8">
                                <label for="name"><strong>{{ __('Sub Ledger Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control rounded" required>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="notes"><strong>{{ __('Notes') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <textarea name="notes" id="notes" class="form-control rounded" style="min-height: 130px">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/sub-ledgers') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Save Sub Ledger</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection