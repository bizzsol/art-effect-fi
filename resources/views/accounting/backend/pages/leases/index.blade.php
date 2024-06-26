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
                    <a href="{{ route('pms.dashboard') }}">Home</a>
                </li>
                <li><a href="#">PMS</a></li>
                <li class="active">Accounts</li>
                <li class="active">{{ $title }}</li>

                <li class="top-nav-btn">
                    <a href="{{ url('accounting/leases/create') }}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="New Lease"> <i class="las la-plus"></i>&nbsp;New Lease</i></a>
                </li>
            </ul>
        </div>

        <div class="page-content">
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

    function leaseDetails(element) {
        $.dialog({
            title: "Lease #"+(element.text()),
            content: "url:{{ url('accounting/leases') }}/"+(element.attr('data-id'))+"?details",
            animation: 'scale',
            columnClass: 'col-md-6 offset-3',
            closeAnimation: 'scale',
            backgroundDismiss: true
        });
    }
</script>
@endsection