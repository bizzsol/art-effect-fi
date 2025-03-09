<hr class="pt-1 mt-1">
<form action="{{ url('accounting/cheque-books/'.$page->id.'/damage') }}" method="post" accept-charset="utf-8"
      id="damage-form">
    @csrf
    <ul class="pl-3">
        <li>Bank Account:
            <strong>{{ $page->chequeBook->bankAccount->name.' ('.$page->chequeBook->bankAccount->number.') ('.($page->chequeBook->bankAccount->currency ? $page->chequeBook->bankAccount->currency->code : '').')' }}</strong>
        </li>
        <li>Book Number: <strong>{{ $page->chequeBook->book_number }}</strong></li>
        <li>Page Number: <strong>{{ $page->page_number }}</strong></li>
    </ul>
    <div class="from-group">
        <label for="damage_reason"><strong>{{ __('Damage Reason') }}:</strong></label>
        <div class="input-group input-group-md mb-3 d-">
            <textarea name="damage_reason" id="damage_reason" class="form-control rounded"
                      style="height: 100px;resize: none"></textarea>
        </div>
    </div>
    <div class="from-group">
        <label for="damaged_at"><strong>{{ __('Damage Datetime') }}:<span
                        class="text-danger">&nbsp;*</span></strong></label>
        <div class="input-group input-group-md mb-3 d-">
            <input type="datetime-local" name="damaged_at" id="damaged_at" value="{{ date('Y-m-d H:i:s') }}"
                   class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-success btn-md damage-button"><i class="la la-save"></i>&nbsp;Mark This page as
        Damaged
    </button>
</form>


<script type="text/javascript">
    $(document).ready(function () {
        var damage_form = $('#damage-form');
        var damage_button = $('.damage-button');
        var content = damage_button.html();

        damage_form.submit(function (event) {
            event.preventDefault();

            damage_button.html('<i class="las la-spinner la-spin"></i>&nbsp;&nbsp;Please Wait...').prop('disabled', true);

            $.ajax({
                url: damage_form.attr('action'),
                type: damage_form.attr('method'),
                dataType: 'json',
                data: damage_form.serializeArray(),
            })
                .done(function (response) {
                    damage_button.html(content).prop('disabled', false);
                    if (response.success) {
                        toastr.success(response.message);
                        $('.jconfirm').remove();
                        reloadDatatable();
                    } else {
                        toastr.error(response.message);
                    }
                });
        });
    });
</script>