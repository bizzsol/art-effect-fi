@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
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
        function markAsDamaged(element) {
            $.dialog({
                title: 'Mark Cheque Book Page as Damaged',
                content: "url:{{ url('accounting/cheque-books') }}/" + element.attr('data-page-id') + "/damage",
                animation: 'scale',
                columnClass: 'medium',
                closeAnimation: 'scale',
                backgroundDismiss: true,
            });
        }

        function tagTransactions(element) {
            $.dialog({
                title: 'Tag Transactions to Cheque book Page',
                content: "url:{{ url('accounting/cheque-books') }}/" + element.attr('data-page-id') + "/tag-transactions",
                animation: 'scale',
                columnClass: 'medium',
                closeAnimation: 'scale',
                backgroundDismiss: true,
            });
        }

        function markAsDamagedApproved(element) {
            let id = element.attr('data-page-id');
            let texStatus = 'Approved';

            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Once you approved, You can not recover this data.')}}",
                icon: "warning",
                dangerMode: false,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: texStatus,
                        value: true,
                        visible: true,
                        closeModal: true,
                        className: 'bg-success'
                    },
                },
            }).then((value) => {
                if (value) {
                    $.ajax({
                        url: "{{ url('accounting/cheque-books/damaged-page-approved') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {_token: "{{ csrf_token() }}", id: id},
                    })
                        .done(function (response) {
                            if (response.success) {
                                notify(response.message, 'success');
                                location.reload();
                            } else {
                                notify(response.message, 'error');
                            }
                        })
                        .fail(function (response) {
                            notify('Something went wrong!', 'error');
                        });
                    return false;
                }
            });
        }
    </script>
@endsection