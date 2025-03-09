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
                    <form action="{{ route('accounting.exchange-rates.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="exchange-rate-form" novalidate>
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-3">
                                <label for="currency_id"><strong>{{ __('Currency Type') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="currency_id" id="currency_id" class="form-control rounded" onchange="getRateForm()">
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
                            <div class="col-md-2">
                                <label for="date"><strong>{{ __('Date') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="time"><strong>{{ __('Time') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="time" name="time" id="time" value="{{ old('time', date('H:i:s')) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="reference"><strong>{{ __('Reference') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="reference" id="reference" value="{{ $reference }}" class="form-control rounded bg-white" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="desc"><strong>{{ __('Description') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="desc" id="desc" value="{{ old('desc') }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 exchange-rates">
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/exchange-rates') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md submit-button"><i class="la la-save"></i>&nbsp;Save Exchange Rates</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    getRateForm();
    function getRateForm() {
        $('.exchange-rates').html('<h3 class="text-center">Please wait...</h3>');
        $.ajax({
            url: "{{ url('accounting/exchange-rates') }}/"+$('#currency_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            console.log(response);
            $('.exchange-rates').html(response);
        });
    }

    $(document).ready(function() {
        var form = $('#exchange-rate-form');
        var button = $('.submit-button');
        form.on('submit', function(e){
            e.preventDefault();

            swal({
                title: "{{__('Are you sure ?')}}",
                text: "{{__('Once you save Exchange Rates, It will have impact to the system.')}}",
                icon: "warning",
                dangerMode: true,
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: false,
                        visible: true,
                        closeModal: true
                    },
                    confirm: {
                        text: "Confirm",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if(value){
                    swal({
                        title: "{{__('Are you sure ?')}}",
                        text: "{{__('Once you save Exchange Rates, It will have impact to the system.')}}",
                        icon: "warning",
                        dangerMode: true,
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                value: false,
                                visible: true,
                                closeModal: true
                            },
                            confirm: {
                                text: "Confirm",
                                value: true,
                                visible: true,
                                closeModal: true
                            },
                        },
                    }).then((value) => {
                        if(value){
                            button.prop('disabled', true).html('<i class="las la-spinner"></i>&nbsp;Please wait...');

                            $.ajax({
                              url: form.attr('action'),
                              type: form.attr('method'),
                              dataType: 'json',
                              data: form.serializeArray(),
                            })
                            .done(function(response) {
                                button.prop('disabled', false).html('<i class="la la-save"></i>&nbsp;Save Exchange Rates');
                                if(response.success){
                                    location.reload();
                                }else{
                                    toastr.error(response.message);
                                }
                            })
                            .fail(function(response) {
                                button.prop('disabled', false).html('<i class="la la-save"></i>&nbsp;Save Exchange Rates');
                                $.each(response.responseJSON.errors, function(index, val) {
                                    toastr.error(val[0]);
                                });
                            });
                        }
                    });
                }
            });
      });
    });
</script>
@endsection