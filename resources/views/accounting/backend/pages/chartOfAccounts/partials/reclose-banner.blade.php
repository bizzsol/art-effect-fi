{{--
    An open re-close obligation.

    A migration moves entry_items but deliberately does not patch
    fiscal_year_closing_ledgers - the correct repair is to re-close the affected
    span so carry-forwards are recomputed from the moved data. Until that runs,
    opening balances and prior-year comparatives for those years are stale, so
    the warning stays visible.
--}}
@if(isset($pendingReclose) && $pendingReclose->count() > 0)
    <div class="alert alert-warning">
        <h5 class="mb-2"><i class="las la-exclamation-triangle"></i>&nbsp;
            {{ $pendingReclose->count() }} ledger migration{{ $pendingReclose->count() == 1 ? '' : 's' }}
            waiting to be re-closed</h5>
        <p class="mb-2">
            <small>Entries were moved between ledgers but the carry-forward balances for the affected
                fiscal years have not been rebuilt. Opening balances and prior-year comparatives are stale
                until each span below is re-closed.</small>
        </p>
        <table class="table table-sm table-bordered bg-white mb-2">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Company</th>
                    <th>Moved</th>
                    <th>Re-close span</th>
                    <th>When</th>
                    <th class="text-center">Done?</th>
                </tr>
            </thead>
            <tbody>
            @foreach($pendingReclose as $migration)
                <tr class="migration-{{ $migration->id }}">
                    <td><small><strong>{{ $migration->reference }}</strong></small></td>
                    <td><small>{{ $migration->company_code }}</small></td>
                    <td><small>{{ $migration->source_code }} &rarr; {{ $migration->target_code }}
                            ({{ number_format($migration->items_moved) }})</small></td>
                    <td><small>{{ optional($migration->recloseFromFiscalYear)->title ?: '?' }}
                            &ndash; {{ optional($migration->recloseToFiscalYear)->title ?: '?' }}</small></td>
                    <td><small>{{ $migration->finished_at ? $migration->finished_at->format('d M Y') : '-' }}</small></td>
                    <td class="text-center">
                        @can('fiscal-year-re-closing')
                            <a class="btn btn-xs btn-primary" href="{{ url('accounting/fiscal-year-re-closing') }}">
                                <i class="las la-redo"></i>&nbsp;Re-close</a>
                        @endcan
                        @can('chart-of-accounts-migrate')
                            <a class="btn btn-xs btn-success" onclick="markMigrationReclosed($(this))"
                               data-src="{{ route('accounting.ledger-migration.mark-reclosed', $migration->id) }}"
                               data-row-class="migration-{{ $migration->id }}"
                               data-reference="{{ $migration->reference }}">
                                <i class="las la-check"></i>&nbsp;Mark done</a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        function markMigrationReclosed(element) {
            swal({
                title: "Mark as re-closed?",
                text: "Only do this once " + element.attr('data-reference') +
                    "'s fiscal year span has actually been re-closed for that company.",
                icon: "warning",
                buttons: {cancel: true, confirm: {text: "Confirm", value: true, visible: true, closeModal: true}},
            }).then((value) => {
                if (!value) return;

                $.ajax({type: 'POST', url: element.attr('data-src'), dataType: 'json'})
                    .done(function (response) {
                        if (response.success) {
                            swal({icon: 'success', text: response.message, button: false});
                            setTimeout(() => { swal.close(); location.reload(); }, 1500);
                        } else {
                            swal({icon: 'error', text: response.message, button: 'OK'});
                        }
                    })
                    .fail(function (xhr) {
                        swal({icon: 'error', text: 'Failed (' + xhr.status + ').', button: 'OK'});
                    });
            });
        }
    </script>
@endif
