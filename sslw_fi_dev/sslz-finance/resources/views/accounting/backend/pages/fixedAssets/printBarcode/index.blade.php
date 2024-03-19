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
                    <div class="row pr-3">
                        <div class="col-md-4 col-sm-12">
                            <label for="product_id"><strong>{{ __('Products') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                            <div class="input-group input-group-md mb-3 d-">
                                <select name="product_id" id="product_id" class="form-control rounded" onchange="getBatches()">
                                    @if(isset($categories[0]))
                                    @foreach($categories as $key => $category)
                                    <optgroup label="{{ $category->name }}">
                                        @if($products->where('category_id', $category->id)->count())
                                        @foreach($products->where('category_id', $category->id) as $key => $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} {{ getProductAttributesFaster($product) }}</option>
                                        @endforeach
                                        @endif
                                    </optgroup>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="final_asset_id"><strong>{{ __('Batches') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                            <div class="input-group input-group-md mb-3 d-">
                                <select name="final_asset_id" id="final_asset_id" class="form-control rounded">
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 pt-4">
                            <button type="button" class="btn btn-success btn-md mr-2 mt-2 btn-block" onclick="window.open('{{ url('accounting/print-fixed-asset-barcodes') }}/'+$('#final_asset_id').val()+'?product_id='+$('#product_id').val(), '_blank')"><i class="la la-print"></i>&nbsp;Print Asset Barcodes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
    getBatches();
    function getBatches(){
        var product_id = $('#product_id').val();
        $.ajax({
            url: "{{ url('accounting/print-fixed-asset-barcodes/create') }}?product_id="+$('#product_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#final_asset_id').html(response);
        });
    }
</script>
@endsection