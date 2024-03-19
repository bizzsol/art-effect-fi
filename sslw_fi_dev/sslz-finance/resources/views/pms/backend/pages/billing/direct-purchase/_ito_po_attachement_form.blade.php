
<div class="col-md-12">
	<div class="form-group">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
				<thead>
					<tr class="text-center">
						<th>{{__('GRN Reference')}}</th>
						<th>{{__('GRN Date')}}</th>
						<th>{{__('Challan No')}}</th>
						<th>{{__('Po Qty')}}</th>
						<th>{{__('GRN Qty')}}</th>
						<th>{{__('Currency')}}</th>
						<th>{{__('GRN Amount')}}</th>
						<th class="text-center">{{__('Bill Amount')}}</th>
						<th>{{__('Receive Status')}}</th>
					</tr>
				</thead>
				<tbody>
					@php
						$billAmount = 0;
					@endphp
					@if($po->relGoodReceiveNote->count() > 0)
						@foreach($po->relGoodReceiveNote as $key=>$grn)
							@php 
							$billAmount +=  $grn->grn_amount;
							@endphp
							<tr>
								<td>{{$grn->reference_no}}</td>
								<td>
									{{date('d-M-Y',strtotime($grn->received_date))}}
								</td>
								<td>
									{{$grn->challan}}
								</td>
								<td class="text-center">{{$po->relPurchaseOrderItems->sum('qty')}}</td>
								<td class="text-center">{{$grn->relGoodsReceivedItems->sum('qty')}}</td>
								<td class="text-center">{{ isset($po->relQuotation->exchangeRate->currency->code)?$po->relQuotation->exchangeRate->currency->code:'' }}</td>
								<td class="text-right">
									@if(!empty($grn->grn_amount))
									{{systemMoneyFormat($grn->grn_amount)}}
									@endif
								</td>
								<td class="text-center">Not Updated Yet</td>
								<td class="capitalize text-center">{{$grn->received_status}}</td>
							</tr>
						
						@endforeach
					@endif
					<tr>
						<td colspan="6" class="text-right"><strong>Total GRN Amount:</strong></td>
						<td class="text-right">{{ systemMoneyFormat($billAmount) }}</td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>


		<div class="table-responsive">
			@if($po->relGoodReceiveNote->count() > 0)
			@foreach($po->relGoodReceiveNote as $key=>$goodReceivedNote)
			<h4 class="mb-2"><strong>#{{$goodReceivedNote->reference_no}}</strong></h4>
			<table class="table table-bordered table-hover">
				<thead>
					<tr class="text-center">
						<th>SL</th>
						<th>Category</th>
						<th>Product</th>
						<th>UOM</th>
						<th>Unit Price ({{ isset($po->relQuotation->exchangeRate->currency->code)?$po->relQuotation->exchangeRate->currency->code:'' }})</th>
						<th>Qty</th>
						<th>Item Total ({{ isset($po->relQuotation->exchangeRate->currency->code)?$po->relQuotation->exchangeRate->currency->code:'' }})</th>
						<th>Vat(%)</th>
						<th>Vat ({{ isset($po->relQuotation->exchangeRate->currency->code)?$po->relQuotation->exchangeRate->currency->code:'' }})</th>
						<th>Total ({{ isset($po->relQuotation->exchangeRate->currency->code)?$po->relQuotation->exchangeRate->currency->code:'' }})</th>
					</tr>
				</thead>
				<tbody>
					@foreach($goodReceivedNote->relGoodsReceivedItems as $key=>$grnItem)
					<tr>
						<td class="text-center">{{$key+1}}</td>
						<td>
							{{isset($grnItem->relProduct->category->name)?$grnItem->relProduct->category->name:''}}
						</td>
						<td>{{isset($grnItem->relProduct->name)?$grnItem->relProduct->name:''}} 	{{ getProductAttributes($grnItem->product_id) }}
						</td>
						<td>
							{{isset($grnItem->relProduct->productUnit->unit_name)?$grnItem->relProduct->productUnit->unit_name:''}}
						</td>
						<td class="text-center">
							<input type="text" step="any" class="form-control mask-money" min="0" name="unit_amount[{{$grnItem->goods_received_note_id}}][{{$grnItem->id}}]" value="{{$grnItem->unit_amount}}" onkeyup="calculateSubtotal({{$grnItem->id}},{{$grnItem->qty}})" onchange="calculateSubtotal({{$grnItem->id}},{{$grnItem->qty}})" id="unitAmount{{$grnItem->id}}">
						</td>
						<td class="text-center">{{$grnItem->qty}}</td>
						<td class="text-right"><span id="subTotalPrice_{{$grnItem->id}}" class="calculateSumOfSubtotal">{{systemDoubleValue($grnItem->sub_total,2)}}</span></td>
						<td class="text-center" id="item_vat_percentage-{{$grnItem->id}}">{{$grnItem->vat_percentage}}</td>
						<td class="text-right" id="item_vat-{{$grnItem->id}}">{{$grnItem->vat}}</td>
						<td class="text-right"><span id="totalPrice_{{$grnItem->id}}" class="calculateSumOfTotal">{{$grnItem->total_amount}}</span></td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@endforeach
			@endif
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="bill_number"><strong>Bill Number</strong></label>
				<input type="text" name="bill_number" value="{{ isset($po_bill->bill_number) ? $po_bill->bill_number : '' }}" class="form-control">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="bill_amount"><strong>Bill Amount ({{ isset($po->relQuotation->exchangeRate->currency->code)?$po->relQuotation->exchangeRate->currency->code:'' }})</strong></label>
				<input type="text" name="bill_amount" value="{{ isset($po_bill->bill_amount) ? $po_bill->bill_amount : $billAmount }}" class="form-control text-right" id="sumOfBillAmount" readonly>
			</div>
		</div>
	</div>

</div>
<div class="col-md-12">
	<div class="form-group">
		<div class="form-line">
			{!! Form::label('invoice_file', 'Invoice File (Supported format ::jpeg,jpg,png,gif,pdf & file size max :: 5MB)', array('class' => 'col-form-label')) !!}

			<div style="position:relative;">
				<a class='btn btn-primary btn-xs font-10' href='javascript:;'>
					Choose File...
					<input name="invoice_file" type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40" onchange='$("#upload-file-info").html($(this).val());'>
				</a>
				&nbsp;
				<span class='label label-info' id="upload-file-info"></span>
			</div>

		</div>
	</div> 
</div>

<div class="col-md-12">
	<div class="form-group">
		<div class="form-line">

			{!! Form::label('vat_challan_file', 'Vat Challan No (Supported format ::jpeg,jpg,png,gif,pdf & file size max :: 5MB)', array('class' => 'col-form-label')) !!}
			<div style="position:relative;">
				<a class='btn btn-success btn-xs font-10' href='javascript:;'>
					Choose File...
					<input name="vat_challan_file" type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40" onchange='$("#upload-vat-file-info").html($(this).val());'>
				</a>
				&nbsp;
				<span class='label label-success' id="upload-vat-file-info"></span>
			</div>
		</div>
	</div> 
</div>

<input type="hidden" readonly required name="purchase_order_id" id="purchase_order_id" value="{{ $po->id }}">