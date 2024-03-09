<hr class="pt-1 mt-1">
<form action="{{ url('accounting/cheque-books/'.$page->id.'/tag-transactions') }}" method="post" accept-charset="utf-8"
      id="tag-form">
    @csrf
    <ul class="pl-3">
        <li>Bank Account:
            <strong>{{ $page->chequeBook->bankAccount->name.' ('.$page->chequeBook->bankAccount->number.') ('.($page->chequeBook->bankAccount->currency ? $page->chequeBook->bankAccount->currency->code : '').')' }}</strong>
        </li>
        <li>Book Number: <strong>{{ $page->chequeBook->book_number }}</strong></li>
        <li>Page Number: <strong>{{ $page->page_number }}</strong></li>
    </ul>
    <div class="from-group">
        <label for="transactions"><strong>{{ __('Transactions') }}:</strong></label>
        <div class="input-group input-group-md mb-3 d-">
            <select name="transactions[]" class="form-control rounded transactions" multiple>
                @if(isset($entries[0]))
                    @foreach($entries as $key => $entry)
                        <option value="{{ $entry->id }}">{{ !empty($entry->number) ? $entry->number : $entry->code }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-success btn-md tag-button"><i class="la la-save"></i>&nbsp;Tag these
        Transactions
    </button>
</form>


<script type="text/javascript">
    $(document).ready(function () {
        $('.transactions').select2({
            dropdownParent: $('.jconfirm-box').parent()
        });

        var tag_form = $('#tag-form');
        var tag_button = $('.tag-button');
        var content = tag_button.html();

        tag_form.submit(function (event) {
            event.preventDefault();

            tag_button.html('<i class="las la-spinner la-spin"></i>&nbsp;&nbsp;Please Wait...').prop('disabled', true);

            $.ajax({
                url: tag_form.attr('action'),
                type: tag_form.attr('method'),
                dataType: 'json',
                data: tag_form.serializeArray(),
            })
                .done(function (response) {
                    tag_button.html(content).prop('disabled', false);
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