<span style="display: none" id="export-title">{{ $title }}</span>
<table style="width: 100%">
    <tbody>
        <tr>
            <td>
               <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 25%">
                                <h5><strong>Codes</strong></h5>
                            </th>
                            <th style="width: 50%">
                                <h5><strong>{{ $assetGroups->pluck('name')->implode(', ') }}</strong></h5>
                            </th>
                            <th style="width: 25%" class="text-right">
                                <h5><strong>Balance ({{ $currency->code }})</strong></h5>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="report-tbody">
                        {!! $assets !!}
                    </tbody>
                </table> 
            </td>
        </tr>
        <tr>
            <td>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 25%">
                                <h5><strong>Codes</strong></h5>
                            </th>
                            <th style="width: 50%">
                                <h5><strong>{{ $liabilityGroups->pluck('name')->implode(', ') }}</strong></h5>
                            </th>
                            <th style="width: 25%" class="text-right">
                                <h5><strong>Balance ({{ $currency->code }})</strong></h5>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="report-tbody">
                        {!! $liabilities !!}
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>