<div class="row pt-2 pl-3">
    @if(!isset($searchHide) || !$searchHide)
    <div class="col-md-4 pt-1 pl-0">
        <button class="btn btn-sm btn-block btn-success report-button" type="submit"><i class="la la-search"></i>&nbsp;Search</button>
    </div>
    @endif

    @if(!isset($clearHide) || !$clearHide)
    <div class="col-md-4 pt-1 pl-0">
        <a class="btn btn-sm btn-block btn-danger" href="{{ explode('?', $url)[0] }}"><i class="la la-times"></i>&nbsp;Clear</a>
    </div>
    @endif

    <div class="col-md-{{ !(!isset($searchHide) || !$searchHide) ? 4 : 2 }} pt-1 pl-0">
        <button class="btn btn-sm btn-block btn-success" type="button" onclick="viewPDFReport('{{ isset($url) ? $url : '' }}')"><i class="lar la-file-pdf"></i>&nbsp;PDF</button>
    </div>
    @php
        $normalExcel = isset($normalExcel) && $normalExcel ? true : false;
    @endphp
    @if($normalExcel)
        <div class="col-md-{{ !(!isset($searchHide) || !$searchHide) ? 4 : 2 }} pt-1 pl-0">
            <button class="btn btn-sm btn-block btn-primary" type="button" onclick="viewExcelReport('{{ isset($url) ? $url : '' }}')"><i class="lar la-file-excel"></i>&nbsp;Excel</button>
        </div>
    @else
        <div class="col-md-{{ !(!isset($searchHide) || !$searchHide) ? 4 : 2 }} pt-1 pl-0">
            <button class="btn btn-sm btn-block btn-primary" type="button" onclick="exportReportToExcel('{{ $title }}')"><i class="lar la-file-excel"></i>&nbsp;Excel</button>
        </div>
    @endif
</div>

@section('page-script')
    <script type="text/javascript">
        function zeroBalanceFilter(){
            $.each($('.closing_balance_column'), function(index, val) {
                if($('#zero_balance').val() == 1){
                    $(this).parent().show();
                }else{
                    if(parseFloat($(this).text()) == 0){
                        $(this).parent().hide();
                    }else{
                        $(this).parent().show();
                    }
                }
            });
        }

        function getChartOfAccounts(){
            $('#chart_of_account_id').html('<option value="">Please wait...</option>').change();
            $.ajax({
                url: "{{ url('accounting/ledger-statement') }}?get-coa&company_id="+$('#company_id').val()+"&chart_of_account_id={{ request()->get('chart_of_account_id') }}",
                type: 'GET',
                data: {},
            })
            .done(function(response) {
                $('#chart_of_account_id').html(response).change();
            });
        }
        
        $(document).ready(function() {
            var form = $('#report-form');
            var button = $('.report-button');
            
            var from = "{{ request()->has('from') }}";
            if(from == 1){
                loadReport(form, button);
            }
            
            form.on('submit', function(e){
                form.attr('formtarget', '_parent');
                e.preventDefault();

                loadReport(form, button)
            });
        });

        function loadReport(form, button){
            $('#report_type').val('report');

            button.prop('disabled', true).html('<i class="las la-spinner"></i>&nbsp;Please Wait...');
            $('.report-view').html('<h3 class="text-center"><strong>Please Wait...</strong></h3>').show();
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serializeArray(),
            })
            .done(function(response) {
                button.prop('disabled', false).html('<i class="la la-search"></i>&nbsp;Search');
                $('.report-view').html(response);
            })
            .fail(function(response) {
                button.prop('disabled', false).html('<i class="la la-search"></i>&nbsp;Search');
                $('.report-view').html(response).hide();
            });
        }

        getDates();
        function getDates() {
            if($('#fiscal_year_id').val() != undefined){
                $('#from').val($('#fiscal_year_id').find(':selected').attr('data-start')).attr('min', $('#fiscal_year_id').find(':selected').attr('data-start')).attr('max', $('#fiscal_year_id').find(':selected').attr('data-end'));
                $('#to').val($('#fiscal_year_id').find(':selected').attr('data-end')).attr('min', $('#fiscal_year_id').find(':selected').attr('data-start')).attr('max', $('#fiscal_year_id').find(':selected').attr('data-end'));
            }
        }
    </script>
@endsection