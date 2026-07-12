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
                        <form action="{{ url('accounting/exchange-rate-analysis') }}" method="get">
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
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exchange_rate_id_"><strong>Exchange Rate</strong></label>
                                        <select name="exchange_rate_id" id="exchange_rate_id_" class="form-control">
                                            <option value="">All Exchange Rates</option>
                                            @if(isset($exchangeRates[0]))
                                                @foreach($exchangeRates as $er)
                                                    <option value="{{ $er->id }}" {{ request()->get('exchange_rate_id') == $er->id ? 'selected' : '' }}>
                                                        [{{ optional($er->currency)->code }}] {{ $er->reference }} ({{ $er->datetime }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label><strong>&nbsp;</strong></label>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="mismatch_only" value="1" {{ request()->boolean('mismatch_only') ? 'checked' : '' }}>
                                                Show Only Mismatches
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 pt-4">
                                    <div class="btn-group mt-2" style="width: 100%">
                                        <button type="submit" class="btn btn-success btn-sm" style="width: 50%"><i
                                                    class="las la-search"></i>&nbsp;Search
                                        </button>
                                        <a href="{{ url('accounting/exchange-rate-analysis') }}" class="btn btn-danger btn-sm"
                                           style="width: 50%"><i class="las la-times"></i>&nbsp;Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if($company_id > 0)
                        <div class="panel-body">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info" id="saveAnalysisBtn">
                                            <i class="las la-save"></i> Save Analysis to Corrections
                                        </button>
                                        <a href="{{ url('accounting/exchange-rate-analysis/export-csv') }}?{{ http_build_query(request()->except('_token')) }}"
                                           class="btn btn-sm btn-success">
                                            <i class="las la-file-excel"></i> Download CSV
                                        </a>
                                        <a href="{{ url('accounting/exchange-rate-corrections') }}" class="btn btn-sm btn-warning">
                                            <i class="las la-list"></i> View Corrections
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2" id="summaryPanel" style="display:none;">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <strong>Summary:</strong>
                                        Total Items: <span id="totalItems">0</span> |
                                        Mismatches: <span id="mismatchCount">0</span> |
                                        Total Difference: <span id="totalDifference">0.00</span>
                                    </div>
                                </div>
                            </div>

                            @include('yajra.datatable')
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="companyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <input type="hidden" name="type" id="type" value="">
                    <div class="form-group">
                        <label for="company_id"><strong>Choose Company</strong></label>
                        <select name="company_id" id="company_id" class="form-control">
                            @if($companies->count() > 0)
                                @foreach($companies as $key => $company)
                                    <option value="{{ $company->id }}">[{{ $company->code }}
                                                                       ] {{ $company->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="la la-times"></i>&nbsp;Close
                        </button>
                        <button type="button" class="btn btn-success"
                                onclick="window.open('{{ url('accounting/entries/create') }}?type='+$('#type').val()+'&company='+$('#company_id').val(), '_parent')">
                            Proceed&nbsp;<i class="las la-arrow-alt-circle-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('yajra.js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[name="mismatch_only"]').on('change', function() {
                if ($(this).is(':checked')) {
                    window.location.href = addQueryParam('mismatch_only', '1');
                } else {
                    window.location.href = removeQueryParam('mismatch_only');
                }
            });
        });

        function addQueryParam(key, value) {
            var uri = window.location.href;
            var re = new RegExp('[?&]' + key + '=.*?(?:&|$)', 'i');
            if (uri.match(re)) {
                return uri.replace(re, function(m) {
                    return m.startsWith('&') ? '&' + key + '=' + value + '&' : '?' + key + '=' + value + (m.endsWith('&') ? '&' : '');
                });
            }
            var sep = uri.indexOf('?') !== -1 ? '&' : '?';
            return uri + sep + key + '=' + value;
        }

        function removeQueryParam(key) {
            var uri = window.location.href;
            var re = new RegExp('[?&]' + key + '=.*?(&|$)', 'i');
            return uri.replace(re, function(match, end) {
                return match.startsWith('?') ? (end ? '?' : '') : end;
            });
        }

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

        $('#saveAnalysisBtn').on('click', function() {
            var btn = $(this);
            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Saving...');

            $.ajax({
                url: '{{ url("accounting/exchange-rate-analysis/save-analysis") }}',
                type: 'POST',
                data: $('form').serialize() + '&_token={{ csrf_token() }}',
                success: function(response) {
                    if (response.success) {
                        notification('success', response.message);
                    } else {
                        notification('error', response.message);
                    }
                },
                error: function(xhr) {
                    notification('error', xhr.responseJSON?.message || 'Error saving analysis');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="las la-save"></i> Save Analysis to Corrections');
                }
            });
        });

        $('.datatable-serverside').on('draw.dt', function() {
            var table = $(this).DataTable();
            var data = table.rows({filter: 'applied'}).data();
            var totalItems = data.length;
            var mismatches = 0;
            var totalDiff = 0;

            data.each(function(row) {
                var diff = parseFloat(row.difference) || 0;
                if (Math.abs(diff) > 0.01) {
                    mismatches++;
                    totalDiff += diff;
                }
            });

            if (totalItems > 0) {
                $('#totalItems').text(totalItems);
                $('#mismatchCount').text(mismatches);
                $('#totalDifference').text(totalDiff.toFixed(2));
                $('#summaryPanel').show();
            }
        });
    </script>
    @include('accounting.backend.pages.approval-scripts')
@endsection