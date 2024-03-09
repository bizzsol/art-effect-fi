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
                                <label for="purchase_order_id"><strong>{{ __('Purchase orders') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="purchase_order_id" id="purchase_order_id" class="form-control rounded" onchange="getStocks()">
                                        <option value="0">Choose a Purchase order</option>
                                        @if(isset($purchaseOrders[0]))
                                        @foreach($purchaseOrders as $key => $purchaseOrder)
                                            <option value="{{ $purchaseOrder->id }}">{{ $purchaseOrder->reference_no }}</option>
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
                            <div class="col-md-2 col-sm-12 pt-4">
                                <button type="button" class="mt-2 btn btn-success btn-md btn-block" onclick="getCosts()"><i class="fa fa-search"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    <form action="{{ route('accounting.asset-costing-entries.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="asset-costing-form">
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-12 costs">
                                
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
    getStocks();
    function getStocks(){
        var purchase_order_id = $('#purchase_order_id').val();
        $.ajax({
            url: "{{ url('accounting/asset-costing-entries/create') }}?action=stocks&purchase_order_id="+$('#purchase_order_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#goods_received_items_stock_in_id').html(response);
            $('.costs').html('');
        });
    }

    function getCosts() {
        $('.costs').html('<h3 class="text-center"><strong>Please wait...</strong></h3>');
        $.ajax({
            url: "{{ url('accounting/asset-costing-entries/create') }}?action=costs&purchase_order_id="+$('#purchase_order_id').val()+"&goods_received_items_stock_in_id="+$('#goods_received_items_stock_in_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('.costs').html(response);
        });
    }

    function calculateTotal(){
        var total = 0;
        $.each($('.cost-amounts'), function(index, val) {
            var value = ($(this).val() != "" && $(this).val() > 0 ? parseFloat($(this).val()) : 0);
            $(this).val(value);

            if($(this).parent().parent().find('.choose-products').is(':checked')){
                total += value;
            }
        });

        $('#total-cost-amount').html(parseFloat(total).toFixed(2));
    }

    function showCostDetails(id) {
        $.ajax({
            url: "{{ url('accounting/asset-costing-entries') }}/"+id,
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

    $(document).ready(function() {
        var form = $('#asset-costing-form');
        var button = $('.asset-costing-button');

        form.submit(function(event) {
            event.preventDefault();
            var content = button.html();
            button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);

            $.confirm({
                title: 'Confirm!',
                content: '<hr><h4 class="text-center">Are you sure to submit costings ?</h4><hr>',
                buttons: {
                    yes: {
                        text: '<i class="las la-check"></i>&nbsp;Yes',
                        btnClass: 'btn-success',
                        action: function(){
                            $.ajax({
                                url: form.attr('action'),
                                type: form.attr('method'),
                                dataType: 'json',
                                data: new FormData(form[0]),
                                cache: false,
                                contentType: false,
                                processData: false
                            })
                            .done(function(response) {
                                if(response.success){
                                    location.reload();
                                }else{
                                    toastr.error(response.message);
                                }
                                button.html(content).prop('disabled', false);
                            })
                            .fail(function(response){
                                $.each(response.responseJSON.errors, function(index, val) {
                                    toastr.error(val[0]);
                                });
                                button.html(content).prop('disabled', false);
                            });
                        }
                    },
                    no: {
                        text: '<i class="las la-ban"></i>&nbsp;No',
                        btnClass: 'btn-red',
                        action: function(){
                            button.html(content).prop('disabled', false);
                        }
                    }
                }
            });
        });
    });
</script>
@endsection