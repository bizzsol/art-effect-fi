@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name'] . ' | ' . $title)
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
                                                                    <option value="{{ $currency->id }}" {{ request()->get('currency_id') == $currency->id ? 'selected' : '' }}>
                                                                        &nbsp;&nbsp;{{ $currency->name }}
                                                                        ({{ $currency->code }}&nbsp;|&nbsp;{{ $currency->symbol }})</option>
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
                                        <input type="date" name="from" value="{{ request()->get('from') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to"><strong>To</strong></label>
                                        <input type="date" name="to" value="{{ request()->get('to') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2 pt-4">
                                    <button type="submit" class="mt-2 btn btn-success btn-md btn-block"><i
                                            class="la la-search"></i>&nbsp;Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3 pt-4">
                        <a class="btn btn-md btn-success mt-2 pull-right ml-2"
                            href="{{ url('accounting/exchange-rates/create') }}" style="float: right"><i
                                class="la la-plus"></i>&nbsp;New Exchange Rate</a>
                    </div>
                </div>
                <div class="panel panel-info mt-2 p-2">
                    <form action="{{ route('accounting.exchange-rates.fix-usd') }}" method="POST" id="fixUsdRatesForm">
                        @csrf
                        @if(auth()->user()->hasRole('Super Admin') || auth()->user()->id == 1)
                            <div class="mb-2 pb-2 text-right">
                                <button type="button" class="btn btn-warning btn-sm" id="fixUsdRatesBtn"><i class="la la-exchange"></i>&nbsp;Process (Fix USD Rates)</button>
                            </div>
                        @endif
                    <table class="table table-bordered" cellspacing="0" width="100%" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 2.5%;vertical-align: middle !important" rowspan="2">
                                    <input type="checkbox" id="checkAll">
                                </th>
                                <th class="text-center" style="width: 2.5%;vertical-align: middle !important" rowspan="2">SL
                                </th>
                                <th class="text-center" style="width: 7.5%;vertical-align: middle !important" rowspan="2">
                                    Currency Type</th>
                                <th class="text-center" style="width: 22.5%" colspan="3">Currency</th>
                                <th class="text-center" style="width: 7.5%;vertical-align: middle !important" rowspan="2">
                                    Reference</th>
                                <th class="text-center" style="width: 10%;vertical-align: middle !important" rowspan="2">
                                    Datetime</th>
                                <th class="text-center" style="width: 40%;vertical-align: middle !important" rowspan="2">
                                    Exchange Rates</th>
                                <th class="text-center" style="width: 10%;vertical-align: middle !important" rowspan="2">
                                    Description</th>
                                @if(auth()->user()->hasPermissionTo('exchange-rate-edit'))
                                    <th class="text-center" style="width: 5%;vertical-align: middle !important" rowspan="2">
                                        Action</th>
                                @endif
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
        // Decode the stored rates JSON directly instead of re-querying per row.
        // Currencies are resolved from the preloaded $currencies map (no DB hits).
        $decodedRates = json_decode($exchangeRate->rates, true) ?: [];
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="exchange_rate_ids[]" value="{{ $exchangeRate->id }}" class="row-checkbox">
                                        </td>
                                        <td>{{ $exchangeRates->firstItem() + $key }}</td>
                                        <td class="text-center">{{ $exchangeRate->currency->currencyType->name }}</td>
                                        <td class="text-center">{{ $exchangeRate->currency->code }}</td>
                                        <td class="text-center">{{ $exchangeRate->currency->name }}</td>
                                        <td class="text-center">{{ $exchangeRate->currency->symbol }}</td>
                                        <td class="text-center">{{ $exchangeRate->reference }}</td>
                                        <td class="text-center">{{ date('Y-m-d g:i:s a', strtotime($exchangeRate->datetime)) }}</td>
                                        <td>
                                            @if(count($decodedRates) > 0)
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
                                                        @foreach($decodedRates as $rateCurrencyId => $rate)
                                                            @php $rateCurrency = $currencies[$rateCurrencyId] ?? null; @endphp
                                                            @if($rateCurrency)
                                                                <tr>
                                                                    <td class="text-center">{{ $rateCurrency->code }}</td>
                                                                    <td class="text-center">{{ $rateCurrency->name }}</td>
                                                                    <td class="text-center">{{ $rateCurrency->symbol }}</td>
                                                                    <td class="text-center">{{ $rate['rate'] }}</td>
                                                                    <td class="text-center">{{ $rate['description'] }}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                        <td>{{ $exchangeRate->desc }}</td>
                                        @if(auth()->user()->hasPermissionTo('exchange-rate-edit'))
                                            <td class="text-center">
                                                <button type="button" class="btn btn-xs btn-primary editBtn" title="Edit"
                                                    data-url="{{ route('accounting.exchange-rates.edit', $exchangeRate->id) }}"><i
                                                        class="la la-edit"></i></button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    </form>
                    @if($exchangeRates->hasPages())
                        <div class="d-flex justify-content-end mt-2">
                            {{ $exchangeRates->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Exchange Rate Modal --}}
    <div class="modal fade" id="editExchangeRateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('Edit Exchange Rate') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="editExchangeRateModalBody">
                    <h4 class="text-center py-4">Please wait...</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn-md" data-dismiss="modal"><i class="la la-times"></i>&nbsp;Cancel</button>
                    <button type="button" class="btn btn-success btn-md updateBtn"><i class="la la-save"></i>&nbsp;Update Exchange Rates</button>
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
                    if (value) form.reset();
                });
            };

            // Open the edit modal and load the form for the selected exchange rate.
            $(document).on('click', '.editBtn', function () {
                var url = $(this).data('url');
                var body = $('#editExchangeRateModalBody');
                body.html('<h4 class="text-center py-4">Please wait...</h4>');
                $('#editExchangeRateModal').modal('show');
                $.ajax({
                    type: 'GET',
                    url: url,
                })
                .done(function (response) {
                    body.html(response);
                })
                .fail(function () {
                    body.html('<h4 class="text-center py-4 text-danger">Failed to load the form. Please try again.</h4>');
                });
            });

            // Submit the edit form (AJAX PUT) from the modal footer button.
            $(document).on('click', '.updateBtn', function () {
                var form = $('#edit-exchange-rate-form');
                if (!form.length) return;
                var button = $(this);

                swal({
                    title: "{{__('Are you sure ?')}}",
                    text: "{{__('Once you update Exchange Rates, It will have impact to the system.')}}",
                    icon: "warning",
                    dangerMode: true,
                    buttons: {
                        cancel: { text: "Cancel", value: false, visible: true, closeModal: true },
                        confirm: { text: "Confirm", value: true, visible: true, closeModal: true },
                    },
                }).then((value) => {
                    if (!value) return;
                    button.prop('disabled', true).html('<i class="las la-spinner"></i>&nbsp;Please wait...');
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        dataType: 'json',
                        data: form.serializeArray(),
                    })
                    .done(function (response) {
                        button.prop('disabled', false).html('<i class="la la-save"></i>&nbsp;Update Exchange Rates');
                        if (response.success) {
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    })
                    .fail(function (response) {
                        button.prop('disabled', false).html('<i class="la la-save"></i>&nbsp;Update Exchange Rates');
                        if (response.responseJSON && response.responseJSON.errors) {
                            $.each(response.responseJSON.errors, function (index, val) {
                                toastr.error(val[0]);
                            });
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    });
                });
            });

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
                    if (value) {
                        var button = $(this);
                        $.ajax({
                            type: 'DELETE',
                            url: $(this).attr('data-src'),
                            dataType: 'json',
                            success: function (response) {
                                if (response.success) {
                                    swal({
                                        icon: 'success',
                                        text: response.message,
                                        button: false
                                    });
                                    setTimeout(() => {
                                        swal.close();
                                    }, 1500);
                                    button.parent().parent().remove();
                                } else {
                                    showAlert('error', response.message);
                                    return;
                                }
                            },
                        });
                    }
                });
            })

            $('#checkAll').on('change', function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
            });

            $('#fixUsdRatesBtn').on('click', function() {
                if($('.row-checkbox:checked').length == 0) {
                    toastr.warning('Please select at least one exchange rate record.');
                    return;
                }
                
                swal({
                    title: "{{__('Are you sure?')}}",
                    text: "{{__('This will recalculate and fix the USD exchange rate (1 / Rate) for selected records.')}}",
                    icon: "warning",
                    dangerMode: true,
                    buttons: {
                        cancel: true,
                        confirm: {
                            text: "Process",
                            value: true,
                            visible: true,
                            closeModal: true
                        },
                    },
                }).then((value) => {
                    if (value) {
                        $('#fixUsdRatesForm').submit();
                    }
                });
            });
        })(jQuery)
    </script>
@endsection