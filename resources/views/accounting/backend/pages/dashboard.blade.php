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
                                        <div class="col-md-3 pr-0">
                                            <label for="{{ $slug }}_fiscal_year_id"><strong>Fiscal Year</strong></label>
                                            <select class="form-control" name="fiscal_year_id" id="{{ $slug }}_fiscal_year_id" onchange="printDate('{{ $slug }}')">
                                                @if(isset($fiscalYears[0]))
                                                @foreach($fiscalYears as $fy)
                                                <option value="{{ $fy->id }}" data-start="{{ $fy->start }}" data-end="{{ $fy->end }}">{{ $fy->title }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="{{ $slug }}_to"><strong>Date</strong></label>
                                            <input type="date" class="form-control" name="to" id="{{ $slug }}_to" value="{{ isset($currentFiscalYear->end) ? $currentFiscalYear->end : '' }}">
                                        </div>
                                        <div class="col-md-2 pl-0">
                                            <label for="{{ $slug }}_view"><strong>View</strong></label>
                                            <select class="form-control" name="view" id="{{ $slug }}_view">
                                                <option value="chart">Chart</option>
                                                <option value="table">Table</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        @if(isset($transaction['group']) && $transaction['group'])
                                        <div class="col-md-9 pr-0">
                                            <label for="{{ $slug }}_account_group_id"><strong>Group</strong></label>
                                            <select class="form-control account-groups" name="account_group_id" id="{{ $slug }}_account_group_id" data-selected="{{ isset($revenue->id) ? $revenue->id : 0 }}">
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
                                                    <select class="form-control account-groups" name="positive_account_group_id" id="{{ $slug }}_positive_account_group_id" data-selected="{{ isset($currentAssets->id) ? $currentAssets->id : 0 }}">
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
                                                    <select class="form-control account-groups" name="negative_account_group_id" id="{{ $slug }}_negative_account_group_id" data-selected="{{ isset($currentLiabilities->id) ? $currentLiabilities->id : 0 }}">
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
   $(document).ready(function () {
    // Delay execution to avoid initial loading issues
    setTimeout(function () {
        // Cache selectors to avoid repeated DOM lookups
        const chartForms = $('.chart-form');
        const accountGroups = $('.account-groups');

        // Optimize chart form submission handling
        chartForms.each(function () {
            const chartForm = $(this);
            const chartType = chartForm.attr('chart-type');
            const chartButton = $(`.chart-button-${chartType}`);

            chartForm.on('submit', function (event) {
                event.preventDefault();

                const chartButtonContent = chartButton.html();
                chartButton.html('<i class="las la-spinner la-spin"></i>').prop('disabled', true);

                $.ajax({
                    url: chartForm.attr('action'),
                    type: chartForm.attr('method'),
                    data: chartForm.serialize(),
                }).done(function (response) {
                    $(`.chart-view-${chartType}`).html(response);
                }).always(function () {
                    // Re-enable the button after the AJAX call
                    chartButton.html(chartButtonContent).prop('disabled', false);
                });
            });
        });

        // Pre-submit chart forms for transactions
        const transactionKeys = <?php echo json_encode(array_keys($transactions)); ?>;
        transactionKeys.forEach(function (key) {
            $(`#${key}_company_id`).trigger('change');
            $(`.chart-form-${key}`).trigger('submit');
        });

        // Set account groups to their default values and trigger change
        accountGroups.each(function () {
            const group = $(this);
            group.val(group.data('selected')).trigger('change');
        });
    }, 2000); // Delay of 2 seconds
});

// Helper function to update fiscal year
function chooseFiscalYear(slug) {
    const companySelect = $(`#${slug}_company_id`);
    const fiscalYearSelect = $(`#${slug}_fiscal_year_id`);

    fiscalYearSelect.val(companySelect.find(':selected').attr('active-fiscal-year')).trigger('change');
}

// Helper function to fetch Chart of Accounts (COA)
function getCOA(slug) {
    const coaSelect = $(`#${slug}_chart_of_account_id`);
    coaSelect.html('<option value="0">Please Wait...</option>');

    $.ajax({
        url: `{{ url('accounting') }}?get-ledgers&company_id=${$(`#${slug}_company_id`).val()}`,
        type: 'GET',
    }).done(function (response) {
        coaSelect.html('<option value="0">All Ledgers</option>' + response);
    });
}

// Helper function to set the end date based on fiscal year
function printDate(slug) {
    const fiscalYear = $(`#${slug}_fiscal_year_id`).find(':selected');
    $(`#${slug}_to`).val(fiscalYear.attr('data-end'));
}

</script>
@endsection
