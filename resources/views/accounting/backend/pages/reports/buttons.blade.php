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
            <button class="btn btn-sm btn-block btn-primary" type="button" onclick="exportReportToExcel()"><i class="lar la-file-excel"></i>&nbsp;Excel</button>
        </div>
    @endif
</div>

@section('page-script')
    <script type="text/javascript">
        function searchReport(element) {
            $.each($('.report-tbody').find('tr'), function(index, val) {
                var tr = $(this).text().toLowerCase();
                if(tr.search(element.val().toLowerCase()) != -1){
                    $(this).show();
                }else{
                    $(this).hide();
                }
            });
        }

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
                $('.report-search').show();
            })
            .fail(function(response) {
                button.prop('disabled', false).html('<i class="la la-search"></i>&nbsp;Search');
                $('.report-view').html(response).hide();
                $('.report-search').hide();
            });
        }

        getDates();
        function getDates() {
            if($('#fiscal_year_id').val() != undefined){
                $('#from').val($('#fiscal_year_id').find(':selected').attr('data-start')).attr('min', $('#fiscal_year_id').find(':selected').attr('data-start')).attr('max', $('#fiscal_year_id').find(':selected').attr('data-end'));
                $('#to').val($('#fiscal_year_id').find(':selected').attr('data-end')).attr('min', $('#fiscal_year_id').find(':selected').attr('data-start')).attr('max', $('#fiscal_year_id').find(':selected').attr('data-end'));
            }
        }

        function getProfitCentres() {
            $('#profit_centre_id').html('<option value="0">Please wait...</option>');
            $.ajax({
                url: "{{ url('accounting') }}?get-profit-centres&company_id="+$('#company_id').val(),
                type: 'GET',
                dataType: 'json',
                data: {},
            })
            .done(function(response) {
                var profitCentres = '<option value="0">All Profit Centres</option>';
                $.each(response, function(index, profitCentre) {
                    profitCentres += '<option value="'+profitCentre.id+'">['+profitCentre.code+'] '+profitCentre.name+'</option>';
                });

                $('#profit_centre_id').html(profitCentres).change();
            });
        }

        function getCostCentres() {
            $('#cost_centre_id').html('<option value="0">Please wait...</option>');
            $.ajax({
                url: "{{ url('accounting') }}?get-cost-centres&company_id="+$('#company_id').val()+"&profit_centre_id="+$('#profit_centre_id').find(':selected').val(),
                type: 'GET',
                data: {},
            })
            .done(function(response) {
                console.log(response);
                $('#cost_centre_id').html(response).change();
            });
        }

        function getCompanies() {
            $('#customer_code').html('<option value="{{ null }}">Please wait...</option>');
            $.ajax({
                url: "{{ url('accounting/customer-ageing') }}?get-companies&company_id="+$('#company_id').val()+"&profit_centre_id="+$('#profit_centre_id').find(':selected').val(),
                type: 'GET',
                data: {},
            })
            .done(function(response) {
                var customer_code = '<option value="{{ null }}">All Customers</option>';
                $.each(response.data, function(index, customer) {
                    if(customer != null && customer != undefined){
                        if(customer.code != undefined && customer.code != ''){
                            customer_code += '<option value="'+customer.code+'">'+customer.code+' :: '+customer.name+'</option>';
                        }
                    }
                });
                $('#customer_code').html(customer_code).change();
            });
        }

        function getSuppliers() {
            $('#customer_code').html('<option value="{{ null }}">Please wait...</option>');
            $.ajax({
                url: "{{ url('accounting/supplier-ageing') }}?get-suppliers&company_id="+$('#company_id').val()+"&profit_centre_id="+$('#profit_centre_id').find(':selected').val(),
                type: 'GET',
                data: {},
            })
            .done(function(response) {
                var suppliers = '<option value="{{ null }}">All Suppliers</option>';
                $.each(response, function(index, supplier) {
                    suppliers += '<option value="'+supplier.id+'">'+supplier.name+'</option>';
                });
                $('#supplier_id').html(suppliers).change();
            });
        }

        function getSubLedgers(element) {
            var values = [];
            $.each(element.find("option:selected"), function(){            
                values.push($(this).val());
            });

            $('#sub_ledger_id').html('');
            $.ajax({
                url: "{{ url('accounting') }}?get-sub-ledgers&ledgers="+values.join(','),
                type: 'GET',
                dataType: 'json',
                data: {},
            })
            .done(function(response) {
                var sub_ledgers = '';
                $.each(response.ledgers, function(index, ledger) {
                    sub_ledgers += '<optgroup label="['+ledger.code+'] '+ledger.name+'">';
                    $.each(ledger.sub_ledgers, function(index, sub_ledger) {
                        sub_ledgers += '<option value="'+sub_ledger.id+'">['+ledger.code+'] ['+sub_ledger.code+'] '+sub_ledger.name+'</option>';
                    });
                    sub_ledgers += '</optgroup>';
                });
                $('#sub_ledger_id').html(sub_ledgers).change();
            });
        }
    </script>
@endsection