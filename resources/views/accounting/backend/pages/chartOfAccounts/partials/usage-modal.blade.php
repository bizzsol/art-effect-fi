{{--
    Shared usage modal.

    Opened from three places: the delete button on the Chart of Accounts index,
    the info button on the Trash page, and the company-unassign pre-flight on the
    edit screen. Everything it renders comes from LedgerUsageService, so the
    counts here are the same ones the server-side guards act on.
--}}
<div class="modal fade" id="ledgerUsageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ledger Usage <span class="text-muted" id="ledgerUsageTitle"></span></h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="ledgerUsageBody">
                <h5 class="text-center py-4">Please wait...</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-md" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Drill-down: the actual vouchers behind a count in the table above. --}}
<div class="modal fade" id="ledgerUsageDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Entry Detail</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="ledgerUsageDetailBody">
                <h5 class="text-center py-4">Please wait...</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-md" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ledgerTransferModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Transfer Entries <span class="text-muted" id="ledgerTransferTitle"></span></h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="ledgerTransferBody">
                <h5 class="text-center py-4">Please wait...</h5>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var COA_USAGE_URL = "{{ url('accounting/chart-of-accounts') }}";
    var LEDGER_MIGRATION_URL = "{{ url('accounting/ledger-migration') }}";
    var MISMATCH_URL = "{{ url('accounting/mitch-match-entries') }}";
    var CAN_MIGRATE_LEDGER = {{ auth()->user()->can('chart-of-accounts-migrate') ? 'true' : 'false' }};

    function money(value) {
        return (parseFloat(value) || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function fetchLedgerUsage(ledgerId) {
        return $.ajax({type: 'GET', url: COA_USAGE_URL + '/' + ledgerId + '/usage', dataType: 'json'});
    }

    // A non-zero count becomes a link into the voucher list behind it, so no
    // number in this table has to be taken on trust.
    function countCell(ledgerId, row, state, value, cssClass) {
        if (!value) return '<span class="text-muted">0</span>';
        if (!row.is_attributable) return '<span class="' + cssClass + '">' + value + '</span>';

        return '<a href="javascript:void(0)" class="' + cssClass + '" style="text-decoration: underline dotted"' +
            ' onclick="showUsageDetail(' + ledgerId + ',' + row.company_id + ',\'' + state + '\')">' + value + '</a>';
    }

    function showUsageDetail(ledgerId, companyId, state) {
        var body = $('#ledgerUsageDetailBody');
        body.html('<h5 class="text-center py-4">Please wait...</h5>');
        $('#ledgerUsageDetailModal').modal('show');

        $.ajax({
            type: 'GET',
            url: COA_USAGE_URL + '/' + ledgerId + '/usage-detail',
            data: {company_id: companyId, state: state}
        }).done(function (html) {
            body.html(html);
        }).fail(function (xhr) {
            body.html('<div class="alert alert-danger mb-0">Could not load the detail (' + xhr.status + ').</div>');
        });
    }

    /**
     * Build the per-company usage table. Live and deleted rows are shown apart
     * on purpose: getLedgerBalances() counts deleted rows while every report
     * excludes them, so a single number would hide a real discrepancy.
     */
    function renderLedgerUsage(response) {
        if (!response.success) {
            return '<div class="alert alert-danger mb-0">' + (response.message || 'Could not load usage.') + '</div>';
        }

        var html = '';

        if (response.usage.length === 0) {
            html += '<div class="alert alert-success">This ledger holds no entries.</div>';
        } else {
            html += '<p class="mb-2"><strong>Entries by company</strong> ' +
                '<small class="text-muted">&mdash; click a count to see the vouchers behind it</small></p>';
            // Only two counts are shown: everything attached to the ledger, and
            // the part that is current. Superseded revisions and deleted-voucher
            // rows are edit history, not something to act on here; they remain
            // available through the Live drill-down.
            html += '<div class="table-responsive"><table class="table table-sm table-bordered mb-3"><thead><tr>' +
                '<th>Company</th><th class="text-right">Entries</th><th class="text-right">Live</th>' +
                '<th class="text-right">Debit</th><th class="text-right">Credit</th>' +
                '<th class="text-center">Action</th></tr></thead><tbody>';

            $.each(response.usage, function (i, row) {
                html += '<tr' + (row.is_attributable ? '' : ' class="table-danger"') + '>';
                html += '<td><strong>' + row.company_code + '</strong>' +
                    (row.is_attributable ? '' : '<br><small class="text-muted">' + row.company_name + '</small>') + '</td>';
                html += '<td class="text-right">' + countCell(response.ledger.id, row, 'live', row.total_items, '') + '</td>';
                html += '<td class="text-right">' + countCell(response.ledger.id, row, 'live', row.live_items, '') + '</td>';
                html += '<td class="text-right">' + money(row.total_debit) + '</td>';
                html += '<td class="text-right">' + money(row.total_credit) + '</td>';
                html += '<td class="text-center">';

                if (row.one_sided_entries > 0) {
                    html += '<a class="btn btn-xs btn-danger mr-1" target="_blank" title="' +
                        row.one_sided_entries + ' one-sided voucher(s) on this ledger" href="' +
                        MISMATCH_URL + '?company_id=' + row.company_id + '&kind=one_sided">' +
                        '<i class="las la-exclamation-triangle"></i>&nbsp;' + row.one_sided_entries + ' one-sided</a>';
                }

                if (row.is_attributable && CAN_MIGRATE_LEDGER) {
                    html += '<a class="btn btn-xs btn-warning" onclick="openLedgerTransfer(' +
                        response.ledger.id + ',' + row.company_id + ',\'' + row.company_code + '\')">' +
                        '<i class="las la-exchange-alt"></i>&nbsp;Transfer</a>';
                } else if (!row.is_attributable) {
                    html += '<small class="text-muted">Not movable</small>';
                } else {
                    html += '<small class="text-muted">&mdash;</small>';
                }
                html += '</td></tr>';
            });

            html += '</tbody></table></div>';

            if (response.has_unattributed) {
                html += '<div class="alert alert-warning py-2"><small><strong>Unattributed entries</strong> belong to a cost centre ' +
                    'with no profit centre, so they map to no company. They cannot be transferred and will always block deletion. ' +
                    'Fix the cost centre first.</small></div>';
            }
        }

        function referenceTable(rows) {
            var table = '<table class="table table-sm table-bordered mb-3"><tbody>';
            $.each(rows, function (i, b) {
                table += '<tr><td>' + b.label + '</td><td class="text-right" style="width:80px"><strong>' + b.count + '</strong></td></tr>';
            });
            return table + '</tbody></table>';
        }

        // Blocking and advisory references are shown apart. Lumping them
        // together was what made an unused ledger look undeletable: the only
        // thing holding it was a pile of 0.00 year-end closing rows.
        var blocking = $.grep(response.blockers, function (b) { return b.blocking && !b.migratable; });
        if (blocking.length > 0) {
            html += '<p class="mb-2"><strong>Blocking references</strong> &mdash; these must be cleared through their own screens; ' +
                'they are shared across companies and cannot be moved for one company alone.</p>';
            html += referenceTable(blocking);
        }

        var advisory = $.grep(response.blockers, function (b) { return !b.blocking; });
        if (advisory.length > 0) {
            html += '<p class="mb-2"><strong>Other references</strong> &mdash; shown for information. These do not stop the delete: ' +
                'a soft delete leaves every row and its links intact and the ledger can be restored from Trash.</p>';
            html += referenceTable(advisory);
        }

        return html;
    }

    /**
     * The company-unassign blocker panel.
     *
     * Not a .alert: the theme makes .alert a flex container, so every bare text
     * node inside one becomes its own flex column and the copy comes out
     * shredded. This keeps the icon and the body as the only two flex children.
     */
    function blockerStat(label, value, cssClass) {
        return '<div class="mr-4 mb-1">' +
            '<div class="text-uppercase" style="font-size:10px;letter-spacing:.6px;color:#9a6068">' + label + '</div>' +
            '<div class="' + (cssClass || '') + '" style="font-size:15px;font-weight:600;color:#7a1c26">' + value + '</div>' +
            '</div>';
    }

    function renderUnassignBlocker(ledger, row) {
        var html = '<div class="d-flex mb-4" style="background:#fdf3f4;border:1px solid #f3c2c7;' +
            'border-left:4px solid #dc3545;border-radius:6px;padding:16px 18px">';

        html += '<div class="mr-3" style="flex:0 0 auto;color:#dc3545;font-size:24px;line-height:1.2">' +
            '<i class="las la-ban"></i></div>';

        html += '<div style="flex:1 1 auto;min-width:0">';
        html += '<div style="font-size:15px;font-weight:600;color:#7a1c26;line-height:1.4">' +
            row.company_code + ' cannot be unassigned</div>';
        html += '<p class="mt-1 mb-3" style="color:#6b4145;line-height:1.6">' +
            'This ledger still holds postings for ' + row.company_code + '. Dropping the assignment would block ' +
            'every future posting for that company while these entries stay in the reports.</p>';

        html += '<div class="d-flex flex-wrap mb-3">' +
            blockerStat('Entry items', row.total_items) +
            blockerStat('Debit', money(row.total_debit)) +
            blockerStat('Credit', money(row.total_credit)) +
            '</div>';

        html += '<div class="d-flex flex-wrap align-items-center" style="gap:12px;border-top:1px solid #f3c2c7;padding-top:12px">' +
            '<span style="color:#6b4145"><strong>Next step:</strong> move the entries to another ledger in the same account class.</span>';

        if (row.is_attributable && CAN_MIGRATE_LEDGER) {
            html += '<a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="openLedgerTransfer(' +
                ledger.id + ',' + row.company_id + ',\'' + row.company_code + '\')">' +
                '<i class="las la-exchange-alt"></i>&nbsp;Transfer ' + row.company_code + ' entries</a>';
        }

        html += '</div></div></div>';

        return html;
    }

    function showLedgerUsage(ledgerId) {
        var body = $('#ledgerUsageBody');
        body.html('<h5 class="text-center py-4">Please wait...</h5>');
        $('#ledgerUsageModal').modal('show');

        fetchLedgerUsage(ledgerId).done(function (response) {
            $('#ledgerUsageTitle').text(response.ledger ? response.ledger.code + ' - ' + response.ledger.name : '');
            body.html(renderLedgerUsage(response));
        }).fail(function () {
            body.html('<div class="alert alert-danger mb-0">Could not load usage for this ledger.</div>');
        });
    }

    function openLedgerTransfer(ledgerId, companyId, companyCode) {
        $('#ledgerUsageModal').modal('hide');

        var body = $('#ledgerTransferBody');
        $('#ledgerTransferTitle').text('for ' + companyCode);
        body.html('<h5 class="text-center py-4">Please wait...</h5>');
        $('#ledgerTransferModal').modal('show');

        $.ajax({
            type: 'GET',
            url: LEDGER_MIGRATION_URL + '/' + ledgerId + '/form',
            data: {company_id: companyId}
        }).done(function (html) {
            body.html(html);
        }).fail(function (xhr) {
            body.html('<div class="alert alert-danger mb-0">Could not load the transfer form (' + xhr.status + ').</div>');
        });
    }
</script>
