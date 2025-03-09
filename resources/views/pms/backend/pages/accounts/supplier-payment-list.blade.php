@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
	.list-unstyled .ratings {
		display: none;
	}
	.select2-container{
		min-width: 100px !important;
		max-width: 100% !important;
		width: 100% !important;
	}
</style>
@include('yajra.css')
@endsection
@section('main-content')
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
				</li>
				<li>
					<a href="#">PMS</a>
				</li>
				<li>
					<a href="#">Accounts</a>
				</li>
				<li class="active">{{__($title)}}</li>
			</ul>
		</div>

		<div class="page-content">
			<div class="">
				<div class="panel panel-info">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form action="{{ url('pms/accounts/supplier-payments') }}" method="get" accept-charset="utf-8">
									<div class="row">
										<div class="col-md-1">
											<p class="font-weight-bold"><label for="company_id"><strong>Company:</strong></label></p>
											<div class="input-group input-group-md mb-3 d-">
												<select name="company_id" id="company_id" class="form-control rounded" onchange="getSuppliers()">
													@if(isset($companies[0]))
													@foreach($companies as $company)
													<option value="{{ $company->id }}" {{ $company_id == $company->id ? 'selected' : '' }}>{{ $company->code }}</option>
													@endforeach
													@endif
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<p class="font-weight-bold"><label for="supplier_id"><strong>Supplier:</strong></label></p>
											<div class="input-group input-group-md mb-3 d-">
												<select name="supplier_id" id="supplier_id" class="form-control rounded">
													
												</select>
											</div>
										</div>

										<div class="col-md-5">
											<div class="row">
												<div class="col-md-4">
													<p class="font-weight-bold"><label for="bill_type"><strong>Bill Type:</strong></label></p>
													<div class="input-group input-group-md mb-3 d-">
														<select name="bill_type" id="bill_type" class="form-control rounded">
															<option value="{{ null }}">All Bills</option>
															<option value="po-advance" {{ $bill_type == 'po-advance' ? 'selected' : '' }}>Vendor Advance</option>
															<option value="po" {{ $bill_type == 'po' ? 'selected' : '' }}>Vendor Invoice</option>
															<option value="grn" {{ $bill_type == 'grn' ? 'selected' : '' }}>Challan Invoice</option>
															<option value="asset-costing-entries" {{ $bill_type == 'asset-costing-entries' ? 'selected' : '' }}>Asset Costing Entries</option>
														</select>
													</div>
												</div>

												<div class="col-md-4">
													<p class="font-weight-bold"><label for="currency_id"><strong>Transaction Currency:</strong></label></p>
													<div class="input-group input-group-md mb-3 d-">
														<select name="currency_id" id="currency_id" class="form-control rounded">
															@php
																$systemCurrency = systemCurrency();
															@endphp
															@if(isset($currencyTypes[0]))
					                                        @foreach($currencyTypes as $currencyType)
					                                        <optgroup label="{{ $currencyType->name }}">
					                                            @if($currencyType->currencies->count() > 0)
					                                            @foreach($currencyType->currencies as $curr)
					                                                <option value="{{ $curr->id }}" {{ request()->has('currency_id') ? (request()->get('currency_id') == $curr->id ? 'selected' : '') : ($systemCurrency->id == $curr->id ? 'selected' : '') }}>&nbsp;&nbsp;{{ $curr->name }} ({{ $curr->code }}&nbsp;|&nbsp;{{ $curr->symbol }})</option>
					                                            @endforeach
					                                            @endif
					                                        </optgroup>
					                                        @endforeach
					                                        @endif
														</select>
													</div>
												</div>

												<div class="col-md-4">
													<p class="font-weight-bold"><label for="payment_currency_id"><strong>Payment Currency:</strong></label></p>
													<div class="input-group input-group-md mb-3 d-">
														<select name="payment_currency_id" id="payment_currency_id" class="form-control rounded">
															@php
																$systemCurrency = systemCurrency();
															@endphp
															@if(isset($currencyTypes[0]))
					                                        @foreach($currencyTypes as $currencyType)
					                                        <optgroup label="{{ $currencyType->name }}">
					                                            @if($currencyType->currencies->count() > 0)
					                                            @foreach($currencyType->currencies as $curr)
					                                                <option value="{{ $curr->id }}" {{ request()->has('payment_currency_id') ? (request()->get('payment_currency_id') == $curr->id ? 'selected' : '') : ($systemCurrency->id == $curr->id ? 'selected' : '') }}>&nbsp;&nbsp;{{ $curr->name }} ({{ $curr->code }}&nbsp;|&nbsp;{{ $curr->symbol }})</option>
					                                            @endforeach
					                                            @endif
					                                        </optgroup>
					                                        @endforeach
					                                        @endif
														</select>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-2">
											<p class="font-weight-bold"><label for="datetime"><strong>Datetime:</strong></label></p>
											<input type="datetime-local" name="datetime" id="datetime" value="{{ request()->has('datetime') ? request()->get('datetime') : date('Y-m-d H:i:s') }}" class="form-control">

											<input type="hidden" name="hidden_datetime" id="hidden_datetime" value="{{ request()->has('datetime') ? request()->get('datetime') : date('Y-m-d H:i:s') }}">
										</div>

										<div class="col-md-1 col-sm-6 pr-3">
											<p class="font-weight-bold"><label for="searchRequisitonBtn"></label></p>
											<div class="input-group input-group-md">
												<button class="btn btn-success rounded mt-8 btn-md btn-block" type="submit"> <i class="las la-search"></i>Search</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<form action="{{ route('pms.accounts.supplier.payment.store') }}" method="post" accept-charset="utf-8" id="supplier-payment-form">
				@csrf
				<input type="hidden" name="currency_id" value="{{ request()->get('currency_id') }}">
				<input type="hidden" name="payment_currency_id" value="{{ request()->get('payment_currency_id') }}">
				<input type="hidden" name="datetime" value="{{ request()->has('datetime') ? request()->get('datetime') : date('Y-m-d H:i:s') }}">
				<input type="hidden" name="hidden_datetime" value="{{ request()->has('datetime') ? request()->get('datetime') : date('Y-m-d H:i:s') }}">
				<div class="panel panel-info mt-2 p-3">
	                @include('yajra.datatable')
	            </div>
	            <div class="panel panel-info mt-2 p-3">
	            	<div class="row">
						<div class="col-md-10">
							@include('payment', [
								'currency_id' => request()->get('payment_currency_id'),
								'company_id' => request()->get('company_id')
							])
						</div>
						<div class="col-md-2 pt-5 mt-2">
							<button class="btn btn-success rounded btn-block mt-5 mb-2" type="submit" id="supplier-payment-button"> <i class="las la-check"></i>&nbsp;Submit Payments</button>
						</div>
					</div>
	            </div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
<script type="text/javascript">
	$('body').addClass('sidebar-main');
	
	getSuppliers();
	function  getSuppliers(){
		$.ajax({
			url: "{{ url('pms/accounts/supplier-payments') }}?get-suppliers&company_id="+$('#company_id').val(),
			type: 'GET',
			dataType: 'json',
			data: {},
		})
		.done(function(response) {
			console.log(response);
			var suppliers = '<option value="{{ null }}">{{ __('All Suppliers') }}</option>';
			var supplier_id = "{{ $supplier_id }}";
			$.each(response, function(index, val) {
				suppliers += '<option value="'+val.id+'" '+(supplier_id == val.id ? 'selected' : '')+'>'+val.name+' ('+val.code+')</option>';
			});
			$('#supplier_id').html(suppliers).change();
		});
	}

	$(document).ready(function() {
		var form = $('#supplier-payment-form');
		var button = $('#supplier-payment-button');
		var content = button.html();

		form.submit(function(event) {
			event.preventDefault();
			button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);

			$.ajax({
				url: form.attr('action'),
				type: form.attr('method'),
				dataType: 'json',
				data: form.serializeArray(),
			})
			.done(function(response) {
				if(response.success){
					location.reload();
				}else{
					button.html(content).prop('disabled', false);
					toastr.error(response.message);
				}
			})
			.fail(function(response) {
				button.html(content).prop('disabled', false);
				$.each(response.responseJSON.errors, function(index, val) {
					toastr.error(val[0]);
				});
			});
		});
	});

	function isNumberKey(evt) {
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
			return false;
		}
		return true;
	}

	function checkClearing(element) {
		if(element.val() == 1){
			element.parent().parent().parent().find('.clearing_amounts').val(parseFloat(element.attr('data-clearing-amount')).toFixed(2)).attr('readonly', 'readonly').change();
		}else{
			element.parent().parent().parent().find('.clearing_amounts').val(0).attr('readonly', 'readonly').change();
		}
	}

	function deductTaxVat(element){
		var max_pay = element.parent().parent().parent().find('.payments').val();
		var vat = parseFloat(element.parent().parent().parent().find('.vat_amount').val() != "" ? element.parent().parent().parent().find('.vat_amount').val() : 0);
		var tax = parseFloat(element.parent().parent().parent().find('.tax_amount').val() != "" ? element.parent().parent().parent().find('.tax_amount').val() : 0);
		var new_payment = parseFloat(max_pay)-(tax+vat);

		if(new_payment <= 0){
			element.parent().parent().parent().find('.vat_amount').val(0);
			element.parent().parent().parent().find('.tax_amount').val(0);

			vat = 0;
			tax = 0;
			element.parent().parent().parent().find('.pay-amounts').val(parseFloat(parseFloat(max_pay)-(tax+vat)).toFixed(4));
		}else{
			element.parent().parent().parent().find('.pay-amounts').val(parseFloat(new_payment).toFixed(4));
		}
	}

	function calculatePaymentAmount(element, due, where) {
		// var advance_clearings = element.parent().parent().parent().find('.advance_clearings').val();
		
		// var clearing_amounts_input = element.parent().parent().parent().find('.clearing_amounts');
		// clearing_amount = (clearing_amounts_input.val() != '' ? parseFloat(clearing_amounts_input.val()).toFixed(4) : 0);

		// var paying_amount_input = element.parent().parent().parent().find('.pay-amounts');
		// paying_amount = (paying_amount_input.val() != '' ? parseFloat(paying_amount_input.val()).toFixed(4) : 0);

		// if(advance_clearings == 1){
		// 	if(where == 'select' || where == 'clearing'){
		// 		paying_amount_input.val(parseFloat(parseFloat(due)+parseFloat(clearing_amount)).toFixed(4));
		// 		paying_amount_input.attr('max', parseFloat(parseFloat(due)+parseFloat(clearing_amount)).toFixed(4));
		// 	}else if(where == 'payment'){
		// 		if(parseFloat(paying_amount) < parseFloat(clearing_amount)){
		// 			paying_amount_input.val(parseFloat(parseFloat(clearing_amount)).toFixed(4));
		// 			paying_amount_input.attr('max', parseFloat(parseFloat(clearing_amount)).toFixed(4));
		// 		}
		// 	}
		// }else{
		// 	if(parseFloat(paying_amount) > parseFloat(due)){
		// 		paying_amount_input.val(parseFloat(parseFloat(due)).toFixed(4));
		// 		paying_amount_input.attr('max', parseFloat(parseFloat(due)).toFixed(4));
		// 	}
		// }

		calculatePayAmount();
	}

	CheckAllCheckboxes();
	function CheckAllCheckboxes() {
		console.log($('.check-all-checkboxes').is(':checked'));
		if ($('.check-all-checkboxes').is(':checked')) {
			$('input.payment-checkboxes').prop('checked', true);
		} else {
			$('input.payment-checkboxes').prop('checked', false);
		}

		calculateTotal();
	}

	function calculateTotal(){
		var grn_amount = 0;
		$.each($('.grn-amounts'), function(index, val) {
			grn_amount += parseFloat($(this).val().replace(',',''));
		});

		var bill_amount = 0;
		$.each($('.bill-amounts'), function(index, val) {
			bill_amount += parseFloat($(this).val().replace(',',''));
		});

		var advanced_amount = 0;
		$.each($('.advanced-amounts'), function(index, val) {
			advanced_amount += parseFloat($(this).val().replace(',',''));
		});

		var paid_amount = 0;
		$.each($('.paid-amounts'), function(index, val) {
			paid_amount += parseFloat($(this).val().replace(',',''));
		});

		var due_amount = 0;
		$.each($('.due-amounts'), function(index, val) {
			due_amount += parseFloat($(this).val().replace(',',''));
		});

		var symbol = "{{ isset($currency->symbol) ? $currency->symbol : '' }}";
		$('#grn-amounts').html('<strong>'+(symbol)+'&nbsp;'+(grn_amount.toFixed(2))+'&nbsp;&nbsp;&nbsp;&nbsp;</strong>');
		$('#bill-amounts').html('<strong>'+(symbol)+'&nbsp;'+(bill_amount.toFixed(2))+'&nbsp;&nbsp;&nbsp;&nbsp;</strong>');
		$('#advanced-amounts').html('<strong>'+(symbol)+'&nbsp;'+(advanced_amount.toFixed(2))+'&nbsp;&nbsp;&nbsp;&nbsp;</strong>');
		$('#paid-amounts').html('<strong>'+(symbol)+'&nbsp;'+(paid_amount.toFixed(2))+'&nbsp;&nbsp;&nbsp;&nbsp;</strong>');
		$('#due-amounts').html('<strong>'+(symbol)+'&nbsp;'+(due_amount.toFixed(2))+'&nbsp;&nbsp;&nbsp;&nbsp;</strong>');

		calculatePayAmount();
	}

	$('.payment-checkboxes').on('change', function() {
		calculatePayAmount();
	});

	function calculatePayAmount() {
		var pay_amount = 0;
		$.each($('.payment-checkboxes'), function(index, val) {
			var amount = ($(this).parent().parent().find('.pay-amounts') ? $(this).parent().parent().find('.pay-amounts').val() : 0);
			if($(this).is(':checked') && amount != undefined){
				pay_amount += parseFloat(amount != "" ? amount : 0);
			}
		});

		var symbol = "{{ isset($currency->symbol) ? $currency->symbol : '' }}";
		$('#pay-amounts').html('<strong>'+(symbol)+'&nbsp;'+(pay_amount.toFixed(2))+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>');
	}

	function distributePayments(element) {
		var parent = element.parent().parent().parent();
		var value = parseFloat(element.val());
		var due = parseFloat(element.attr('data-due'));
		var min = parseFloat(element.attr('min'));
		var max = parseFloat(element.attr('max'));

		if(value < min){
			element.val(min);
			value = min;
		}

		if(value > max){
			element.val(max);
			value = max;
		}

		var percentage = value > 0 && due > 0 ? (value/due)*100 : 0;

		parent.find('.vat_amount').val(parseFloat(parseFloat(parent.find('.vat_amount').attr('data-real'))*(percentage > 0 ? percentage/100 : 0)).toFixed(4)).attr('max', value-1);

		parent.find('.tax_amount').val(parseFloat(parseFloat(parent.find('.tax_amount').attr('data-real'))*(percentage > 0 ? percentage/100 : 0)).toFixed(4)).attr('max', value-1);

		parent.find('.pay-amounts').val(parseFloat(parseFloat(parent.find('.pay-amounts').attr('data-real'))*(percentage > 0 ? percentage/100 : 0)).toFixed(4));

		parent.find('.currency-gain-loss').val(parseFloat(parseFloat(parent.find('.currency-gain-loss').attr('data-real'))*(percentage > 0 ? percentage/100 : 0)).toFixed(4));
		parent.find('.system-gain-loss').val(parseFloat(parseFloat(parent.find('.system-gain-loss').attr('data-real'))*(percentage > 0 ? percentage/100 : 0)).toFixed(4));

		parent.find('.advance_clearings').attr('data-clearing-amount', parseFloat(parseFloat(parent.find('.advance_clearings').attr('data-clearing-amount'))*(percentage > 0 ? percentage/100 : 0)).toFixed(4));
		checkClearing(parent.find('.advance_clearings'));
	}
</script>
@endsection