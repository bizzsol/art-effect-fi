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
                            <label for="purchase_order_id"><strong>{{ __('Purchase Orders') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                            <div class="input-group input-group-md mb-3 d-">
                                <select name="purchase_order_id" id="purchase_order_id" class="form-control rounded" onchange="getStocks()">
                                    @if(isset($purchaseOrders[0]))
                                    @foreach($purchaseOrders as $key => $purchaseOrder)
                                        <option value="{{ $purchaseOrder->id }}">{{ $purchaseOrder->reference_no }} ({{ date('Y-m-d', strtotime($purchaseOrder->po_date)) }})</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label for="goods_received_items_stock_in_id"><strong>{{ __('GRN') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                            <div class="input-group input-group-md mb-3 d-">
                                <select name="goods_received_items_stock_in_id" id="goods_received_items_stock_in_id" class="form-control rounded">
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-12 pt-4">
                            <button type="button" class="btn btn-block btn-success mt-2" onclick="getItems()"><i class="la la-search"></i></button>
                        </div>
                    </div>
                    
                    <div class="row pr-3">
                        <div class="col-md-12 items">
                            
                        </div>
                    </div>
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
    getStocks();
    function getStocks(){
        var purchase_order_id = $('#purchase_order_id').val();
        $.ajax({
            url: "{{ url('accounting/asset-history-report/create') }}?action=stocks&purchase_order_id="+$('#purchase_order_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#goods_received_items_stock_in_id').html(response);
        });
    }

    function getItems() {
        $('.button-row').hide();
        $('.items').html('<h3 class="text-center"><strong>Please wait...</strong></h3>');
        $.ajax({
            url: "{{ url('accounting/asset-history-report/create') }}?action=items&purchase_order_id="+$('#purchase_order_id').val()+"&goods_received_items_stock_in_id="+$('#goods_received_items_stock_in_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('.items').html(response);
            $('.select2-updated').select2();

            $('.button-row').show();
        });
    }
</script>
@endsection