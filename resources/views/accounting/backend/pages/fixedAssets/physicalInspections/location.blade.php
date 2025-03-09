@if(isset($item->currentUser->id))
	<a class="text-primary" onclick="loadHistory('{{ $item->id }}', '{{ $item->asset_code }}')">
		{{ $item->currentUser->fixedAssetLocation->name }}
	</a>
@else
	Not Distributed
@endif