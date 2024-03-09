<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Purchase Order</title>
		<style>
			@page {
				margin-top: 1.2in;
				margin-bottom: 1.6in;
				header: page-header;
				footer: page-footer;
			}
			
			html, body, p  {
				font-size:  14px !important;
				color: #000000;
			}
			table {
				width: 100% !important;
				border-spacing: 0px !important;
				margin-top: 10px !important;
				margin-bottom: 15px !important;
			}
			table caption {
				color: #000000 !important;
			}
			table td {
				padding-top: 1px !important;
				padding-bottom: 1px !important;
				padding-left: 7px !important;
				padding-right: 7px !important;
			}
			.table-non-bordered {
				padding-left: 0px !important;
			}
			.table-bordered {
				border-collapse: collapse;
			}
			.table-bordered td {
				border: 1px solid #000000;
				padding: 5px;
			}
			.table-bordered tr:first-child td {
				border-top: 0;
			}
			.table-bordered tr td:first-child {
				border-left: 0;
			}
			.table-bordered tr:last-child td {
				border-bottom: 0;
			}
			.table-bordered tr td:last-child {
				border-right: 0;
			}
			.mt-0 {
				margin-top: 0; 
			}
			.mb-0 {
				margin-bottom: 0; 
			}
			.image-space {
				white-space: wrap !important;
				padding-top: 45px !important;
			}
			.break-before {
				page-break-before: always;
				break-before: always;
			}
			.break-after {
				break-after: always;
			}
			.break-inside {
				page-break-inside: avoid;
				break-inside: avoid;
			}
			.break-inside-auto { 
				page-break-inside: auto;
				break-inside: auto;
			}
			.space-top {
				margin-top: 10px;
			}
			.space-bottom {
				margin-bottom: 10px;
			}

			.text-right{
				text-align:  right;
			}			
		</style>	
	</head>
	
	<body>
		<htmlpageheader name="page-header">
			<div class="row mb-3 print-header">
				<div class="col-md-6" style="width: 50%;float:left;padding-top: 50px">
					<h2><strong>Purchase Order</strong></h2>
				</div>
				<div class="col-md-6 text-right" style="width: 50%;float:left;padding-top: 50px">
					<img src="{{ str_replace('/assets','assets', $purchaseOrder->Unit->hr_unit_logo) }}" alt="logo" style="float: right !important;height: 15mm; width:  35mm; margin: 0;" />
				</div>
			</div>
		</htmlpageheader>

		<htmlpagefooter name="page-footer">

			<table class="table-bordered">
				<tbody>
					<tr>
						<td colspan="2" style="text-align: center;border: none !important">
							PO Issued by <strong>{{ auth()->user()->name  }}</strong>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="border: none !important">
							<small>(Note: This purchase order doesn’t require signature as it is automatically generated from MBM Group’s ERP)</small>
						</td>
					</tr>
					<tr>
						<td style="border-left: none !important;border-bottom: none !important">
							<small>
								Factory: M-19 & M-14, Section-14, Mirpur, Dhaka-1206
								<br>
								Phone: +8809678-411412, Mail: info@mbm.group
							</small>
						</td>
						<td style="padding-left: 25px;border-right: none !important;border-bottom: none !important">
							<small>
								Corporate Office: Plot: 1358, Road: 50 (Old), 9 (New)
								<br>
								Avenue: 11, DOHS, Mirpur-12, Dhaka-1216
								<br>
								Website: www.mbm.group
							</small>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: right;border: none !important">
							<small>Page {PAGENO} of {nb}</small>
						</td>
					</tr>
				</tbody>
			</table>
		</htmlpagefooter>
		
		<div class="container">
			<table class="table table-bordered">
				<tbody>
					<tr>
						<td style="width: 50% !important">
							<h5 class="mb-0"><strong>Vendor Name:&nbsp;{{isset($purchaseOrder->relQuotation->relSuppliers->name) ? $purchaseOrder->relQuotation->relSuppliers->name : ''}}</strong></h5>
						</td>
						<td style="width: 50% !important;text-align: right !important">
							{!! printBarcode($purchaseOrder->reference_no, 'float: right !important') !!}
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
							{{$purchaseOrder->Unit->hr_unit_name}}
							<div>
								{!! $purchaseOrder->Unit->hr_unit_address?$purchaseOrder->Unit->hr_unit_address:'' !!}
							</div>
						</td>
					</tr>
					<tr>
						<td style="width: 50% !important;font-size: 14px !important;font-weight: bold !important">
							Payment Mode:&nbsp;{{ isset($purchaseOrder->relQuotation->relSupplierPaymentTerm->relPaymentTerm->term) ? $purchaseOrder->relQuotation->relSupplierPaymentTerm->relPaymentTerm->term : '' }}
						</td>
						<td style="width: 50% !important;font-size: 14px !important;" class="text-right">
							Delivery Contact:&nbsp;{!! isset($purchaseOrder->Unit->hr_unit_telephone) ? $purchaseOrder->Unit->hr_unit_telephone : ''!!}
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-bordered">
				<thead>
					<tr>
						<td><strong>SL</strong></td>
						<td><strong>Product</strong></td>
						<td><strong>Unit</strong></td>
						<td style="widtd: 12% !important"><strong>Unit Price</strong></td>
						<td><strong>Qty</strong></td>
						<td><strong>Price</strong></td>
					</tr>
				</thead>
				<tbody>
					@foreach($purchaseOrder->relPurchaseOrderItems as $key=>$item)
					<tr>
						<td>{{$key+1}}</td>
						<td>{{$item->relProduct->name}} {{ getProductAttributes($item->product_id) }}</td>
						<td>{{$item->relProduct->productUnit->unit_name}}</td>
						<td class="text-right">{{$item->unit_price}}</td>
						<td class="text-right">{{number_format($item->qty,0)}}</td>
						<td class="text-right">{{number_format($item->sub_total_price,2)}}</td>
					</tr>
					@endforeach

					<tr>
						<td colspan="3" class="text-right">Total</td>
						<td colspan="" class="text-right">{{number_format($purchaseOrder->relPurchaseOrderItems->sum('unit_price'),2)}}</td>
						<td colspan="" class="text-right" class="text-right">{{$purchaseOrder->relPurchaseOrderItems->sum('qty')}}</td>
						<td colspan="" class="text-right">{{number_format($purchaseOrder->relPurchaseOrderItems->sum('sub_total_price'),2)}}</td>
					</tr>
					@if($purchaseOrder->discount > 0)
					<tr>
						<td colspan="5" class="text-right">(-) Discount</td>
						<td class="text-right">{{number_format($purchaseOrder->discount,2)}}</td>
					</tr>
					@endif
					<tr>
						<td colspan="5" class="text-right">(+) Vat </td>
						<td class="text-right">{{number_format($purchaseOrder->vat,2)}}</td>
					</tr>
					<tr>
						<td colspan="5" class="text-right"><strong>Total Amount</strong></td>
						<td class="text-right"><strong>{{number_format($purchaseOrder->gross_price,2)}}</strong></td>
					</tr>
				</tbody>
			</table>
			<strong>In word</strong>: <strong>{{ inWordBn($purchaseOrder->gross_price) }} only</strong>
			<br>
			<br>
			<strong>Remarks:</strong>
			<div>
				{!! $purchaseOrder->remarks?$purchaseOrder->remarks:'' !!}
			</div>
			<br>
			<br>
			<strong>Terms & Conditions:</strong>
			<div>
				{!! isset($purchaseOrder->relQuotation->relSuppliers->term_condition) ? $purchaseOrder->relQuotation->relSuppliers->term_condition : '' !!}
			</div>
		</div>
	</body>
</html>																																																								