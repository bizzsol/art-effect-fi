@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .dropdown-toggle::after{
        display: none !important;
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
              <li class="active">{{__($title)}} List</li>
              <li class="top-nav-btn">
                    <a class="btn btn-sm btn-info text-white readAllNotification" data-toggle="tooltip" title="Back" > <i class="las la-check-circle"></i>Read All Notification</a>
                </li>
          </ul>
      </div>

    <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <div class="panel-body">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
<script>

    $('.readAllNotification').on('click', function () {
        $.ajax({
            url: "{{ url('pms/requisition/mark-as-read-all') }}",
            type: 'POST',
            dataType: 'json',
            data: {_token: "{{ csrf_token() }}"},
        })
        .done(function(response) {
            if(response.result=='success'){
                $('.type').html('<span class="btn btn-sm btn-success"><i class="las la-check-circle"></i></span>');
                $('.action').html('Already Read');
                notify(response.message,response.result);
                $('.iq-card-body').hide();
                $('.readAllNotification').hide();
                $('.notification-count').html(response.total_notification);
                
            }else if(response.result=='error'){
                notify(response.message,'error');
            }
        })
        .fail(function(response){
            notify('Something went wrong!','error');
        });
        return false;
    });

    function markAsRead(element){
        let id = element.attr('data-id');
        
        $.ajax({
            url: "{{ url('pms/requisition/mark-as-read') }}",
            type: 'POST',
            dataType: 'json',
            data: {_token: "{{ csrf_token() }}", id:id},
        })
        .done(function(response) {
            if(response.result=='success'){
                $('#type'+id).html('<span class="btn btn-sm btn-success"><i class="las la-check-circle"></i></span>');
                $('#action'+id).html('Already Read');
                notify(response.message,response.result);
                $('#read'+id).hide();
                $('.notification-count').html(response.total_notification);
                $('.iq-card-body').hide();
            }else if(response.result=='error'){
                notify(response.message,'error');
            }
        })
        .fail(function(response){
            notify('Something went wrong!','error');
        });
        return false;
    }
</script>
@endsection
