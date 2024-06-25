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
                        <a class="btn btn-sm btn-success text-white" title="Upload Excel" data-toggle="modal" data-target="#uploadExcel"><i class="las la-upload"></i>&nbsp;Upload Excel</a>

                        @if(isset(session()->get('entries-items')[0]))
                        <a class="btn btn-sm btn-danger text-white" href="{{ url()->full() }}&clear-entry-items"><i class="las la-times"></i>&nbsp;Reset Excel Data</a>
                        @endif

                        <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i
                                    class="las la-arrow-left"></i> Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-3">
                    <div class="panel-boby p-3">
                        <form action="{{ route('accounting.entries.store') }}?type={{ request()->get('type') }}&company={{ request()->get('company') }}"
                              method="post" accept-charset="utf-8" class="entry-form">
                            @csrf
                            @if(request()->get('type') == "payment")
                                <div class="row pr-3">
                                    <div class="col-md-2">
                                        <label for="form_type"><strong>{{ ucwords(request()->get('type')) }} Type:<span
                                                        class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="form_type" id="form_type" class="form-control rounded"
                                                    onchange="checkIfPO()">
                                                <option value="without_po">Without PO Reference</option>
                                                <option value="with_po">With PO Reference</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 with-po">
                                        <label for="supplier_id"><strong>Choose Supplier:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="supplier_id" id="supplier_id" class="form-control rounded"
                                                    onchange="getPurchaseOrders()">
                                                <option value="0">Choose Supplier</option>
                                                @if(isset($suppliers[0]))
                                                    @foreach($suppliers as $key => $supplier)
                                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}
                                                            ({{ $supplier->phone }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 with-po">
                                        <label for="purchase_order_id"><strong>Choose Purchase Order:<span
                                                        class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="purchase_order_id" id="purchase_order_id"
                                                    class="form-control rounded" onchange="printAmount()">
                                                <option value="0"
                                                        data-advance-account="{{ $accountDefaultSettings['supplier_advance_account'] }}"
                                                        data-payable-account="{{ $accountDefaultSettings['supplier_payable_account'] }}"
                                                        data-credit-account="{{ $accountDefaultSettings['bank_account'] }}"
                                                        data-due-amount="{{ systemDoubleValue(0, 2) }}"
                                                        data-supplier-id="{{ null }}"
                                                        data-currency="{{ $systemCurrency->id }}"
                                                        data-currency-text="{{ $systemCurrency->symbol }}">Choose
                                                    Purchase order
                                                </option>
                                                @if(isset($purchaseOrders[0]))
                                                    @foreach($purchaseOrders as $key => $purchaseOrder)
                                                        @php
                                                            $supplier = $purchaseOrder->relQuotation->relSuppliers;
                                                            $advance_account = (isset($supplier->advance_account_id) && $supplier->advance_account_id > 0 ? $supplier->advance_account_id : $accountDefaultSettings['supplier_advance_account']);
                                                            $payable_account = (isset($supplier->payable_account_id) && $supplier->payable_account_id > 0 ? $supplier->payable_account_id : $accountDefaultSettings['supplier_payable_account']);
                                                            $credit_account = $accountDefaultSettings['bank_account'];

                                                            $getPODueAmount = getPODueAmount($purchaseOrder);
                                                        @endphp
                                                        @if($getPODueAmount > 0)
                                                            <option value="{{ $purchaseOrder->id }}"
                                                                    data-advance-account="{{ $advance_account }}"
                                                                    data-payable-account="{{ $payable_account }}"
                                                                    data-credit-account="{{ $credit_account }}"
                                                                    data-due-amount="{{ $getPODueAmount }}"
                                                                    data-supplier-id="{{ $purchaseOrder->relQuotation->supplier_id }}"
                                                                    data-currency="{{ $purchaseOrder->relQuotation->exchangeRate->currency_id }}"
                                                                    data-currency-text="{{ $purchaseOrder->relQuotation->exchangeRate->currency->symbol }}">{{ $purchaseOrder->reference_no }}
                                                                &nbsp;|&nbsp;{{ date('Y-m-d', strtotime($purchaseOrder->po_date))}}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 with-po">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="pay_amount"><strong><span
                                                                id="pay-amount-label">Pay Amount</span>:<span
                                                                class="text-danger">&nbsp;*</span></strong></label>
                                                <input type="number" name="pay_amount" id="pay_amount" step="any"
                                                       value="0.00" class="form-control text-right"
                                                       onchange="printPOAmount()" onkeyup="printPOAmount()">
                                            </div>

                                            {{-- <div class="col-md-3">
                                                <label for="is_advance"><strong>Advance:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="is_advance" id="is_advance" class="form-control rounded" onchange="checkAdvance()">
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>
                                            </div> --}}

                                            <div class="col-md-7 is-advance">
                                                <label for="advance_category_id"><strong>Advance Category:<span
                                                                class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="advance_category_id" id="advance_category_id"
                                                            class="form-control rounded">
                                                        <option value="{{ null }}">Choose Advance Category</option>
                                                        @if(isset($advanceCategories[0]))
                                                            @foreach($advanceCategories as $key => $advanceCategory)
                                                                <option value="{{ $advanceCategory->id }}">{{ '['.$advanceCategory->code.'] '.$advanceCategory->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row pr-3">
                                <div class="col-md-3">
                                    <label for="number"><strong>{{ __('Reference') }}:</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="number" id="number" value="{{ old('number') }}"
                                               class="form-control rounded">
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
                                @if(isset(session()->get('entries-errors')[0]))
                                <div class="col-md-12 mb-3">
                                    <ol>
                                        @foreach(session()->get('entries-errors') as $error)
                                        <li class="text-danger">{!! $error !!}</li>
                                        @endforeach
                                    </ol>
                                </div>
                                @endif

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
                                        @if(isset(session()->get('entries-items')[0]))
                                            @foreach(session()->get('entries-items') as $key => $item)
                                                <tr>
                                                    <td>
                                                        <select name="cost_centre_id[]" class="form-control cost_centre_id select2 choose-me" data-selected="{{ $item['cost_centre_id'] }}">{!! $costCentres !!}</select>
                                                    </td>
                                                    <td>
                                                        <select name="chart_of_account_id[]" class="form-control chart_of_account_id select2 choose-me" onchange="Entries()" data-selected="{{ $item['chart_of_account_id'] }}">{!! $chartOfAccountsOptions !!}</select>
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" step="any" name="debit[]"
                                                               class="form-control debit text-right"
                                                               onchange="debitChanged($(this))"
                                                               onkeyup="debitChanged($(this))"
                                                               onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187"
                                                               value="{{ isset($item['debit']) && $item['debit'] > 0 ? $item['debit'] : 0 }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" step="any" name="credit[]"
                                                               class="form-control credit text-right"
                                                               onchange="creditChanged($(this))"
                                                               onkeyup="creditChanged($(this))"
                                                               onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187"
                                                               value="{{ isset($item['credit']) && $item['credit'] > 0 ? $item['credit'] : 0 }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="narration[]"
                                                               class="form-control narration"
                                                               value="{{$item['narration']}}">
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
                                                  class="form-control rounded">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label for="files"><strong>Attachments:</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="file" name="files[]" id="files" multiple class="form-control rounded"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 text-right">
                                    <a class="btn btn-dark btn-md" href="{{ url('accounting/entries') }}"><i
                                                class="la la-times"></i>&nbsp;Cancel</a>
                                    <button type="submit" class="btn btn-success btn-md entry-button"><i
                                                class="la la-save"></i>&nbsp;Save Entry
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="las la-upload"></i>&nbsp;Upload Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="las la-times-circle mt-1"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('accounting.entries.upload.excel') }}" method="post"
                          enctype="multipart/form-data" id="excel-upload-form" onsubmit="return false;">
                        @csrf
                        <div class="form-group">
                            <a href="{{ asset('samples/entries-excel-sample.xlsx') }}"
                               download="Entries Excel Sample.xlsx">Download Sample Excel file</a>
                        </div>
                        <div class="form-group">
                            <label for="excel_file"><strong>Choose Excel File <span
                                            class="text-danger">*</span></strong></label>
                            <input type="file" name="excel_file" id="excel_file" class="form-control">
                            <input type="hidden" name="company_id" id="company_id" value="{{$company->id}}">
                            <input type="hidden" name="entry_type_id" id="entry_type_id" value="{{$entryType->id}}">
                        </div>
                        <button type="button" class="btn btn-success btn-md excel-upload-button float-right"
                                onclick="uploadExcelFile()"><i class="las la-uplaod"></i>&nbsp;Upload Entries Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="coa" style="display: none">
        {!! $chartOfAccountsOptions !!}
    </div>
@endsection
@section('page-script')
    <script type="text/javascript">
        var session_items = parseInt("{{ isset(session()->get('entries-items')[0]) ? count(session()->get('entries-items')) : 0 }}");
        if(session_items > 0){
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
                '<select name="chart_of_account_id[]" class="form-control chart_of_account_id select2" onchange="Entries()">'+($('#coa').html())+'</select>' +
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

            if (parseFloat(total_debit).toFixed(2) == parseFloat(total_credit).toFixed(2)) {
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
                                    processData: false,
                                    contentType: false,
                                    data: new FormData(form[0]),
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

        checkIfPO();

        function checkIfPO() {
            if ($('#form_type').find(':selected').val() == 'with_po') {
                $('.without-po').hide();
                $('.with-po').show();
                if ($('.entries').find('tr').length > 2) {
                    $.each($('.entries').find('tr'), function (index, val) {
                        if ($(this).index() > 1) {
                            $(this).remove();
                        }
                    });
                } else if ($('.entries').find('tr').length < 2) {
                    for (var i = $('.entries').find('tr').length; i < 2; i++) {
                        add();
                    }
                }
            } else {
                $('.with-po').hide();
                $('.without-po').show();
            }

            printAmount();
        }

        printAmount();
        function printAmount() {
            if ($('#form_type').find(':selected').val() == 'with_po') {
                var purchaseOrder = $('#purchase_order_id').find(':selected');

                var advance_account = purchaseOrder.attr('data-advance-account');
                var payable_account = purchaseOrder.attr('data-payable-account');
                var credit_account = purchaseOrder.attr('data-credit-account');
                var supplier_id = purchaseOrder.attr('data-supplier-id');
                var due = purchaseOrder.attr('data-due-amount');

                var is_advance = $('#is_advance').val();

                $('#supplier_id').val(supplier_id).select2();

                if (is_advance == 1) {
                    $('.entries').find('.chart_of_account_id').eq(1).select2().val(advance_account).trigger("change");
                } else {
                    $('.entries').find('.chart_of_account_id').eq(1).select2().val(payable_account).trigger("change");
                }

                $('.entries').find('.chart_of_account_id').eq(2).select2().val(credit_account).trigger("change");

                $('#currency_id').val($('#purchase_order_id').find(':selected').attr('data-currency')).trigger("change");
                $('#currency-choose').hide();

                $('#pay_amount').attr('min', 0).attr('max', due).val(due);
                $('#pay-amount-label').html('Pay Amount (' + $('#purchase_order_id').find(':selected').attr('data-currency-text') + ')');
            } else {
                $('#currency-choose').show();
                $('#currency_id').val($('#purchase_order_id').find(':selected').attr('data-currency') != undefined ? $('#purchase_order_id').find(':selected').attr('data-currency') : "{{ $systemCurrency->id }}").trigger("change");
                $('#pay_amount').attr('min', 0).attr('max', 0).val(0);
                $('#pay-amount-label').html('Pay Amount (' + ($('#purchase_order_id').find(':selected').attr('data-currency-text') != undefined ? $('#purchase_order_id').find(':selected').attr('data-currency-text') : "{{ $systemCurrency->code.'&nbsp;|&nbsp;'.$systemCurrency->name }}") + ')');
            }

            printPOAmount();
        }

        function getPurchaseOrders() {
            $.ajax({
                url: "{{ url('accounting/entries') }}/" + $('#supplier_id').val() + "?get-purchase-orders",
                type: 'GET',
                dataType: 'json',
                data: {},
            })
                .done(function (response) {
                    $('#purchase_order_id').html(response.orders).select2();

                    printAmount();
                });
        }

        checkAdvance();

        function checkAdvance() {
            var is_advance = $('#is_advance').val();
            // if(is_advance == 1){
            //     $('.is-advance').show();
            // }else{
            //     $('.is-advance').hide();
            // }

            printAmount();
        }

        function printPOAmount() {
            if(session_items <= 0){
                var pay_amount = parseFloat($('#pay_amount').val() != "" ? $('#pay_amount').val() : 0).toFixed(2);
                $('.entries').find(':nth-child(1)').find('.credit').val(pay_amount);
                $('.entries').find(':nth-child(2)').find('.debit').val(pay_amount);

                calculation();
            }
        }

        function uploadExcelFile() {
            var excel_form = $('#excel-upload-form');
            var excel_button = $('.excel-upload-button');
            var excel_button_content = excel_button.html();

            excel_button.prop('disabled', true).html("<i class='las la-spinner la-spin'></i>&nbsp;Please wait...");

            $.ajax({
                url: excel_form.attr('action'),
                type: excel_form.attr('method'),
                dataType: 'json',
                processData: false,
                contentType: false,
                data: new FormData(excel_form[0]),
            })
                .done(function (response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }

                    excel_button.prop('disabled', false).html(excel_button_content);
                })
                .fail(function (response) {
                    var errors = '<ul class="">';
                    $.each(response.responseJSON.errors, function (index, val) {
                        errors += '<li class="text-white">' + val[0] + '</li>';
                    });
                    errors += '</ul>';
                    toastr.error(errors);

                    excel_button.prop('disabled', false).html(excel_button_content);
                });
        }
    </script>
@endsection