@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    @include('yajra.css')
@endsection
@section('main-content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('pms.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li><a href="#">Accounting</a></li>
                <li><a href="{{ url('accounting/exchange-rate-analysis') }}">Exchange Rate Analysis</a></li>
                <li class="active">{{ __($title) }}</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-3">
                <div class="panel-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <strong>Pending Corrections:</strong> {{ $pendingCount ?? 0 }} |
                                <strong>Total Difference:</strong> {{ number_format($totalDifference ?? 0, 2) }}
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-success btn-sm" id="applyAllBtn">
                                    <i class="las la-check-double"></i> Apply All
                                </button>
                                <a href="{{ url('accounting/exchange-rate-analysis') }}" class="btn btn-info btn-sm">
                                    <i class="las la-arrow-left"></i> Back to Analysis
                                </a>
                            </div>
                        </div>
                    </div>
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
<script>
    function CheckAllCheckboxes() {
        var checked = $('#check-all-checkboxes').is(':checked');
        $('.correction-checkbox').prop('checked', checked);
    }

    $(document).ready(function() {
        $('#applyAllBtn').on('click', function() {
            if (!confirm('Apply ALL pending corrections? This will update entry_items.reporting_amount and entries.reporting_debit/credit.')) return;

            var btn = $(this);
            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Applying...');

            $.ajax({
                url: '{{ url("accounting/exchange-rate-corrections/apply-all") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        notification('success', response.message);
                        reloadDatatable();
                        $('#applyAllBtn').prop('disabled', false).html('<i class="las la-check-double"></i> Apply All');
                    } else {
                        notification('error', response.message);
                        btn.prop('disabled', false).html('<i class="las la-check-double"></i> Apply All');
                    }
                },
                error: function(xhr) {
                    notification('error', xhr.responseJSON?.message || 'Error applying corrections');
                    btn.prop('disabled', false).html('<i class="las la-check-double"></i> Apply All');
                }
            });
        });

        $(document).on('click', '.apply-single', function() {
            var id = $(this).data('id');
            var btn = $(this);
            btn.prop('disabled', true).html('Applying...');

            $.ajax({
                url: '{{ url("accounting/exchange-rate-corrections") }}/' + id + '/apply',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        notification('success', response.message);
                        reloadDatatable();
                    } else {
                        notification('error', response.message);
                        btn.prop('disabled', false).html('Apply');
                    }
                },
                error: function(xhr) {
                    notification('error', xhr.responseJSON?.message || 'Error');
                    btn.prop('disabled', false).html('Apply');
                }
            });
        });

        $(document).on('click', '.skip-single', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '{{ url("accounting/exchange-rate-corrections") }}/' + id + '/skip',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        notification('success', response.message);
                        reloadDatatable();
                    }
                }
            });
        });

        $(document).on('click', '.delete-single', function() {
            if (!confirm('Delete this correction record?')) return;
            var id = $(this).data('id');
            $.ajax({
                url: '{{ url("accounting/exchange-rate-corrections") }}/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        notification('success', response.message);
                        reloadDatatable();
                    }
                }
            });
        });

    });
</script>
@endsection
