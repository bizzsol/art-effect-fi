<form action="{{ route('accounting.my-assets.update', $user->id) }}" method="post" accept-charset="utf-8" id="movement-form">
@csrf
@method('PUT')
<div class="form-group row">
	<div class="col-md-5">
		<label for="fixed_asset_location_id"><strong>Location</strong></label>
		<select name="fixed_asset_location_id" id="fixed_asset_location_id" class="form-control select2">
			@if(isset($fixedAssetLocations[0]))
			@foreach($fixedAssetLocations as $key => $location)
				<option value="{{ $location->id }}">{{ $location->name }}</option>
			@endforeach
			@endif
		</select>
	</div>
	<div class="col-md-4">
		<label for="user_id"><strong>User</strong></label>
		<select name="user_id" id="user_id" class="form-control select2">
			<option value="0">Not Necessary</option>}
			@if(isset($users[0]))
			@foreach($users as $key => $u)
				<option value="{{ $u->id }}">{{ $u->name.' ('.$u->phone.')' }}</option>
			@endforeach
			@endif
		</select>
	</div>
	<div class="col-md-3">
		<label for="expected_date"><strong>Expected Date</strong></label>
		<input type="date" name="expected_date" id="expected_date" value="{{ date('Y-m-d') }}" class="form-control">
	</div>
</div>
<div class="form-group">
	<label for="remarks"><strong>Remarks</strong></label>
	<textarea name="remarks" id="remarks" class="form-control" style="height: 120px !important;resize: none"></textarea>
</div>
<button type="submit" class="btn btn-success movement-button"><i class="la la-check"></i>&nbsp;Submit Requisition</button>
</form>
<script type="text/javascript">
	$('.select2').select2();

	$(document).ready(function() {
		var form = $('#movement-form');
		var button = $('.movement-button');
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