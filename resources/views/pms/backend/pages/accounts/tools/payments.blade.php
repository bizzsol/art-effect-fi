@if($payment->bill_type == 'asset-costing-entries')

	<div style="width: 525px;">
		@php
			$due = $paymentDetails['payment-bill-amount']-$paymentDetails['payment-payment-paid'];
			$max_pay_amount = $due;
			$vat = 0;
			$currency_gain_loss = ($paymentDetails['bill-amount']-$paymentDetails['payment-paid'])-($paymentDetails['payment-bill-amount']-$paymentDetails['payment-payment-paid']);
			
			$system_gain_loss = 0;
			if($systemExchangeRate){
				$system_gain_loss = ($paymentDetails['system-old-bill-amount']-$paymentDetails['system-old-payment-paid'])-($paymentDetails['system-bill-amount']-$paymentDetails['system-payment-paid']);
			}
		@endphp
		@if($due > 0)
			<div class="row">
				<div class="col-md-12 mb-2">
					<div class="form-group mb-0">
						<label for="amounts-{{ $payment->id }}">
							<strong>Payment</strong>
						</label>
						<input type="number" name="payments[{{ $payment->id }}]" value="{{ $due }}" class="form-control payments" min="0" max="{{ $max_pay_amount }}" data-due="{{ $max_pay_amount }}" step="any" onkeypress="return isNumberKey(event)" onchange="distributePayments($(this))" onkeyup="distributePayments($(this))">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group mb-0">
						<label for="vat-amounts-{{ $payment->id }}">
							<strong>VAT</strong>
						</label>
						<input type="number" name="vat_amount[{{ $payment->id }}]" value="{{ $vat }}" data-real="{{ $vat }}" class="form-control vat_amount" min="0" max="{{ $due-1 }}" step="any" onkeypress="return isNumberKey(event)" onchange="deductTaxVat($(this))" onkeyup="deductTaxVat($(this)">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group mb-0">
						<label for="tax-amounts-{{ $payment->id }}">
							<strong>TAX</strong>
						</label>
						<input type="number" name="tax_amount[{{ $payment->id }}]" value="0" data-real="0" min="0" max="{{ $due-1 }}" step="any" class="form-control tax_amount" onchange="deductTaxVat($(this))" onkeyup="deductTaxVat($(this))" onkeypress="return isNumberKey(event)">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group mb-0">
						<label for="pay-amounts-{{ $payment->id }}">
							<strong>Vendor Amount</strong>
						</label>
						<input type="number" step="any" min="0" max="{{ $due }}" class="form-control text-right pay-amounts rounded" name="pay_amount[{{ $payment->id }}]" onkeypress="return isNumberKey(event)" onchange="calculatePaymentAmount($(this), '{{ systemDoubleValue(($due), ) }}', 'payment')" value="{{ systemDoubleValue(($due), 4) }}" data-real="{{ systemDoubleValue(($due), 4) }}" readonly>
					</div>
				</div>
			</div>

			@if($currency_gain_loss != 0)
				<div class="row mt-2">
					@if($currency_gain_loss > 0)
						<div class="col-md-4">
							<div class="form-group mb-0">
								<label for="currency-gain-loss-{{ $payment->id }}" class="text-success">
									<strong>Currency Gain</strong>
								</label>
								<input type="number" step="any" min="0" max="{{ $currency_gain_loss }}" class="form-control text-right currency-gain-loss rounded text-success" name="currency_gain[{{ $payment->id }}]" value="{{ systemDoubleValue($currency_gain_loss) }}" data-real="{{ systemDoubleValue($currency_gain_loss) }}" readonly>
							</div>
						</div>
					@else
						<div class="col-md-4">
							<div class="form-group mb-0">
							<label for="currency-gain-loss-{{ $payment->id }}" class="text-danger">
								<strong>Currency Loss</strong>
							</label>
							<input type="number" step="any" min="0" max="{{ $currency_gain_loss*(-1) }}" class="form-control text-right currency-gain-loss rounded text-danger" name="currency_loss[{{ $payment->id }}]" value="{{ systemDoubleValue($currency_gain_loss*(-1)) }}" data-real="{{ systemDoubleValue($currency_gain_loss*(-1)) }}" readonly>
							</div>
						</div>
					@endif
				</div>
			@endif

			@if($system_gain_loss != 0)
				<div class="row mt-2">
					@if($system_gain_loss > 0)
						<div class="col-md-4">
							<div class="form-group mb-0">
								<label for="system-gain-loss-{{ $payment->id }}" class="text-success">
									<strong>Currency Gain ({{ $systemCurrency->code }})</strong>
								</label>
								<input type="number" step="any" min="0" max="{{ $system_gain_loss }}" class="form-control text-right system-gain-loss rounded text-success" name="system_gain[{{ $payment->id }}]" value="{{ systemDoubleValue($system_gain_loss) }}" data-real="{{ systemDoubleValue($system_gain_loss) }}" readonly>
							</div>
						</div>
					@else
						<div class="col-md-4">
							<div class="form-group mb-0">
							<label for="system-gain-loss-{{ $payment->id }}" class="text-danger">
								<strong>Currency Loss ({{ $systemCurrency->code }})</strong>
							</label>
							<input type="number" step="any" min="0" max="{{ $system_gain_loss*(-1) }}" class="form-control text-right system-gain-loss rounded text-danger" name="system_loss[{{ $payment->id }}]" value="{{ systemDoubleValue($system_gain_loss*(-1)) }}" data-real="{{ systemDoubleValue($system_gain_loss*(-1)) }}" readonly>
							</div>
						</div>
					@endif
				</div>
			@endif

		@endif
	</div>

@else

	<div style="width: 525px;">
		@php
			$due = (in_array($payment->bill_type, ['po-advance', 'grn']) ? ($paymentDetails['payment-bill-amount']-$paymentDetails['payment-payment-paid']) : $poDetails['payment-po-due']);
			$max_pay_amount = $due;

			$vat = systemDoubleValue($payment->relPurchaseOrder->vat, 4);
			$due = systemDoubleValue($due-$vat, 4);
			
			$currency_gain_loss = ((in_array($payment->bill_type, ['po-advance', 'grn']) ? $paymentDetails['bill-amount']-$paymentDetails['payment-paid'] : $poDetails['po-due']))-((in_array($payment->bill_type, ['po-advance', 'grn']) ? $paymentDetails['payment-bill-amount']-$paymentDetails['payment-payment-paid'] : $poDetails['payment-po-due']));

			$system_gain_loss = 0;
			if($systemExchangeRate){
				$system_gain_loss = ((in_array($payment->bill_type, ['po-advance', 'grn']) ? $paymentDetails['system-old-bill-amount']-$paymentDetails['system-old-payment-paid'] : $poDetails['system-old-po-due']))-((in_array($payment->bill_type, ['po-advance', 'grn']) ? $paymentDetails['system-bill-amount']-$paymentDetails['system-payment-paid'] : $poDetails['system-po-due']));
			}
		@endphp
		@if($paymentDetails['payment-bill-amount']-$paymentDetails['payment-payment-paid'] > 0 && $due > 0)
			<div class="row">
				<div class="col-md-12 mb-2">
					<div class="form-group mb-0">
						<label for="amounts-{{ $payment->id }}">
							<strong>Payment</strong>
						</label>
						<input type="number" name="payments[{{ $payment->id }}]" value="{{ $max_pay_amount }}" class="form-control payments" min="0" max="{{ $max_pay_amount }}" data-due="{{ $max_pay_amount }}" step="any" onkeypress="return isNumberKey(event)" onchange="distributePayments($(this))" onkeyup="distributePayments($(this))">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group mb-0">
						<label for="vat-amounts-{{ $payment->id }}">
							<strong>VAT</strong>
						</label>
						<input type="number" name="vat_amount[{{ $payment->id }}]" value="{{ $vat }}" data-real="{{ $vat }}" class="form-control vat_amount" min="0" max="{{ $due-1 }}" step="any" onkeypress="return isNumberKey(event)" onchange="deductTaxVat($(this))" onkeyup="deductTaxVat($(this))">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group mb-0">
						<label for="tax-amounts-{{ $payment->id }}">
							<strong>TAX</strong>
						</label>
						<input type="number" name="tax_amount[{{ $payment->id }}]" value="0" data-real="0" min="0" max="{{ $due-1 }}" step="any" class="form-control tax_amount" onchange="deductTaxVat($(this))" onkeyup="deductTaxVat($(this))" onkeypress="return isNumberKey(event)">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group mb-0">
						<label for="pay-amounts-{{ $payment->id }}">
							<strong>Vendor Amount</strong>
						</label>
						<input type="number" step="any" min="0" max="{{ $due }}" class="form-control text-right pay-amounts rounded" name="pay_amount[{{ $payment->id }}]" onkeypress="return isNumberKey(event)" onchange="calculatePaymentAmount($(this), '{{ systemDoubleValue(($due), ) }}', 'payment')" value="{{ systemDoubleValue(($due), 4) }}" data-real="{{ systemDoubleValue(($due), 4) }}" readonly>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				@if($currency_gain_loss != 0)
					@if($currency_gain_loss > 0)
						<div class="col-md-4">
							<div class="form-group mb-0">
								<label for="currency-gain-loss-{{ $payment->id }}" class="text-success">
									<strong>Currency Gain</strong>
								</label>
								<input type="number" step="any" min="0" max="{{ $currency_gain_loss }}" class="form-control text-right currency-gain-loss rounded text-success" name="currency_gain[{{ $payment->id }}]" value="{{ systemDoubleValue($currency_gain_loss) }}" data-real="{{ systemDoubleValue($currency_gain_loss) }}" readonly>
							</div>
						</div>
					@else
						<div class="col-md-4">
							<div class="form-group mb-0">
								<label for="currency-gain-loss-{{ $payment->id }}" class="text-danger">
									<strong>Currency Loss</strong>
								</label>
								<input type="number" step="any" min="0" max="{{ $currency_gain_loss*(-1) }}" class="form-control text-right currency-gain-loss rounded text-danger" name="currency_loss[{{ $payment->id }}]" value="{{ systemDoubleValue($currency_gain_loss*(-1)) }}" data-real="{{ systemDoubleValue($currency_gain_loss*(-1)) }}" readonly>
							</div>
						</div>
					@endif
				@endif

				@if($system_gain_loss != 0)
					@if($system_gain_loss > 0)
						<div class="col-md-4">
							<div class="form-group mb-0">
								<label for="system-gain-loss-{{ $payment->id }}" class="text-success">
									<strong>Currency Gain ({{ $systemCurrency->code }})</strong>
								</label>
								<input type="number" step="any" min="0" max="{{ $system_gain_loss }}" class="form-control text-right system-gain-loss rounded text-success" name="system_gain[{{ $payment->id }}]" value="{{ systemDoubleValue($system_gain_loss) }}" data-real="{{ systemDoubleValue($system_gain_loss) }}" readonly>
							</div>
						</div>
					@else
						<div class="col-md-4">
							<div class="form-group mb-0">
								<label for="system-gain-loss-{{ $payment->id }}" class="text-danger">
									<strong>Currency Loss ({{ $systemCurrency->code }})</strong>
								</label>
								<input type="number" step="any" min="0" max="{{ $system_gain_loss*(-1) }}" class="form-control text-right system-gain-loss rounded text-danger" name="system_loss[{{ $payment->id }}]" value="{{ systemDoubleValue($system_gain_loss*(-1)) }}" data-real="{{ systemDoubleValue($system_gain_loss*(-1)) }}" readonly>
							</div>
						</div>
					@endif
				@endif

				@if($due >= ($poDetails['payment-advance-paid']-$poDetails['payment-advance-cleared']))
					<div class="col-md-4">
						@if($payment->bill_type != 'po-advance' && $poDetails['payment-advance-paid'] > 0 && $poDetails['payment-advance-paid']-$poDetails['payment-advance-cleared'] > 0)
						<div class="form-group mb-0">
							<label for="advance-clearings-{{ $payment->id }}">
								<strong>Advance Clearing</strong>
							</label>
							<select name="advance_clearings[{{ $payment->id }}]" onchange="checkClearing($(this))" data-clearing-amount="{{ systemDoubleValue($poDetails['payment-advance-paid']-$poDetails['payment-advance-cleared']) }}" class="advance_clearings form-control">
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
						@endif
					</div>
					<div class="col-md-4">
						@if($payment->bill_type != 'po-advance' && $poDetails['payment-advance-paid'] > 0 && $poDetails['payment-advance-paid']-$poDetails['payment-advance-cleared'] > 0)
						<div class="form-group mb-0">
							<label for="clearing-amount-{{ $payment->id }}">
								<strong>Clearing Amount</strong>
							</label>
							<input type="number" name="clearing_amounts[{{ $payment->id }}]" value="0" min="0" step="any" class="form-control  clearing_amounts" onchange="calculatePaymentAmount($(this), '{{ systemDoubleValue($poDetails['payment-po-due'], 4) }}', 'clearing')" readonly>
						</div>
						@endif
					</div>
				@endif
			</div>
		
		@endif
	</div>

@endif