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
                        <form action="{{ url('accounting/failed-logs-entries') }}" method="get">
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
                                        <label>Source</label>
                                        <select name="source" id="source" class="form-control">
                                            <option value="">All</option>
                                            @foreach($sources as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 pt-4">
                                    <div class="btn-group mt-2" style="width: 100%">
                                        <button type="submit" class="btn btn-success btn-sm" style="width: 50%"><i
                                                    class="las la-search"></i>&nbsp;Search
                                        </button>
                                        <a href="{{ url('accounting/failed-logs-entries') }}"
                                           class="btn btn-danger btn-sm"
                                           style="width: 50%"><i class="las la-times"></i>&nbsp;Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if($company_id > 0)
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
    <script>
        function fixEntry(id) {
            if(!confirm('Are you sure you want to try to fix this entry automatically?')) return;

            $.ajax({
                url: "{{ url('accounting/failed-logs') }}/" + id + "/fix",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $('button').prop('disabled', true);
                },
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                        $('button').prop('disabled', false);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while fixing the entry.');
                    $('button').prop('disabled', false);
                }
            });
        }

        function ignoreEntry(id) {
            if(!confirm('Are you sure you want to ignore this entry?')) return;

            $.ajax({
                url: "{{ url('accounting/failed-logs') }}/" + id + "/ignore",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $('button').prop('disabled', true);
                },
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                        $('button').prop('disabled', false);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while ignoring the entry.');
                    $('button').prop('disabled', false);
                }
            });
        }

        function deleteFailedLog(id) {
            if(!confirm('Are you sure you want to delete this failed log? This action cannot be undone.')) return;

            $.ajax({
                url: "{{ url('accounting/failed-logs') }}/" + id,
                method: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $('button').prop('disabled', true);
                },
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href = "{{ route('accounting.failed.entries.logs') }}";
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                        $('button').prop('disabled', false);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while deleting the log.');
                    $('button').prop('disabled', false);
                }
            });
        }
    </script>
@endsection