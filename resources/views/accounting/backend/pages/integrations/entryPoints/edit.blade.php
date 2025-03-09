<div style="overflow: hidden;">
<hr class="pt-1 mt-1">
<form action="{{ route('accounting.entry-points.update', $entryPoint->id) }}?{{ request()->has('debits') ? 'debits' : '' }}&{{ request()->has('credits') ? 'credits' : '' }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="entry-point-form">
@csrf
@method('PUT')
    @if(request()->has('debits') || request()->has('credits'))
        <h5><strong>{{ $entryPoint->short_name }} :: {{ $entryPoint->name }}</strong></h5>
        <hr>
    @else
        <div class="row pr-3">
            <div class="col-md-5">
                <label for="short_name"><strong>{{ __('Short Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                <div class="input-group input-group-md mb-3 d-">
                    <input type="text" name="short_name" id="short_name" value="{{ old('short_name', $entryPoint->short_name) }}" class="form-control rounded">
                </div>
            </div>
            <div class="col-md-7">
                <label for="name"><strong>{{ __('Entry Point Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                <div class="input-group input-group-md mb-3 d-">
                    <input type="text" name="name" id="name" value="{{ old('name', $entryPoint->name) }}" class="form-control rounded">
                </div>
            </div>
            <div class="col-md-12">
                <label for="description"><strong>{{ __('Description') }}:</strong></label>
                <div class="input-group input-group-md mb-3 d-">
                    <input type="text" name="description" id="description" value="{{ old('description', $entryPoint->description) }}" class="form-control rounded">
                </div>
            </div>
        </div>
    @endif

    @if(request()->has('debits'))
    <div class="row">
        <div class="col-md-12">
            <label for="debit_ledgers"><strong>{{ __('Debit Ledgers') }}:</strong></label>
            <div class="input-group input-group-md mb-3 d-">
                <select name="debit_ledgers[]" class="form-control debit_ledgers select2" multiple data-placeholder="Choose Debit Ledgers...">
                    {!! $debit_ledgers !!}
                </select>
            </div>
        </div>
    </div>
    @endif

    @if(request()->has('credits'))
    <div class="row">
        <div class="col-md-12">
            <label for="credit_ledgers"><strong>{{ __('Credit Ledgers') }}:</strong></label>
            <div class="input-group input-group-md mb-3 d-">
                <select name="credit_ledgers[]" class="form-control credit_ledgers select2" multiple data-placeholder="Choose Credit Ledgers...">
                    {!! $credit_ledgers !!}
                </select>
            </div>
        </div>
    </div>
    @endif
        
    <div class="row">
        <div class="col-md-12 text-right pb-5 mt-5">
            <button type="submit" class="btn btn-success btn-md entry-point-button"><i class="la la-save"></i>&nbsp;Update Control Point</button>
        </div>
    </div>
</form>
</div>

<script type="text/javascript">
    $('.select2').select2({
        dropdownParent: $('.jconfirm-box').parent()
    });

    $(document).ready(function() {
        var form = $('#entry-point-form');
        var button = form.find('.entry-point-button');
        var buttonContent = button.html();

        form.submit(function(event) {
            event.preventDefault();
            button.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...');

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
                    reloadDatatable();
                }else{
                    toastr.error(response.message);
                }
                button.prop('disabled', false).html(buttonContent);
            })
            .fail(function(response) {
                var errors = '<ul class="pl-3">';
                $.each(response.responseJSON.errors, function(index, val) {
                    errors += '<li>'+val[0]+'</li>';
                });
                errors += '</ul>';
                toastr.error(errors);

                button.prop('disabled', false).html(buttonContent);
            });
        });
    });
</script>