<div style="width: 325px;">
	<table class="table mb-0">
		<tbody>
			<tr>
				<td style="width: 35%">
					Unit: 
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 60%" class="text-right">
					<strong>{{isset($payment->relPurchaseOrder->Unit->hr_unit_short_name)? ucfirst($payment->relPurchaseOrder->Unit->hr_unit_short_name):''}}</strong>
				</td>
			</tr>
			<tr>
				<td style="width: 35%">
					Supplier: 
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 60%" class="text-right">
					@if($payment->bill_type == 'asset-costing-entries')
						<strong>{{isset($payment->relSupplier->name) ? ucfirst($payment->relSupplier->name).' ('.$payment->relSupplier->code.')' : ''}}</strong>
					@else
						<strong>{{isset($payment->relPurchaseOrder->relQuotation->relSuppliers->name) ? ucfirst($payment->relPurchaseOrder->relQuotation->relSuppliers->name).' ('.$payment->relPurchaseOrder->relQuotation->relSuppliers->code.')' : ''}}</strong>
					@endif
					
				</td>
			</tr>
			<tr>
				<td style="width: 35%">
					PO Ref No: 
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 60%" class="text-right">
					<strong>{{$payment->relPurchaseOrder->reference_no}}</strong>
				</td>
			</tr>
			@if($payment->bill_type != 'asset-costing-entries')
			<tr>
				<td style="width: 35%">
					Bill Amount: 
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 60%" class="text-right">
					<strong> {{ $payment->relPurchaseOrder->relQuotation->exchangeRate->currency->symbol }}&nbsp;{{systemMoneyFormat($payment->relPurchaseOrder->relPoAttachment->sum('bill_amount'))}}</strong>
				</td>
			</tr>
			<tr>
				<td style="width: 35%">
					Paid Amount: 
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 60%" class="text-right">
					<strong> {{ $payment->relPurchaseOrder->relQuotation->exchangeRate->currency->symbol }}&nbsp;{{systemMoneyFormat($totalPaid)}}</strong>
				</td>
			</tr>
			<tr>
				<td style="width: 35%">
					Due Amount: 
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 60%" class="text-right">
					<strong> {{ $payment->relPurchaseOrder->relQuotation->exchangeRate->currency->symbol }}&nbsp;{{systemMoneyFormat($payment->relPurchaseOrder->relPoAttachment->sum('bill_amount')-$totalPaid)}}</strong>
				</td>
			</tr>
			@endif
		</tbody>
	</table>
</div>