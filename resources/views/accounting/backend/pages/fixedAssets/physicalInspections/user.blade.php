@if(isset($item->currentUser->id))
	<a class="text-primary" onclick="loadHistory('{{ $item->id }}', '{{ $item->asset_code }}')">
		{{ $item->currentUser->user ? $item->currentUser->user->name.' ('.$item->currentUser->user->phone.')' : '' }}
	</a>
@else
	Not Distributed
@endif