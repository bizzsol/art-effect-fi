@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
            font-size: 14px;
            font-weight: 600;
        }

        .entry-badge {
            display: inline-block;
            margin: 1px 2px;
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
                    <li class="active"><a href="{{ url('accounting/chart-of-accounts') }}">Chart of Accounts</a></li>
                    <li class="active">{{__('Trash')}}</li>
                </ul>
            </div>

            <div class="page-content">

                @include('accounting.backend.pages.chartOfAccounts.partials.reclose-banner')

                <div class="row" style="margin-top: -15px">
                    <div class="col-md-6 pt-3">
                        <form action="{{ url('accounting/chart-of-accounts-trash') }}" method="get">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <select name="company_id" id="company_id" class="form-control">
                                            <option value="">All Companies</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" {{ $company_id == $company->id ? 'selected' : '' }}>[{{ $company->code }}] {{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-block btn-success"><i class="las la-search"></i>&nbsp;Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 pt-3">
                        <a class="btn btn-sm btn-primary pull-right ml-2" href="{{ url('accounting/chart-of-accounts') }}">
                            <i class="la la-arrow-left"></i>&nbsp;Back to Chart of Accounts</a>

                        @can('chart-of-accounts-logs')
                            <a class="btn btn-sm btn-info pull-right" href="{{ url('accounting/chart-of-accounts-logs') }}">
                                <i class="las la-history"></i>&nbsp;Change Logs</a>
                        @endcan
                    </div>
                </div>

                <div class="panel panel-info mt-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <input type="text" name="search" id="search" placeholder="Search deleted ledgers here..."
                                       class="form-control" onkeyup="searchCOA($(this))" onchange="searchCOA($(this))">
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <table class="table table-head" cellspacing="0" width="100%" id="dataTable">
                                <thead>
                                    <tr>
                                        <th style="width: 12%">Account Code</th>
                                        <th style="width: 20%">Account Name</th>
                                        <th class="text-center" style="width: 6%">Type</th>
                                        <th class="text-center" style="width: 8%">Class</th>
                                        <th class="text-center" style="width: 16%">Entries</th>
                                        <th class="text-center" style="width: 14%">Companies</th>
                                        <th class="text-center" style="width: 10%">Deleted By</th>
                                        <th class="text-center" style="width: 8%">Deleted At</th>
                                        <th class="text-center" style="width: 6%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($accounts as $account)
                                    @php
                                        $rows = $usage->get($account->id, collect());
                                        $totalItems = $rows->sum('total_items');
                                    @endphp
                                    <tr class="account-{{ $account->id }} coa" data-code="{{ $account->code }}" data-name="{{ $account->name }}">
                                        <td>{{ $account->code }}</td>
                                        <td>{{ $account->name }}</td>
                                        <td class="text-center">Ledger</td>
                                        <td class="text-center"><strong>{{ optional(optional($account->accountGroup)->accountClass)->name }}</strong></td>
                                        <td class="text-center">
                                            @if($totalItems == 0)
                                                <span class="text-muted">0</span>
                                            @else
                                                @foreach($rows as $row)
                                                    <span class="badge badge-{{ $row['is_attributable'] ? 'warning' : 'danger' }} entry-badge"
                                                          title="{{ $row['company_name'] }} &mdash; current: {{ $row['live_items'] }}, superseded by edits: {{ $row['superseded_items'] }}, on deleted vouchers: {{ $row['voucher_deleted_items'] }}">
                                                        {{ $row['company_code'] }}: {{ $row['total_items'] }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-center"><strong>{{ $account->companies->pluck('company.code')->filter()->implode(', ') }}</strong></td>
                                        <td class="text-center">{{ optional($account->deletedBy)->name ?: '-' }}</td>
                                        <td class="text-center">{{ $account->deleted_at ? $account->deleted_at->format('d M Y') : '-' }}</td>
                                        <td class="action-td text-center">
                                            <a class="btn btn-xs btn-info" title="Usage"
                                               onclick="showLedgerUsage({{ $account->id }})"><i class="las la-info-circle"></i></a>
                                            @can('chart-of-accounts-logs')
                                                <a class="btn btn-xs btn-secondary" title="History"
                                                   onclick="showLedgerHistory({{ $account->id }}, '{{ $account->code }}')"><i class="las la-history"></i></a>
                                            @endcan
                                            <a class="btn btn-xs btn-success" title="Restore"
                                               onclick="restoreLedger($(this))"
                                               data-src="{{ route('accounting.chart-of-accounts.restore', $account->id) }}"
                                               data-row-class="account-{{ $account->id }}"
                                               data-code="{{ $account->code }}"><i class="las la-trash-restore"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">No deleted ledgers.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('accounting.backend.pages.chartOfAccounts.partials.usage-modal')
    @include('accounting.backend.pages.chartOfAccounts.partials.history-modal')

    <script type="text/javascript">
        function restoreLedger(element) {
            swal({
                title: "Restore this ledger?",
                text: "Ledger " + element.attr('data-code') + " will be returned to the Chart of Accounts.",
                icon: "warning",
                buttons: {
                    cancel: true,
                    confirm: {text: "Restore", value: true, visible: true, closeModal: true},
                },
            }).then((value) => {
                if (!value) return;

                var row_class = element.attr('data-row-class');
                $.ajax({
                    type: 'POST',
                    url: element.attr('data-src'),
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            swal({icon: 'success', text: response.message, button: false});
                            setTimeout(() => { swal.close(); }, 1800);
                            $('.' + row_class).remove();
                        } else {
                            swal({icon: 'error', text: response.message, button: 'OK'});
                        }
                    },
                    error: function (xhr) {
                        swal({icon: 'error', text: 'Restore failed (' + xhr.status + ').', button: 'OK'});
                    }
                });
            });
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
