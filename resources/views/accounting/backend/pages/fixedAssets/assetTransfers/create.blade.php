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
                    <form action="{{ url('accounting/asset-transfers/create') }}" method="get" accept-charset="utf-8">
                        <div class="row pr-3">
                            <div class="col-md-2 col-sm-12">
                                <label for="company_id"><strong>{{ __('Company') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="company_id" id="company_id" class="form-control rounded" onchange="getBatches()">
                                        @if(isset($companies[0]))
                                        @foreach($companies as $key => $company)
                                            <option value="{{ $company->id }}" {{ request()->get('company_id') == $company->id ? 'selected' : '' }}>{{ $company->code }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
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
                            <div class="col-md-3 col-sm-12">
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
                    <form action="{{ route('accounting.asset-transfers.store') }}" method="post" accept-charset="utf-8" id="transfer-form">
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
                                            <th style="width: 20%">Written Value({{ $item->batch->goodsReceivedItemsStockIn->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
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
                                                <strong>{{ systemMoneyFormat($depreciation_total) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $depreciation_count > 0 ? systemDoubleValue($depreciation_count/12, 2) : 0 }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ systemMoneyFormat($item->asset_value-$depreciation_total) }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row pr-3">
                            <div class="col-md-2 col-sm-12">
                                <label for="destination_company_id"><strong>Destination Company :<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="destination_company_id" id="destination_company_id" class="form-control rounded" onchange="getLocations()">
                                        @if(isset($companies[0]))
                                        @foreach($companies as $key => $company)
                                        @if(request()->get('company_id') != $company->id)
                                            <option value="{{ $company->id }}">{{ $company->code }}</option>
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="cost_centre_id"><strong>Destination Cost Centre :<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="cost_centre_id" id="cost_centre_id" class="form-control rounded">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="fixed_asset_location_id"><strong>Destination Location :<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="fixed_asset_location_id" id="fixed_asset_location_id" class="form-control rounded">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="user_id"><strong>Assigned User :<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="user_id" id="user_id" class="form-control rounded">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <button class="btn btn-success btn-md btn-block text-white mt-2 transfer-button"><i class="las la-check"></i>&nbsp;Process Asset Transfer</button>
                            </div>
                        </div>
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
        var form = $('#transfer-form');
        var button = $('.transfer-button');
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
                                    window.open("{{ url('accounting/asset-transfers') }}", "_parent");
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
            url: "{{ url('accounting/asset-transfers/create') }}?action=batches&company_id="+$('#company_id').val()+"&product_id="+$('#product_id').val()+"&selected={{ request()->get('fixed_asset_batch_id') }}",
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
            url: "{{ url('accounting/asset-transfers/create') }}?action=items&company_id="+$('#company_id').val()+"&product_id="+$('#product_id').val()+"&fixed_asset_batch_id="+$('#fixed_asset_batch_id').val()+"&selected={{ request()->get('fixed_asset_batch_item_id') }}",
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#fixed_asset_batch_item_id').html(response);
        });
    }

    getLocations();
    function getLocations(){
        $.ajax({
            url: "{{ url('accounting/asset-transfers/create') }}?action=locations&company_id="+$('#destination_company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#fixed_asset_location_id').html(response).change();
            getCostCentres();
            getUsers();
        });
    }

    function getCostCentres(){
        $.ajax({
            url: "{{ url('accounting/asset-transfers/create') }}?action=cost-centres&company_id="+$('#destination_company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#cost_centre_id').html(response).change();
        });
    }

    function getUsers(){
        $.ajax({
            url: "{{ url('accounting/asset-transfers/create') }}?action=users&company_id="+$('#destination_company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#user_id').html(response).change();
        });
    }
</script>
@endsection