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
                <div class="col-md-4">
                    <select class="form-control" name="company_id" id="company_id" onchange="window.open('{{ url('accounting/transactions') }}?company_id='+$('#company_id').val(), '_parent')">
                        <option value="{{ null }}">All Companies</option>
                        @if(isset($companies[0]))
                        @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request()->get('company_id') == $company->id ? 'selected' : '' }}>[{{ $company->code }}] {{ $company->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-8">
                    <a class="btn btn-sm btn-success pull-right ml-2" href="{{ url('accounting/transactions/create') }}" style="float: right"><i class="la la-plus"></i>&nbsp;New Transaction</a>
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

    function viewLogs(id) {
        $.dialog({
            title: "Trabsaction Logs",
            content: "url:{{ url('accounting/transactions') }}/"+id+"?logs",
            animation: 'scale',
            columnClass: 'col-md-8',
            closeAnimation: 'scale',
            backgroundDismiss: true
        });
    }
</script>
@endsection