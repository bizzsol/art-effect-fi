<input type="hidden" name="purchase_order_id" value="{{ $purchase_order_id }}">
@if(isset($items[0]))
<div class="row">
    <div class="col-md-12 pr-3">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th style="width: 5%">Choose</th>
					<th style="width: 30%">Product</th>
					<th style="width: 10%">Quantity</th>
					<th style="width: 10%">Currency</th>
					<th style="width: 15%">Item Total</th>
					<th style="width: 15%">Total Costs</th>
					<th style="width: 15%">Total Asset Value</th>
				</tr>
			</thead>
			<tbody>
				@foreach($items as $key => $item)
				@php
		            $purchaseOrderItem = $purchaseOrder->relPurchaseOrderItems->where('product_id', $item->relGoodsReceivedItems->product_id)->first();
		            $unit_price = 0;
		            if(isset($purchaseOrderItem->id)){
		                $unit_price = $purchaseOrderItem->unit_price;
		            }

		            $quotationsItem = $purchaseOrder->relQuotation->relQuotationItems->where('product_id', $item->relGoodsReceivedItems->product_id)->first();
		            $vat_percentage = 0;
		            if(isset($quotationsItem->id)){
		                $vat_percentage = $quotationsItem->vat_percentage;
		            }

					$total = ($unit_price*$item->received_qty);
					$total_price = ($total+($vat_percentage > 0 ? $total*($vat_percentage/100) : 0));
				@endphp
				<tr>
					<td class="text-center">
						<input type="checkbox" name="products[]" class="choose-products" value="{{ $item->id }}" checked onchange="calculateTotal()">
					</td>
					<td>
						<a class="text-primary" onclick="showCostDetails('{{ $item->id }}')">{{ $item->relGoodsReceivedItems->relProduct->name }} {{ getProductAttributesFaster($item->relGoodsReceivedItems->relProduct) }} {{ getProductAttributesFaster($item->relGoodsReceivedItems) }}</a>
					</td>
					<td>{{ $item->delivery_qty }} {{ $item->relGoodsReceivedItems->relProduct->productUnit->unit_name }}</td>
					<td class="text-center">{{ $purchaseOrder->relQuotation->exchangeRate->currency->code }}</td>
					<td class="text-right item-amount">{{ systemMoneyFormat($total_price) }}</td>
					<td class="text-right cost-amount">{{ systemMoneyFormat($item->assetCostingEntries->sum('cost')) }}</td>
					<td class="text-right asset-value">{{ systemMoneyFormat($total_price+$item->assetCostingEntries->sum('cost')) }}</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="text-right"><strong>Total:</strong></td>
					<td class="text-right"><strong id="total-item-amount" class="mask-money">0.00</strong></td>
					<td class="text-right"><strong id="total-cost-amount" class="mask-money">0.00</strong></td>
					<td class="text-right"><strong id="total-asset-value" class="mask-money">0.00</strong></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label for="final_asset_id"><strong>Choose Final Asset</strong></label>
			<div class="select-search-group input-group input-group-md mb-3 d-">
				<select name="final_asset_id" id="final_asset_id" class="form-control final_asset_id">
					@if($final_asset_id == 0)
						<option value="0">No Final Asset Needed</option>
					@endif

					@if(isset($finalAssets[0]))
					@foreach($finalAssets as $key => $finalAsset)
						<option value="{{ $finalAsset->id }}" {{ $final_asset_id == $finalAsset->id ? 'selected' : '' }}>{{ $finalAsset->name }} {{ getProductAttributesFaster($finalAsset) }}</option>
					@endforeach
					@endif
				</select>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label for="cost_centre_id"><strong>Choose Cost Centre</strong></label>
			<select name="cost_centre_id" id="cost_centre_id" class="form-control cost_centre_id select2">
				{!! $costCentres !!}
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label for="fixed_asset_location_id"><strong>Choose Location</strong></label>
			<select name="fixed_asset_location_id" id="fixed_asset_location_id" class="form-control locations select2">
				<option value="{{ null }}">Choose Location</option>
				@if($locations->count() > 0)
				@foreach($locations as $location)
					<option value="{{ $location->id }}">[{{ $location->code }}] {{ $location->name }}</option>
				@endforeach
				@endif
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label for="user_id"><strong>Assign User</strong></label>
			<select name="user_id" class="form-control users">
				<option value="{{ null }}">Choose User</option>
				@foreach($users as $key => $user)
				<option value="{{ $user->id }}">{{ $user->name }} ({{ $user->phone }})</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label for="date"><strong>Assigning From</strong></label>
			<input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-control">
		</div>
	</div>
	<div class="col-md-2 text-right pr-2 pt-4">
        <button type="submit" class="btn btn-success btn-block btn-md mr-2 mt-2 cwip-button"><i class="la la-save"></i>&nbsp;Convert to Asset</button>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12 pr-3">
    	<h3 class="text-danger text-center"><strong>No Asset found to shift to asset!</strong></h3>
    </div>
</div>
@endif

<script type="text/javascript">
	$('.cost_centre_id').select2();
	$('.users').select2();
	$('.locations').select2();
	$('.final_asset_id').select2();
	$('.cost_centre_id').select2();
	$('.mask-money').maskMoney();

	$(document).ready(function() {
		var form = $('#cwip-form');
		var button = $('.cwip-button');
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
					toastr.error(response.message);
				}

			    button.html(content).prop('disabled', false);
			})
			.fail(function(response) {
				$.each(response.responseJSON.errors, function(index, val) {
					toastr.error(val[0]);
				});

				button.html(content).prop('disabled', false);
			});
		});
	});
</script>