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
            <div class="row mb-2">
                <div class="col-md-12">
                    @if(request()->has('closed'))
                    <a class="btn btn-sm btn-success pull-right ml-2" href="{{ url('accounting/po-closing-activities') }}" style="float: right"><i class="las la-folder-open"></i>&nbsp;Open Purchase order List</a>
                    @else
                    <a class="btn btn-sm btn-danger pull-right ml-2" href="{{ url('accounting/po-closing-activities?closed') }}" style="float: right"><i class="las la-folder-minus"></i>&nbsp;Closed Purchase order List</a>
                    @endif
                </div>
            </div>

            <div class="panel panel-info">
                @include('yajra.datatable')
            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="modal">
  <div class="modal-dialog">
    <form action="{{ route('accounting.po-closing-activities.store') }}" method="post" accept-charset="utf-8">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
</form>
</div>
</div>

@endsection

@section('page-script')
@include('yajra.js')
<script>
    function OpenModal(element) {
        $.ajax({
            url: "{{ url('accounting/po-closing-activities/create') }}?purchase_order_id="+element.attr('data-purchase-order-id'),
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