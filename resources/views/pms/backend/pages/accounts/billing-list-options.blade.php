<a href="{{ route('pms.accounts.po.invoice.list',$po->id) }}" class="btn mb-1 btn-xs btn-primary" data-toggle="tooltip" title="Click here to PO Challan List">
    Challan ({{ $po->relGoodReceiveNote->count() }})
</a>
<a target="__blank" href="{{ route('pms.billing-audit.po.invoice.print', $po->id) }}" class="btn mb-1 btn-warning btn-xs">
    <i class="las la-print"></i>
</a>
<a class="btn mb-1 btn-success btn-xs" onclick="viewBills('Bills of #{{ $po->reference_no  }}', '{{ route('pms.billing-audit.po.invoice.bills', $po->id) }}')">
    <i class="las la-file-invoice-dollar"></i>&nbsp;Bills
</a>