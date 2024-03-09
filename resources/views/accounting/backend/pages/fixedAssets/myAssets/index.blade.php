@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
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
                        <div class="col-md-12">
                            @include('yajra.datatable')
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
@include('yajra.js')

<script type="text/javascript">
 function receiveAsset(id, code) {
    $('#sampleModal').find('.modal-title').html('Receive Fixed Asset #'+code);
    $('#sampleModal').find('.modal-body').html('<h3 class="text-center"><strong>Please wait...</strong></h3>');
    $('#sampleModal').modal('show');
    $.ajax({
        url: "{{ url('accounting/my-assets') }}/"+id,
        type: 'GET',
        data: {},
    })
    .done(function(response) {
        $('#sampleModal').find('.modal-body').html(response);
    });
}

function movementRequisition(id, code) {
    $('#sampleModal').find('.modal-title').html('Fixed Asset Movement Requisition for asset #'+code);
    $('#sampleModal').find('.modal-body').html('<h3 class="text-center"><strong>Please wait...</strong></h3>');
    $('#sampleModal').modal('show');
    $.ajax({
        url: "{{ url('accounting/my-assets') }}/"+id+"/edit",
        type: 'GET',
        data: {},
    })
    .done(function(response) {
        $('#sampleModal').find('.modal-body').html(response);
    });
}
</script>
@endsection