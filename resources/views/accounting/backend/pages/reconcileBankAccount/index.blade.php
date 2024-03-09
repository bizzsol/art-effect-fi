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
                <li class="active">{{__($title)}} </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-3" style="padding-bottom: 0 !important;">
                <form action="{{ url('accounting/reconcile-bank-account') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="reconcilliation-form">
                @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-3">
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bank_account_id_"><strong>Bank Account</strong></label>
                                                <select name="bank_account_id" id="bank_account_id_" class="form-control rounded">
                                                    <option value="0">Choose Bank Account</option>
                                                    @foreach($bankAccounts as $key => $ba)
                                                    <option value="{{ $ba->id }}" {{ request()->get('bank_account_id') == $ba->id ? 'selected' : '' }}>{{ $ba->name }} ({{$ba->number}}) ({{ $ba->currency->code }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="date"><strong>Reconcilliation Date</strong></label>
                                                <input type="date" name="date" id="date" value="{{ $date }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2 pt-2 pl-0">
                                            <div class="form-group mt-4">
                                                <button type="button" class="btn btn-block btn-md btn-success" onclick="window.open('{{ url('accounting/reconcile-bank-account') }}?bank_account_id='+$('#bank_account_id_').val()+'&date='+$('#date').val(), '_parent')"><i class="la la-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="row">
                                        @if(isset($bankAccount->id))
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"><strong>Currency</strong></label>
                                                <h5><strong>{{ $bankAccount->currency->code }}</strong></h5>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="code"><strong>Reference</strong></label>
                                                <h5><strong>{{ $code }}</strong></h5>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="code"><strong>Last Reconciled Balance</strong></label>
                                                <h5><strong>{{ $balance }}</strong></h5>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(isset($bankAccount->id))
                            <div class="row mb-3">
                                <div class="col-md-9">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="bank_interest_earned" class="text-success"><strong>Bank Interest</strong></label>
                                                        <input type="number" name="bank_interest_earned" id="bank_interest_earned" value="0.00" step="any" min="0" class="form-control text-right text-success" style="font-weight: bold" onchange="calculateReconcilliation()" onkeyup="calculateReconcilliation()">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="bank_interest_narration" class="text-success"><strong>Narration</strong></label>
                                                        <input type="text" name="bank_interest_narration" id="bank_interest_narration" class="form-control text-success">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="bank_charges" class="text-danger"><strong>Bank Charges</strong></label>
                                                        <input type="number" name="bank_charges" id="bank_charges" class="form-control text-right text-danger" value="0.00" step="any" min="0" style="font-weight: bold" onchange="calculateReconcilliation()" onkeyup="calculateReconcilliation()">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="bank_charges_narration" class="text-danger"><strong>Narration</strong></label>
                                                        <input type="text" name="bank_charges_narration" id="bank_charges_narration" class="form-control text-danger">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="file"><strong>Upload Bank Statement (.xlsx)</strong></label>
                                        <input type="file" name="file" id="file" class="form-control">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if(isset($bankAccount->id))
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                       <th style="width: 5%">Date</th>
                                       <th style="width: 7.5%">Receipt Number</th>
                                       <th style="width: 3.5%">Type</th>
                                       <th style="width: 7.5%">Source</th>
                                       <th style="width: 15.5%">Narration</th>
                                       <th style="width: 5%">Currency</th>
                                       <th style="width: 7%">Debit</th>
                                       <th style="width: 7%">Credit</th>
                                       <th style="width: 7%">Status</th>
                                   </tr>
                               </thead>
                               <tbody>
                                @php
                                    $total_debit = 0;
                                    $total_credit = 0;
                                @endphp
                                @if(isset($entries[0]))
                                @foreach($entries as $key => $entry)
                                @php
                                    $debit = $entry->items->whereIn('chart_of_account_id', $accounts)->where('debit_credit', 'D')->whereNull('reconciliation_date')->sum('amount');
                                    $credit = $entry->items->whereIn('chart_of_account_id', $accounts)->where('debit_credit', 'C')->whereNull('reconciliation_date')->sum('amount');
                                    $total_debit += $debit;
                                    $total_credit += $credit;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $entry->date }}

                                        <input type="hidden" name="debits[{{ $entry->id }}]" value="{{ $debit }}">
                                        <input type="hidden" name="credits[{{ $entry->id }}]" value="{{ $credit }}">
                                        <input type="hidden" name="entries[]" value="{{ $entry->id }}" class="entries" data-debit="{{ $debit }}" data-credit="{{ $credit }}">
                                    </td>
                                    <td>{{ $entry->number }}</td>
                                    <td class="text-center">
                                        {{ $entry->entryType ? $entry->entryType->name : '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $entry->purchaseOrder ? ucwords(str_replace('-', ' ', $entry->purchaseOrder->type)) : '' }}
                                    </td>
                                    <td>{{ $entry->notes }}</td>
                                    <td class="text-center">{{ $entry->exchangeRate->currency->code }}</td>
                                    <td class="text-right debit">
                                        {{ $debit  }}
                                    </td>
                                    <td class="text-right credit">
                                        {{ $credit  }}
                                    </td>
                                    <td class="text-center">
                                        @include('accounting.backend.pages.approval-stage',[
                                            'object' => $entry
                                        ])
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right"><strong id="total-debit">{{ $total_debit }}</strong></td>
                                    <td class="text-right"><strong id="total-credit">{{ $total_credit }}</strong></td>
                                    <td></td>
                                </tr>
                               </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3 offset-md-6 pt-2">
                                    <select name="cost_centre_id" class="form-control cost_centre_id select2">
                                        {!! $costCentres !!}
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button class="btn mt-2 btn-block btn-md btn-success pull-right mb-4" type="submit" id="reconcilliation-button"><i class="la la-check"></i>&nbsp;Process Bank Reconcilliation</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {
        calculateReconcilliation();

        var button = $('#reconcilliation-button');
        var form = $('#reconcilliation-form');
        form.on('submit', function(e){
            e.preventDefault();

            button.html('<i class="las la-stopwatch"></i>&nbsp;Please wait...').prop('disabled', true);
            var formData = new FormData(form[0]);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
            })
            .done(function(response) {
                if(response.success){
                    location.reload();
                }else{
                    swal({
                        icon: 'error',
                        text: response.message,
                        button: false
                    });
                    setTimeout(()=>{
                        swal.close();
                    }, 3000);
                }

                button.html('<i class="la la-check"></i>&nbsp;Process Bank Reconcilliation').prop('disabled', false);
            })
            .fail(function(response){
                $.each(response.responseJSON.errors, function(index, val) {
                    toastr.error(val[0]);
                });

                button.html('<i class="la la-check"></i>&nbsp;Process Bank Reconcilliation').prop('disabled', false);
            });
        });
    });

    function calculateReconcilliation() {
        var book_balance = parseFloat($('#book_balance').text());
        var bank_interest_earned = parseFloat($('#bank_interest_earned').val() != '' && $('#bank_interest_earned').val() > 0 ? $('#bank_interest_earned').val() : 0);
        var bank_balance = parseFloat($('#bank_balance').val() != '' && $('#bank_balance').val() > 0 ? $('#bank_balance').val() : 0);
        var bank_charges = parseFloat($('#bank_charges').val() != '' && $('#bank_charges').val() > 0 ? $('#bank_charges').val() : 0);

        var rec_debit = 0;
        var rec_credit = 0;
        $.each($('.entries'), function(index, val) {
            rec_debit += parseFloat($(this).attr('data-debit'));
            rec_credit += parseFloat($(this).attr('data-credit'));
        });

        var rec_amount = parseFloat(rec_debit-rec_credit);
        var diff = parseFloat(((book_balance+bank_interest_earned+rec_amount)-bank_balance)-bank_charges);

        $('#bank_interest_earned_view').html(bank_interest_earned.toFixed(2));
        $('#reconciled-amount').html(rec_amount.toFixed(2));
        $('#bank-charges').html(bank_charges.toFixed(2));
        $('#difference').html(diff.toFixed(2));
    }
</script>
@endsection