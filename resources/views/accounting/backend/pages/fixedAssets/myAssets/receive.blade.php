<form action="{{ url('accounting/my-assets') }}" method="post" accept-charset="utf-8" id="receive-form">
@csrf
<input type="hidden" name="id" value="{{ $user->id }}">
<div class="form-group">
	<label for="receiving_remarks"><strong>Receiving Remarks</strong></label>
	<textarea name="receiving_remarks" id="receiving_remarks" class="form-control" style="height: 120px !important;resize: none"></textarea>
</div>
<button type="submit" class="btn btn-success receive-button"><i class="la la-check"></i>&nbsp;Confirm Receive</button>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		var form = $('#receive-form');
		var button = $('.receive-button');
		var content = button.html();
		
		form.submit(function(event) {
			event.preventDefault();

			button.html('<i class="las la-spinner la-spin"></i>&nbsp;Prease wait...').prop('disabled', true);
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
					$('#sampleModal').modal('hide');
					reloadDatatable();
				}else{
					toastr.error(response.message);
				}
			})
			.fail(function(response) {
				var errors = '<ul class="pl-2">';
	            $.each(response.responseJSON.errors, function(index, val) {
	                errors += "<li class='text-white'>"+val[0]+"</li>";
	            });
	            errors += '</ul>';
	            toastr.error(errors);

	            button.html(content).prop('disabled', false);
			});
		});	
	});
</script>