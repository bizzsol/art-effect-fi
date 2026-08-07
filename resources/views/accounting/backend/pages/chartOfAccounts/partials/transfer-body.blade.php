{{--
    Transfer form body, loaded into the modal by openLedgerTransfer().

    Targets are already filtered server-side to the same account class and to
    ledgers mapped to this company; every rule is re-checked again on submit, so
    nothing here is trusted.
--}}
@php
    // Resolved through the service so it still works when the source ledger's
    // account group is itself soft-deleted, which is common on the Trash page.
    $sourceClass = app(\App\Services\Accounting\LedgerMigrationService::class)->accountClassNameOf($source);
@endphp

@if($source->deleted_at)
    <div class="alert alert-info py-2">
        <small><strong>{{ $source->code }} is in the trash.</strong> Moving these entries off it is exactly what
            lets it stay deleted. The ledger itself is not restored by this transfer.</small>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <table class="table table-sm table-bordered mb-3">
            <tr>
                <th style="width: 25%">From ledger</th>
                <td><strong>{{ $source->code }}</strong> &mdash; {{ $source->name }}
                    @if($source->deleted_at)<span class="badge badge-danger">Deleted</span>@endif
                </td>
            </tr>
            <tr>
                <th>Company</th>
                <td><strong>{{ $company->code }}</strong> &mdash; {{ $company->name }}</td>
            </tr>
            <tr>
                <th>Account class</th>
                <td>{{ $sourceClass }} <small class="text-muted">(the target must match)</small></td>
            </tr>
            @if($scope)
                <tr>
                    <th>Entries in scope</th>
                    <td>
                        <strong>{{ number_format($scope['items']) }}</strong> item(s)
                        &mdash; current {{ number_format($scope['live_items']) }},
                        superseded by edits {{ number_format($scope['superseded_items']) }},
                        on deleted vouchers {{ number_format($scope['voucher_deleted_items']) }}
                        <br>
                        <small class="text-muted">
                            Debit {{ number_format($scope['total_debit'], 2) }} /
                            Credit {{ number_format($scope['total_credit'], 2) }}
                        </small>
                    </td>
                </tr>
            @endif
            @if($fiscalYears->count() > 0)
                <tr>
                    <th>Fiscal years affected</th>
                    <td>
                        @foreach($fiscalYears as $year)
                            <span class="badge badge-info">{{ $year->title }} ({{ $year->items }})</span>
                        @endforeach
                    </td>
                </tr>
            @endif
        </table>
    </div>
</div>

@if(!$scope || $scope['items'] === 0)
    <div class="alert alert-info mb-0">There are no entries on this ledger for {{ $company->code }}.</div>
@elseif($targets->isEmpty())
    <div class="alert alert-warning mb-0">
        No eligible target ledger. A target must be in the same account class ({{ $sourceClass }})
        and already assigned to {{ $company->code }}. Create or assign one first.
    </div>
@else
    <form id="ledgerTransferForm" onsubmit="return false;">
        <input type="hidden" name="company_id" value="{{ $company->id }}">

        <div class="form-group">
            <label for="target_chart_of_account_id"><strong>Transfer to ledger</strong></label>
            <select name="target_chart_of_account_id" id="target_chart_of_account_id" class="form-control">
                <option value="">-- Select a target ledger --</option>
                @foreach($targets as $target)
                    <option value="{{ $target->id }}">{{ $target->code }} &mdash; {{ $target->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="ledgerTransferPreflight"></div>

        <div class="form-group">
            <label for="reason"><strong>Reason</strong> <small class="text-muted">(recorded in the audit trail)</small></label>
            <textarea name="reason" id="reason" rows="2" class="form-control"
                      placeholder="Why are these entries being reclassified?"></textarea>
        </div>

        <div class="alert alert-warning">
            <div class="icheck-primary">
                <input type="checkbox" id="transferConfirm" name="confirm" value="1">
                <label for="transferConfirm">
                    I understand this reclassifies posted entries. Carry-forward balances for the affected
                    fiscal years will be stale until that span is re-closed for {{ $company->code }}.
                </label>
            </div>
        </div>

        <div class="text-right">
            <button type="button" class="btn btn-dark btn-md" data-dismiss="modal">
                <i class="la la-times"></i>&nbsp;Cancel</button>
            <button type="button" class="btn btn-warning btn-md" id="runLedgerTransfer"
                    data-src="{{ route('accounting.ledger-migration.store', $source->id) }}"
                    data-preflight="{{ route('accounting.ledger-migration.preflight', $source->id) }}">
                <i class="las la-exchange-alt"></i>&nbsp;Transfer Entries</button>
        </div>
    </form>

    <script type="text/javascript">
        (function () {
            var form = $('#ledgerTransferForm');
            var button = $('#runLedgerTransfer');
            var preflightBox = $('#ledgerTransferPreflight');

            // Dry run as soon as a target is picked, so blockers appear before
            // the operator commits rather than as an error afterwards.
            $('#target_chart_of_account_id').on('change', function () {
                var targetId = $(this).val();
                preflightBox.html('');
                if (!targetId) return;

                preflightBox.html('<p class="text-muted">Checking...</p>');

                $.ajax({
                    type: 'POST',
                    url: button.attr('data-preflight'),
                    data: {
                        company_id: form.find('[name=company_id]').val(),
                        target_chart_of_account_id: targetId
                    },
                    dataType: 'json'
                }).done(function (response) {
                    var html = '';

                    if (response.blockers && response.blockers.length > 0) {
                        html += '<div class="alert alert-danger"><strong>This transfer cannot run:</strong><ul class="mb-0 pl-3">';
                        $.each(response.blockers, function (i, b) { html += '<li>' + b + '</li>'; });
                        html += '</ul></div>';
                    }

                    if (response.warnings && response.warnings.length > 0) {
                        html += '<div class="alert alert-warning"><strong>Check before continuing:</strong><ul class="mb-0 pl-3">';
                        $.each(response.warnings, function (i, w) { html += '<li>' + w + '</li>'; });
                        html += '</ul></div>';
                    }

                    if (response.success && (!response.warnings || response.warnings.length === 0)) {
                        html += '<div class="alert alert-success mb-3">Pre-flight checks passed.</div>';
                    }

                    preflightBox.html(html);
                    button.prop('disabled', !response.success);
                }).fail(function () {
                    preflightBox.html('<div class="alert alert-danger">Pre-flight check failed.</div>');
                    button.prop('disabled', true);
                });
            });

            button.on('click', function () {
                var payload = {
                    company_id: form.find('[name=company_id]').val(),
                    target_chart_of_account_id: $('#target_chart_of_account_id').val(),
                    reason: $('#reason').val(),
                    confirm: $('#transferConfirm').is(':checked') ? 1 : 0
                };

                if (!payload.target_chart_of_account_id) {
                    swal({icon: 'warning', text: 'Select a target ledger first.', button: 'OK'});
                    return;
                }

                button.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>&nbsp;Transferring...');

                $.ajax({type: 'POST', url: button.attr('data-src'), data: payload, dataType: 'json'})
                    .done(function (response) {
                        button.prop('disabled', false).html('<i class="las la-exchange-alt"></i>&nbsp;Transfer Entries');

                        if (!response.success) {
                            swal({icon: 'error', text: response.message, button: 'OK'});
                            return;
                        }

                        $('#ledgerTransferModal').modal('hide');
                        swal({
                            icon: 'success',
                            title: response.reference,
                            text: response.message + '\n\n' + response.reclose_warning,
                            button: 'OK'
                        }).then(function () { location.reload(); });
                    })
                    .fail(function (xhr) {
                        button.prop('disabled', false).html('<i class="las la-exchange-alt"></i>&nbsp;Transfer Entries');
                        var message = 'Transfer failed (' + xhr.status + ').';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = $.map(xhr.responseJSON.errors, function (v) { return v[0]; }).join('\n');
                        }
                        swal({icon: 'error', text: message, button: 'OK'});
                    });
            });
        })();
    </script>
@endif
