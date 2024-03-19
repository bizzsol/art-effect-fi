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
                    <form action="{{ route('accounting.unrealized-currency-evaluation.update', 0) }}" method="post" accept-charset="utf-8" id="search-form">
                    @csrf
                    @method('PUT')
                        <div class="row pr-3 pt-3">
                            <div class="col-md-12">
                                <label for="asset_ledgers"><strong>Asset Ledgers:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="asset_ledgers[]" id="asset_ledgers" class="form-control rounded" multiple>
                                        @if($ledgers->whereIn('accountGroup.account_class_id', [1])->count() > 0)
                                        @foreach($ledgers->whereIn('accountGroup.account_class_id', [1]) as $key => $ledger)
                                            <option value="{{ $ledger->id }}" {{ in_array($ledger->id, $unrealizedCurrencyEventSettings['asset_ledgers']) ? 'selected' : '' }}>[{{ $ledger->code }}]&nbsp;{{ $ledger->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <label for="liability_ledgers"><strong>Liability Ledgers:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="liability_ledgers[]" id="liability_ledgers" class="form-control rounded" multiple>
                                        @if($ledgers->whereIn('accountGroup.account_class_id', [2, 3])->count() > 0)
                                        @foreach($ledgers->whereIn('accountGroup.account_class_id', [2, 3]) as $key => $ledger)
                                            <option value="{{ $ledger->id }}" {{ in_array($ledger->id, $unrealizedCurrencyEventSettings['liability_ledgers']) ? 'selected' : '' }}>[{{ $ledger->code }}]&nbsp;{{ $ledger->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="run_date"><strong>Run Date:<span class="text-danger">&nbsp;*</span></strong></label>
                                <input type="date" name="run_date" id="run_date" value="{{ $run_date }}" class="form-control" @if($from) min="{{ $from }}" @endif>
                            </div>
                            <div class="col-md-2">
                                <label for="receivables"><strong>All Receivables:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="receivables" id="receivables" class="form-control rounded">
                                        <option value="1" {{ $receivables == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ $receivables == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="payables"><strong>All Payables:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="payables" id="payables" class="form-control rounded">
                                        <option value="1" {{ $payables == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ $payables == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2 pt-4">
                                <button type="submit" class="btn btn-success btn-lg pull-right btn-block mt-2" id="search-button"><i class="la la-search"></i>&nbsp;Run</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3" id="form-view">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {

        var search_form = $('#search-form');
        var search_button = $('#search-button');
        var search_content = search_button.html();

        search_form.submit(function(event) {
            event.preventDefault();

            search_button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);
            $('#form-view').html('<h1 class="text-center"><strong><i class="las la-spinner la-spin"></i>&nbsp;&nbsp;Please wait...</strong></h1>')
            $.ajax({
                url: search_form.attr('action'),
                type: search_form.attr('method'),
                data: search_form.serializeArray(),
            })
            .done(function(response) {
                $('#form-view').html(response);
                search_button.html(search_content).prop('disabled', false);
            })
            .fail(function(response) {
                search_button.html(search_content).prop('disabled', false);
                $.each(response.responseJSON.errors, function(index, val) {
                    toastr.error(val[0]);
                });
            });
        });
    });
</script>
@endsection