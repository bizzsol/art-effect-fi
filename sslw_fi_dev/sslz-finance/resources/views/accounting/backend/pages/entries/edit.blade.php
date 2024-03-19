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
                                <label for="code"><strong>{{ __('Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="code" id="code" value="{{ $entry->code }}" readonly class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="number"><strong>{{ __('Receipt Number') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="number" id="number" value="{{ old('number', $entry->number) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="date"><strong>{{ __('Date') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="date" name="date" id="date" value="{{ old('date', $entry->date) }}" min="{{ $fiscalYear->start }}" max="{{ $fiscalYear->end }}" class="form-control rounded">
                                </div>
                            </div>
                            {{-- <div class="col-md-2">
                                <label for="tag_id"><strong>{{ __('Tag') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="tag_id" id="tag_id" class="form-control rounded">
                                        @if(isset($tags[0]))
                                        @foreach($tags as $key => $tag)
                                        <option value="{{ $tag->id }}" {{ $tag->id == $entry->tag_id ? 'selected' : '' }}>{{ $tag->title }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-4">
                                <label for="fiscal_year_id"><strong>{{ __('Fiscal Year') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="fiscal_year_id" id="fiscal_year_id" class="form-control rounded">
                                        <option value="{{ $entry->fiscalYear->id }}">{{ $entry->fiscalYear->title }}&nbsp;|&nbsp;{{ date('d-M-y', strtotime($entry->fiscalYear->start)).' to '.date('d-M-y', strtotime($entry->fiscalYear->end)) }})</option>
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
                                            <th style="width: 15%">Debit</th>
                                            <th style="width: 15%">Credit</th>
                                            <th style="width: 10%">Narration</th>
                                            <th style="width: 10%">Balance</th>
                                            <th style="width: 15%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="entries">
                                        @if($entry->items->count() > 0)
                                        @foreach($entry->items as $key => $item)
                                        <tr>
                                            <td>
                                               <select name="cost_centre_id[]" class="form-control cost_centre_id select2 select-cost-centre" data-selected-cost-centre="{{ $item->cost_centre_id }}">
                                                    {!! $costCentres !!}
                                               </select>
                                            </td>
                                            <td>
                                                <select name="chart_of_account_id[]" class="form-control chart_of_account_id select2 select-account" data-selected-account="{{ $item->chart_of_account_id }}" onchange="Entries()">{!! $chartOfAccountsOptions !!}</select>
                                            </td>
                                            <td>
                                                <input type="number" name="debit[]" class="form-control debit text-right" @if($item->debit_credit == "D") value="{{ $item->amount }}" @else value="0" @endif onchange="Entries()" onkeyup="Entries()" onclick="debitClicked($(this))">
                                            </td>
                                            <td>
                                                <input type="number" name="credit[]" class="form-control credit text-right" @if($item->debit_credit == "C") value="{{ $item->amount }}" @else value="0" @endif onchange="Entries()" onkeyup="Entries()" onclick="creditClicked($(this))">
                                            </td>
                                             <td>
                                                <input type="text" name="narration[]" class="form-control narration" value="{{ $item->narration }}">
                                            </td>
                                            <td class="text-right closing-balance"></td>
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
@endsection
@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {
        $.each($('.select-cost-centre'), function(index, val) {
            $(this).val($(this).attr('data-selected-cost-centre')).trigger('change');
        });

        $.each($('.select-account'), function(index, val) {
            $(this).val($(this).attr('data-selected-account')).trigger('change');
        });
    });

    Entries();
    function add() {
        $('.entries').append('<tr>'+
                                '<td>'+
                                   '<select name="cost_centre_id[]" class="form-control cost_centre_id select2">{!! $costCentres !!}</select>'+
                                '</td>'+
                                '<td>'+
                                    '<select name="chart_of_account_id[]" class="form-control chart_of_account_id select2" onchange="Entries()">{!! $chartOfAccountsOptions !!}</select>'+
                                '</td>'+
                                '<td>'+
                                    '<input type="number" name="debit[]" class="form-control debit text-right" onchange="Entries()" onkeyup="Entries()" onclick="debitClicked($(this))" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187" value="0">'+
                                '</td>'+
                                '<td>'+
                                    '<input type="number" name="credit[]" class="form-control credit text-right" onchange="Entries()" onkeyup="Entries()" onclick="creditClicked($(this))" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187" value="0">'+
                                '</td>'+
                                '<td>'+
                                    '<input type="text" name="narration[]" class="form-control narration">'+
                                '</td>'+
                                '<td class="text-right closing-balance"></td>'+
                                '<td class="text-center">'+
                                    '<a onclick="remove($(this))"><i class="text-danger la la-trash" style="transform: scale(2, 2)"></i></a>'+
                                '</td>'+
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

        $.each($('.entries').find('tr'), function(index, tr) {
            $(this).find('.closing-balance').html($(this).find('.chart_of_account_id :selected').attr('data-closing-balance'));
        });

        calculation();
    }

    function debitClicked(element) {
        element.parent().parent().find('.credit').val(0);
        calculation();
    }

    function creditClicked(element) {
        element.parent().parent().find('.debit').val(0);
        calculation();
    }

    function calculation() {
        var total_debit = 0;
        var total_credit = 0;

        $.each($('.debit'), function(index, val) {
            total_debit += parseFloat($(this).val());
        });

        $.each($('.credit'), function(index, val) {
            total_credit += parseFloat($(this).val());
        });

        $('.total-debit').html(total_debit.toFixed(2));
        $('.total-credit').html(total_credit.toFixed(2));

        if(total_debit == total_credit){
            $('.total-debit').removeClass('bg-danger').addClass('bg-success');
            $('.total-credit').removeClass('bg-danger').addClass('bg-success');
            $('.debit-difference').html('-');
            $('.credit-difference').html('');
        }else{
            $('.total-debit').removeClass('bg-success').addClass('bg-danger');
            $('.total-credit').removeClass('bg-success').addClass('bg-danger');
            if(total_debit > total_credit){
                $('.debit-difference').html('');
                $('.credit-difference').html((total_debit-total_credit).toFixed(2));
            }else{
                $('.credit-difference').html('');
                $('.debit-difference').html((total_credit-total_debit).toFixed(2));
            }
        }
    }

    $(document).ready(function() {
        var form = $('.entry-form');
        var button = $('.btn-submit');
        form.on('submit', function(e){
          e.preventDefault();

          button.prop('disabled', true);
          $.ajax({
              url: form.attr('action'),
              type: form.attr('method'),
              dataType: 'json',
              data: form.serializeArray(),
          })
          .done(function(response) {
              if(response.success){
                location.reload();
              }else{
                toastr.error(response.message);
              }

              button.prop('disabled', false);
          })
          .fail(function(response) {
              $.each(response.responseJSON.errors, function(index, error) {
                   toastr.error(error[0]);
              });

              button.prop('disabled', false);
          });
        });
    });
</script>
@endsection