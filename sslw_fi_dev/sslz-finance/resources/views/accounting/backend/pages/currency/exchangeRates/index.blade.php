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
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-md-9">
                    <form action="{{ url('accounting/exchange-rates') }}" method="get" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency_id"><strong>Currency</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="currency_id" id="currency_id" class="form-control rounded">
                                            <option value="0">All Currencies</option>
                                            @if(isset($currencyTypes[0]))
                                            @foreach($currencyTypes as $key => $currencyType)
                                            <optgroup label="{{ $currencyType->name }}">
                                                @if($currencyType->currencies->count() > 0)
                                                @foreach($currencyType->currencies as $key => $currency)
                                                    <option value="{{ $currency->id }}" {{ request()->get('currency_id') == $currency->id ? 'selected' : '' }}>&nbsp;&nbsp;{{ $currency->name }} ({{ $currency->code }}&nbsp;|&nbsp;{{ $currency->symbol }})</option>
                                                @endforeach
                                                @endif
                                            </optgroup>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="from"><strong>From</strong></label>
                                    <input type="date" name="from" value="{{ request()->get('from') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="to"><strong>To</strong></label>
                                    <input type="date" name="to" value="{{ request()->get('to') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2 pt-4">
                                <button type="submit" class="mt-2 btn btn-success btn-md btn-block"><i class="la la-search"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-3 pt-4">
                    <a class="btn btn-md btn-success mt-2 pull-right ml-2" href="{{ url('accounting/exchange-rates/create') }}" style="float: right"><i class="la la-plus"></i>&nbsp;New Exchange Rate</a>
                </div>
            </div>
            <div class="panel panel-info mt-2 p-2">
                <table class="table table-bordered" cellspacing="0" width="100%" id="dataTable">
                    <thead>
                        <tr>
                           <th class="text-center" style="width: 2.5%;vertical-align: middle !important" rowspan="2">SL</th>
                           <th class="text-center" style="width: 7.5%;vertical-align: middle !important" rowspan="2">Currency Type</th>
                           <th class="text-center" style="width: 22.5%" colspan="3">Currency</th>
                           <th class="text-center" style="width: 7.5%;vertical-align: middle !important" rowspan="2">Reference</th>
                           <th class="text-center" style="width: 10%;vertical-align: middle !important" rowspan="2">Datetime</th>
                           <th class="text-center" style="width: 40%;vertical-align: middle !important" rowspan="2">Exchange Rates</th>
                           <th class="text-center" style="width: 10%;vertical-align: middle !important" rowspan="2">Description</th>
                       </tr>
                       <tr>
                           <th class="text-center" style="width: 7.5%">Code</th>
                           <th class="text-center" style="width: 7.5">Name</th>
                           <th class="text-center" style="width: 7.5%">Symbol</th>
                       </tr>
                   </thead>
                   <tbody>
                    @if(isset($exchangeRates[0]))
                    @foreach($exchangeRates as $key => $exchangeRate)
                    @php
                        $rates = getExchangeRates($exchangeRate->currency_id, date('Y-m-d', strtotime($exchangeRate->datetime)), date('H:i:s', strtotime($exchangeRate->datetime)), $exchangeRate->id);
                    @endphp
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td class="text-center">{{ $exchangeRate->currency->currencyType->name }}</td>
                        <td class="text-center">{{ $exchangeRate->currency->code }}</td>
                        <td class="text-center">{{ $exchangeRate->currency->name }}</td>
                        <td class="text-center">{{ $exchangeRate->currency->symbol }}</td>
                        <td class="text-center">{{ $exchangeRate->reference }}</td>
                        <td class="text-center">{{ date('Y-m-d g:i:s a', strtotime($exchangeRate->datetime)) }}</td>
                        <td>
                            @if(count($rates['rates']) > 0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                       <th class="text-center" style="width: 15%">Code</th>
                                       <th class="text-center" style="width: 15">Name</th>
                                       <th class="text-center" style="width: 15%">Symbol</th>
                                       <th class="text-center" style="width: 20%">Rate</th>
                                       <th class="text-center" style="width: 35%">Description</th>
                                   </tr>
                                </thead>
                                <tbody>
                                    @foreach($rates['rates'] as $currency_id => $rate)
                                    <tr>
                                        <td class="text-center">{{ $rate['currency']->code }}</td>
                                        <td class="text-center">{{ $rate['currency']->name }}</td>
                                        <td class="text-center">{{ $rate['currency']->symbol }}</td>
                                        <td class="text-center">{{ $rate['rate'] }}</td>
                                        <td class="text-center">{{ $rate['description'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </td>
                        <td>{{ $exchangeRate->desc }}</td>
                    </tr>
                    @endforeach
                    @endif
                   </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    (function ($) {
        "use script";
        const showAlert = (status, error) => {
            swal({
                icon: status,
                text: error,
                dangerMode: true,
                buttons: {
                    cancel: false,
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if(value)form.reset();
            });
        };

        $('.deleteBtn').on('click', function () {
            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Once you delete, You can not recover this data and related files.')}}",
                icon: "warning",
                dangerMode: true,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Delete",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if(value){
                    var button = $(this);
                    $.ajax({
                        type: 'DELETE',
                        url: $(this).attr('data-src'),
                        dataType: 'json',
                        success:function (response) {
                            if(response.success){
                                swal({
                                    icon: 'success',
                                    text: response.message,
                                    button: false
                                });
                                setTimeout(()=>{
                                    swal.close();
                                }, 1500);
                                button.parent().parent().remove();
                            }else{
                                showAlert('error', response.message);
                                return;
                            }
                        },
                    });
                }
            });
        })
    })(jQuery)
</script>
@endsection