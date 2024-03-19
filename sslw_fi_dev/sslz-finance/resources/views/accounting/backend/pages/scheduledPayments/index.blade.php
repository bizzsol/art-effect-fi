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
                <li class="active">{{__($title)}} </li>
            </ul>
        </div>
    </div>
    <div class="panel panel-info mt-2 p-2">
        <div class="row mb-3">
            <div class="col-md-12 text-right">
                <a class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#companyModal" style="float: right"><i class="la la-plus"></i>&nbsp;New Payment</a>
            </div>
        </div>
       @include('yajra.datatable')
   </div>
</div>
</div>
</div>


<div class="modal fade" id="companyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <label for="company_id"><strong>Choose Company</strong></label>
                    <select name="company_id" id="company_id" class="form-control">
                        @if($companies->count() > 0)
                        @foreach($companies as $key => $company)
                            <option value="{{ $company->id }}">[{{ $company->code }}] {{ $company->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="la la-times"></i>&nbsp;Close</button>
                    <button type="button" class="btn btn-success" onclick="window.open('{{ url('accounting/scheduled-payment/create') }}?&company='+$('#company_id').val(), '_parent')">Proceed&nbsp;<i class="las la-arrow-alt-circle-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
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
</script>
@include('yajra.js')
@endsection