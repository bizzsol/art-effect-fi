<script type="text/javascript">
    function toggleAccountingApproval(table, id, field, text, field_value) {
        swal({
            title: "Are you sure to " + text + " ?",
            text: "Once you " + text + ", It will have effects on other modules.",
            icon: "warning",
            dangerMode: true,
            buttons: {
                cancel: true,
                confirm: {
                    text: "Confirm",
                    value: true,
                    visible: true,
                    closeModal: true
                },
            },
        }).then((value) => {
            if (value) {
                var button = $(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('accounting/approval') }}",
                    dataType: 'json',
                    data: {
                        table: table,
                        id: id,
                        field: field,
                        value: field_value,
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            reloadDatatable();
                        } else {
                            toastr.error(response.message);
                            return;
                        }
                    },
                });
            }
        });
    }

    function entryApproval(id) {
        $.dialog({
            title: '&nbsp;',
            content: "url:{{ url('accounting/entries/0') }}?approval&id="+id,
            animation: 'scale',
            columnClass: 'medium',
            closeAnimation: 'scale',
            backgroundDismiss: true,
        });
    }

    function entryApprovalHistory(id) {
        $.dialog({
            title: '&nbsp;',
            content: "url:{{ url('accounting/entries') }}/"+id+"?approval-history",
            animation: 'scale',
            columnClass: 'medium',
            closeAnimation: 'scale',
            backgroundDismiss: true,
        });
    }
</script>