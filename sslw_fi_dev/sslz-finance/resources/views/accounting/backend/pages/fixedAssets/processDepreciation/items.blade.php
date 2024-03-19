<input type="hidden" name="from" value="{{ $from }}">
<input type="hidden" name="to" value="{{ $to }}">
@if(strtotime(date('Y-m-t')) >= strtotime($to))
	@if(isset($items[0]))
		<div class="row">
		    <div class="col-md-12 pr-3">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="width: 5%"></th>
							<th style="width: 20%">Asset</th>
							<th style="width: 10%">Batch</th>
							<th style="width: 10%">Asset Code</th>
							<th style="width: 10%">Currency</th>
							<th style="width: 10%">Rate</th>
							<th style="width: 10%">Amount</th>
							<th style="width: 20%">Remarks</th>
						</tr>
					</thead>
					<tbody>
						@php
							$total_amount = 0;
						@endphp
						@if(isset($finalAssets[0]))
						@foreach($finalAssets as $key => $finalAsset)
						@php
							$total_rate = 0;
							$amount = 0;
							if($items->where('asset_code', $finalAsset->asset_code)->count() > 0){
								foreach($items->where('asset_code', $finalAsset->asset_code) as $key => $item){
									$total_rate += $item->batch->depreciation_rate;
									$amount += calculateDepreciationAmount($item, $from, $to);
								}
							}

							$total_amount += $amount;
						@endphp
							<tr>
								<td class="text-center">
									<input type="checkbox" name="assets[]" value="{{ $finalAsset->asset_code }}" checked>
								</td>
								<td>{{ $finalAsset->finalAsset->name }} {{ getProductAttributesFaster($finalAsset->finalAsset) }}</td>
								<td>{{ $finalAsset->batch->batch }}</td>
								<td>{{ $finalAsset->asset_code }}</td>
								<td class="text-right">
									{{ $finalAsset->batch->goodsReceivedItemsStockIn->relPurchaseOrder->relQuotation->exchangeRate->currency->code }}
								</td>
								<td class="text-right">{{ systemMoneyFormat($finalAsset->batch->depreciation_rate) }}%</td>
								<td class="text-right">{{ systemMoneyFormat($amount) }}</td>
								<td>
									<input type="text" name="remarks[{{ $finalAsset->asset_code }}]" class="form-control">
								</td>
							</tr>
						@endforeach
						@endif
						
						{{-- @php
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
						@endforeach --}}
						
					</tbody>
					<tfoot>
						<tr>
							<td colspan="6" class="text-right"><strong>Total Depriciation Amount:</strong></td>
							<td class="text-right"><strong>{{ systemMoneyFormat($total_amount) }}</strong></td>
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
@else
	<div class="row">
	    <div class="col-md-12 pr-3">
	    	<h3 class="text-danger text-center"><strong>Advance depreciation is not valid!</strong></h3>
	    </div>
	</div>
@endif