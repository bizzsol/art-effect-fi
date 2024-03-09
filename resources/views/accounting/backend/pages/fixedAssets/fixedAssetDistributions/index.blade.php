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
                            <div class="col-md-3 col-sm-12">
                                <label for="product_id"><strong>{{ __('Assets') }}:<span class="text-danger">&nbsp;*</span></strong></label>
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
                            {{-- <div class="col-md-6 col-sm-12">
                                <label for="fixed_asset_batch_id"><strong>{{ __('Batches') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="fixed_asset_batch_id" id="fixed_asset_batch_id" class="form-control rounded">
                                        
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-2 col-sm-12">
                                <label for="asset_code"><strong>{{ __('Asset Code') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="asset_code" id="asset_code" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 pt-4">
                                <button type="button" class="btn btn-block btn-success mt-2" onclick="getItems()"><i class="la la-search"></i></button>
                            </div>
                        </div>
                        
                        <form action="{{ route('accounting.fixed-asset-distributions.store') }}" method="post" accept-charset="utf-8">
                        @csrf
                            <div class="row pr-3">
                                <div class="col-md-12 items">
                                    
                                </div>
                            </div>
                            <div class="row button-row" style="display: none">
                                <div class="col-md-12 text-right pr-4">
                                    <button type="submit" class="btn btn-success btn-md mr-2"><i class="la la-save"></i>&nbsp;Process distributions</button>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Start -->
<div class="modal" id="sampleModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->
@endsection

@section('page-script')
<script type="text/javascript">
    getBatches();
    function getBatches(){
        var product_id = $('#product_id').val();
        $.ajax({
            url: "{{ url('accounting/fixed-asset-distributions/create') }}?action=batches&product_id="+$('#product_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#fixed_asset_batch_id').html(response);
        });
    }

    function getItems() {
        $('.button-row').hide();
        $('.items').html('<h3 class="text-center"><strong>Please wait...</strong></h3>');
        $.ajax({
            url: "{{ url('accounting/fixed-asset-distributions/create') }}?action=items&fixed_asset_batch_id="+$('#fixed_asset_batch_id').val()+"&asset_code="+$('#asset_code').val()+"&product_id="+$('#product_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('.items').html(response);
            $('.select2-updated').select2();

            $('.button-row').show();
        });
    }

    function loadHistory(id, code) {
        $('#sampleModal').find('.modal-title').html('Distribution History of Fixed Asset #'+code);
        $('#sampleModal').find('.modal-body').html('<h3 class="text-center"><strong>Please wait...</strong></h3>');
        $('#sampleModal').modal('show');
        $.ajax({
            url: "{{ url('accounting/fixed-asset-distributions') }}/"+id,
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#sampleModal').find('.modal-body').html(response);
        });
    }
</script>
@endsection