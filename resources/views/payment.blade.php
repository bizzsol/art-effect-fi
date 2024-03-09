@php
	$bankAccounts = \App\Models\PmsModels\Accounts\BankAccount::with([
		'currency'
	])
	->when(isset($currency_id), function($query) use($currency_id){
		return $query->where('currency_id', $currency_id);
	})
	->orderBy('id','desc')
	->get();

	$suppliers = \App\Models\PmsModels\Suppliers::where('status', 'Active')->orderBy('name','asc')
	->get();
@endphp
<div class="row">
	<div class="col-md-3">
		<label for="payment_type"><strong>Payment Type</strong></label>
		<select name="payment_type" id="payment_type" class="form-control this-page-select2" onchange="checkPaymentType($(this))">
			@foreach(paymentTypes(isset($ap) ? $ap : false) as $key => $type)
			<option value="{{ $key }}" data-type-title="{{ $type['title'] }}" data-type-placeholder="{{ $type['placeholder'] }}">{{ $type['name'] }}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-5 bank-account" style="display:none">
		<label for="bank_account_id"><strong>Bank Account</strong></label>
		<br>
		<select name="bank_account_id" id="bank_account_id" class="form-control this-page-select2" onchange="getChequeNumber()">
			@isset($bankAccounts[0])
			@foreach($bankAccounts as $key => $bankAccount)
			<option value="{{ $bankAccount->id }}">{{ $bankAccount->name }} ({{$bankAccount->number}}) ({{ $bankAccount->currency ? $bankAccount->currency->code : '' }})</option>
			@endforeach
			@endif
		</select>
	</div>
	<div class="col-md-5 choose-ledger" style="display:none">
		<label for="chart_of_account_id"><strong>Choose Ledger</strong></label>
		<select name="chart_of_account_id" id="chart_of_account_id" class="form-control this-page-select2">
			{!! chartOfAccountsOptions([], 0, 0, getAllGroupAndLedgers()) !!}
		</select>
	</div>
	<div class="col-md-5 supplier-payable" style="display:none">
		<label for="payment_supplier_id"><strong>Supplier</strong></label>
		<br>
		<select name="payment_supplier_id" id="payment_supplier_id" class="form-control this-page-select2">
			@isset($suppliers[0])
			@foreach($suppliers as $key => $supplier)
			<option value="{{ $supplier->id }}">{{ $supplier->name }} ({{$supplier->code}})</option>
			@endforeach
			@endif
		</select>
	</div>
	<div class="col-md-4">
		<label for="payment_details"><strong id="payment-title">Payment Details</strong></label>
		<input type="text" name="payment_details" id="payment_details" class="form-control" placeholder="{{ paymentTypes()['cash']['placeholder'] }}">
	</div>
</div>
<div class="row mt-1">
	<div class="col-md-12">
		<label for="narration"><strong>Narration</strong></label>
		<input type="text" name="narration" id="narration" class="form-control" placeholder="Write your Narration">
	</div>
</div>

<script type="text/javascript">
	function checkPaymentType(element) {
		$('#payment_details').removeAttr('readonly').val('');

		if(element.val() == 'cash'){
			$('.bank-account').hide();
			$('.supplier-payable').hide();
			$('.choose-ledger').hide();
		}else if (element.val() == 'cheque') {
			$('.choose-ledger').hide();
			$('.supplier-payable').hide();
			$('.bank-account').show();

			getChequeNumber();
		}else if (element.val() == 'fund-transfer') {
			$('.choose-ledger').hide();
			$('.supplier-payable').hide();
			$('.bank-account').show();
		}else if(element.val() == 'others-gl'){
			$('.bank-account').hide();
			$('.supplier-payable').hide();
			$('.choose-ledger').show();
		}else if(element.val() == 'supplier-payable'){
			$('.bank-account').hide();
			$('.choose-ledger').hide();
			$('.supplier-payable').show();
		}

		$('#payment-title').html(element.find(':selected').attr('data-type-title'));
		$('#payment_details').attr('placeholder', element.find(':selected').attr('data-type-placeholder'));
	}

	function getChequeNumber() {
		$.ajax({
			url: "{{ url('accounting/get-cheque-number') }}/"+$('#bank_account_id').val(),
			type: 'GET',
			data: {},
		})
		.done(function(response) {
			$('#payment_details').val(response).prop('readonly', 'readonly');
		});
	}
</script>

@if(isset($select2) && $select2)
<script type="text/javascript">
	$('.this-page-select2').select2();
</script>
@endif