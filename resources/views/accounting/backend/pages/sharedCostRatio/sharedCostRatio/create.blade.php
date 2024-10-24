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
                    <form action="{{ route('accounting.shared-cost-ratios.create') }}" method="get">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name"><strong>Cost Centre Allocation <span class="text-danger">*</span></strong></label>
                                    <select name="cost_centre_allocation_id" id="cost_centre_allocation_id" class="form-control">
                                        <option value="{{ null }}">Choose Cost Centre Allocation</option>
                                        @if(isset($costCentreAllocations[0]))
                                        @foreach($costCentreAllocations as $allocation)
                                        <option value="{{ $allocation->id }}" {{ request()->get('cost_centre_allocation_id') == $allocation->id ? 'selected' : '' }}>{{ $allocation->name }} | [{{ $allocation->costCentre->code }}] {{ $allocation->costCentre->name }} | [{{ $allocation->chartOfAccount->code }}] {{ $allocation->chartOfAccount->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="name"><strong>Currency <span class="text-danger">*</span></strong></label>
                                    <select name="currency_id" id="currency_id" class="form-control rounded">
                                        @if(isset($currencyTypes[0]))
                                            @foreach($currencyTypes as $key => $currencyType)
                                                <optgroup label="{{ $currencyType->name }}">
                                                    @if($currencyType->currencies->count() > 0)
                                                        @foreach($currencyType->currencies as $key => $currency)
                                                            <option value="{{ $currency->id }}" {{ $currency_id == $currency->id ? 'selected' : '' }}>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="from"><strong>Date From <span class="text-danger">*</span></strong></label>
                                    <input type="date" name="from" id="from" class="form-control" value="{{ $from }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="to"><strong>Date To <span class="text-danger">*</span></strong></label>
                                    <input type="date" name="to" id="to" class="form-control" value="{{ $to }}">
                                </div>
                            </div>
                            <div class="col-md-2 pt-4">
                                <button type="submit" class="btn btn-md btn-block btn-success text-white mt-2"><i class="las la-search"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    </form>

                    @if(isset($costCentreAllocation->id))
                        <form action="{{ route('accounting.shared-cost-ratios.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="share-cost-ratio-form">
                        @csrf
                        <input type="hidden" name="cost_centre_allocation_id" value="{{ $costCentreAllocation->id }}" />
                        <input type="hidden" name="currency_id" value="{{ $currency_id }}" />
                        <input type="hidden" name="from" value="{{ $from }}" />
                        <input type="hidden" name="to" value="{{ $to }}" />

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%">Type</th>
                                    <th style="width: 10%">Company</th>
                                    <th style="width: 20%">Cost Centre</th>
                                    <th style="width: 22.5%">Chart of Account</th>
                                    <th style="width: 22.5%">Counter Chart of Account</th>
                                    <th class="text-right" style="width: 5%">Allocation</th>
                                    <th class="text-right" style="width: 15%">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><strong>Source</strong></td>
                                    <td>{{ $costCentreAllocation->costCentre->profitCentre->company->code }}</td>
                                    <td>[{{ $costCentreAllocation->costCentre->code }}] {{ $costCentreAllocation->costCentre->name }}</td>
                                    <td>[{{ $costCentreAllocation->chartOfAccount->code }}] {{ $costCentreAllocation->chartOfAccount->name }}</td>
                                    <td>[{{ $costCentreAllocation->counterChartOfAccount->code }}] {{ $costCentreAllocation->counterChartOfAccount->name }}</td>
                                    <td class="text-right"><strong>{{ $costCentreAllocation->allocation }}%</strong></td>
                                    <td class="text-right"><strong>{{ systemMoneyFormat($balance) }}</strong></td>
                                </tr>

                                @if(isset($costCentreAllocation->targets[0]))
                                @foreach($costCentreAllocation->targets as $target)
                                <tr>
                                    <td class="text-center"><strong>Destination</strong></td>
                                    <td>{{ $target->costCentre->profitCentre->company->code }}</td>
                                    <td>[{{ $target->costCentre->code }}] {{ $target->costCentre->name }}</td>
                                    <td>[{{ $target->chartOfAccount->code }}] {{ $target->chartOfAccount->name }}</td>
                                    <td>[{{ $target->counterChartOfAccount->code }}] {{ $target->counterChartOfAccount->name }}</td>
                                    <td class="text-right"><strong>{{ $target->allocation }}%</strong></td>
                                    <td class="text-right"><strong>{{ systemMoneyFormat($balance != 0 && $target->allocation > 0 ? $balance*($target->allocation/100) : 0) }}</strong></td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{-- @if($balance != 0) --}}
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a class="btn btn-dark btn-md" href="{{ url('accounting/shared-cost-ratios') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                    <button type="submit" class="btn btn-success btn-md share-cost-ratio-button"><i class="la la-save"></i>&nbsp;Process Cost Centre Ratio Share</button>
                                </div>
                            </div>
                        {{-- @endif --}}
                        </form>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {
        var form = $('#share-cost-ratio-form');
        var button = form.find('.share-cost-ratio-button');
        var buttonContent = button.html();

        form.submit(function(event) {
            event.preventDefault();
            button.prop('disabled', true).html('<i class="las la-spinner fa-spin"></i>&nbsp;Please wait...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                processData: false,
                contentType: false,
                data: new FormData(form[0]),
            })
            .done(function(response) {
                if(response.success){
                    window.open("{{ url('accounting/shared-cost-ratios') }}", "_parent");
                }else{
                    toastr.error(response.message);
                }
                button.prop('disabled', false).html(buttonContent);
            })
            .fail(function(response) {
                $.each(response.responseJSON.errors, function(index, val) {
                    toastr.error(val[0]);
                });

                button.prop('disabled', false).html(buttonContent);
            });
        });
    });
</script>
@endsection