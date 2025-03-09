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
                            <div class="col-md-2 col-sm-12">
                                <label for="company_id"><strong>Company:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="company_id" id="company_id" class="form-control rounded" onchange="getPO()">
                                        @if(isset($companies[0]))
                                        @foreach($companies as $key => $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="purchase_order_id"><strong>{{ __('Purchase orders') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="purchase_order_id" id="purchase_order_id" class="form-control rounded" onchange="getStocks()">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="goods_received_items_stock_in_id"><strong>{{ __('GRN') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="goods_received_items_stock_in_id" id="goods_received_items_stock_in_id" class="form-control rounded">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 pt-4">
                                <button type="button" class="mt-2 btn btn-success btn-md btn-block" onclick="getCosts()"><i class="fa fa-search"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    <form action="{{ route('accounting.cwip-to-asset.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="cwip-form">
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-12 items">
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content modal-xl">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
    getPO();
    function getPO(){
        var company_id = $('#company_id').val();
        $.ajax({
            url: "{{ url('accounting/cwip-to-asset/create') }}?action=po&company_id="+$('#company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#purchase_order_id').html(response).change();
            $('.items').html('');
        });
    }

    function getStocks(){
        var purchase_order_id = $('#purchase_order_id').val();
        $.ajax({
            url: "{{ url('accounting/cwip-to-asset/create') }}?action=stocks&purchase_order_id="+$('#purchase_order_id').val()+"&company_id="+$('#company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#goods_received_items_stock_in_id').html(response);
             $('.items').html('');
        });
    }

    function getCosts() {
        $('.items').html('<h3 class="text-center"><strong>Please wait...</strong></h3>');
        $.ajax({
            url: "{{ url('accounting/cwip-to-asset/create') }}?action=items&purchase_order_id="+$('#purchase_order_id').val()+"&goods_received_items_stock_in_id="+$('#goods_received_items_stock_in_id').val()+"&company_id="+$('#company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('.items').html(response);
            calculateTotal();
        });
    }

    function calculateTotal(){
        var item_amount = 0;
        var cost_amount = 0;
        var asset_value = 0;
        $.each($('.choose-products'), function(index, val) {
            if($(this).is(':checked')){
                item_amount += parseFloat($(this).parent().parent().find('.item-amount').text().replace(/,/g, ""));
                cost_amount += parseFloat($(this).parent().parent().find('.cost-amount').text().replace(/,/g, ""));
                asset_value += parseFloat($(this).parent().parent().find('.asset-value').text().replace(/,/g, ""));
            }
        });

        $('#total-item-amount').html(parseFloat(item_amount).toFixed(2));
        $('#total-cost-amount').html(parseFloat(cost_amount).toFixed(2));
        $('#total-asset-value').html(parseFloat(asset_value).toFixed(2));

        $('.mask-money').maskMoney();
    }

    function showCostDetails(id) {
        $.ajax({
            url: "{{ url('accounting/cwip-to-asset') }}/"+id,
            type: 'GET',
            dataType: 'json',
            data: {},
        })
        .done(function(response) {
            var modal = $('#modal');
            modal.find('.modal-title').html(response.title);
            modal.find('.modal-body').html(response.body);
            modal.modal('show');
        });
    }
</script>
@endsection