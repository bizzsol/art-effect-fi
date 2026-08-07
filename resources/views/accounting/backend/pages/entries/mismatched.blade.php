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
                    <li><a href="#">Accounting</a></li>
                    <li class="active">Accounts</li>
                    <li class="active">{{ __($title) }}</li>
                    <li class="top-nav-btn">

                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-2 p-3">
                    <div class="panel-body">
                        <form action="{{ url('accounting/mitch-match-entries') }}" method="get">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="company_id_"><strong>Company</strong></label>
                                        <select name="company_id" id="company_id_" class="form-control"
                                                onchange="getLedgers();">
                                            @if(isset($companies[0]))
                                                @foreach($companies as $key => $company)
                                                    <option value="{{ $company->id }}" {{ request()->get('company_id') == $company->id ? 'selected' : '' }}>
                                                        [{{ $company->code }}] {{ $company->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="entry_type_id_"><strong>Entry Type</strong></label>
                                        <select name="entry_type_id" id="entry_type_id_" class="form-control">
                                            <option value="{{ null }}">All Entry Types</option>
                                            @if(isset($entryTypes[0]))
                                                @foreach($entryTypes as $key => $entryType)
                                                    <option value="{{ $entryType->id }}" {{ request()->get('entry_type_id') == $entryType->id ? 'selected' : '' }}>{{ $entryType->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fiscal_year_id_"><strong>Fiscal Year</strong></label>
                                        <select name="fiscal_year_id" id="fiscal_year_id_" class="form-control"
                                                onchange="printDates()">
                                            @if(isset($fiscalYears[0]))
                                                @foreach($fiscalYears as $key => $fiscalYear)
                                                    <option value="{{ $fiscalYear->id }}"
                                                            {{  $fiscal_year_id == $fiscalYear->id ? 'selected' : '' }} data-start="{{ $fiscalYear->start }}"
                                                            data-end="{{ $fiscalYear->end }}">{{ $fiscalYear->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="from_"><strong>Date From</strong></label>
                                        <input type="date" name="from" id="from_" class="form-control"
                                               value="{{ $from }}"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="to_"><strong>Date To</strong></label>
                                        <input type="date" name="to" id="to_" class="form-control" value="{{ $to }}"/>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="kind"><strong>Issue Type</strong></label>
                                        <select name="kind" class="form-control" id="kind">
                                            <option value="">All types</option>
                                            @foreach($kindLabels as $key => $label)
                                                <option value="{{ $key }}" {{ request()->get('kind') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="missing_side"><strong>Search Text</strong></label>
                                        <input type="text" name="search_text" class="form-control" id="search_text"
                                               placeholder="Search..." value="{{ request()->get('search_text') }}"/>
                                    </div>
                                </div>

                                <div class="col-md-2 pt-4">
                                    <div class="btn-group mt-2" style="width: 100%">
                                        <button type="submit" class="btn btn-success btn-sm" style="width: 50%"><i
                                                    class="las la-search"></i>&nbsp;Search
                                        </button>
                                        <a href="{{ url('accounting/mitch-match-entries') }}"
                                           class="btn btn-danger btn-sm"
                                           style="width: 50%"><i class="las la-times"></i>&nbsp;Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if($company_id > 0)
                        {{-- Summary chips. Each one filters the table below by
                             classification; orphan vouchers are part of that table
                             now rather than a separate panel underneath it. --}}
                        <div class="panel-body pb-0">
                            @php
                                $qs = request()->except(['kind', 'page']);
                                $chip = function ($k, $label, $colour, $count) use ($qs) {
                                    $active = request()->get('kind') === $k;
                                    $url = url('accounting/mitch-match-entries') . '?' . http_build_query(array_merge($qs, $k ? ['kind' => $k] : []));
                                    return '<a href="' . $url . '" class="btn btn-sm ' . ($active ? 'btn-' . $colour : 'btn-outline-' . $colour) . ' mr-2 mb-2">'
                                        . $label . ' <span class="badge badge-light">' . number_format($count) . '</span></a>';
                                };
                            @endphp

                            {!! $chip(null, 'All issues', 'dark', $summary['total']) !!}
                            {!! $chip('one_sided', 'One-sided', 'danger', $summary['one_sided']) !!}
                            {!! $chip('unbalanced', 'Both sides, unbalanced', 'info', $summary['unbalanced']) !!}
                            {!! $chip('orphan_coa', 'Orphan (ledger deleted)', 'warning', $summary['orphan_coa']) !!}

                            @if(abs($summary['net_difference']) > 0.005)
                                <span class="float-right pt-1">
                                    <strong>Net out of balance:</strong>
                                    <span class="text-danger">{{ number_format($summary['net_difference'], 2) }}</span>
                                </span>
                            @endif

                            @if($summary['orphan_coa'] > 0)
                                <div class="alert alert-warning py-2 mt-1 mb-0">
                                    <small>
                                        <strong>Orphan vouchers can look perfectly balanced.</strong>
                                        A line sitting on a deleted ledger is dropped by every report
                                        (<code>chart_of_accounts.deleted_at IS NULL</code>), so only the surviving half
                                        reaches the trial balance. Restoring the ledger, or moving those entries to a live
                                        one, is what fixes it &mdash; the voucher itself may need no correction at all.
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="panel-body">
                            @include('yajra.datatable')
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

@endsection

@section('page-script')
    @include('yajra.js')
    <script type="text/javascript">
        function getShortDetails(element) {
            $.dialog({
                title: (element.attr('data-entry-type')) + " Voucher #" + (element.attr('data-code')),
                content: "url:{{ url('accounting/entries') }}/" + (element.attr('data-id')) + "?short-details",
                animation: 'scale',
                columnClass: 'col-md-12',
                closeAnimation: 'scale',
                backgroundDismiss: true
            });
        }


        function printDates() {
            $('#from_').val($('#fiscal_year_id_').find(':selected').attr('data-start'));
            $('#to_').val($('#fiscal_year_id_').find(':selected').attr('data-end'));
        }

        $(document).ready(function () {
            $.each($('.select-me'), function (index, val) {
                $(this).select2().val($(this).attr('data-selected')).trigger("change");
            });
        });
    </script>
@endsection