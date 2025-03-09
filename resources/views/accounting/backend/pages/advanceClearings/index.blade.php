@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style>
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
        function runAdvanceClearPostings(element) {
            var advance_id = element.attr('data-advance-id');
            $.confirm({
                title: 'Confirm!',
                content: '<hr><h6><strong>Are you sure to run Advance Clearing postings?</strong></h6>',
                buttons: {
                    yes: {
                        text: 'Yes',
                        btnClass: 'btn-green',
                        action: function () {
                            $.ajax({
                                url: "{{ route('accounting.advance-clearings.store') }}",
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: advance_id
                                },
                            })
                                .done(function (response) {
                                    if (response.success) {
                                        toastr.success(response.message);
                                    } else {
                                        toastr.error(response.message);
                                    }

                                    reloadDatatable();
                                });
                        }
                    },
                    no: {
                        text: 'No',
                        btnClass: 'btn-red',
                        action: function () {

                        }
                    }
                }
            });
        }
    </script>
@endsection