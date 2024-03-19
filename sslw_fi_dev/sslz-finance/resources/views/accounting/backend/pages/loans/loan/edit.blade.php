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
                    <form action="{{ route('accounting.loans.update', $loan->id) }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="loan-form">
                    @csrf
                    @method('PUT')
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="loan_type_id"><strong>{{ __('Loan Type') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="loan_type_id" id="loan_type_id" class="form-control rounded">
                                                @if(isset($loanTypes[0]))
                                                @foreach($loanTypes as $key => $loanType)
                                                    <option value="{{ $loanType->id }}" {{ $loan->loan_type_id == $loanType->id ? 'selected' : '' }}>{{ $loanType->name }}</attribute>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="bank_account_id"><strong>{{ __('Bank Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="bank_account_id" id="bank_account_id" class="form-control rounded">
                                                @if(isset($bankAccounts[0]))
                                                @foreach($bankAccounts as $key => $bankAccount)
                                                    <option value="{{ $bankAccount->id }}" {{ $loan->bank_account_id == $bankAccount->id ? 'selected' : '' }}>{{ $bankAccount->name }} ({{ $bankAccount->number }}) ({{ $bankAccount->currency ? $bankAccount->currency->name : '' }})</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="code"><strong>{{ __('Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="code" id="code" value="{{ $loan->code }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="name"><strong>{{ __('Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ $loan->name }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="cycle"><strong>{{ __('Cycle') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="cycle" id="cycle" class="form-control rounded">
                                                @foreach(loanCycles() as $key => $loanCycle)
                                                    <option value="{{ $key }}" {{ $loan->cycle == $key ? 'selected' : '' }}>{{ $loanCycle['name'] }}</attribute>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="principal"><strong>{{ __('Principal') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="number" name="principal" id="principal" value="{{ $loan->principal }}" class="form-control text-right" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="interest_rate"><strong>{{ __('Interest Rate (%)') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="number" name="interest_rate" id="interest_rate" value="{{ $loan->interest_rate }}" class="form-control text-right" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="number_of_installments"><strong>{{ __('Number of Installments') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="number" name="number_of_installments" id="number_of_installments" value="{{ $loan->number_of_installments }}" class="form-control text-right" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="installment_amount"><strong>{{ __('Installment Amount') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="number" name="installment_amount" id="installment_amount" value="{{ $loan->installment_amount }}" class="form-control text-right" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="first_instalment_date"><strong>{{ __('First Instalment Date') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="date" name="first_instalment_date" id="first_instalment_date" class="form-control" min="{{ date('Y-m-d') }}" value="{{ $loan->first_instalment_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="interest_calculation_starts_from"><small><strong>{{ __('Interest Calculation Starts From') }}</strong></small><strong>:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="date" name="interest_calculation_starts_from" id="interest_calculation_starts_from" class="form-control" min="{{ $loan->interest_calculation_starts_from }}" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="attachments"><strong>{{ __('Attachments') }}:</strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="file" name="attachments[]" multiple id="attachments" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="desc"><strong>{{ __('Description') }}:</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <textarea name="desc" id="desc" class="form-control" style="height: 120px;resize: none">{{ $loan->desc  }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/loans') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md save-button"><i class="la la-save"></i>&nbsp;Update Loans</button>
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
        var form = $('#loan-form');
        var button = form.find('.save-button');
        var buttonContent = button.html();

        form.submit(function(event) {
            event.preventDefault();
            button.prop('disabled', true).html('<i class="las la-spinner"></i>&nbsp;Please wait...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                data: new FormData(this),
                contentType: false,
                processData: false, 
            })
            .done(function(response) {
                button.prop('disabled', false).html(buttonContent);
                if(response.success){
                    location.reload();
                }else{
                    toastr.error(response.message);
                }
            })
            .fail(function(response) {
                button.prop('disabled', false).html(buttonContent);
                $.each(response.responseJSON.errors, function(index, val) {
                    toastr.error(val[0]);
                });
            });
        });
    });
</script>
@endsection