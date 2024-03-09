<input type="hidden" name="product_id" value="{{ $product_id }}">
<input type="hidden" name="asset_code" value="{{ $asset_code }}">
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th style="width: 25%">Asset</th>
			<th style="width: 7.5%">Batch ID</th>
			<th style="width: 7.5%">Asset Code</th>
			<th style="width: 15%">Location</th>
			<th style="width: 15%">User</th>
			<th style="width: 12.5%">Using From</th>
			<th style="width: 10%">Remarks</th>
			<th style="width: 7.5%">Options</th>
		</tr>
	</thead>
	<tbody>
		@if(isset($items[0]))
		@foreach($items as $key => $item)
		
		<tr>
			<td>
				{{ $item->finalAsset->name }} {{ getProductAttributesFaster($item->finalAsset) }}
				<br>
				GRN: <strong>{{ $item->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no }}</strong>
			</td>
			<td>{{ $item->batch->batch }}</td>
			<td>{{ $item->asset_code }}</td>
			<td>
				<select name="fixed_asset_location_id[{{ $item->asset_code }}]" class="form-control select2-updated">
					@if(!isset($item->currentUser->id))
					<option value="0">Not Distributed</option>
					@endif
					@foreach($fixedAssetLocations as $key => $location)
					<option value="{{ $location->id }}" {{ isset($item->currentUser->id) && $item->currentUser->fixed_asset_location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
					@endforeach
				</select>
			</td>
			<td>
				<select name="user_id[{{ $item->asset_code }}]" class="form-control select2-updated">
					@if(!isset($item->currentUser->id) || $item->currentUser->user_id == 0)
					<option value="0">Not Distributed</option>
					@endif
					@foreach($users as $key => $user)
					<option value="{{ $user->id }}" {{ isset($item->currentUser->id) && $item->currentUser->user_id == $user->id ? 'selected' : '' }}>{{ $user->name.' ('.$user->phone.')' }}</option>
					@endforeach
				</select>
			</td>
			<td>
				<input type="date" name="from[{{ $item->asset_code }}]" value="{{ isset($item->currentUser->id) ? $item->currentUser->from : date('Y-m-d') }}" class="form-control">
			</td>
			<td>
				<input type="text" name="remarks[{{ $item->asset_code }}]" value="{{ isset($item->currentUser->id) ? $item->currentUser->giving_remarks : '' }}" class="form-control">
			</td>
			<td class="text-center">
				@if($item->users->count() > 0)
				<a class="btn btn-xs btn-success" onclick="loadHistory('{{ $item->id }}', '{{ $item->asset_code }}')"><i class="las la-file-alt"></i>&nbsp;History</a>
				@endif
			</td>
		</tr>
		@endforeach
		@endif
	</tbody>
</table>