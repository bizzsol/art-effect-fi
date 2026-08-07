@if($logs->isEmpty())
    <div class="alert alert-info mb-0">
        No recorded changes for {{ $account->code }}.
        @if($account->created_at)
            <br><small class="text-muted">
                Created {{ $account->created_at->format('d M Y') }}
                @if($account->createdBy) by {{ $account->createdBy->name }} @endif.
                Change logging started when this feature was installed, so earlier edits were never recorded.
            </small>
        @endif
    </div>
@else
    <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <thead>
                <tr>
                    <th style="width: 15%">When</th>
                    <th style="width: 18%">Action</th>
                    <th style="width: 15%">By</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
            @foreach($logs as $log)
                <tr>
                    <td><small>{{ $log->created_at ? $log->created_at->format('d M Y g:i a') : '-' }}</small></td>
                    <td><span class="badge badge-{{ $log->action_colour }}">{{ $log->action_label }}</span></td>
                    <td><small>{{ optional($log->causer)->name ?: $log->causer_name ?: 'System' }}</small></td>
                    <td>
                        <small>
                            @if($log->company_code)
                                <strong>{{ $log->company_code }}</strong>
                            @endif

                            @if($log->field)
                                {{ $log->field }}:
                            @endif

                            @if($log->old_value !== null && $log->new_value !== null)
                                <span class="text-danger">{{ \Illuminate\Support\Str::limit($log->old_value, 60) }}</span>
                                &rarr;
                                <span class="text-success">{{ \Illuminate\Support\Str::limit($log->new_value, 60) }}</span>
                            @elseif($log->new_value !== null)
                                <span class="text-success">{{ \Illuminate\Support\Str::limit($log->new_value, 60) }}</span>
                            @elseif($log->old_value !== null)
                                <span class="text-danger">{{ \Illuminate\Support\Str::limit($log->old_value, 60) }}</span>
                            @endif

                            @if($log->reason)
                                <br><em class="text-muted">{{ $log->reason }}</em>
                            @endif

                            @if(isset($log->context['reference']))
                                <br><span class="text-muted">Migration {{ $log->context['reference'] }}
                                    &mdash; {{ number_format($log->context['items_moved'] ?? 0) }} item(s)</span>
                            @endif
                        </small>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
