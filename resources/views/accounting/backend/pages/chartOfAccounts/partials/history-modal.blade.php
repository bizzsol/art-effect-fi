<div class="modal fade" id="ledgerHistoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change History <span class="text-muted" id="ledgerHistoryTitle"></span></h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="ledgerHistoryBody">
                <h5 class="text-center py-4">Please wait...</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-md" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function showLedgerHistory(ledgerId, ledgerCode) {
        var body = $('#ledgerHistoryBody');
        $('#ledgerHistoryTitle').text(ledgerCode || '');
        body.html('<h5 class="text-center py-4">Please wait...</h5>');
        $('#ledgerHistoryModal').modal('show');

        $.ajax({
            type: 'GET',
            url: "{{ url('accounting/chart-of-accounts') }}/" + ledgerId + "/history"
        }).done(function (html) {
            body.html(html);
        }).fail(function (xhr) {
            body.html('<div class="alert alert-danger mb-0">Could not load history (' + xhr.status + ').</div>');
        });
    }
</script>
