@if($opening->is_approved == 'pending')
    @if(auth()->user()->hasPermissionTo('fiscal-year-opening-approval'))
        <a class="btn btn-xs mb-1 btn-success" onclick="Approve('{{ $opening->id }}')"><i class="las la-check-circle"></i>&nbsp;Approve</a>
        
        <a class="btn btn-xs mb-1 btn-danger" onclick="Deny('{{ $opening->id }}')"><i class="las la-times-circle"></i>&nbsp;Deny</a>
    @endif
@else
    {{ ucwords($opening->is_approved) }} at {{ date('M jS, y g:i a', strtotime($opening->opened_at)) }}

    @if($opening->status == 'opened')
        <a class="btn btn-xs mb-1 btn-success" href="{{ url('accounting/fiscal-year-openings/'.$opening->id.'/edit') }}"><i class="las la-check-circle"></i>&nbsp;Close</a>
    @endif
@endif