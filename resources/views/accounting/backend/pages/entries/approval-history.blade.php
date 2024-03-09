<div style="overflow: hidden">
    <div class="form-group pl-0">
        <table class="table table-bordered mb-4">
            <tbody>
                <tr>
                    <td style="vertical-align: top !important;">
                        <h5 class="mb-2"><strong>Information: </strong></h5>
                        <ul  style="list-style: square;padding-left: 20px !important;">
                            <li>Reference: <strong>{{ $entry->number }}</strong></li>
                            <li>Fiscal Year: <strong>{{ $entry->fiscalYear->title }}</strong></li>
                            <li>Currency: <strong>{{ $entry->exchangeRate->currency->name }}</strong></li>
                            <li>Entry Type: <strong>{{ $entry->entryType->name }}</strong></li>
                        </ul>
                    </td>
                </tr>
                @if(isset($entry->approvals[0]))
                @foreach($entry->approvals as $key => $approval)
                <tr>
                    <td style="vertical-align: top !important;">
                        <h5 class="mb-2"><strong>{{ $approval->approvalLevel->name }}: </strong></h5>
                        @php
                            $logs = !empty($approval->logs) ? json_decode($approval->logs, true) : [];
                        @endphp
                        <ul  style="list-style: square;padding-left: 20px !important;">
                            @if(isset($logs[0]))
                            @foreach($logs as $key => $log)
                            <li>{!! $log !!}</li>
                            @endforeach
                            @endif
                        </ul>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>