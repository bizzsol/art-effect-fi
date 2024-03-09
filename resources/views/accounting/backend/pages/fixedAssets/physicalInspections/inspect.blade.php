<form action="{{ url('accounting/fixed-asset-inspections') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="inspect-form">
@csrf
	<input type="hidden" name="fixed_asset_batch_item_id" value="{{ $item->id }}">
	<input type="hidden" name="fixed_asset_location_id" value="{{ isset($item->currentUser->id) ? $item->currentUser->fixed_asset_location_id : 0 }}">
	<input type="hidden" name="user_id" value="{{ isset($item->currentUser->id) ? $item->currentUser->user_id : 0 }}">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th style="width: 15%">Asset</th>
						<th style="width: 10%">GRN</th>
						<th style="width: 5%">Batch ID</th>
						<th style="width: 5%">Asset Code</th>
						<th style="width: 15%">Current Location</th>
						<th style="width: 15%">Current User</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{ isset($item->finalAsset->name) ? $item->finalAsset->name.' '.getProductAttributesFaster($item->finalAsset) : '' }}</td>
						<td>{{ isset($item->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no) ? $item->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no : '' }}</td>
						<td>{{ $item->batch->batch }}</td>
						<td>{{ $item->asset_code }}</td>
						<td>
							@if(isset($item->currentUser->id))
								<a class="text-primary" onclick="loadHistory('{{ $item->id }}', '{{ $item->asset_code }}')">
									{{ $item->currentUser->fixedAssetLocation ? $item->currentUser->fixedAssetLocation->name : '' }}
								</a>
							@else
								Not Distributed
							@endif
						</td>

						<td>
							@if(isset($item->currentUser->id))
								<a class="text-primary" onclick="loadHistory('{{ $item->id }}', '{{ $item->asset_code }}')">
									{{ $item->currentUser->user ? $item->currentUser->user->name.' ('.$item->currentUser->user->phone.')' : '' }}
								</a>
							@else
								Not Distributed
							@endif
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 mt-4">
			<h4><strong>Inspection Information</strong></h4>
			<hr>
		</div>
		<div class="col-md-3">
			<label for="date"><strong>Date</strong></label>
			<input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control">
		</div>
		<div class="col-md-3">
			<label for="time"><strong>Time</strong></label>
			<input type="time" name="time" id="time" value="{{ date('H:i') }}" class="form-control">
		</div>
		<div class="col-md-2">
			<label for="ratings"><strong>Ratings</strong></label>
			<select name="ratings" id="ratings" class="form-control">
				@for($i=10;$i>=0;$i--)
				<option value="{{ $i }}">{{ inWord($i) }}</option>
				@endfor
			</select>
		</div>
		<div class="col-md-4">
			<label for="file"><strong>Image</strong></label>
			<input type="file" name="file" id="file" class="form-control">
		</div>
	</div>
	<div class="row mt-3 mb-3">
		<div class="col-md-12">
			<label for="remarks"><strong>Remarks</strong></label>
			<textarea name="remarks" id="remarks" class="form-control" style="height: 150px;resize: none"></textarea>
		</div>
	</div>
	<button type="submit" class="btn btn-success inspect-button"><i class="la la-check"></i>&nbsp;Submit Inspection</button>
</form>

<script type="text/javascript">
	var form = $('#inspect-form');
	var button = $('.inspect-button');
	var button_content = button.html();

	$(document).ready(function() {
		form.submit(function(event) {
			event.preventDefault();

			button.html('Please wait...&nbsp;<i class="las la-spinner"></i>').prop('disabled', true);
		    $.ajax({
		        url: form.attr('action'),
		        type: form.attr('method'),
		        dataType: 'json',
		        processData: false,
            	contentType: false,
		        data: new FormData(form[0]),
		    })
		    .done(function(response) {
		        if(response.success){
		        	$('#sampleModal').modal('hide');
		        	toastr.success(response.message);
		        }else{
		            toastr.error(response.message);
		        }
		        button.html(button_content).prop('disabled', false); 
		    })
		    .fail(function(response) {
		        var errors = '<ul class="pl-2">';
	            $.each(response.responseJSON.errors, function(index, val) {
	                errors += "<li class='text-white'>"+val[0]+"</li>";
	            });
	            errors += '</ul>';
	            toastr.error(errors);

		        button.html(button_content).prop('disabled', false);
		    });
		});
	});
</script>