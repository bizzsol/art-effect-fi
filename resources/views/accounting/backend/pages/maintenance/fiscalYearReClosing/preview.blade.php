<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Analysis & Preview for {{ $company->name }}</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="la la-info-circle"></i> <strong>Important:</strong> Proceeding will delete all <strong>Fiscal Year Closings</strong> and <strong>Opening Entry Links</strong> in the <code>fiscal_year_opening_entries</code> table for the years listed below. Data will be fresh-closed step-by-step.
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h5><i class="la la-calculator"></i> Accounting Rules Applied:</h5>
                    <ul>
                        <li><strong>Assets, Liabilities, Equity:</strong> Full Closing Balance will be carried forward to the next year as Opening Balance.</li>
                        <li><strong>Revenue & Expenses:</strong> Will be closed (Carry Forward = 0). Net Profit/Loss will be transferred to <strong>Retained Earnings</strong>.</li>
                        <li><strong>Linkage:</strong> Sequence will be maintained (Opening 2024 comes from Closing 2023).</li>
                    </ul>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Step #</th>
                    <th>Fiscal Year (From)</th>
                    <th>Next Year (To)</th>
                    <th>Current Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($previewData as $index => $data)
                    @php
                        $nextYear = \App\Models\PmsModels\Accounts\FiscalYear::where('start', '>', $data['year']->end)->orderBy('start', 'asc')->first();
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $data['year']->title }}</strong><br>
                            <small>{{ date('d M, Y', strtotime($data['year']->start)) }} to {{ date('d M, Y', strtotime($data['year']->end)) }}</small>
                        </td>
                        <td>
                            @if($nextYear)
                                <strong>{{ $nextYear->title }}</strong>
                            @else
                                <span class="text-danger">No next year found!</span>
                            @endif
                        </td>
                        <td>
                            @if($data['closing'])
                                <span class="badge badge-success">Closed</span> (Ref: {{ $data['closing']->reference }})<br>
                                <small>Net P/L: {{ systemMoneyFormat($data['closing']->profit_loss) }}</small>
                            @else
                                <span class="badge badge-secondary">Not Closed</span>
                            @endif
                            <br>
                            @if($data['opening_entries_count'] > 0)
                                <span class="badge badge-warning">{{ $data['opening_entries_count'] }} Opening Entry Links</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ url('accounting/trial-balance?report_type=report&company_id='.$company->id.'&fiscal_year_id='.$data['year']->id.'&from='.$data['year']->start.'&to='.$data['year']->end.($unit_id ? '&hr_unit_id='.$unit_id : '')) }}" target="_blank" class="btn btn-xs btn-info"><i class="la la-search"></i> Examine TB</a>
                            <div class="mt-1 text-danger" style="font-size: 11px">
                                <i class="la la-trash"></i> Data will be reset & re-calculated
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-right">
            <button type="button" class="btn btn-danger btn-lg" id="reRunBtn" onclick="processReClosing()">
                <i class="la la-check"></i> Proceed with Re-Closing (Clean & Fresh Run)
            </button>
        </div>
    </div>
</div>
