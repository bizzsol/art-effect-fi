@if($object->is_reviewed == 'approved')
	@if($object->is_assessed == 'approved')
		@if($object->is_approved == 'approved')
			<a class="btn btn-xs btn-success">Approved</a>
		@else
			<a class="btn btn-xs btn-{{ $object->is_approved == 'pending' ? 'warning' : 'danger' }}">Approval {{ ucwords($object->is_approved) }}</a>
		@endif
	@else
		<a class="btn btn-xs btn-{{ $object->is_assessed == 'pending' ? 'warning' : 'danger' }}">Assessment {{ ucwords($object->is_assessed) }}</a>
	@endif
@else

	<a class="btn btn-xs btn-{{ $object->is_reviewed == 'pending' ? 'warning' : 'danger' }}">Review {{ ucwords($object->is_reviewed) }}</a>
@endif
