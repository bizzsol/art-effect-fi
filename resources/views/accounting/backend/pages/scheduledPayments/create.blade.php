@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
            font-size: 14px;
            font-weight: 600;
        }

        .select2-container--default .select2-results__option[aria-disabled=true] {
            color: #000 !important;
            font-weight: bold !important;
        }

        .select2-container {
            width: 100% !important;
        }

        tr td {
            padding: 10px 3px 10px 3px !important;
        }

        .with-po {
            display: none;
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
                <div class="panel panel-info mt-3">
                    <div class="panel-boby p-3">
                        <form action="{{ route('accounting.scheduled-payment.store') }}" method="post" accept-charset="utf-8" class="entry-form">
                        @csrf
                            <div class="row pr-3">
                                <div class="col-md-5">
                                    <label for="schedule_payment_id"><strong>Payment Schedule:<span class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="schedule_payment_id" id="schedule_payment_id" class="form-control rounded" onchange="window.open('{{ url('accounting/scheduled-payment/create?company='.$company->id) }}&schedule='+$('#schedule_payment_id').find(':selected').val(), '_parent')">
                                            <option value="{{ null }}">Choose Payment Schedule</option>
                                            @if(isset($company->schedulePayments[0]))
                                                @foreach($company->schedulePayments as $key => $scheduledPayment)
                                                    <option value="{{ $scheduledPayment->id }}" {{ request()->get('schedule') == $scheduledPayment->id ? 'selected' : '' }}>{{ $scheduledPayment->code }} | {{ $scheduledPayment->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                @if(isset($schedule->id))
                                <div class="col-md-7">
                                    <label for="purpose"><strong>Purpose <span class="text-danger">*</span></strong></label>
                                    <input type="text" name="purpose" id="purpose" value="{{ old('purpose') }}" class="form-control">
                                </div>
                                @endif
                                
                            </div>
                            
                            @if(isset($schedule->id))
                            <div class="row pr-3">
                                <div class="col-md-3">
                                    <label for="number"><strong>{{ __('Reference') }}:</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="number" id="number" value="{{ old('number') }}" class="form-control rounded">
                                    </div>
                                </div>
                                <div class="col-md-2" id="currency-choose">
                                    <label for="currency_id"><strong>{{ __('Currency') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="currency_id" id="currency_id" class="form-control rounded">
                                            @if(isset($currencyTypes[0]))
                                                @foreach($currencyTypes as $key => $currencyType)
                                                    <optgroup label="{{ $currencyType->name }}">
                                                        @if($currencyType->currencies->count() > 0)
                                                            @foreach($currencyType->currencies as $key => $currency)
                                                                <option value="{{ $currency->id }}" {{ $accountDefaultSettings['currency_id'] == $currency->id ? 'selected' : '' }}>
                                                                    &nbsp;&nbsp;{{ $currency->name }}
                                                                    ({{ $currency->code }}
                                                                    &nbsp;|&nbsp;{{ $currency->symbol }})
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </optgroup>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="datetime"><strong>{{ __('Date & Time') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="datetime-local" name="datetime" id="datetime" value="{{ old('datetime', date('Y-m-d H:i:s')) }}" class="form-control rounded">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="fiscal_year_id"><strong>{{ __('Fiscal Year') }}:<span
                                                    class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="fiscal_year_id" id="fiscal_year_id" class="form-control rounded">
                                            <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->title }}
                                                &nbsp;|&nbsp;{{ date('d-M-y', strtotime($fiscalYear->start)).' to '.date('d-M-y', strtotime($fiscalYear->end)) }}
                                            </option>

                                            @if(isset($fiscalYears[0]))
                                            @foreach($fiscalYears as $fiscalYear)
                                            <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->title }}
                                                &nbsp;|&nbsp;{{ date('d-M-y', strtotime($fiscalYear->start)).' to '.date('d-M-y', strtotime($fiscalYear->end)) }}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th style="width: 25%">Cost Centre</th>
                                            <th style="width: 35%">Ledger</th>
                                            <th style="width: 15%">Debit</th>
                                            <th style="width: 15%">Credit</th>
                                            <th style="width: 10%">Narration</th>
                                            {{--<th style="width: 10%">Balance</th>--}}
                                            <th style="width: 5%">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody class="entries">
                                        @if(isset($schedule->ledgers[0]))
                                            @foreach($schedule->ledgers as $key => $item)
                                                <tr>
                                                    <td>
                                                        <select name="cost_centre_id[]" class="form-control cost_centre_id select2">{!! $costCentres !!}</select>
                                                    </td>
                                                    <td>
                                                        <select name="chart_of_account_id[]" class="form-control chart_of_account_id select2 choose-me" onchange="Entries()" data-selected="{{ $item->chart_of_account_id }}">{!! $chartOfAccountsOptions !!}</select>
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" step="any" name="debit[]"
                                                               class="form-control debit text-right"
                                                               onchange="debitChanged($(this))"
                                                               onkeyup="debitChanged($(this))"
                                                               onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187"
                                                               value="{{ $item->type == 'D' ? $item->amount : 0 }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" step="any" name="credit[]"
                                                               class="form-control credit text-right"
                                                               onchange="creditChanged($(this))"
                                                               onkeyup="creditChanged($(this))"
                                                               onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187"
                                                               value="{{ $item->type == 'C' ? $item->amount : 0 }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="narration[]"
                                                               class="form-control narration"
                                                               value="{{ $schedule->name }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <a onclick="remove($(this))" class="action-buttons"><i
                                                                    class="text-danger la la-trash"
                                                                    style="transform: scale(2, 2)"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="2">
                                                <h5><strong>Total</strong></h5>
                                            </td>
                                            <td style="font-weight: bold;padding-right: 28px !important"
                                                class="text-right total-debit"></td>
                                            <td style="font-weight: bold;padding-right: 28px !important"
                                                class="text-right total-credit"></td>

                                            <td></td>
                                            {{--                                            <td></td>--}}

                                            <td class="text-center">
                                                <a onclick="add();" class="action-buttons"><i
                                                            class="text-success las la-plus-circle"
                                                            style="transform: scale(2, 2)"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <h5><strong>Difference</strong></h5>
                                            </td>
                                            <td style="font-weight: bold;padding-right: 28px !important"
                                                class="text-right debit-difference"></td>
                                            <td style="font-weight: bold;padding-right: 28px !important"
                                                class="text-right credit-difference"></td>

                                            <td></td>
                                            {{--                                            <td></td>--}}

                                            <td class="text-center">

                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label for="notes"><strong>{{ __('Narration') }}:</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <textarea name="notes" id="notes"
                                                  class="form-control rounded">{{ old('notes', ($schedule->name.' | '.$schedule->description)) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a class="btn btn-dark btn-md" href="{{ url('accounting/scheduled-payment') }}"><i
                                                class="la la-times"></i>&nbsp;Cancel</a>
                                    <button type="submit" class="btn btn-success btn-md entry-button"><i
                                                class="la la-save"></i>&nbsp;Process Payment
                                    </button>
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script type="text/javascript">
        var items = parseInt("{{ isset($schedule->ledgers[0]) ? $schedule->ledgers->count() : 0 }}");
        if(items > 0){
            $.each($('.choose-me'), function(index, val) {
                $(this).select2().val($(this).attr('data-selected')).trigger("change");
            });
            calculation();
        }else{
            add();
            add();
        }

        function add() {
            $('.entries').append('<tr>' +
                '<td>' +
                '<select name="cost_centre_id[]" class="form-control cost_centre_id select2">{!! $costCentres !!}</select>' +
                '</td>' +
                '<td>' +
                '<select name="chart_of_account_id[]" class="form-control chart_of_account_id select2" onchange="Entries()">{!! $chartOfAccountsOptions !!}</select>' +
                '</td>' +
                '<td>' +
                '<input type="number" min="0" step="any" name="debit[]" class="form-control debit text-right" onchange="debitChanged($(this))" onkeyup="debitChanged($(this))" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187" value="0">' +
                '</td>' +
                '<td>' +
                '<input type="number" min="0" step="any" name="credit[]" class="form-control credit text-right" onchange="creditChanged($(this))" onkeyup="creditChanged($(this))" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187" value="0">' +
                '</td>' +
                '<td>' +
                '<input type="text" name="narration[]" class="form-control narration">' +
                '</td>' +
                // '<td class="text-right closing-balance"></td>'+
                '<td class="text-center">' +
                '<a onclick="remove($(this))" class="action-buttons"><i class="text-danger la la-trash" style="transform: scale(2, 2)"></i></a>' +
                '</td>' +
                '</tr>');
            Entries();
        }

        function remove(element) {
            element.parent().parent().remove();
            Entries();
        }

        function Entries() {
            $('.cost_centre_id').select2();
            $('.chart_of_account_id').select2();

            $.each($('.entries').find('tr'), function (index, tr) {
                $(this).find('.closing-balance').html($(this).find('.chart_of_account_id :selected').attr('data-closing-balance'));
            });

            calculation();
        }

        function debitChanged(element) {
            var debit = (element.val() != "" ? parseFloat(element.val()) : parseFloat(0));
            if (debit > 0) {
                element.parent().parent().find('.credit').val(0);
            }
            calculation();
        }

        function creditChanged(element) {
            var credit = (element.val() != "" ? parseFloat(element.val()) : parseFloat(0));
            if (credit > 0) {
                element.parent().parent().find('.debit').val(0);
            }
            calculation();
        }

        function calculation() {
            var total_debit = 0;
            var total_credit = 0;

            $.each($('.debit'), function (index, val) {
                total_debit += parseFloat($(this).val() != "" ? $(this).val() : 0);
            });

            $.each($('.credit'), function (index, val) {
                total_credit += parseFloat($(this).val() != "" ? $(this).val() : 0);
            });

            $('.total-debit').html(total_debit.toFixed(2));
            $('.total-credit').html(total_credit.toFixed(2));

            if (total_debit == total_credit) {
                $('.total-debit').removeClass('bg-danger').addClass('bg-success');
                $('.total-credit').removeClass('bg-danger').addClass('bg-success');
                $('.debit-difference').html('-');
                $('.credit-difference').html('');
            } else {
                $('.total-debit').removeClass('bg-success').addClass('bg-danger');
                $('.total-credit').removeClass('bg-success').addClass('bg-danger');
                if (total_debit > total_credit) {
                    $('.debit-difference').html('');
                    $('.credit-difference').html((total_debit - total_credit).toFixed(2));
                } else {
                    $('.credit-difference').html('');
                    $('.debit-difference').html((total_credit - total_debit).toFixed(2));
                }
            }
        }

        $(document).ready(function () {
            var form = $('.entry-form');
            var button = $('.entry-button');
            content = button.html();
            form.on('submit', function (e) {
                e.preventDefault();
                button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);
                $.confirm({
                    title: 'Confirm!',
                    content: '<hr class="pt-0 mt-0"><h5>Are you sure to save the transactions ?</h5>',
                    buttons: {
                        no: {
                            text: '<i class="la la-times"></i>&nbsp;No',
                            btnClass: 'btn-red',
                            action: function () {
                                button.html(content).prop('disabled', false);
                            }
                        },
                        yes: {
                            text: '<i class="la la-check"></i>&nbsp;Yes',
                            btnClass: 'btn-success',
                            action: function () {
                                $.ajax({
                                    url: form.attr('action'),
                                    type: form.attr('method'),
                                    dataType: 'json',
                                    data: form.serializeArray(),
                                })
                                .done(function (response) {
                                    if (response.success) {
                                        toastr.success(response.message);

                                        $('#number').val('');
                                        $('.debit').val(0);
                                        $('.credit').val(0);

                                        $.dialog({
                                            title: response.entry_type + " Voucher #" + response.code,
                                            content: "url:{{ url('accounting/entries') }}/" + response.id + "?short-details",
                                            animation: 'scale',
                                            columnClass: 'col-md-12',
                                            closeAnimation: 'scale',
                                            backgroundDismiss: true
                                        });
                                    } else {
                                        toastr.error(response.message);
                                    }

                                    button.html(content).prop('disabled', false);
                                })
                                .fail(function (response) {
                                    $.each(response.responseJSON.errors, function (index, error) {
                                        toastr.error(error[0]);
                                    });

                                    button.html(content).prop('disabled', false);
                                });
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection