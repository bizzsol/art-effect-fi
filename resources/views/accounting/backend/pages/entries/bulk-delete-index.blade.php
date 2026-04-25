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
                        <div class="row">
                                <div class="col-md-4">
                                    <a class="btn btn-warning btn-xs mr-2" target="__blank"
                                       href="{{route('accounting.entries.index')}}"><i class="la la-list"></i>
                                        All Transaction</a>
                                </div>

                                <div class="col-md-4">
                                    <a class="btn btn-info btn-xs" target="__blank"
                                       href="{{route('accounting.entries.bulk-delete.logs')}}"><i class="la la-list"></i>
                                        Logs</a>
                                </div>
                                
                            </div>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-2 p-3">
                    <div class="panel-body">
                        <form action="{{ url('accounting/entries-bulk-delete') }}" method="get">
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
                                        <label for="currency_id_"><strong>Currency</strong></label>
                                        <select name="currency_id" id="currency_id_" class="form-control">
                                            <option value="{{ null }}">All Currencies</option>
                                            @if(isset($currencies[0]))
                                                @foreach($currencies as $key => $currency)
                                                    <option value="{{ $currency->id }}" {{ request()->get('currency_id') == $currency->id ? 'selected' : '' }}>{{ $currency->code }}</option>
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
                                <div class="col-md-4 ledger-parent">
                                    <div class="form-group ledger">
                                        <label for="debit_ledger_id_"><strong>Debit Ledger</strong></label>
                                        <select name="debit_ledger_id" id="debit_ledger_id_"
                                                class="form-control select-me"
                                                data-selected="{{ request()->get('debit_ledger_id') }}"
                                                onchange="getSubLedgers($(this))">
                                            <option value="{{ null }}">All Debit Ledgers</option>
                                        </select>
                                    </div>
                                    <div class="form-group sub-ledger mt-2" style="display: none">
                                        <select name="debit_sub_ledger_id" class="form-control sub-ledger-select2"
                                                data-selected="{{ request()->get('debit_sub_ledger_id') }}">
                                            <option value="{{ null }}">Without Sub-Ledger</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 ledger-parent">
                                    <div class="form-group ledger">
                                        <label for="credit_ledger_id_"><strong>Credit Ledger</strong></label>
                                        <select name="credit_ledger_id" id="credit_ledger_id_"
                                                class="form-control select-me"
                                                data-selected="{{ request()->get('credit_ledger_id') }}"
                                                onchange="getSubLedgers($(this))">
                                            <option value="{{ null }}">All Credit Ledgers</option>
                                        </select>
                                    </div>
                                    <div class="form-group sub-ledger mt-2" style="display: none">
                                        <select name="credit_sub_ledger_id" class="form-control sub-ledger-select2"
                                                data-selected="{{ request()->get('credit_sub_ledger_id') }}">
                                            <option value="{{ null }}">Without Sub-Ledger</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="status_"><strong>Status</strong></label>
                                        <select name="status" id="status_" class="form-control">
                                            <option value="{{ null }}">All Status</option>
                                            <option value="approved" {{ request()->get('status') == 'approved' ? 'selected' : '' }}>
                                                Approved
                                            </option>
                                            @if(isset($approvalLevels[0]))
                                                @foreach($approvalLevels as $approvalLevel)
                                                    <option value="{{ $approvalLevel->name }}-approved" {{ request()->get('status') == $approvalLevel->name.'-approved' ? 'selected' : '' }}>{{ $approvalLevel->name }}
                                                        Approved
                                                    </option>
                                                    <option value="{{ $approvalLevel->name }}-pending" {{ request()->get('status') == $approvalLevel->name.'-pending' ? 'selected' : '' }}>{{ $approvalLevel->name }}
                                                        Pending
                                                    </option>
                                                    <option value="{{ $approvalLevel->name }}-denied" {{ request()->get('status') == $approvalLevel->name.'-denied' ? 'selected' : '' }}>{{ $approvalLevel->name }}
                                                        Denied
                                                    </option>
                                                @endforeach
                                            @endif

                                            <option value="deleted" {{ request()->get('status') == 'deleted' ? 'selected' : '' }}>
                                                Deleted
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 pt-4">
                                    <div class="btn-group mt-2" style="width: 100%">
                                        <button type="submit" class="btn btn-success btn-sm" style="width: 50%"><i
                                                    class="las la-search"></i>&nbsp;Search
                                        </button>
                                        <a href="{{ url('accounting/entries-bulk-delete') }}" class="btn btn-danger btn-sm"
                                           style="width: 50%"><i class="las la-times"></i>&nbsp;Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if(!$searched)
                        {{-- Placeholder shown before the user runs any search --}}
                        <div class="text-center py-5" style="color: #aaa;">
                            <i class="las la-search" style="font-size: 48px;"></i>
                            <h4 class="mt-3" style="font-weight: 400;">Select your filters above and click <strong>Search</strong> to load entries.</h4>
                        </div>
                    @else
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><strong>Results</strong></h4>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="btn-group">
                                        <!-- Select / Deselect dropdown -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                <i class="las la-check-square"></i>&nbsp;Select
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" id="select-all"><i class="las la-check-square"></i>&nbsp;Select All</a></li>
                                                <li><a href="#" id="deselect-all"><i class="las la-square"></i>&nbsp;Deselect All</a></li>
                                            </ul>
                                        </div>

                                        @if(request()->get('status') == 'deleted')
                                            <!-- Restore button (only visible in deleted mode) -->
                                            <button type="button" id="bulk-restore" class="btn btn-success btn-sm ml-1">
                                                <i class="las la-trash-restore"></i>&nbsp;Bulk Restore
                                            </button>
                                        @else
                                            <!-- Delete button (visible for normal / non-deleted entries) -->
                                            <button type="button" id="bulk-delete" class="btn btn-danger btn-sm ml-1">
                                                <i class="las la-trash"></i>&nbsp;Bulk Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
        $(document).on('click', '#select-all', function () {
            $('.entry-ids').prop('checked', true);
        });

        $(document).on('click', '#deselect-all', function () {
            $('.entry-ids').prop('checked', false);
        });

        $(document).on('click', '.entry-ids', function () {
            if ($(this).prop('checked')) {
                $(this).parent().parent().addClass('selected');
            } else {
                $(this).parent().parent().removeClass('selected');
            }
        });

        $(document).on('click', '#bulk-delete', function () {
            var ids = [];
            $('.entry-ids:checked').each(function () {
                ids.push($(this).val());
            });
            if (ids.length === 0) {
                toastr.error('Please select at least one entry to delete.');
                return;
            }

            if (!confirm('Are you sure you want to delete ' + ids.length + ' entry/entries? This action can be restored later.')) {
                return;
            }

            $.ajax({
                url: "{{ route('accounting.entries.bulk-delete.destroy') }}",
                type: "DELETE",
                data: {
                    ids: ids,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);

                        // Show fiscal year warnings if any
                        if (response.warnings && response.warnings.length > 0) {
                            $.each(response.warnings, function (i, warning) {
                                toastr.warning(warning, 'Fiscal Year Warning', { timeOut: 10000, closeButton: true });
                            });
                        }

                        $('.entry-ids').prop('checked', false);
                        reloadDatatable();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    toastr.error('An unexpected error occurred during bulk deletion.');
                }
            });
        });

        $(document).on('click', '#bulk-restore', function () {
            var ids = [];
            $('.entry-ids:checked').each(function () {
                ids.push($(this).val());
            });
            if (ids.length === 0) {
                toastr.error('Please select at least one entry to restore.');
                return;
            }

            if (!confirm('Restore ' + ids.length + ' entry/entries?')) {
                return;
            }

            $.ajax({
                url: "{{ route('accounting.entries.bulk-delete.restore') }}",
                type: "POST",
                data: {
                    ids: ids,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        $('.entry-ids').prop('checked', false);
                        reloadDatatable();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    toastr.error('An unexpected error occurred during bulk restore.');
                }
            });
        });





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

        function getSubLedgers(element, div) {
            var subLedgers = '<option value="{{ null }}">Without Sub-Ledger</option>';
            element.parent().parent().find('.sub-ledger-select2').html(subLedgers);
            element.parent().parent().find('.sub-ledger').hide();

            $.ajax({
                url: "{{ url('accounting/entries/create?get-sub-ledgers') }}&chart_of_account_id=" + element.val(),
                type: 'GET',
                dataType: 'json',
                data: {},
            })
                .done(function (response) {
                    if (response.count > 0) {
                        $.each(response.sub_ledgers, function (index, sub_ledger) {
                            subLedgers += '<option value="' + sub_ledger.id + '" ' + (element.parent().parent().find('.sub-ledger-select2').attr('data-selected') == sub_ledger.id ? 'selected' : '') + '>[' + sub_ledger.code + '] ' + sub_ledger.name + '</option>';
                        });

                        element.parent().parent().find('.sub-ledger-select2').html(subLedgers);
                        element.parent().parent().find('.sub-ledger').show();
                    }
                });
        }

        function getLedgers() {
            $('#debit_ledger_id_').html('<option value="{{ null }}">Please wait...</option>');
            $('#credit_ledger_id_').html('<option value="{{ null }}">Please wait...</option>');
            $.ajax({
                url: "{{ url('accounting/entries') }}?get-ledgers&company_id=" + $('#company_id_').val(),
                type: 'GET',
                data: {},
            })
                .done(function (response) {
                    $('#debit_ledger_id_').html('<option value="{{ null }}">All Debit Ledgers</option>' + response.coa);
                    $('#credit_ledger_id_').html('<option value="{{ null }}">All Credit Ledgers</option>' + response.coa);

                    $.each($('.select-me'), function (index, val) {
                        $(this).select2().val($(this).attr('data-selected')).trigger("change");
                    });

                    var fiscal_year_id = parseInt("{{ request()->get('fiscal_year_id') > 0 ? request()->get('fiscal_year_id') : 0 }}");
                    if (fiscal_year_id == 0) {
                        $('#fiscal_year_id_').select2().val(response.fy.id).trigger("change");
                    }
                });
        }

        function printDates() {
            $('#from_').val($('#fiscal_year_id_').find(':selected').attr('data-start'));
            $('#to_').val($('#fiscal_year_id_').find(':selected').attr('data-end'));
        }

        // Load ledgers after DOM is ready (non-blocking)
        $(document).ready(function () {
            getLedgers();
            
            // Load failed log count asynchronously
            $.ajax({
                url: "{{ url('accounting/entries') }}?get-failed-log-count",
                type: 'GET',
                dataType: 'json',
            }).done(function(response) {
                if (response.count > 0) {
                    $('#failed-log-badge').html('<span class="badge badge-danger badge-pill" style="margin-top: -5px; font-weight: bold; border: 1px solid white;">' + response.count + '</span>');
                }
            });

            $.each($('.select-me'), function (index, val) {
                $(this).select2().val($(this).attr('data-selected')).trigger("change");
            });
        });
    </script>
    @include('accounting.backend.pages.approval-scripts')
@endsection