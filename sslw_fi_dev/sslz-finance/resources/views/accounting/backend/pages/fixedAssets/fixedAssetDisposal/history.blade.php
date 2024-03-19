<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>SL</th>
			<th>User</th>
			<th>From</th>
			<th>To</th>
			<th>Given Remarks</th>
			<th>Received</th>
			<th>Received Remarks</th>
			<th>Taken</th>
			<th>Taken Remarks</th>
		</tr>
	</thead>
	<tbody>
		@if(isset($users[0]))
		@foreach($users as $key => $user)
		<tr>
			<td class="text-center">{{ $key+1 }}</td>
			<td>{{ $user->user->name }} ({{ $user->user->phone }})</td>
			<td class="text-center">{{ $user->from }}</td>
			<td class="text-center">{{ strtotime($user->to) > 0 ? $user->to : '-' }}</td>
			<td>{{ $user->giving_remarks }}</td>
			<td class="text-center">{{ $user->is_received == 1 ? 'Yes' : 'No' }}</td>
			<td>{{ $user->receiving_remarks }}</td>
			<td class="text-center">{{ $user->is_taken == 1 ? 'Yes' : 'No' }}</td>
			<td>{{ $user->taken_remarks }}</td>
		</tr>
		@endforeach
		@endif
	</tbody>
</table>