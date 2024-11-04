@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .am5exporting-menu.am5exporting-valign-top{
        top: -80px !important;
    }
    .am5exporting-icon{
        font-size: 20px !important;
        height: 35px !important;
        width: 35px !important;
        padding: 1px 7px !important;
        color: #fff !important;
        background-color: black !important;
        border-color: #1e7e34 !important;
        text-decoration: none !important;
    }
    .am5exporting-list{
        margin-top: 30px !important;
        margin-right: 0px !important;
    }
    .am5exporting-type-separator{
        display: none;
    }
    .am5exporting-item a {
        text-decoration: none !important;
    }
</style>
@endsection
@section('main-content')
<div class="row pt-4">
    <div class="col-md-12">
        <div class="row">
            @foreach ($transactions as $slug => $transaction)
            <div class="col-md-6 mb-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0 text-white"><strong>{{ $transaction['name'] }}</strong></h4>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <form action="{{ url('accounting') }}?get-chart&slug={{ $slug }}" method="get" class="chart-form chart-form-{{ $slug }}" chart-type="{{ $slug }}">
                                    <div class="form-group row">
                                        <div class="col-md-4 pr-0">
                                            <label for="{{ $slug }}_company_id"><strong>Company</strong></label>
                                            <select class="form-control" name="company_id" id="{{ $slug }}_company_id" @if(isset($transaction['ledger']) && $transaction['ledger'])  onchange="chooseFiscalYear('{{ $slug }}');getCOA('{{ $slug }}')" @else  onchange="chooseFiscalYear('{{ $slug }}')" @endif>
                                                @if(isset($companies[0]))
                                                @foreach($companies as $company)
                                                <option value="{{ $company->id }}" active-fiscal-year="{{ isset($activeFiscalYears[$company->id]->id) ? $activeFiscalYears[$company->id]->id : 0 }}">{{ $company->code }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4 pr-0">
                                            <label for="{{ $slug }}_fiscal_year_id"><strong>Fiscal Year</strong></label>
                                            <select class="form-control" name="fiscal_year_id" id="{{ $slug }}_fiscal_year_id" onchange="printDate('{{ $slug }}')">
                                                @if(isset($fiscalYears[0]))
                                                @foreach($fiscalYears as $fy)
                                                <option value="{{ $fy->id }}" data-start="{{ $fy->start }}" data-end="{{ $fy->end }}">{{ $fy->title }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="{{ $slug }}_to"><strong>Date</strong></label>
                                            <input type="date" class="form-control" name="to" id="{{ $slug }}_to" value="{{ isset($currentFiscalYear->end) ? $currentFiscalYear->end : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        @if(isset($transaction['group']) && $transaction['group'])
                                        <div class="col-md-9 pr-0">
                                            <label for="{{ $slug }}_account_group_id"><strong>Group</strong></label>
                                            <select class="form-control" name="account_group_id" id="{{ $slug }}_account_group_id">
                                                <option value="0">All Account Groups</option>
                                                {!! $groups !!}
                                            </select>
                                        </div>
                                        @endif

                                        @if(isset($transaction['dual']) && $transaction['dual'])
                                        <div class="col-md-9 pr-0">
                                            <div class="row">
                                                <div class="col-md-5 pr-0">
                                                    <label for="{{ $slug }}_positive_account_group_id"><strong>Positive Group</strong></label>
                                                    <select class="form-control" name="positive_account_group_id" id="{{ $slug }}_positive_account_group_id">
                                                        <option value="0">All Account Groups</option>
                                                        {!! $groups !!}
                                                    </select>
                                                </div>
                                                <div class="col-md-2 pt-5 text-center">
                                                    <div class="row">
                                                        <div class="col-md-6 offset-2">
                                                            <div style="width: 100%;border-top: 3px solid black"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 pl-0">
                                                    <label for="{{ $slug }}_negative_account_group_id"><strong>Negative Group</strong></label>
                                                    <select class="form-control" name="negative_account_group_id" id="{{ $slug }}_negative_account_group_id">
                                                        <option value="0">All Account Groups</option>
                                                        {!! $groups !!}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if(isset($transaction['ledger']) && $transaction['ledger'])
                                        <div class="col-md-9 pr-0">
                                            <label for="{{ $slug }}_chart_of_account_id"><strong>Group</strong></label>
                                            <select class="form-control" name="chart_of_account_id" id="{{ $slug }}_chart_of_account_id">
                                                <option value="0">All Ledgers</option>
                                                
                                            </select>
                                        </div>
                                        @endif

                                        <div class="col-md-2 pt-4">
                                            <button class="btn btn-success mt-2 btn-md btn-block text-white chart-button-{{ $slug }}" type="submit"><i class="las la-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12 chart-view-{{ $slug }}">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="//cdn.amcharts.com/lib/5/plugins/exporting.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.each($('.chart-form'), function(index, val) {
            var chart_form = $(this);
            $(this).submit(function(event) {
                event.preventDefault();

                var chart_type = chart_form.attr('chart-type');
                var chart_button = $('.chart-button-'+chart_type);
                var chart_button_content = chart_button.html();
                chart_button.html('<i class="las la-spinner la-spin"></i>').prop('disabled', true);

                $.ajax({
                    url: chart_form.attr('action'),
                    type: chart_form.attr('method'),
                    data: chart_form.serializeArray(),
                })
                .done(function(response) {
                    $('.chart-view-'+chart_type).html(response);
                    chart_button.html(chart_button_content).prop('disabled', false);
                });
            });
        });

        $.each(<?php echo json_encode(array_keys($transactions)); ?>, function(index, val) {
            $('#'+val+'_company_id').change();
            $('.chart-form-'+val).submit();
        });
    });

    function chooseFiscalYear(slug) {
        $('#'+slug+'_fiscal_year_id').val($('#'+slug+'_company_id').find(':selected').attr('active-fiscal-year')).change();
    }

    function getCOA(slug) {
        $('#'+slug+'_chart_of_account_id').html('<option value="0">Please Wait...</option>');
        $.ajax({
            url: "{{ url('accounting') }}?get-ledgers&company_id="+$('#'+slug+'_company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#'+slug+'_chart_of_account_id').html('<option value="0">All Ledgers</option>'+response);
        });
    }

    function printDate(slug){
        $('#'+slug+'_to').val($('#'+slug+'_fiscal_year_id').find(':selected').attr('data-end'));
    }
</script>
@endsection
