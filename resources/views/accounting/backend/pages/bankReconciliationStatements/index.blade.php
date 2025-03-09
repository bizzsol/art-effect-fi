@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
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
                    <li class="active">{{__($title)}} </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-2 p-3" style="padding-bottom: 0 !important;">
                    <form action="{{ url('accounting/bank-reconciliation-statements') }}" method="get"
                          accept-charset="utf-8">
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="bank_account_id"><strong>Bank Account</strong></label>
                                <select name="bank_account_id" id="bank_account_id" class="form-control rounded">
                                    <option value="0">Choose Bank Account</option>
                                    @foreach($bankAccounts as $key => $bankAccount)
                                        <option value="{{ $bankAccount->id }}" {{ request()->get('bank_account_id') == $bankAccount->id ? 'selected' : '' }}>{{ $bankAccount->name }}
                                            ({{$bankAccount->number}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="from"><strong>Reconcile Date From</strong></label>
                                <input type="date" name="from" id="from" value="{{ request()->get('from') }}"
                                       class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="to"><strong>Reconcile Date To</strong></label>
                                <input type="date" name="to" id="to" value="{{ request()->get('to') }}"
                                       class="form-control">
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6 pt-1">
                                        <button type="submit" class="mt-4 btn btn-block btn-success"><i
                                                    class="la la-search"></i>&nbsp;Search
                                        </button>
                                    </div>
                                    <div class="col-md-6 pt-1">
                                        <button type="submit" class="mt-4 btn btn-block btn-danger"><i
                                                    class="la la-times"></i>&nbsp;Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel panel-info mt-2 p-3">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('yajra.js')
@endsection