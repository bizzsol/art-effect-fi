@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .dropdown-toggle::after {
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
                    <li class="active">{{__($title)}}</li>
                    <li class="top-nav-btn">
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="">
                    <div class="panel panel-info">
                        <div id="accordion">
                            <div class="card">
                                <div class="card-header bg-primary p-0" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#filter"
                                                aria-expanded="true" aria-controls="filter">
                                            <h5 class="text-white"><strong><i class="las la-chevron-circle-right la-spin"></i>&nbsp;Filters</strong>
                                            </h5>
                                        </button>
                                    </h5>
                                </div>

                                <div id="filter" class="collapse show"
                                     aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                        <form action="{{ url('accounting/requisition-finance-approval') }}" method="get" accept-charset="utf-8">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="status"><strong>Status</strong></label>
                                                        <select name="status" id="status" class="form-control">
                                                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Waiting for Approval</option>
                                                            <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                            <option value="denied" {{ $status == 'denied' ? 'selected' : '' }}>Denied</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="from"><strong>Start Date</strong></label>
                                                        <input type="date" name="from" id="from" value="{{ $from }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="to"><strong>End Date</strong></label>
                                                        <input type="date" name="to" id="to" value="{{ $to }}"
                                                               class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-1 pt-1 pl-0 mt-4">
                                                    <button class="btn btn-md mt-1 btn-block btn-success report-button"
                                                            type="submit"><i class="la la-search"></i>&nbsp;Search
                                                    </button>
                                                </div>
                                                <div class="col-md-1 pt-1 pl-0 mt-4">
                                                    <a class="btn btn-md mt-1 btn-block btn-danger"
                                                       href="{{ url('accounting/requisition-finance-approval') }}"><i
                                                                class="la la-times"></i>&nbsp;Clear</a>
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

    <div class="modal" id="requisitionDetailModal">
        <div class="modal-dialog modal-lg" style="max-width: 80% !important;">
            <div class="modal-content" style="max-width: 100% !important;">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Requisition Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body" id="tableData">

                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('page-script')
    @include('yajra.js')
    <script>
        function financeApproval(element) {
            swal({
                title: "Are you sure ?",
                text: "Once you Approve, You can not rollback from there.'",
                icon: "warning",
                dangerMode: false,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: 'Approve',
                        value: true,
                        visible: true,
                        closeModal: true,
                        className: 'bg-success'
                    },
                },
            })
            .then((value) => {
                if (value) {
                    $.ajax({
                        type: 'POST',
                        url: element.attr('data-src'),
                        dataType: "json",
                        data: {
                            _token: '{!! csrf_token() !!}',
                            requisition_id: element.attr('data-id')
                        }
                    })
                    .done(function(response) {
                        if (response.success) {
                            notify(response.message, 'success');
                            reloadDatatable();
                        } else {
                            notify(response.message, 'error');
                        }
                    });
                    return false;
                }
            });
        }

        function financeDeniel(element) {
            $.confirm({
                title: 'Deny Requisition',
                content: '' +
                '<hr class="mt-0 pt-0">'+
                '<form action="" class="formName">' +
                    '<div class="form-group">' +
                        '<textarea placeholder="Your Comments" class="comments form-control" required rows="5"></textarea>' +
                    '</div>' +
                '</form>',
                buttons: {
                    deny: {
                        text: 'Deny',
                        btnClass: 'btn-red',
                        action: function () {
                            var comments = this.$content.find('.comments').val();
                            if(!comments){
                                $.alert('Please Write some comments.');
                                return false;
                            }

                            $.ajax({
                                type: 'POST',
                                url: element.attr('data-src'),
                                dataType: "json",
                                data: {
                                    _token: '{!! csrf_token() !!}',
                                    requisition_id: element.attr('data-id'),
                                    comments: comments
                                }
                            })
                            .done(function(response) {
                                if (response.success) {
                                    notify(response.message, 'success');
                                    reloadDatatable();
                                } else {
                                    notify(response.message, 'error');
                                }
                            });
                        }
                    },
                    close: {
                        text: 'Close',
                        btnClass: 'btn-dark',
                        action: function () {
                            
                        }
                    },
                }
            });
        }

        function openModal(requisitionId) {
            $('#tableData').load('{{URL::to(Request()->route()->getPrefix()."/store-inventory-compare")}}/' + requisitionId);
            $('#requisitionDetailModal').modal('show');
        }
    </script>
@endsection