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
                    <div class="col-md-6 pt-3">
                        <form action="{{ url('accounting/chart-of-accounts') }}" method="get">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select name="company_id" id="company_id" name="form-control">
                                            @if(isset($companies[0]))
                                            @foreach($companies as $key => $company)
                                            <option value="{{ $company->id }}" {{ $company_id == $company->id ? 'selected' : '' }}>[{{ $company->code }}] {{ $company->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="level" id="level" name="form-control">
                                            @for($i=1;$i<=$levels;$i++)
                                            <option value="{{ $i }}" {{ $level == $i ? 'selected' : '' }}>Level {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-block btn-success"><i class="las la-search"></i>&nbsp;Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 pt-3">
                        <a class="btn btn-sm btn-info pull-right ml-2 show-ledger-details" style="display: none"
                           onclick="showLedgerDetails()"><i class="las la-plus-square"></i>&nbsp;Ledger Details</a>
                        <a class="btn btn-sm btn-danger pull-right ml-2 hide-ledger-details"
                           onclick="hideLedgerDetails()"><i class="las la-minus-square"></i>&nbsp;Ledger Details</a>

                        @can('chart-of-accounts-create')
                            <a class="btn btn-sm btn-success pull-right ml-2"
                               href="{{ url('accounting/chart-of-accounts/create') }}"><i class="la la-plus"></i>&nbsp;New Ledger</a>
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
                        <div class="col-md-12">
                            <div id="jstree">
                                <ul>{!! $accountGroupTree !!}</ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info mt-3 chart-of-accounts-details">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-0">
                                <input type="text" name="search" id="search" placeholder="Search Chart of Accounts here..." class="form-control" onkeyup="searchCOA($(this))" onchange="searchCOA($(this))">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-sm btn-block btn-success" href="{{ url('accounting/chart-of-accounts?company_id='.$company_id.'&level='.$level.'&pdf') }}" target="_blank"><i class="lar la-file-pdf"></i>&nbsp;Download PDF</a>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-sm btn-block btn-info" onclick="exportReportToExcel('{{ $title }}')"><i class="lar la-file-excel"></i>&nbsp;Download Excel</a>
                        </div>
                        <div class="col-md-12 mt-2">
                            <table class="table table-head export-table" cellspacing="0" width="100%" id="dataTable">
                                <thead>
                                    <tr>
                                        <th style="width: 20%">Account Code</th>
                                        <th style="width: 27.5%">Account Name</th>
                                        <th class="text-center" style="width: 7.5%">Type</th>
                                        <th class="text-center" style="width: 7.5%">Class</th>
                                        <th class="text-center" style="width: 20%">Companies</th>
                                        <th class="text-center" style="width: 7.5%">Status</th>
                                        <th class="text-center" style="width: 10%">Actions</th>
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
                title: "Are you sure ?",
                text: "Once you delete, You can not recover this data and related files.",
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
                                swal({
                                    icon: 'error',
                                    text: response.message,
                                    button: false
                                });
                                setTimeout(() => {
                                    swal.close();
                                }, 1500);
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