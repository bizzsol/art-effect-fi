@php
	$clickable = $object->items->whereIn('cost_centre_id', $userCostCentres)->count() > 0 ? true : false;
@endphp
<div style="width: 150px !important">
	@if($object->is_approved == 'approved')
		<a class="btn btn-xs btn-success" onclick="entryApprovalHistory($(this))" data-id="{{ $object->id  }}">Approved</a>
	@else
		@if($object->approvals->whereIn('approval_level_id', isset($approvals) ? $approvals : [])->count() > 0)
		@foreach($object->approvals->whereIn('approval_level_id', $approvals) as $key => $value)
			<a class="btn btn-xs btn-{{ approvalLevelClass()[$value->status] }} mb-1" @if($clickable) onclick="entryApproval($(this))" data-id="{{ $value->id  }}" @endif>{{ $value->approvalLevel->name }} {{ ucwords($value->status) }}</a>
		@endforeach
		@endif
	@endif
</div>
