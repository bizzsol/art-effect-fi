<div style="overflow: hidden">
    <form action="{{ route('accounting.entries.update', 0) }}?global-approval&id={{ $entry->id }}" method="post" accept-charset="utf-8" id="approval-form">
    @csrf
    @method('PUT')

        <div class="col-md-12">
            <table class="table table-bordered mb-4">
                <tbody>
                    <tr>
                        <td style="width: 50%;vertical-align: top !important;">
                            <ul  style="list-style: square;padding-left: 20px !important;" class="mb-0">
                                <li>Reference: <strong>{{ $entry->number }}</strong></li>
                                <li>Fiscal Year: <strong>{{ $entry->fiscalYear->title }}</strong></li>
                            </ul>
                        </td>
                        <td style="width: 50%;vertical-align: top !important;">
                            <ul  style="list-style: square;padding-left: 20px !important;" class="mb-0">
                                <li>Currency: <strong>{{ $entry->exchangeRate->currency->name }}</strong></li>
                                <li>Entry Type: <strong>{{ $entry->entryType->name }}</strong></li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-12">
            <div class="row">
                @if(isset($approvals[0]))
                @foreach($approvals as $level)
                <div class="col-md-4 mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-white"><strong>{{ $level->approvalLevel->name }}</strong></h5>
                        </div>
                        <div class="card-body" style="border: 1px solid #ccc;">
                            <div class="form-group text-center">
                                <div class="icheck-warning d-inline">
                                    <input type="radio" id="status_pending_{{ $level->id }}" name="status[{{ $level->id }}]" value="pending" {{ $level->status == 'pending' ? 'checked' : '' }}>
                                    <label for="status_pending_{{ $level->id }}" class="text-warning">
                                      <strong>Pending&nbsp;&nbsp;&nbsp;</strong>
                                    </label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" id="status_approved_{{ $level->id }}" name="status[{{ $level->id }}]" value="approved" {{ $level->status == 'approved' ? 'checked' : '' }}>
                                    <label for="status_approved_{{ $level->id }}" class="text-success">
                                      <strong>Approve&nbsp;&nbsp;&nbsp;</strong>
                                    </label>
                                </div>
                                <div class="icheck-danger d-inline">
                                    <input type="radio" id="status_denied_{{ $level->id }}" name="status[{{ $level->id }}]" value="denied" {{ $level->status == 'denied' ? 'checked' : '' }}>
                                    <label for="status_denied_{{ $level->id }}" class="text-danger">
                                      <strong>Deny&nbsp;&nbsp;&nbsp;</strong>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="notes_{{ $level->id }}"><strong>Notes</strong></label>
                                <textarea name="notes[{{ $level->id }}]" id="notes_{{ $level->id }}" class="form-control" rows="3" style="resize: none"></textarea>
                            </div>
                            <h6 class="mb-2"><strong>Logs: </strong></h6>
                            @php
                                $logs = !empty($level->logs) ? json_decode($level->logs, true) : [];
                            @endphp
                            <ul  style="list-style: square;padding-left: 20px !important;">
                                @if(isset($logs[0]))
                                @foreach($logs as $key => $log)
                                <li>{!! $log !!}</li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-md approval-button"><i class="las la-check"></i>&nbsp;Submit Approval</button>
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
                    if($('.datatable-serverside') != undefined){
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