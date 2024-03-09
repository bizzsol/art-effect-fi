<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Date</th>
			<th>Time</th>
			<th>Cost Amount</th>
			<th>Details</th>
			<th>Attachments</th>
			<th>Cost Added by</th>
		</tr>
	</thead>
	<tbody>
		@if(isset($costs[0]))
		@foreach($costs as $key => $cost)
		<tr>
			<td>{{ $key+1 }}</td>
			<td>{{ date('F jS, Y', strtotime($cost->date)) }}</td>
			<td>{{ date('g:i a', strtotime($cost->time)) }}</td>
			<td class="text-right">{{ systemMoneyFormat($cost->cost) }}</td>
			<td>{{ $cost->details }}</td>
			<td>
				@if(!empty($cost->attachments))
				<a href="{{ url($cost->attachments) }}" target="_blank">View Attachments</a>
				@endif
			</td>
			<td>{{ $cost->creator ? $cost->creator->name : '' }}</td>
		</tr>
		@endforeach
		@endif

		<tr>
			<td colspan="3" class="text-right"><strong>Total Cost: </strong></td>
			<td class="text-right"><strong>{{ systemMoneyFormat($costs->sum('cost')) }}</strong></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>