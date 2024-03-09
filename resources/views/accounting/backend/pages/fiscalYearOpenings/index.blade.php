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
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    @can('open-fiscal-year')
                        <a class="btn btn-sm btn-success pull-right ml-2" href="{{ url('accounting/fiscal-year-openings/create') }}" style="float: right"><i class="la la-plus"></i>&nbsp;Open a Fiscal Year</a>
                    @endcan
                </div>
            </div>
            <div class="panel panel-info mt-2 p-2">
                @include('yajra.datatable')
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
<script type="text/javascript">
    function Approve(id){
        $.confirm({
            title: 'Confirm!',
            content: '<hr>Are you sure to Approve this Fiscal Year Opening ?',
            buttons: {
                yes: {
                    text: '<i class="las la-check-circle"></i>&nbsp;Yes',
                    btnClass: 'btn-blue',
                    action: function(){
                        $.ajax({
                            url: '{{ url('accounting/fiscal-year-openings') }}/'+id,
                            type: 'PUT',
                            dataType: 'json',
                            data: {
                                _token: "{{ csrf_token() }}",
                                is_approved: 'approved'
                            },
                        })
                        .done(function(response) {
                            if(response.success){
                                toastr.success(response.message);
                                reloadDatatable();
                            }else{
                                toastr.success(response.message);
                            }
                        });
                    }
                },
                no: {
                    text: '<i class="las la-times-circle"></i>&nbsp;No',
                    btnClass: 'btn-dark',
                    action: function(){
                        
                    }
                },
            }
        });
    }

    function Deny(id){
        $.confirm({
            title: 'Confirm!',
            content: '<hr>Are you sure to Deny this Fiscal Year Opening ?',
            buttons: {
                yes: {
                    text: '<i class="las la-check-circle"></i>&nbsp;Yes',
                    btnClass: 'btn-blue',
                    action: function(){
                        $.ajax({
                            url: '{{ url('accounting/fiscal-year-openings') }}/'+id,
                            type: 'PUT',
                            dataType: 'json',
                            data: {
                                _token: "{{ csrf_token() }}",
                                is_approved: 'denied'
                            },
                        })
                        .done(function(response) {
                            if(response.success){
                                toastr.success(response.message);
                                reloadDatatable();
                            }else{
                                toastr.success(response.message);
                            }
                        });
                    }
                },
                no: {
                    text: '<i class="las la-times-circle"></i>&nbsp;No',
                    btnClass: 'btn-dark',
                    action: function(){
                        
                    }
                },
            }
        });
    }
</script>
@endsection