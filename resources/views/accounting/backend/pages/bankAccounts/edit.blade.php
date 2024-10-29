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

        .bordered {
            border: 1px #ccc solid
        }

        .floating-title {
            position: absolute;
            top: -13px;
            left: 15px;
            background: white;
            padding: 0px 5px 5px 5px;
            font-weight: 500;
        }

        .card-body {
            padding-top: 20px !important;
            padding-bottom: 0px !important;
        }

        .label {
            font-weight: bold !important;
        }

        .tab-pane {
            padding-top: 15px;
        }

        .select2-container {
            width: 100% !important;
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
                        <form action="{{ route('accounting.bank-accounts.update', $bankAccount->id) }}" method="post"
                              accept-charset="utf-8">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body bordered">
                                            <h5 class="floating-title">Bank Account Information</h5>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="code"><strong>{{ __('Code') }}:<span
                                                                    class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <input type="text" name="code" id="code"
                                                               value="{{ $bankAccount->code }}" readonly
                                                               class="form-control rounded">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="type"><strong>{{ __('Account Type') }}:<span
                                                                    class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="bank_account_type_id" id="bank_account_type_id"
                                                                class="form-control rounded">
                                                            @foreach($bankAccountTypes as $key => $type)
                                                                <option value="{{ $type->id }}" {{ $bankAccount->bank_account_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}
                                                                    ({{ $type->code }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="currency_id"><strong>{{ __('Currency') }}:<span
                                                                    class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="currency_id" id="currency_id"
                                                                class="form-control rounded">
                                                            @if(isset($currencyTypes[0]))
                                                                @foreach($currencyTypes as $key => $currencyType)
                                                                    <optgroup label="{{ $currencyType->name }}">
                                                                        @if($currencyType->currencies->count() > 0)
                                                                            @foreach($currencyType->currencies as $key => $currency)
                                                                                <option value="{{ $currency->id }}" {{ $bankAccount->currency_id == $currency->id ? 'selected' : '' }}>
                                                                                    &nbsp;&nbsp;{{ $currency->name }}
                                                                                    ({{ $currency->code }}
                                                                                    &nbsp;|&nbsp;{{ $currency->symbol }}
                                                                                    )
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </optgroup>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-5">
                                                    <label for="bank_name"><strong>{{ __('Select Branch') }}:<span
                                                                    class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="bank_branch_id" id="bank_branch_id"
                                                                class="form-control rounded">
                                                            @if(isset($banks[0]))
                                                                @foreach($banks as $key => $bank)
                                                                    <optgroup label="{{ $bank->name }}">
                                                                        @if($bank->branches->count() > 0)
                                                                            @foreach($bank->branches as $key => $branch)
                                                                                <option value="{{ $branch->id }}" {{ $bankAccount->bank_branch_id == $branch->id ? 'selected' : '' }}>
                                                                                    &nbsp;&nbsp;{{ $branch->name }}
                                                                                    ({{ $branch->code }})
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
                                                    <label for="number"><strong>{{ __('Account Number') }}:<span
                                                                    class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <input type="text" name="number" id="number"
                                                               value="{{ old('number', $bankAccount->number) }}"
                                                               class="form-control rounded">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="name"><strong>{{ __('Account Name') }}:<span
                                                                    class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <input type="text" name="name" id="name"
                                                               value="{{ old('name', $bankAccount->name) }}"
                                                               class="form-control rounded">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="company_id"><strong>{{ __('Company') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="company_id" id="company_id" 
                                                        class="form-control company_id select2" onchange="getCOA()">
                                                            @if(isset($companies[0]))
                                                            @foreach($companies as $key => $company)
                                                                <option value="{{ $company->id }}" {{ $bankAccount->chartOfAccount->companies->first()->company_id == $company->id ? 'selected' : '' }}>{{ $company->code }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="chart_of_account_id"><strong>{{ __('Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="chart_of_account_id" id="chart_of_account_id" 
                                                        class="form-control chart_of_account_id select2">
                                                            {!! $chartOfAccountsOptions !!}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 pt-4">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <a class="btn btn-dark btn-md btn-block mt-2" href="{{ url('accounting/bank-accounts') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <button type="submit" class="btn btn-success btn-md btn-block mt-2"><i class="la la-save"></i>&nbsp;Update Bank Accounts</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
    function getCOA() {
        $('#chart_of_account_id').html('<option value="{{ null }}">Please wait....</option>');
        $.ajax({
            url: "{{ url('accounting/bank-accounts/create') }}?get-coa&company_id="+$('#company_id').val()+"&chosen={{ $bankAccount->chart_of_account_id }}",
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#chart_of_account_id').html(response);
        });
    }
</script>
@endsection