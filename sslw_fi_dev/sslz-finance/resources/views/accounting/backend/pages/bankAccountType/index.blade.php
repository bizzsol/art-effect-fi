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
                <div class="row">
                    <div class="col-md-12">
                        @can('bank-account-create')
                            <a class="btn btn-sm btn-success pull-right ml-2"
                               href="{{ url('accounting/bank-account-types/create') }}" style="float: right"><i
                                        class="la la-plus"></i>&nbsp;New Account Types</a>
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
    <script>
        (function ($) {
            "use script";
            const showAlert = (status, error) => {
                swal({
                    icon: status,
                    text: error,
                    dangerMode: true,
                    buttons: {
                        cancel: false,
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            closeModal: true
                        },
                    },
                }).then((value) => {
                    if (value) form.reset();
                });
            };
        })(jQuery);

        function deleteBtn(element) {
            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Once you delete, You can not recover this data and related files.')}}",
                icon: "warning",
                dangerMode: true,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Delete",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if (value) {
                    var button = element;
                    $.ajax({
                        type: 'DELETE',
                        url: element.attr('data-src'),
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                swal({
                                    icon: 'success',
                                    text: response.message,
                                    button: false
                                });
                                setTimeout(() => {
                                    swal.close();
                                }, 1500);
                                button.parent().parent().remove();
                            } else {
                                showAlert('error', response.message);
                                return;
                            }
                        },
                    });
                }
            });
        }
    </script>
@endsection