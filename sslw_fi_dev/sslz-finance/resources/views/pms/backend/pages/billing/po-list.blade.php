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

    .dropdown-toggle::after{
        display: none !important;
    }
</style>
@include('yajra.css')
@endsection
@section('main-content')
{{-- @php
use App\Models\PmsModels\Purchase\PurchaseOrderAttachment;
@endphp --}}

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
                <li class="active">Bill Manage</li>
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
                                    <form action="{{ route('pms.billing-audit.po.list') }}" method="get" accept-charset="utf-8">
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
                                                            <a href="{{ route('pms.billing-audit.po.list') }}" class="btn btn-danger btn-block rounded mt-8"><i class="las la-times"></i>&nbsp;Clear</a>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Purchase Order Attachment</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {!! Form::open(['route' => 'pms.billing-audit.po.attachment.upload',  'files'=> true, 'id'=>'', 'class' => 'form-horizontal']) !!}

            <div class="modal-body">

            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('page-script')
@include('yajra.js')
<script>
    function showPODetails(element){
        $.ajax({
            url: element.attr('data-src'),
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

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        {
            return false;
        }
        return true;
    }

    function viewRemarks(element){
        let model = element.attr('po-attachment');
        $('#POdetailsModel').find('#body').html(model);
        $('#POdetailsModel').find('.modal-title').html(`Notes From Audit`);
        $('#POdetailsModel').modal('show');
    }

    function UploadPOAttachment(element){
        let id = element.attr('data-id');

        $.ajax({
            url: "{{ route('pms.billing-audit.po.list.attachment-upload') }}",
            type: 'POST',
            data: {_token: "{{ csrf_token() }}", id:id},
        })
        .done(function(response) {
            $('#PurchaseOrderAttachmentModal').find('.modal-body').html(response);
            $('#PurchaseOrderAttachmentModal').modal('show');

        })
        .fail(function(response){
            notify('Something went wrong!','error');
        });
    }

    function calculateSubtotal(id,qty, advance = 0) {
        var unitAmount = $('#unitAmount'+id).val();
        if(unitAmount !='' && qty !=''){
            var sub_total = parseFloat(unitAmount*qty).toFixed(2);
            $('#subTotalPrice_'+id).html(sub_total);
            console.log(sub_total);

            var vat_percentage = parseFloat($('#item_vat_percentage-'+id).text()).toFixed(2);
            var vat = parseFloat(vat_percentage > 0 && sub_total > 0 ? (sub_total*(vat_percentage/100)) : 0).toFixed(2);
            console.log(vat);

            var item_total = parseFloat(parseFloat(sub_total)+parseFloat(vat)).toFixed(2);
            console.log(item_total);

            $('#item_vat-'+id).html(vat);
            $('#totalPrice_'+id).html(item_total);

            var total = 0;
            $(".calculateSumOfTotal").each(function(){
                total += parseFloat($(this).text()||0);
            });
            $("#sumOfBillAmount").val(parseFloat(parseFloat(total)-parseFloat(advance)).toFixed(2));
            console.log(total);
        }else{
            notify('Please enter unit price and qty!!','error');
        }
        return false;
    }

</script>

@endsection