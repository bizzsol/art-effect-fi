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
                        <form action="{{ route('accounting.multi-currency-transaction.store') }}"
                              method="post" accept-charset="utf-8" class="entry-form">
                            @csrf
                            <div class="row pr-3">
                                <div class="col-md-4">
                                    <label for="company_id"><strong>Company:<span class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="company_id" id="company_id" class="form-control rounded">
                                            @if(isset($companies[0]))
                                                @foreach($companies as $key => $c)
                                                    <option value="{{ $c->id }}" {{ request()->get('company_id') == $c->id ? 'selected' : '' }}>
                                                        {{ $c->code }} | {{ $c->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="entry_type_id"><strong>Entry Type:<span class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="entry_type_id" id="entry_type_id" class="form-control rounded">
                                            @if(isset($entryTypes[0]))
                                                @foreach($entryTypes as $key => $et)
                                                    <option value="{{ $et->id }}" {{ request()->get('entry_type_id') == $et->id ? 'selected' : '' }}>{{ $et->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 pt-4">
                                    <button type="button" class="btn btn-success btn-sm btn-md btn-block mt-2" onclick="getForm()"><i class="las la-search"></i>&nbsp;Get Transaction Form</button>
                                </div>
                            </div>
                            @if(isset($company->id) && isset($entryType->id))
                                <div class="row pr-3">
                                    <div class="col-md-4">
                                        <label for="number"><strong>{{ __('Reference') }}:</strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="number" id="number" value="{{ old('number') }}"
                                                   class="form-control rounded">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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
                                                <th style="width: 20%">Cost Centre</th>
                                                <th style="width: 30%">Ledger</th>
                                                <th style="width: 10%">Currency</th>
                                                <th style="width: 15%">Debit</th>
                                                <th style="width: 15%">Credit</th>
                                                <th style="width: 10%">Narration</th>
                                                <th style="width: 5%">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody class="entries">
                                            
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="6">
                                                    
                                                </td>
                                                <td class="text-center">
                                                    <a onclick="add();" class="action-buttons"><i
                                                                class="text-success las la-plus-circle"
                                                                style="transform: scale(2, 2)"></i></a>
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
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <a class="btn btn-dark btn-md" href="{{ url('accounting/entries') }}"><i
                                                    class="la la-times"></i>&nbsp;Cancel</a>
                                        <button type="submit" class="btn btn-success btn-md entry-button"><i
                                                    class="la la-save"></i>&nbsp;Save Entry
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

    <div id="cc" style="display: none">
        {!! $costCentres !!}
    </div>
    <div id="coa" style="display: none">
        {!! $chartOfAccountsOptions !!}
    </div>
    <div id="ct" style="display: none">
        {!! $currencyTypes !!}
    </div>
@endsection
@section('page-script')
    <script type="text/javascript">
        function getForm() {
            window.open("{{ url('accounting/multi-currency-transaction') }}?company_id="+$('#company_id').val()+"&entry_type_id="+$('#entry_type_id').val(), "_parent");
        }

        var check = "{{ isset($company->id) && isset($entryType->id) ? 1 : 0 }}";
        if(check == 1){
            add();
            add();
        }

        function add() {
            $('.entries').append('<tr>' +
                '<td>' +
                    '<select name="cost_centre_id[]" class="form-control cost_centre_id select2">'+($('#cc').html())+'</select>' +
                '</td>' +
                '<td>' +
                    '<select name="chart_of_account_id[]" class="form-control chart_of_account_id select2">'+($('#coa').html())+'</select>' +
                '</td>' +
                '<td>' +
                    '<select name="currency_id[]" class="form-control currency_id select2">'+($('#ct').html())+'</select>' +
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

            $('.cost_centre_id').select2();
            $('.chart_of_account_id').select2();
            $('.currency_id').select2();
        }

        function remove(element) {
            element.parent().parent().remove();
        }

        function debitChanged(element) {
            var debit = (element.val() != "" ? parseFloat(element.val()) : parseFloat(0));
            if (debit > 0) {
                element.parent().parent().find('.credit').val(0);
            }
        }

        function creditChanged(element) {
            var credit = (element.val() != "" ? parseFloat(element.val()) : parseFloat(0));
            if (credit > 0) {
                element.parent().parent().find('.debit').val(0);
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
                                            title: 'Transaction Details',
                                            content: response.entries,
                                            animation: 'scale',
                                            columnClass: 'col-md-12',
                                            closeAnimation: 'scale',
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