@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
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
                        <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i
                                    class="las la-arrow-left"></i> Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                @if($entries->count() > 0)
                    @foreach($entries as $key => $entry)
                        @php
                            $currency = $entry->exchangeRate->currency->code;
                            $same = ($entry->exchangeRate->currency_id == $systemCurrency->id ? true : false);
                            $exchangeRate = exchangeRate($entry->exchangeRate, $systemCurrency->id);
                        @endphp
                        @include('accounting.backend.pages.entries.entry-details')
                        <hr style="border-top: 2px dashed #ccc">
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection
