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
                    <form action="{{ url('accounting/asset-re-scheduling/create') }}" method="get" accept-charset="utf-8">
                        <div class="row pr-3">
                            <div class="col-md-3 col-sm-12">
                                <label for="product_id"><strong>{{ __('Assets') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="product_id" id="product_id" class="form-control rounded" onchange="getBatches()">
                                        <option value="0">All Assets</option>
                                        @if(isset($categories[0]))
                                        @foreach($categories as $key => $category)
                                        <optgroup label="{{ $category->name }}">
                                            @if($products->where('category_id', $category->id)->count() > 0)
                                            @foreach($products->where('category_id', $category->id) as $key => $product)
                                            <option value="{{ $product->id }}" {{ request()->get('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }} {{ getProductAttributesFaster($product) }}</option>
                                            @endforeach
                                            @endif
                                        </optgroup>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="fixed_asset_batch_id"><strong>{{ __('Batches') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="fixed_asset_batch_id" id="fixed_asset_batch_id" class="form-control rounded" onchange="getItems()">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="fixed_asset_batch_item_id"><strong>{{ __('Asset Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="fixed_asset_batch_item_id" id="fixed_asset_batch_item_id" class="form-control rounded">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 pt-4">
                                <button type="submit" class="mt-2 btn btn-success btn-md btn-block"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    @if(isset($item->id))
                    @php
                        $depreciation_count = $item->depreciations->count();
                        $depreciation_total = $item->depreciations->sum('amount');
                    @endphp
                    <form action="{{ route('accounting.asset-re-scheduling.store') }}" method="post" accept-charset="utf-8" id="re-schedule-form">
                    @csrf
                    <input type="hidden" name="fixed_asset_batch_item_id" value="{{ $item->id }}">
                        <div class="row pr-3">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">Cost({{ $item->batch->goodsReceivedItemsStockIn->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
                                            <th style="width: 10%">Life(Year)</th>
                                            <th style="width: 20%">Accumulated Depreciation({{ $item->batch->goodsReceivedItemsStockIn->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
                                            <th style="width: 15%">Consumed Life(Year)</th>
                                            <th style="width: 20%">Reschedule Cost({{ $item->batch->goodsReceivedItemsStockIn->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
                                            <th style="width: 20%">Estimated Life(Year)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">
                                                <strong>{{ $item->asset_value }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ 100/$item->depreciation_rate }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $depreciation_total }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $depreciation_count > 0 ? systemDoubleValue($depreciation_count/12, 2) : 0 }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" min="0" value="0" name="cost" id="cost" class="form-control">
                                            </td>
                                            <td class="text-center">
                                                <input type="number" min="0" value="0" name="life" id="life" class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @include('payment', [
                            'currency_id' => $item->batch->goodsReceivedItemsStockIn->relPurchaseOrder->relQuotation->exchangeRate->currency_id,
                            'company_id' => $item->batch->goodsReceivedItemsStockIn->relPurchaseOrder->Unit->company_id
                        ])

                        <button class="btn btn-success btn-md text-white mt-3 re-schedule-button"><i class="las la-check"></i>&nbsp;Process Asset Re-Schedule</button>
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
        var form = $('#re-schedule-form');
        var button = $('.re-schedule-button');
        var content = button.html();

        form.submit(function(event) {
            event.preventDefault();
            button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);
            
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure ?',
                buttons: {
                    yes: {
                        text: 'Yes',
                        btnClass: 'btn-blue',
                        action: function(){
                            $.ajax({
                                url: form.attr('action'),
                                type: form.attr('method'),
                                dataType: 'json',
                                data: form.serializeArray(),
                            })
                            .done(function(response) {
                                if(response.success){
                                    window.open("{{ url('accounting/asset-re-scheduling') }}", "_parent");
                                }else{
                                    toastr.error(response.message);
                                }

                                button.prop('disabled', false).html(content);
                            })
                            .fail(function(response) {
                                var errors = '<ul class="pl-3">';
                                $.each(response.responseJSON.errors, function(index, val) {
                                    errors += '<li>'+val[0]+'</li>';
                                });
                                errors += '</ul>';
                                toastr.error(errors);
                                button.prop('disabled', false).html(content);
                            });
                        }
                    },
                    no: {
                        text: 'No',
                        btnClass: 'btn-dark',
                        action: function(){
                            button.html(content).prop('disabled', false);
                        }
                    }
                }
            });
        });
    });

    getBatches();
    function getBatches(){
        var product_id = $('#product_id').val();
        $.ajax({
            url: "{{ url('accounting/asset-re-scheduling/create') }}?action=batches&product_id="+$('#product_id').val()+"&selected={{ request()->get('fixed_asset_batch_id') }}",
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#fixed_asset_batch_id').html(response).change();
        });
    }

    function getItems() {
        $('.items').html('<option value="0">Please wait...</option>');
        $.ajax({
            url: "{{ url('accounting/asset-re-scheduling/create') }}?action=items&product_id="+$('#product_id').val()+"&fixed_asset_batch_id="+$('#fixed_asset_batch_id').val()+"&selected={{ request()->get('fixed_asset_batch_item_id') }}",
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#fixed_asset_batch_item_id').html(response);
        });
    }
</script>
@endsection