@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
            font-size: 14px;
            font-weight: 600;
        }

        .jstree-anchor {
            pointer-events: none !important;
        }
    </style>
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
                <div class="row" style="margin-top: -15px">
                    <div class="col-md-4">
                        <div class="row pl-3">
                            <div class="col-md-12 pt-0">
                                @include('accounting.backend.pages.reports.buttons', [
                                    'title' => $title,
                                    'url' => url('accounting/chart-of-accounts'),
                                    'searchHide' => true,
                                    'clearHide' => true,
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 pt-3">
                        <div class="form-group" id="search-div">
                            <input type="text" name="search" id="search" placeholder="Search Chart of Accounts here..."
                                   class="form-control" onkeyup="searchCOA($(this))" onchange="searchCOA($(this))">
                        </div>
                    </div>
                    <div class="col-md-4 pt-3">
                        <a class="btn btn-sm btn-info pull-right ml-2 show-ledger-details" style="display: none"
                           onclick="showLedgerDetails()"><i class="las la-plus-square"></i>&nbsp;Ledger Details</a>
                        <a class="btn btn-sm btn-danger pull-right ml-2 hide-ledger-details"
                           onclick="hideLedgerDetails()"><i class="las la-minus-square"></i>&nbsp;Ledger Details</a>

                        @can('chart-of-accounts-create')
                            <a class="btn btn-sm btn-success pull-right ml-2"
                               href="{{ url('accounting/chart-of-accounts/create') }}"><i class="la la-plus"></i>&nbsp;New
                                Ledger</a>
                        @endcan

                        @can('account-groups-create')
                            <a class="btn btn-sm btn-primary pull-right"
                               href="{{ url('accounting/account-groups/create') }}"><i class="la la-plus"></i>&nbsp;New
                                Group</a>
                        @endcan
                    </div>
                </div>

                <div class="panel panel-info mt-3 pt-4 pb-4 pl-3 chart-of-accounts-tree" style="display: none">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <strong>Levels >> </strong>
                            <div class="btn-group mr-2" role="group" aria-label="Chart of Accounts Levels">
                                @for($i=1;$i<=$levels;$i++)
                                    <a class="btn btn-xs {{ $i <= $level ? 'btn-success' : 'btn-dark' }} p-3 mr-2"
                                       href="{{ url('accounting/chart-of-accounts') }}?level={{ $i }}">{{ $i }}</a>
                                @endfor
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="jstree">
                                <ul>{!! $accountGroupTree !!}</ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info mt-3 export-table chart-of-accounts-details">
                    <table class="table table-head" cellspacing="0" width="100%" id="dataTable">
                        <thead>
                        <tr>
                            <th style="width: 15%">{{__('Account Code')}}</th>
                            <th style="width: 30%">{{__('Account Name')}}</th>
                            <th class="text-center" style="width: 12.5%">{{__('Type')}}</th>
                            <th class="text-center" style="width: 12.5%">{{__('Class')}}</th>
                            <th class="text-center" style="width: 15%">{{__('Status')}}</th>
                            <th class="text-center" style="width: 15%">{{__('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {!! $accountGroups !!}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('accounting.backend.pages.approval-scripts')

    <script type="text/javascript">
        function showAlert(status, erro) {
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
        }

        function deleteMe(element) {
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
                    var row_class = element.attr('data-row-class');
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
                                $('.' + row_class).remove();
                            } else {
                                showAlert('error', response.message);
                                return;
                            }
                        },
                    });
                }
            });
        }

        function showLedgerDetails() {
            $('.show-ledger-details').hide();
            $('.hide-ledger-details').show();
            $('.chart-of-accounts-tree').hide('slow');
            $('.chart-of-accounts-details').show('slow');

            $('#search-div').show('slow');
        }

        function hideLedgerDetails() {
            $('.hide-ledger-details').hide();
            $('.show-ledger-details').show();
            $('.chart-of-accounts-tree').show('slow');
            $('.chart-of-accounts-details').hide('slow');

            $('#search-div').hide('slow');
        }

        function searchCOA(element) {
            var search = element.val().trim().toLowerCase();

            $.each($('.coa'), function (index, val) {
                var code = $(this).attr('data-code').trim().toLowerCase();
                var name = $(this).attr('data-name').trim().toLowerCase();

                if (code.indexOf(search) !== -1 || name.indexOf(search) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    </script>
@endsection