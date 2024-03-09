<div style="overflow: hidden">
    <form action="{{ route('accounting.entries.update', 0) }}?approval&id={{ $approval->id }}" method="post" accept-charset="utf-8" id="approval-form">
    @csrf
    @method('PUT')
        <div class="form-group pl-0">
            <table class="table table-bordered mb-4">
                <tbody>
                    <tr>
                        <td style="width: 41.66%;vertical-align: top !important;">
                            <h5 class="mb-2"><strong>Information: </strong></h5>
                            <ul  style="list-style: square;padding-left: 20px !important;">
                                <li>Reference: <strong>{{ $approval->entry->number }}</strong></li>
                                <li>Fiscal Year: <strong>{{ $approval->entry->fiscalYear->title }}</strong></li>
                                <li>Currency: <strong>{{ $approval->entry->exchangeRate->currency->name }}</strong></li>
                                <li>Entry Type: <strong>{{ $approval->entry->entryType->name }}</strong></li>
                            </ul>
                        </td>
                        <td style="width: 58.33%;vertical-align: top !important;">
                            <h5 class="mb-2"><strong>Logs: </strong></h5>
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
                </tbody>
            </table>
        </div>
        <div class="form-group text-center">
            <div class="icheck-warning d-inline">
                <input type="radio" id="status_pending" name="status" value="pending" {{ $approval->status == 'pending' ? 'checked' : '' }}>
                <label for="status_pending" class="text-warning">
                  <strong>Pending&nbsp;&nbsp;&nbsp;</strong>
                </label>
            </div>
            <div class="icheck-success d-inline">
                <input type="radio" id="status_approved" name="status" value="approved" {{ $approval->status == 'approved' ? 'checked' : '' }}>
                <label for="status_approved" class="text-success">
                  <strong>Approve&nbsp;&nbsp;&nbsp;</strong>
                </label>
            </div>
            <div class="icheck-danger d-inline">
                <input type="radio" id="status_denied" name="status" value="denied" {{ $approval->status == 'denied' ? 'checked' : '' }}>
                <label for="status_denied" class="text-danger">
                  <strong>Deny&nbsp;&nbsp;&nbsp;</strong>
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="notes"><strong>Notes</strong></label>
            <textarea name="notes" id="notes" class="form-control" rows="4" style="resize: none"></textarea>
        </div>

        <button type="submit" class="btn btn-success btn-md approval-button"><i class="las la-check"></i>&nbsp;Submit</button>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var form = $('#approval-form');
        var button = $('.approval-button');
        var content = button.html();

        form.submit(function(event) {
            event.preventDefault();
            button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                data: form.serializeArray(),
            })
            .done(function(response) {
                if(response.success){
                    toastr.success(response.message);
                    $('.jconfirm').remove();
                    if($('.datatable-serverside')){
                        reloadDatatable();
                    }else{
                        location.reload();
                    }
                }else{
                    toastr.error(response.message);
                    button.html(content).prop('disabled', false);
                }
            })
            .fail(function(response) {
                $.each(response.responseJSON.errors, function(index, val) {
                    toastr.error(val[0]);
                });
                button.html(content).prop('disabled', false);
            });
        });
    });
</script>