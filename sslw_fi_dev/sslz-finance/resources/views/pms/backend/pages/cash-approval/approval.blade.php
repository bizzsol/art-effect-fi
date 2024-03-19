<form action="{{ route('pms.po.cash.approval.store') }}" method="POST" id="cash-approval-form">
	@csrf
	<input type="hidden" readonly required name="id" id="purchase_order_id" value="{{ $purchase_order_id }}">
	<input type="hidden" readonly required name="cash_status" id="po_cash_status" value="{{ $po_cash_status }}">
	@if($po_cash_status == 'approved')
	<div class="form-group">
		<label for="cost_centre_id"><strong>{{ __('Cost Centre') }}:<span class="text-danger">&nbsp;*</span></strong></label>
        <div class="input-group input-group-md mb-3 d-">
            <select name="cost_centre_id" id="cost_centre_id" class="form-control rounded select2">
            	@if(isset($costCentres[0]))
                @foreach($costCentres as $key => $costCentre)
                <option value="{{ $costCentre->id }}">[{{ $costCentre->code }}] {{ $costCentre->name }} ({{ $costCentre->company->name }})</option>
                @endforeach
                @endif
            </select>
        </div>
	</div>
	@endif
	<div class="form-group">
		<label for="cash_note">Notes (Optional):</label>
		<textarea class="form-control" name="cash_note" rows="3" id="cash_note" placeholder="Write here..."></textarea>
	</div>
	<button type="submit" class="btn btn-primary" id="cash-approval-button"><i class="la la-check"></i>&nbsp;Submit</button>
</form>

<script>
	$('.select2').select2({
        dropdownParent: $('.jconfirm-box').parent()
    });

	$(document).ready(function() {
		var form = $('#cash-approval-form');
		var button = $('#cash-approval-button');
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
				button.html(content).prop('disabled', false);
				if(response.success){
					toastr.success(response.message);
					$('.jconfirm').remove();
					reloadDatatable();
				}else{
					toastr.error(response.message);
				}
			});
		});
	});
</script>