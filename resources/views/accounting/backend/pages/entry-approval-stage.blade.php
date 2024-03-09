<div style="width: 150px !important">
	@if($object->is_approved == 'approved')
		<a class="btn btn-xs btn-success" onclick="entryApprovalHistory('{{ $object->id  }}')">Approved</a>
	@else
		@if($object->approvals->whereIn('approval_level_id', $approvals)->count() > 0)
		@foreach($object->approvals->whereIn('approval_level_id', $approvals) as $key => $value)
			<a class="btn btn-xs btn-{{ approvalLevelClass()[$value->status] }} mb-1" onclick="entryApproval('{{ $value->id  }}')">{{ $value->approvalLevel->name }} {{ ucwords($value->status) }}</a>
		@endforeach
		@endif
	@endif
</div>