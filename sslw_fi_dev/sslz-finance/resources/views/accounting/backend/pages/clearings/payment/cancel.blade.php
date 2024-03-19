@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@include('yajra.css')
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
            <div class="panel panel-info mt-2 p-2">
                <div class="panel-body">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
<script type="text/javascript">
    function getShortDetails(element) {
        $.dialog({
            title: (element.attr('data-entry-type'))+" Voucher #"+(element.attr('data-code')),
            content: "url:{{ url('accounting/entries') }}/"+(element.attr('data-id'))+"?short-details",
            animation: 'scale',
            columnClass: 'col-md-12',
            closeAnimation: 'scale',
            backgroundDismiss: true
        });
    }

    function cancelClearing(id) {
        $.confirm({
            title: 'Confirm!',
            content: '<hr class="mt-0 pt-2"><h5>Are you sure to cancel the Clearings ?</h5>',
            buttons: {
                yes: {
                    text: '<i class="la la-check"></i>&nbsp;Yes',
                    btnClass: 'btn-success',
                    action: function(){
                        $.ajax({
                            url: '{{ url('accounting/payment-clearing-cancellation') }}/'+id,
                            type: 'PUT',
                            dataType: 'json',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                        })
                        .done(function(response) {
                            if(response.success){
                                reloadDatatable();
                                toastr.success(response.message);
                            }else{
                                toastr.error(response.message);
                            }
                        });
                    }
                },
                no: {
                    text: '<i class="la la-times"></i>&nbsp;No',
                    btnClass: 'btn-red',
                    action: function(){
                        
                    }
                },
            }
        });
    }
</script>
@endsection
