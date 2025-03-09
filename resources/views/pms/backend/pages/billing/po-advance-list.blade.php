@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
    .list-unstyled .ratings {
        display: none;
    }
</style>
@include('yajra.css')
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
                <li>
                    <a href="#">PMS</a>
                </li>
                <li class="active">{{__($title)}}</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="">
                <div class="panel panel-info">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header bg-primary p-0" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#filter" aria-expanded="true" aria-controls="filter">
                                        <h5 class="text-white"><strong><i class="las la-chevron-circle-right la-spin"></i>&nbsp;Filters</strong></h5>
                                    </button>
                                </h5>
                            </div>

                            <div id="filter" class="collapse {{ !request()->has('from_date') ? 'show' : '' }}" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <form action="{{ route('pms.billing-audit.po.advance.list') }}" method="get" accept-charset="utf-8">
                                        <div class="row">
                                            
                                            <div class="col-md-2 col-sm-6">
                                                <p class="mb-1 font-weight-bold"><label for="from_date">{{ __('From Date') }}:</label></p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="from_date" id="from_date" class="form-control rounded search-datepicker" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required value="{{  request()->has('from_date')? request()->get('from_date'):date("Y-m-d", strtotime(date('Y-m-01'))) }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6">
                                                <p class="mb-1 font-weight-bold"><label for="to_date">{{ __('To Date') }}:</label></p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="to_date" id="to_date" class="form-control rounded search-datepicker" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required value="{{ request()->has('to_date')? request()->get('to_date'):date('Y-m-d') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6">
                                                <p class="mb-1 font-weight-bold"><label for="status">{{ __('Status') }}:</label></p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="status" id="status" class="form-control rounded">
                                                        <option value="{{ null }}">{{ __('Select One') }}</option>
                                                        @foreach(stringStatusArray(true) as $key=> $values)
                                                        <option {{ request()->get('status')==$key?'selected':'' }} value="{{ $key }}">{{ $values}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <p class="mb-1 font-weight-bold"><label for=""></label></p>
                                                        <div class="input-group input-group-md">
                                                            <button type="submit" class="btn btn-success btn-block rounded mt-8"><i class="las la-search"></i>&nbsp;Search</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <p class="mb-1 font-weight-bold"><label for=""></label></p>
                                                        <div class="input-group input-group-md">
                                                            <a href="{{ route('pms.billing-audit.po.advance.list') }}" class="btn btn-danger btn-block rounded mt-8"><i class="las la-times"></i>&nbsp;Clear</a>
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
                    <div class="panel-body">
                        @include('yajra.datatable')
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="POdetailsModel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Purchase Order</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body" id="body">

                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal" id="PurchaseOrderAttachmentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">PO Advance Approval</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <form action="{{route('pms.billing-audit.po.advance.approved')}}" method="POST" id="po-invoice-form">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="remarks">Notes :</label>
                            <textarea class="form-control" name="remarks" rows="3" id="remarks" placeholder="Remarks...."></textarea>

                            <input type="hidden" readonly required name="bill_type" id="billType">
                            <input type="hidden" readonly required name="po_id" id="poId">
                            <input type="hidden" readonly required name="status" id="poStatus">
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="po-invoice-button"><i class="la la-check"></i>&nbsp;Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endsection
    @section('page-script')
    @include('yajra.js')
    <script>
        $(document).ready(function() {
            var form = $('#po-invoice-form');
            var button = $('#po-invoice-button');
            var content = button.html();

            form.submit(function(event) {
                event.preventDefault();

                button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    dataType: 'json',
                    data: form.serializeArray(),
                })
                .done(function(response) {
                    button.html(content).prop('disabled', false);
                    if(response.success){
                        reloadDatatable();
                        toastr.success(response.message);
                        $('#PurchaseOrderAttachmentModal').modal('hide');
                    }else{
                        toastr.error(response.message);
                    }
                });
            });
        });

        function showPODetails(ele){
            $.ajax({
                url: ele.attr('data-src'),
                type: 'get',
                dataType: 'json',
                data: '',
            })
            .done(function(response) {

                if (response.result=='success') {
                    $('#POdetailsModel').find('#body').html(response.body);
                    $('#POdetailsModel').find('.modal-title').html(`Purchase Order Details`);
                    $('#POdetailsModel').modal('show');
                }

            })
            .fail(function(response){
                notify('Something went wrong!','error');
            });
        }

        function changeStatus(element){
            let billType = element.attr('bill-type');
            let poId = element.attr('po-id');
            let status = element.val();

            $('#billType').val(billType);
            $('#poId').val(poId);
            $('#poStatus').val(status);

            if (status!='autoSelect') {
                return $('#PurchaseOrderAttachmentModal').modal('show').on('hidden.bs.modal', function (e) {
                    let form = document.querySelector('#PurchaseOrderAttachmentModal').querySelector('form').reset();
                });
            }
        }
    </script>

    @endsection