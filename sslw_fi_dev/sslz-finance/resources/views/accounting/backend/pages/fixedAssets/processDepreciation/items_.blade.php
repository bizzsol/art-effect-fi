<input type="hidden" name="from" value="{{ $from }}">
<input type="hidden" name="to" value="{{ $to }}">
@if(isset($items[0]))
<div class="row">
    <div class="col-md-12 pr-3">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th style="width: 5%;" class="text-center"  rowspan="2">Choose</th>
					<th colspan="3" class="text-center">Asset Information</th>
					<th colspan="3" class="text-center">Depreciation</th>
					<th style="width: 25%" class="text-center" rowspan="2">Remarks</th>
				</tr>
				<tr>
					<th style="width: 25%">Product</th>
					<th style="width: 10%">Batch ID</th>
					<th style="width: 7.5%">Asset Code</th>
					<th style="width: 7.5%">Rate</th>
					<th style="width: 5%">Currency</th>
					<th style="width: 12.5%">Amount</th>
				</tr>
			</thead>
			<tbody>
				@php
					$total = 0;
				@endphp
				@foreach($items as $key => $item)
				@php
					$amount = calculateDepreciationAmount($item, $from, $to);
				@endphp
				@if($amount > 0)
				@php
					$total += $amount;
				@endphp
				<tr>
					<td class="text-center">
						<input type="checkbox" name="items[]" value="{{ $item->id }}" checked>
					</td>
					<td>{{ isset($item->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->name) ? $item->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->name.' '.getProductAttributesFaster($item->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct) : '' }}</td>
					<td>{{ $item->batch->batch }}</td>
					<td>{{ $item->asset_code }}</td>
					<td class="text-right">{{ $item->batch->depreciation_rate }}%</td>
					<td class="text-center">{{ $item->batch->goodsReceivedItemsStockIn->relPurchaseOrder->relQuotation->exchangeRate->currency->code }}</td>
					<td class="text-right">
						<input type="hidden" name="amount[{{ $item->id }}]" value="{{ $amount }}">
						{{ $amount }}
					</td>
					<td>
						<input type="text" name="remarks[{{ $item->id }}]" class="form-control">
					</td>
				</tr>
				@endif
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6" class="text-right"><strong>Total Depriciation Amount:</strong></td>
					<td class="text-right"><strong>{{ $total }}</strong></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<div class="row">
    <div class="col-md-12 text-right pr-2">
        <button type="submit" class="btn btn-success btn-md mr-2"><i class="la la-save"></i>&nbsp;Process Depreciations</button>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12 pr-3">
    	<h3 class="text-danger text-center"><strong>No Asset found for process depreciation!</strong></h3>
    </div>
</div>
@endif