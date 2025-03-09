<input type="hidden" name="purchase_order_id" value="{{ $purchase_order_id }}">
@if(isset($items[0]))
<div class="row">
    <div class="col-md-12 pr-3">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th style="width: 5%">Choose</th>
					<th style="width: 10%">Category</th>
					<th style="width: 20%">Item</th>
					<th style="width: 10%">Quantity</th>
					<th style="width: 10%">Costs ({{ $purchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
					<th style="width: 15%">Datetime</th>
					<th style="width: 15%">Details</th>
					<th style="width: 15%">Attachments</th>
				</tr>
			</thead>
			<tbody>
				@foreach($items as $key => $item)
				@php
					$show = true;
					
					if($item->relGoodsReceivedItems->relProduct->is_cwip == 0){
						$delivered = \App\Models\PmsModels\RequisitionDeliveryItem::where('product_id', $item->relGoodsReceivedItems->product_id)
						->whereHas('relRequisitionDelivery', function($query) use($item){
							return $query->whereIn('requisition_id', $item->relPurchaseOrder->purchaseOrderRequisitions->pluck('requisition_id')->toArray());
						})
						->sum('delivery_qty');
						
						if($delivered >= $item->received_qty){
							$show = false;
						}
					}
				@endphp
				@if($show)
				<tr>
					<td class="text-center">
						<input type="checkbox" name="products[]" class="choose-products" value="{{ $item->id }}" checked>
					</td>
					<td>
						{{ $item->relGoodsReceivedItems->relProduct->category->name }}
						{{ $item->relGoodsReceivedItems->relProduct->is_service == 1 ? ' | Service' : '' }}
					</td>
					<td>
						<a class="text-primary" onclick="showCostDetails('{{ $item->id }}')">{{ $item->relGoodsReceivedItems->relProduct->name }} {{ getProductAttributesFaster($item->relGoodsReceivedItems->relProduct) }} {{ getProductAttributesFaster($item->relGoodsReceivedItems) }}</a>
					</td>
					<td class="text-center">{{ $item->received_qty }} {{ $item->relGoodsReceivedItems->relProduct->productUnit->unit_name }}</td>
					<td>
						<input type="number" name="costs[{{ $item->id }}]" step="0.01" value="0" class="form-control text-right cost-amounts" onchange="calculateTotal()" onkeyup="calculateTotal()" min="0">
					</td>
					<td>
						<input type="datetime-local" name="datetime[{{ $item->id }}]" value="{{ date('Y-m-d H:i:s') }}" class="form-control">
					</td>
					<td>
						<input type="text" name="details[{{ $item->id }}]" class="form-control">
					</td>
					<td>
						<input type="file" name="attachments[{{ $item->id }}]" class="form-control">
					</td>
				</tr>
				@endif
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="text-right">
						<strong>Total Costs Amount ({{ $purchaseOrder->relQuotation->exchangeRate->currency->code }}):</strong>
					</td>
					<td class="text-right">
						<strong id="total-cost-amount">0.00</strong>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					<td class="text-right"><strong></strong></td>
					<td class="text-right"><strong></strong></td>
					<td class="text-right"><strong></strong></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<div class="row">
    <div class="col-md-8">
    	@include('payment', [
			'currency_id' => $purchaseOrder->relQuotation->exchangeRate->currency_id,
			'ap' => true,
			'select2' => true,
			'company_id' => false
		])
    </div>
    <div class="col-md-4">
    	<div class="row">
    		<div class="col-md-12">
    			<label for="cost_centre_id"><strong>Cost Centre</strong></label>
	    		<select name="cost_centre_id" class="form-control cost_centre_id select2">
					{!! $costCentres !!}
				</select>
	    	</div>
	    	<div class="col-md-12 pt-4">
		        <button type="submit" class="btn mt-2 btn-block btn-success btn-md mr-2 asset-costing-button"><i class="la la-save"></i>&nbsp;Submit Costings</button>
		    </div>
    	</div>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12 pr-3">
    	<h3 class="text-danger text-center"><strong>No Asset found for costings!</strong></h3>
    </div>
</div>
@endif

<script type="text/javascript">
	$('.cost_centre_id').select2();
</script>