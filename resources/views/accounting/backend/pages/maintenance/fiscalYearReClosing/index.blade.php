@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
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
                <li class="active">Maintenance</li>
                <li class="active">{{__($title)}}</li>
                <li class="top-nav-btn">
                    <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3">
                    <form id="reClosingForm" action="{{ route('accounting.fiscal-year-re-closing.preview') }}" method="post" accept-charset="utf-8">
                    @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="company_id"><strong>Company</strong></label>
                                    <select name="company_id" id="company_id" class="form-control" required>
                                        <option value="">Choose Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">[{{ $company->code }}] {{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="unit_id"><strong>Unit (Optional)</strong></label>
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option value="">Choose Unit</option>
                                    </select>
                                    <small class="text-danger">Note: Selecting a unit will only include transactions from that unit in the closing balance.</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="from_fiscal_year_id"><strong>From Fiscal Year</strong></label>
                                    <select name="from_fiscal_year_id" id="from_fiscal_year_id" class="form-control" required>
                                        <option value="">Choose Year</option>
                                        @foreach($fiscalYears as $year)
                                            <option value="{{ $year->id }}">{{ $year->title }} ({{ date('d-M-y', strtotime($year->start)) }} to {{ date('d-M-y', strtotime($year->end)) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="to_fiscal_year_id"><strong>To Fiscal Year</strong></label>
                                    <select name="to_fiscal_year_id" id="to_fiscal_year_id" class="form-control" required>
                                        <option value="">Choose Year</option>
                                        @foreach($fiscalYears as $year)
                                            <option value="{{ $year->id }}">{{ $year->title }} ({{ date('d-M-y', strtotime($year->start)) }} to {{ date('d-M-y', strtotime($year->end)) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary"><i class="la la-eye"></i> Show Preview & Analysis</button>
                            </div>
                        </div>
                    </form>

                    <div id="previewArea" class="mt-4">
                        <!-- Preview content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        $('#company_id').on('change', function() {
            var company_id = $(this).val();
            if(company_id) {
                $.ajax({
                    url: '{{ url("accounting/fiscal-year-re-closing/get-units") }}',
                    type: 'GET',
                    data: { company_id: company_id },
                    success: function(data) {
                        $('#unit_id').empty().append('<option value="">Choose Unit</option>');
                        $.each(data, function(key, unit) {
                            $('#unit_id').append('<option value="'+unit.hr_unit_id+'">'+unit.hr_unit_name+'</option>');
                        });
                    }
                });
            } else {
                $('#unit_id').empty().append('<option value="">Choose Unit</option>');
            }
        });

        $('#reClosingForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize();

            $('#previewArea').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i><p>Generating Preview...</p></div>');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#previewArea').html(response);
                },
                error: function(xhr) {
                    $('#previewArea').html('<div class="alert alert-danger">Error generating preview. Please check your selections.</div>');
                }
            });
        });
    });

    function processReClosing() {
        if(confirm('Are you sure you want to proceed? This will delete existing closing data and re-calculate everything for the selected range. This action cannot be undone.')) {
            var formData = $('#reClosingForm').serialize();
            $('#reRunBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

            $.ajax({
                url: '{{ route("accounting.fiscal-year-re-closing.process") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if(response.success) {
                        alert(response.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + response.message);
                        $('#reRunBtn').prop('disabled', false).html('<i class="la la-check"></i> Proceed with Re-Closing');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred during processing.');
                    $('#reRunBtn').prop('disabled', false).html('<i class="la la-check"></i> Proceed with Re-Closing');
                }
            });
        }
    }
</script>
@endsection
