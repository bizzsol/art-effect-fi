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
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-3">
                    <div class="panel-boby p-3">
                        <form action="{{ route('accounting.bank-currency-transfer.store') }}" method="post"
                              accept-charset="utf-8" id="transfer-form">
                            @csrf
                            <div class="row pr-3 mb-3">
                                <div class="col-md-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="from_bank_account_id"><strong>Sending Bank Account
                                                                <span class="text-danger">*</span></strong></label>
                                                        <select name="from_bank_account_id" id="from_bank_account_id"
                                                                class="form-control" onchange="getCurrencies()">
                                                            <option value="0">Choose Bank Account</option>
                                                            @if(isset($bankAccounts[0]))
                                                                @foreach($bankAccounts as $key => $bankAccount)
                                                                    <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}
                                                                        ({{$bankAccount->number}})
                                                                        ({{ $bankAccount->currency ? $bankAccount->currency->code : '' }}
                                                                        )
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="sending_amount"><strong>Sending Amount <span
                                                                        class="text-danger">*</span></strong></label>
                                                        <input type="number" name="sending_amount" id="sending_amount"
                                                               value="0.00" step="any" min="0"
                                                               class="form-control text-right"
                                                               onchange="getCurrencies()">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="to_bank_account_id"><strong>Receiving Bank Account
                                                                <span class="text-danger">*</span></strong></label>
                                                        <select name="to_bank_account_id" id="to_bank_account_id"
                                                                class="form-control" onchange="getCurrencies()">
                                                            <option value="0">Choose Bank Account</option>
                                                            @if(isset($bankAccounts[0]))
                                                                @foreach($bankAccounts as $key => $bankAccount)
                                                                    <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}
                                                                        ({{$bankAccount->number}})
                                                                        ({{ $bankAccount->currency ? $bankAccount->currency->code : '' }}
                                                                        )
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="estimated_amount"><strong>Estimated Amount <span
                                                                        class="text-danger">*</span></strong></label>
                                                        <input type="number" name="estimated_amount"
                                                               id="estimated_amount" value="0.00" step="any" min="0"
                                                               class="form-control text-right" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="receiving_amount"><strong>Receiving Amount <span class="text-danger">*</span></strong></label>
                                                        <input type="number" name="receiving_amount"
                                                               id="receiving_amount" value="0.00" step="any" min="0"
                                                               class="form-control text-right" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-success btn-md transfer-button"><i
                                                        class="la la-save"></i>&nbsp;Transfer Currency
                                            </button>
                                        </div>
                                    </div>
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
        getCurrencies();

        function getCurrencies() {
            $.ajax({
                url: "{{ url('accounting/bank-currency-transfer/create') }}",
                type: 'GET',
                dataType: 'json',
                data: $('#transfer-form').serializeArray(),
            })
                .done(function (response) {
                    $('#receiving_amount').val(response.receiving_amount);
                    $('#estimated_amount').val(response.receiving_amount);
                    $('#gain_loss').val(response.gain_loss);

                    if (response.same == 1) {
                        $('#receiving_amount').attr('readonly', 'readonly');
                    } else {
                        $('#receiving_amount').removeAttr('readonly');
                    }

                    // calculateGainLoss();
                });
        }


        function calculateGainLoss() {
            var estimated_amount = parseFloat($('#estimated_amount').val() != "" ? $('#estimated_amount').val() : 0);
            var receiving_amount = parseFloat($('#receiving_amount').val() != "" ? $('#receiving_amount').val() : 0);

            $('#gain_loss').val(parseFloat(receiving_amount - estimated_amount).toFixed(4));
        }

        $(document).ready(function () {
            var form = $('#transfer-form');
            var button = $('.transfer-button');
            var content = button.html();
            form.on('submit', function (e) {
                e.preventDefault();

                swal({
                    title: "{{__('Are you sure ?')}}",
                    text: "{{__('Are you sure to transfer currency ?')}}",
                    icon: "warning",
                    dangerMode: false,
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
                            closeModal: true,
                            className: 'bg-success'
                        },
                    },
                }).then((value) => {
                    if (value) {
                        button.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...');

                        $.ajax({
                            url: form.attr('action'),
                            type: form.attr('method'),
                            dataType: 'json',
                            data: form.serializeArray(),
                        })
                            .done(function (response) {
                                if (response.success) {
                                    window.open("{{ url('accounting/bank-currency-transfer') }}/" + response.reference, "_parent");
                                } else {
                                    toastr.error(response.message);
                                }
                                button.prop('disabled', false).html(content);
                            })
                            .fail(function (response) {
                                button.prop('disabled', false).html(content);
                                $.each(response.responseJSON.errors, function (index, val) {
                                    toastr.error(val[0]);
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection