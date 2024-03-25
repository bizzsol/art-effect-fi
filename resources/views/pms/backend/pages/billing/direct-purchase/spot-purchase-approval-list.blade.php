@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
            font-size: 14px;
            font-weight: 600;
        }

        .list-unstyled .ratings {
            display: none;
        }

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
                    <li class="active">Bill Manage</li>
                    <li class="active">{{__($title)}}</li>
                </ul>
            </div>

            <div class="page-content">
                <div class="">
                    <div class="panel panel-info mt-2 p-2">
                        @include('yajra.datatable')
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
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Spot Purchase Price Approval</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <form action="{{route('pms.billing-audit.spot.purchase.price.approval.submit')}}" method="POST" id="po-invoice-form">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="remarks">Notes :</label>
                            <textarea class="form-control" name="remarks" rows="3" id="remarks"
                                      placeholder="Notes...."></textarea>

                            <input type="hidden" readonly required name="approvalType" id="approvalType">
                            <input type="hidden" readonly required name="attachmentId" id="attachmentId">
                            <input type="hidden" readonly required name="status" id="attachmentStatus">
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="po-invoice-button"><i class="la la-check"></i>&nbsp;Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('page-script')
    @include('yajra.js')
    <script>
        $(document).ready(function () {
            var form = $('#po-invoice-form');
            var button = $('#po-invoice-button');
            var content = button.html();

            form.submit(function (event) {
                event.preventDefault();

                button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    dataType: 'json',
                    data: form.serializeArray(),
                })
                    .done(function (response) {
                        button.html(content).prop('disabled', false);
                        if (response.success) {
                            reloadDatatable();
                            toastr.success(response.message);
                            $('#PurchaseOrderAttachmentModal').modal('hide');
                        } else {
                            toastr.error(response.message);
                        }
                    });
            });
        });

        function changeStatus(element) {
            let approvalType = element.attr('approval-type');
            let attachmentId = element.attr('approval-id');
            let status = element.val();

            $('#approvalType').val(approvalType);
            $('#attachmentId').val(attachmentId);
            $('#attachmentStatus').val(status);

            if (status != 'autoSelect') {
                return $('#PurchaseOrderAttachmentModal').modal('show').on('hidden.bs.modal', function (e) {
                    let form = document.querySelector('#PurchaseOrderAttachmentModal').querySelector('form').reset();
                });
            }
        }

        function showPODetails(element) {
            $.ajax({
                url: element.attr('data-src'),
                type: 'get',
                dataType: 'json',
                data: '',
            })
                .done(function (response) {

                    if (response.result == 'success') {
                        $('#POdetailsModel').find('#body').html(response.body);
                        $('#POdetailsModel').find('.modal-title').html(`Purchase Order Details`);
                        $('#POdetailsModel').modal('show');
                    }

                })
                .fail(function (response) {
                    notify('Something went wrong!', 'error');
                });
        }

    </script>

@endsection