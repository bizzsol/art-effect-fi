<table style="width: 100%">
    <tbody>
        <tr>
            <td>
               <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            @if(!$groupWise)
                            <th style="width: 25%">
                                <h5><strong>Codes</strong></h5>
                            </th>
                            @endif
                            <th style="width: 50%">
                                <h5><strong>{{ $incomeGroups->pluck('name')->implode(', ') }}</strong></h5>
                            </th>
                            <th style="width: 25%" class="text-right">
                                <h5><strong>Balance ({{ $currency->code }})</strong></h5>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="report-tbody">
                        {!! $incomes !!}
                    </tbody>
                </table> 
            </td>
        </tr>
        <tr>
            <td>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            @if(!$groupWise)
                            <th style="width: 25%">
                                <h5><strong>Codes</strong></h5>
                            </th>
                            @endif
                            <th style="width: 50%">
                                <h5><strong>{{ $expenseGroups->pluck('name')->implode(', ') }}</strong></h5>
                            </th>
                            <th style="width: 25%" class="text-right">
                                <h5><strong>Balance ({{ $currency->code }})</strong></h5>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="report-tbody">
                        {!! $expenses !!}
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>