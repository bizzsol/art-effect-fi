@if($entry->approvals->count() > 0)
@endif
<tr>
    @foreach($entry->approvals as $key => $approval)
    <td style="width: {{ 100/$entry->approvals->count() }}%;border-top: none !important" class="text-center pl-4 pr-4">
        <strong>{{ ucwords($approval->status) }} {{ isset($approval->user->name) ? 'by '.$approval->user->name : '' }}</strong>
        <hr class="mt-0 pt-0 mb-0 pb-0" style="border-top: 2px solid black">
        {{ $approval->approvalLevel->name }}
    </td>
    @endforeach
</tr>