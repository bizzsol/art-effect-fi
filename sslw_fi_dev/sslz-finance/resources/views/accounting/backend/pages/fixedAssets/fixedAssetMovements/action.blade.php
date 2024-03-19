<a class="mb-1 btn btn-xs btn-dark">Movement {{ ucwords($movement->status) }}</a>
@if(in_array($movement->status, ['pending', 'cancelled']))
<a class="mb-1 btn btn-success btn-xs" onclick="Approval('{{ $movement->id }}', '{{ $movement->batchItemUser->batchItem->asset_code }}', 'Approval', 'approved')"><i class="la la-check"></i>&nbsp;Approve</a>
@if($movement->status != "cancelled")
<a class="mb-1 btn btn-danger btn-xs" onclick="Approval('{{ $movement->id }}', '{{ $movement->batchItemUser->batchItem->asset_code }}', 'Cancellation', 'cancelled')"><i class="la la-ban"></i>&nbsp;Cancel</a>
@endif
@elseif(in_array($movement->status, ['approved']) && !in_array($movement->status, ['moved']))
<a class="mb-1 btn btn-success btn-xs" onclick="Approval('{{ $movement->id }}', '{{ $movement->batchItemUser->batchItem->asset_code }}', 'Process Movement', 'moved')"><i class="la la-check"></i>&nbsp;Process Movement</a>
@endif