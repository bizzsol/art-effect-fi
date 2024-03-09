<div style="width: 225px;">
	<input type="hidden" class="form-control text-right rounded grn-amounts" onkeypress="return isNumberKey(event)"  value="{{systemMoneyFormat(isset($paymentDetails['grn-amount']) ? $paymentDetails['grn-amount'] : 0)}}" readonly>
	<input type="hidden" class="form-control text-right rounded bill-amounts" onkeypress="return isNumberKey(event)"  
	value="{{($payment->bill_type=='po-advance')?0.00:systemMoneyFormat($paymentDetails['bill-amount'])}}" readonly>
	<input type="hidden" class="form-control text-right rounded advanced-amounts" onkeypress="return isNumberKey(event)"  value="{{systemMoneyFormat($payment->pay_amount)}}" readonly>
	<input type="hidden" class="form-control text-right rounded paid-amounts" onkeypress="return isNumberKey(event)"  value="{{systemMoneyFormat($paymentDetails['paid-amount'])}}" readonly>
	<input type="hidden" class="form-control text-right rounded due-amounts" @if($paymentDetails['bill-amount']-$paymentDetails['paid-amount'] < 0) name="due_amount[{{ $payment->id }}]" @endif onkeypress="return isNumberKey(event)"  value="{{systemMoneyFormat(($paymentDetails['bill-amount']-$paymentDetails['paid-amount'] > 0 ? $paymentDetails['bill-amount']-$paymentDetails['paid-amount'] : 0))}}" readonly>
	<table class="table mb-0">
		<tbody>
			@if(in_array($payment->bill_type, ['po', 'grn']))
			<tr>
				<td style="width: 55%">
					GRN Amount
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 40%" class="text-right">
					<strong>{{ $payment->relPurchaseOrder->relQuotation->exchangeRate->currency->symbol }}&nbsp;{{systemMoneyFormat(isset($paymentDetails['grn-amount']) ? $paymentDetails['grn-amount'] : 0)}}</strong>
				</td>
			</tr>
			@endif

			@if(in_array($payment->bill_type, ['po', 'grn', 'asset-costing-entries']))
			<tr>
				<td style="width: 55%">
					Bill Amount
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 40%" class="text-right">
					<strong>{{ $payment->relPurchaseOrder->relQuotation->exchangeRate->currency->symbol }}&nbsp;{{($payment->bill_type=='po-advance')?0.00:systemMoneyFormat($paymentDetails['bill-amount'])}}</strong>
				</td>
			</tr>
			@endif

			@if(in_array($payment->bill_type, ['po-advance']))
			<tr>
				<td style="width: 55%">
					Advance Amount
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 40%" class="text-right">
					<strong>{{ $payment->relPurchaseOrder->relQuotation->exchangeRate->currency->symbol }}&nbsp;{{systemMoneyFormat($payment->pay_amount)}}</strong>
				</td>
			</tr>
			@endif

			<tr>
				<td style="width: 55%">
					Paid Amount
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 40%" class="text-right">
					<strong>{{ $payment->relPurchaseOrder->relQuotation->exchangeRate->currency->symbol }}&nbsp;{{systemMoneyFormat($paymentDetails['paid-amount'])}}</strong>
				</td>
			</tr>

			@if(!in_array($payment->bill_type, ['po-advance', 'asset-costing-entries', 'grn']))
			<tr>
				<td style="width: 55%">
					Advance Paid
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 40%" class="text-right">
					<strong>{{ $payment->relPurchaseOrder->relQuotation->exchangeRate->currency->symbol }}&nbsp;{{systemMoneyFormat($advancePaid)}}</strong>
				</td>
			</tr>
			@endif
		</tbody>
		<tfoot>
			<tr>
				<td style="width: 55%">
					Due Amount
				</td>
				<td class="text-center" style="width: 5%">
					:
				</td>
				<td style="width: 40%" class="text-right">
					<strong>{{ $payment->relPurchaseOrder->relQuotation->exchangeRate->currency->symbol }}&nbsp;{{systemMoneyFormat(($paymentDetails['bill-amount']-$paymentDetails['paid-amount'] > 0 ? $paymentDetails['bill-amount']-($paymentDetails['paid-amount']+(!in_array($payment->bill_type, ['po-advance', 'grn']) ? $advancePaid : 0)) : 0))}}</strong>
				</td>
			</tr>
		</tfoot>
	</table>
</div>