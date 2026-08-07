@php
    $labels = [
        'live'            => ['Current entries', 'success'],
        'superseded'      => ['Superseded revisions', 'secondary'],
        'voucher_deleted' => ['Entries on deleted vouchers', 'danger'],
    ];
    [$label, $colour] = $labels[$state] ?? ['Entries', 'info'];
@endphp

<h5 class="mb-1">
    <span class="badge badge-{{ $colour }}">{{ $label }}</span>
    <small class="text-muted">{{ $account->code }} &mdash; {{ $account->name }}
        @if($company) &middot; {{ $company->code }} @endif
    </small>
</h5>

@if($state === 'superseded')
    <div class="alert alert-info py-2">
        <small>
            <strong>These are not deleted entries.</strong> Every time a voucher is edited, the system
            soft-deletes its whole previous line set and inserts a fresh one, so each edit leaves a
            complete historical copy behind. The vouchers below are still live &mdash; these rows are
            simply earlier revisions of them, and no report counts them.
        </small>
    </div>
@elseif($state === 'voucher_deleted')
    <div class="alert alert-warning py-2">
        <small>The vouchers below were soft-deleted. They can be restored, which is why they still
            block this ledger from being deleted.</small>
    </div>
@endif

@if($rows->isEmpty())
    <div class="alert alert-secondary mb-0">Nothing to show.</div>
@else
    <div class="table-responsive" style="max-height: 55vh; overflow-y: auto;">
        <table class="table table-sm table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Voucher</th>
                    <th>Date</th>
                    <th>Year</th>
                    <th>Type</th>
                    <th class="text-right">Amount</th>
                    <th>Cost centre</th>
                    <th>{{ $state === 'superseded' ? 'Replaced at' : 'Last edited' }}</th>
                    <th>By</th>
                </tr>
            </thead>
            <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>
                        <small>
                            <strong>{{ $row->entry_code }}</strong>
                            @if($row->number && $row->number !== $row->entry_code)
                                <br><span class="text-muted">{{ $row->number }}</span>
                            @endif
                            <br><span class="text-muted">item #{{ $row->entry_item_id }}</span>
                        </small>
                    </td>
                    <td><small>{{ $row->date }}</small></td>
                    <td><small>{{ $row->fiscal_year }}</small></td>
                    <td><small>{{ $row->entry_type }}</small></td>
                    <td class="text-right">
                        <small>{{ $row->debit_credit }} {{ number_format($row->reporting_amount, 2) }}</small>
                    </td>
                    <td><small>{{ \Illuminate\Support\Str::limit($row->cost_centre, 24) }}</small></td>
                    <td>
                        <small>{{ $state === 'superseded'
                            ? ($row->item_deleted_at ?: '-')
                            : ($row->updated_at ?: '-') }}</small>
                    </td>
                    <td><small>{{ $row->last_edited_by ?: '-' }}</small></td>
                </tr>
                @if($row->notes)
                    <tr>
                        <td colspan="8" class="py-1 bg-light">
                            <small class="text-muted"><em>{{ \Illuminate\Support\Str::limit($row->notes, 140) }}</em></small>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
    @if($rows->count() >= 200)
        <small class="text-muted">Showing the most recent 200 rows.</small>
    @endif
@endif
