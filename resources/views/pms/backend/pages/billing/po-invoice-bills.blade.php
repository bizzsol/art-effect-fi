<hr class="mt-0 pt-0">
<table class="table table-bordered mb-3">
	<thead>
		<tr>
			<th>PO Date</th>
			<th>Supplier</th>
			<th>Reference</th>
			<th>Currency</th>
			<th>PO Amount</th>
			<th>GRN Amount</th>
			<th>Bill Amount</th>
			<th>Paid Amount</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="text-center">{{ isset($purchaseOrder->po_date) ? date('Y-m-d', strtotime($purchaseOrder->po_date)) : '' }}</td>
			<td class="text-center">{{ isset($purchaseOrder->relQuotation->relSuppliers->name)?$purchaseOrder->relQuotation->relSuppliers->name.' ('.$purchaseOrder->relQuotation->relSuppliers->code.')':'' }}</td>
			<td class="text-center">{{ $purchaseOrder->reference_no }}</td>
			<td class="text-center">{{ $purchaseOrder->relQuotation->exchangeRate->currency->name }}</td>
			<td class="text-right">{{ systemMoneyFormat($purchaseOrder->gross_price) }}</td>
			<td class="text-right">
                {{ isset($purchaseOrder->relGoodsReceivedItemStockIn[0]) ? systemMoneyFormat($purchaseOrder->relGoodsReceivedItemStockIn->where('is_grn_complete', 'yes')->sum('total_amount')) : '' }}
			</td>
			<td class="text-right">{{ $purchaseOrder->relPoAttachment->sum('bill_amount') > 0 ? systemMoneyFormat($purchaseOrder->relPoAttachment->sum('bill_amount')) : 'Not Updated Yet' }}</td>
			<td class="text-right">{{ systemMoneyFormat($purchaseOrder->relSupplierPayments->sum('pay_amount')) }}</td>
		</tr>
	</tbody>
</table>

<h5><strong>#Bills</strong></h5>
<table class="table table-bordered mb-3">
	<thead>
		<tr>
			<th class="text-center">#</th>
			<th class="text-center">Bill Type</th>
			<th class="text-center">Bill Number</th>
			<th class="text-center">Remarks</th>
			<th class="text-center">Submitted at</th>
			<th class="text-center">Attachments</th>
			<th class="text-right">Bill Amount</th>
			<th class="text-center">Status</th>
			<th class="text-center">Updated at</th>
		</tr>
	</thead>
	<tbody>
		@if($purchaseOrder->relPoAttachment->count() > 0)
		@foreach($purchaseOrder->relPoAttachment as $key => $bill)
		<tr>
			<td class="text-center">{{ $key+1 }}</td>
			<td class="text-center">{{ ucwords($bill->bill_type) }}</td>
			<td class="text-center">{{ $bill->bill_number }}</td>
			<td class="text-center">{{ $bill->remarks }}</td>
			<td class="text-center">
				{{ date('D, M jS, y g:i a', strtotime($bill->created_at)) }}
				<br>
				{{ $bill->created_at->diffForHumans() }}
			</td>
			<td class="text-center">
                @if(!empty($bill->invoice_file))
                    <a href="{{ asset($bill->invoice_file) }}" target="__blank" class="btn btn-success btn-xs">
                        <i class="las la-eye">&nbsp;Invoice</i>
                    </a>
                    &nbsp;&nbsp;
                @endif

                @if(isset($bill->vat_challan_file))
                    <a href="{{ asset($bill->vat_challan_file) }}" target="_blank" class="btn btn-success btn-xs">
                        <i class="las la-eye">&nbsp;VAT</i>
                    </a>
                @endif
			</td>
			<td class="text-right">{{ systemMoneyFormat($bill->bill_amount) }}</td>
			<td class="text-center">
				<a class="btn btn-xs btn-{{ $bill->status == 'approved' ? 'success' : ($bill->status == 'halt' ? 'danger' : 'warning') }} text-white">{{ ucwords($bill->status) }}</a>
			</td>
			<td class="text-center">
				{!! strtotime($bill->updated_at) > 0 ? (date('D, M jS, y g:i a', strtotime($bill->updated_at)).'<br>'.$bill->updated_at->diffForHumans()) : '' !!}
			</td>
		</tr>
		@endforeach
		@endif
	</tbody>
</table>