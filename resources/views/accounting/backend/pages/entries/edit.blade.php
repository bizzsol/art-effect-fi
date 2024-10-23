@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
    .select2-container--default .select2-results__option[aria-disabled=true] {
        color: #000 !important;
        font-weight:  bold !important;
    }
    .select2-container{
        width:  100% !important;
    }
    tr td{
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
                    <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3">
                    <form action="{{ route('accounting.entries.update', $entry->id) }}?type={{ request()->get('type')  }}" method="post" accept-charset="utf-8" class="entry-form">
                    @csrf
                    @method('PUT')
                        <div class="row pr-3">
                            <div class="col-md-3">
                                <label for="number"><strong>{{ __('Reference') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="number" id="number" value="{{ $entry->number }}" class="form-control rounded">
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
                                                            <option value="{{ $currency->id }}" {{ $entry->exchangeRate->currency_id == $currency->id ? 'selected' : '' }}>
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
                                    <input type="datetime-local" name="datetime" id="datetime" value="{{ date('Y-m-d H:i:s', strtotime($entry->date.' '.$entry->time)) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="fiscal_year_id"><strong>{{ __('Fiscal Year') }}:<span
                                                class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="fiscal_year_id" id="fiscal_year_id" class="form-control rounded">
                                        <option value="{{ $fiscalYear->id }}" {{ $entry->fiscal_year_id == $fiscalYear->id ? 'selected' : '' }}>{{ $fiscalYear->title }}
                                            &nbsp;|&nbsp;{{ date('d-M-y', strtotime($fiscalYear->start)).' to '.date('d-M-y', strtotime($fiscalYear->end)) }}
                                        </option>

                                        @if(isset($fiscalYears[0]))
                                        @foreach($fiscalYears as $fiscalYear)
                                        <option value="{{ $fiscalYear->id }}" {{ $entry->fiscal_year_id == $fiscalYear->id ? 'selected' : '' }}>{{ $fiscalYear->title }}
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
                                            <th style="width: 5%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="entries">
                                        @if($entry->items->count() > 0)
                                        @foreach($entry->items as $key => $item)
                                        <tr>
                                            <td>
                                               <select name="cost_centre_id[]" class="form-control cost_centre_id select2 select-cost-centre" data-selected-cost-centre="{{ $item->cost_centre_id }}"></select>
                                            </td>
                                            <td>
                                                <div class="row ledger-parent">
                                                    <div class="col-md-12 ledger">
                                                        <select name="chart_of_account_id[]" class="form-control chart_of_account_id select2 select-account" data-selected-account="{{ $item->chart_of_account_id }}" onchange="getSubLedgers($(this));Entries();"></select>
                                                    </div>
                                                    <div class="col-md-12 sub-ledger mt-2" style="display: none">
                                                        <select name="sub_ledgers[]" class="form-control sub-ledger-select2" data-selected="{{ $item->sub_ledger_id }}">
                                                            <option value="{{ null }}">Without Sub-Ledger</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" step="any" min="0" name="debit[]" class="form-control debit text-right" @if($item->debit_credit == "D") value="{{ $item->amount }}" @else value="0" @endif onchange="debitChanged($(this))" onkeyup="debitChanged($(this))" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187">
                                            </td>
                                            <td>
                                                <input type="number" step="any" min="0" name="credit[]" class="form-control credit text-right" @if($item->debit_credit == "C") value="{{ $item->amount }}" @else value="0" @endif onchange="creditChanged($(this))" onkeyup="creditChanged($(this))" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187" value="0">
                                            </td>
                                             <td>
                                                <input type="text" name="narration[]" class="form-control narration" value="{{ $item->narration }}">
                                            </td>
                                            <td class="text-center">
                                                <a onclick="remove($(this))"><i class="text-danger la la-trash" style="transform: scale(2, 2)"></i></a>
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
                                            <td style="font-weight: bold;padding-right: 28px !important" class="text-right total-debit"></td>
                                            <td style="font-weight: bold;padding-right: 28px !important" class="text-right total-credit"></td>
                                            <td></td>
                                            <td class="text-center">
                                                <a onclick="add();"><i class="text-success las la-plus-circle" style="transform: scale(2, 2)"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <h5><strong>Difference</strong></h5>
                                            </td>
                                            <td style="font-weight: bold;padding-right: 28px !important" class="text-right debit-difference"></td>
                                            <td style="font-weight: bold;padding-right: 28px !important" class="text-right credit-difference"></td>
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
                                    <textarea name="notes" id="notes" class="form-control rounded">{{ old('notes', $entry->notes) }}</textarea>
                                </div>
                            </div>
                        </div>

                        @if($entry->attachments->count() > 0)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="files"><strong>Uploaded Attachments:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    &nbsp;
                                    @foreach($entry->attachments as $attachment)
                                    <label>
                                        <input type="checkbox" name="old_files[]" value="{{ $attachment->id }}" checked style="transform: scale(1.5, 1.5);cursor: pointer">&nbsp;&nbsp;<a href="{{ asset($attachment->path) }}" target="_blank" style="text-decoration: none">{{ $attachment->name }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ $attachment->type}}&nbsp;&nbsp;|&nbsp;&nbsp;{{ formatBytes($attachment->size) }}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="files"><strong>New Attachments:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="file" name="files[]" id="files" multiple class="form-control rounded"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/entries') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md btn-submit"><i class="la la-save"></i>&nbsp;Save Entry</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="coa" style="display: none">
    {!! $chartOfAccountsOptions !!}
</div>
<div id="cc" style="display: none">
    {!! $costCentres !!}
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    setTimeout(function(){
        var cc = $('#cc').html();
        var coa = $('#coa').html();
        $.each($('.select-cost-centre'), function(index, val) {
            $(this).html(cc).val($(this).attr('data-selected-cost-centre')).trigger('change');
        });

        $.each($('.select-account'), function(index, val) {
            $(this).html(coa).val($(this).attr('data-selected-account')).trigger('change');
        });
    }, 1000);

    Entries();
    function add() {
        $('.entries').append('<tr>'+
                                '<td>'+
                                   '<select name="cost_centre_id[]" class="form-control cost_centre_id select2">'+($('#cc').html())+'</select>'+
                                '</td>'+
                                '<td>' +
                                    '<div class="row ledger-parent">' +
                                        '<div class="col-md-12 ledger">' +
                                            '<select name="chart_of_account_id[]" class="form-control chart_of_account_id select2" onchange="getSubLedgers($(this));Entries();">'+($('#coa').html())+'</select>' +
                                        '</div>' +
                                        '<div class="col-md-12 sub-ledger mt-2" style="display: none">'+
                                            '<select name="sub_ledgers[]" class="form-control sub-ledger-select2" data-selected="{{ null }}"></select>'+
                                        '</div>'+
                                    '</div>' +
                                '</td>' +
                                '<td>'+
                                    '<input type="number" step="any" min="0" name="debit[]" class="form-control debit text-right" onchange="debitChanged($(this))" onkeyup="debitChanged($(this))" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187" value="0">'+
                                '</td>'+
                                '<td>'+
                                    '<input type="number" step="any" min="0" name="credit[]" class="form-control credit text-right" onchange="creditChanged($(this))" onkeyup="creditChanged($(this))" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187" value="0">'+
                                '</td>'+
                                '<td>'+
                                    '<input type="text" name="narration[]" class="form-control narration">'+
                                '</td>'+
                                '<td class="text-center">'+
                                    '<a onclick="remove($(this))"><i class="text-danger la la-trash" style="transform: scale(2, 2)"></i></a>'+
                                '</td>'+
                            '</tr>');
        getSubLedgers($('.entries tr:last-child').find('.chart_of_account_id'));
        Entries();
    }

    function getSubLedgers(element) {
        var subLedgers = '<option value="{{ null }}">Without Sub-Ledger</option>';
        element.parent().parent().find('.sub-ledger-select2').html(subLedgers);
        element.parent().parent().find('.sub-ledger').hide();

        $.ajax({
            url: "{{ url('accounting/entries/create?get-sub-ledgers') }}&chart_of_account_id="+element.val(),
            type: 'GET',
            dataType: 'json',
            data: {},
        })
        .done(function(response) {
            if(response.count > 0){
                $.each(response.sub_ledgers, function(index, sub_ledger) {
                    subLedgers += '<option value="'+sub_ledger.id+'" '+(element.parent().parent().find('.sub-ledger-select2').attr('data-selected') == sub_ledger.id ? 'selected' : '')+'>['+sub_ledger.code+'] '+sub_ledger.name+'</option>';
                });

                element.parent().parent().find('.sub-ledger-select2').html(subLedgers);
                element.parent().parent().find('.sub-ledger').show();
            }
        });
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
                                processData: false,
                                contentType: false,
                                data: new FormData(form[0]),
                            })
                            .done(function (response) {
                                if (response.success) {
                                    window.open("{{ url('accounting/entries') }}", "_parent");
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