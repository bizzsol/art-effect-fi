<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>SL</th>
			<th>Batch</th>
			<th>Asset</th>
			<th>Location</th>
			<th>User</th>
			<th>Inspector</th>
			<th>Date & Time</th>
			<th>Ratings</th>
			<th>Remarks</th>
			<th>Attachements</th>
		</tr>
	</thead>
	<tbody>
		@if(isset($physicalStatuses[0]))
		@foreach($physicalStatuses as $key => $status)
		<tr>
			<td>{{ $key+1 }}</td>
			<td>{{ $status->batchItem->batch->batch }}</td>
			<td>{{ $status->batchItem->asset_code }}</td>
			<td>{{ $status->fixedAssetLocation ? $status->fixedAssetLocation->name : 'Not Distributed' }}</td>
			<td>{{ $status->user ? $status->user->name.' ('.$status->user->phone.')' : 'Not Distributed' }}</td>
			<td>{{ $status->inspector ? $status->inspector->name.' ('.$status->inspector->phone.')' : '' }}</td>
			<td>{{ $status->date.' '.date('g:i a', strtotime($status->time)) }}</td>
			<td>{{ inWord($status->ratings) }}</td>
			<td>{{ $status->remarks }}</td>
			<td>
				@if(!empty($status->image))
				<a href="{{ url($status->image) }}" target="_blank">Click Here</a>
				@endif
			</td>
		</tr>
		@endforeach
		@endif
	</tbody>
</table>