@if(auth()->user()->hasRole('Accounts'))
	@if($object->is_assessed == 'approved' && $object->is_approved != 'approved')
		<a class="btn btn-xs btn-success" title="Accept Final Approval" onclick="toggleAccountingApproval('{{ $table }}', '{{ $object->id }}', 'is_approved', 'Approve', 'approved')"><i class="fa fa-check"></i></a>
		@if($object->is_approved != 'denied')
		<a class="btn btn-xs btn-danger" title="Deny Final Approval" onclick="toggleAccountingApproval('{{ $table }}', '{{ $object->id }}', 'is_approved', 'Deny', 'Deny', 'denied')"><i class="fa fa-ban"></i></a>
		@endif
	@endif
@elseif(auth()->user()->hasRole('Accounts-Assessment'))
	@if($object->is_reviewed == 'approved' && $object->is_assessed != 'approved')
		<a class="btn btn-xs btn-success" title="Accept Assesment" onclick="toggleAccountingApproval('{{ $table }}', '{{ $object->id }}', 'is_assessed', 'Approve', 'approved')"><i class="fa fa-check"></i></a>
		@if($object->is_assessed != 'denied')
		<a class="btn btn-xs btn-danger" title="Deny Assesment" onclick="toggleAccountingApproval('{{ $table }}', '{{ $object->id }}', 'is_assessed', 'Deny', 'denied')"><i class="fa fa-ban"></i></a>
		@endif
	@endif
@elseif(auth()->user()->hasRole('Accounts-Reviewer'))
	@if($object->is_reviewed != 'approved')
		<a class="btn btn-xs btn-success" title="Accept Review" onclick="toggleAccountingApproval('{{ $table }}', '{{ $object->id }}', 'is_reviewed', 'Approve', 'approved')"><i class="fa fa-check"></i></a>
		@if($object->is_reviewed != 'denied')
		<a class="btn btn-xs btn-danger" title="Deny Review" onclick="toggleAccountingApproval('{{ $table }}', '{{ $object->id }}', 'is_reviewed', 'Deny', 'denied')"><i class="fa fa-ban"></i></a>
		@endif
	@endif
@endif
