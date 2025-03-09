@php
$corporateAddress = \App\Models\PmsModels\SupplierAddress::where(['supplier_id' => isset($purchaseOrder->relQuotation->relSuppliers->id) ? $purchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'corporate'])->first();
$contactPersonSales = \App\Models\PmsModels\SupplierContactPerson::where(['supplier_id' => isset($purchaseOrder->relQuotation->relSuppliers->id) ? $purchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'sales'])->first();
@endphp
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Purchase Order</title>
	<link rel="shortcut icon" href="{{ asset('images/mbm.ico')}} " />
	<link href="{{ asset('css/app.css') }}" rel="stylesheet" media='screen,print'>
	<link rel="stylesheet" href="{{ asset('assets/css/all.css') }}" media='screen,print'>
	@stack('css')
	<link rel="stylesheet" href="{{ asset('assets/css/custom.css?v=1.3') }}" media='screen,print'>
	<!-- Responsive CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" media='screen,print'>
	<style type="text/css">
		@media print {
			.print_the_pages {
				display: none;
			}

			/* .full-height{
				height:100% !important;
			}

			.print-header {
				width: 100%;
			    position: fixed;
			    top: 0;
			    left: 0;
			}

			.print-header-left{
				padding-left: 100px;
			}

			.print-header-right{
				padding-right: 70px;
			}

			.print-body{
				padding-top:  75px !important;
			}

			.print-footer {
				position: absolute;
  				bottom: 0;
			}*/
		}

	    .list-unstyled .ratings {
	        display: none;
	    }
	</style>
</head>
<body>
	<div id="app">

		<div id="content-page" class="container">
			<main class="" style="padding-bottom: 0;">
				<div id="main-body" class="">
					<div class="main-content">
						<div class="main-content-inner">
							@if(isset($purchaseOrder))
							<div class="row">
							<div class="col-md-12 p-30 full-height" id="print_invoice">

								<div class="panel panel-body">
									<div class="row mb-3 print-header">
										<div class="col-md-6 pt-5 print-header-left">
											<h2><strong>Purchase Order</strong></h2>
										</div>
										<div class="col-md-6  print-header-right text-right">
											@if(!empty($purchaseOrder->Unit->hr_unit_logo))
											<img src="{{ asset($purchaseOrder->Unit->hr_unit_logo) }}" alt="" style="height: 100px;width: 250px;">
											@else
											<img src="{{ asset($purchaseOrder->Unit->hr_unit_logo) }}" alt="" style="height: 100px;width: 250px;">
											@endif
										</div>
									</div>

									<div class="print-body">
										<div class="table-responsive">
											<table class="table table-bordered">
												<tbody>
													<tr>
														<td style="width: 50% !important">
															<h5 class="mb-0"><strong>Vendor Name:&nbsp;{{isset($purchaseOrder->relQuotation->relSuppliers->name) ? $purchaseOrder->relQuotation->relSuppliers->name : ''}}</strong></h5>
														</td>
														<td style="width: 50% !important;text-align: right !important">
															{!! printBarcode($purchaseOrder->reference_no, 'float: right') !!}
														</td>
													</tr>
													<tr>
														<td style="width: 50% !important;font-size: 14px !important;">
															Address:&nbsp;
															@if(isset($corporateAddress->id))
															{{ $corporateAddress->road.' '.$corporateAddress->village.', '.$corporateAddress->city.'-'.$corporateAddress->zip.', '.$corporateAddress->country }}
															<br>
															{{ $corporateAddress->adddress }}
															@endif
														</td>
														<td style="width: 50% !important;font-size: 14px !important;" class="text-right">
															PO Ref. No:&nbsp;{{ $purchaseOrder->reference_no }}
															<br>
															PO Date:&nbsp;{{ date('jS F Y', strtotime($purchaseOrder->po_date)) }}
															<br>
															Quotation Ref. No:&nbsp;{{ isset($purchaseOrder->relQuotation->id) ? $purchaseOrder->relQuotation->reference_no : '' }}
														</td>
													</tr>
													<tr>
														<td style="width: 50% !important;font-size: 14px !important;">
															Attention:&nbsp;
															@if(isset($contactPersonSales->id))
															{{ $contactPersonSales->name.', '.$contactPersonSales->designation }},
															<br>
															Mobile:&nbsp;{{ $contactPersonSales->mobile }},
															<br> 
															Mail:&nbsp;{{ $contactPersonSales->email }}
															@endif
														</td>
														<td style="width: 50% !important;font-size: 14px !important;" class="text-right">
															Delivery Location:&nbsp;
															{{isset($purchaseOrder->Unit->hr_unit_name)?$purchaseOrder->Unit->hr_unit_name:''}}
															<div>
																{!! isset($purchaseOrder->Unit->hr_unit_address)?$purchaseOrder->Unit->hr_unit_address:'' !!}
															</div>
														</td>
													</tr>
													<tr>
														<td style="width: 50% !important;font-size: 14px !important;font-weight: bold !important">
															Payment Mode:&nbsp;{{ isset($purchaseOrder->relQuotation->relSupplierPaymentTerm->relPaymentTerm->term) ? $purchaseOrder->relQuotation->relSupplierPaymentTerm->relPaymentTerm->term : '' }}
														</td>
														<td style="width: 50% !important;font-size: 14px !important;" class="text-right">
															Delivery Contact:&nbsp;{!! isset($deliveryContact->id) ? $deliveryContact->name.'&nbsp;&nbsp;|&nbsp;&nbsp;'.isset($deliveryContact->employee) ? isset($deliveryContact->employee->designation) ? $deliveryContact->employee->designation->hr_designation_name : '' : ''.'&nbsp;&nbsp;|&nbsp;&nbsp;'.$deliveryContact->phone : '' !!}
														</td>
													</tr>
												</tbody>
											</table>
										</div>

										<div class="table-responsive">
											<table class="table table-bordered">
												<thead>
													<tr class="text-center">
														<th>SL</th>
														<th>Product</th>
														<th>UOM</th>
														<th style="width: 12% !important">Unit Price</th>
														<th>Qty</th>
														<th>Price</th>
													</tr>
												</thead>
												<tbody>
													@foreach($purchaseOrder->relPurchaseOrderItems as $key=>$item)
													<tr>
														<td class="text-center">{{$key+1}}</td>
														<td>{{$item->relProduct->name}} {{ getProductAttributes($item->product_id) }}</td>
														<td>{{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}</td>
														<td class="text-right">{{systemMoneyFormat($item->unit_price)}}</td>
														<td class="text-center">{{systemMoneyFormat($item->qty,0)}}</td>
														<td class="text-right">{{systemMoneyFormat($item->sub_total_price,4)}}</td>
													</tr>
													@endforeach

													<tr>
														<td colspan="3" class="text-right">Total</td>
														<td colspan="" class="text-right">{{systemMoneyFormat($purchaseOrder->relPurchaseOrderItems->sum('unit_price'))}}</td>
														<td class="text-center">{{$purchaseOrder->relPurchaseOrderItems->sum('qty')}}</td>
														<td  class="text-right">{{systemMoneyFormat($purchaseOrder->relPurchaseOrderItems->sum('sub_total_price'))}}</td>
													</tr>

													{{-- <tr>
														<td colspan="5" class="text-right">(-) Discount</td>
														<td class="text-right">{{systemMoneyFormat($purchaseOrder->discount,4)}}</td>
													</tr> --}}
													<tr>
														<td colspan="5" class="text-right">(+) Vat </td>
														<td class="text-right">{{systemMoneyFormat($purchaseOrder->vat)}}</td>
													</tr>
													<tr>
														<td colspan="5" class="text-right"><strong>Total Amount</strong></td>
														<td class="text-right"><strong>{{systemMoneyFormat($purchaseOrder->gross_price)}}</strong></td>
													</tr>
												</tbody>
											</table>
											In word: <strong>{{ inWordBn($purchaseOrder->gross_price, $purchaseOrder->relQuotation->exchangeRate->currency->name, $purchaseOrder->relQuotation->exchangeRate->currency->hundreds) }} only</strong>
										</div>
										
										<div class="form-group">
											<label for="terms-condition"><strong>Terms & Conditions</strong>:</label>
											<div class="pl-4">{!! isset($purchaseOrder->relQuotation->relSuppliers->term_condition) ? $purchaseOrder->relQuotation->relSuppliers->term_condition : '' !!}</div>
										</div>
									</div>

									<div class="print-footer" id="footer">
										<div class="form-group text-center pt-2 pb-2">
											PO Issued by <strong>{{ auth()->user()->name  }}</strong>
										</div>

										<div class="form-group">
											<small>(Note: This purchase order doesn’t require signature as it is automatically generated from MBM Group’s ERP)</small>
										</div>

										<div class="form-group row pt-2" style="border-top: 3px solid black">
											<div class="col-md-6" style="border-right: 1px solid black">
												Factory: M-19 & M-14, Section-14, Mirpur, Dhaka-1206
												<br>
												Phone: +8809678-411412, Mail: info@mbm.group
											</div>
											<div class="col-md-6 pl-5" style="border-left: 2px solid black">
												Corporate Office: Plot: 1358, Road: 50 (Old), 9 (New)
												<br>
												Avenue: 11, DOHS, Mirpur-12, Dhaka-1216
												<br>
												Website: www.mbm.group
											</div>
										</div>
									</div>

								</div>
							</div>
							<div class="col-md-12 mb-3">
								<center>
									<a href="#" class="btn btn-info btn-sm print_the_pages text-center">
										<i class="las la-print" aria-hidden="true"></i>
										<span>Print Invoice</span></a>
									</center>
								</div>
							</div>
							@endif

						</div>
					</div>
				</div>
			</main>
		</div>
	</div>
</body>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{asset('assets/js/all.js')}}"></script>
<script>
	window.print();
	
	var count = 0;
	var refreshIntervalId =setInterval(function(){
		count++;
		jQuery(document).ready(function() {
			clearInterval(refreshIntervalId);
			jQuery("#load").fadeOut();
			jQuery("#loading").fadeOut("");

		});
		if( count == 5){
			clearInterval(refreshIntervalId);
			jQuery("#load").fadeOut();
			jQuery("#loading").fadeOut("");
		}
	}, 300);

	var loaderContent = '<div class="animationLoading"><div id="container-loader"><div id="one"></div><div id="two"></div><div id="three"></div></div><div id="four"></div><div id="five"></div><div id="six"></div></div>';
	let afterLoader = '<div class="loading-select left"><img src="{{ asset('images/loader.gif')}}" /></div>';

	const PrintPage=()=>{
		$('.print_the_pages').on('click', function () {
			var restorepage = $('body').html();
			var printcontent = $('#print_invoice').clone();
			$('body').empty().html(printcontent);
			window.print();
			$('body').html(restorepage)

			return false;
		}); 
	};
	PrintPage();
</script>
</html>