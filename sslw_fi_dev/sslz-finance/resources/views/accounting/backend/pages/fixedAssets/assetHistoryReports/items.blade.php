<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th style="width: 15%">Asset</th>
			<th style="width: 15%">Sub-Assets</th>
			<th style="width: 7.5%">Batch</th>
			<th style="width: 7.5%">Asset</th>
			<th style="width: 10%">Location</th>
			<th style="width: 15%">Assigned To</th>
			<th style="width: 10%">Purchase Price</th>
			<th style="width: 10%">Depreciated</th>
			<th style="width: 10%">Book Value</th>
		</tr>
	</thead>
	<tbody>

		@php
			$total_price = 0;
			$total_depreciated = 0;
			$total_current_value = 0;
		@endphp
		@if(isset($products[0]))
		@foreach($products as $key => $product)
		@php
			$assets = $items->filter(function ($item, $key) use($product) {
			    return $item->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->product_id == $product->id;
			});
			$assets->all();
		@endphp

		@if($assets->count() > 0)
		@foreach($assets as $key => $asset)
			@if($key == 0)
			@php
				$subAssets = $items->filter(function ($item, $key) use($product) {
				    return $item->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->parent_id == $product->id;
				});
				$subAssets->all();

	            $assetValue = assetValue($asset);
	            $depreciated = $asset->depreciations->sum('amount');
			    $current_value = $assetValue-$depreciated;
			    $gain_loss = $assetValue-($depreciated+$asset->disposal_amount);

			    $total_price += $assetValue;
				$total_depreciated += $depreciated;
				$total_current_value += $current_value;

				$sub_asset_value = 0;
				if($subAssets->count() > 0){
					foreach($subAssets as $key => $subAsset){
					    $sub_asset_value += assetValue($subAsset)-$subAsset->depreciations->sum('amount');
					}
				}
			@endphp
			<tr>
				<td rowspan="{{ $subAssets->count()+1 }}">
					{{ isset($asset->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->name) ? $asset->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->name.' '.getProductAttributesFaster($asset->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct) : '' }}
				</td>
				<td>
					{{ isset($asset->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->name) ? $asset->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->name.' '.getProductAttributesFaster($asset->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct) : '' }}
				</td>
				<td>{{ $asset->batch->batch }}</td>
				<td>{{ $asset->asset_code }}</td>
				<td>
					{{ isset($asset->currentUser->fixedAssetLocation->name) ? $asset->currentUser->fixedAssetLocation->name : '' }}
				</td>
				<td>
					{{ isset($asset->currentUser->user->name) ? $asset->currentUser->user->name.' ('.$asset->currentUser->user->phone.')' : '' }}
				</td>
				<td class="text-right">
					{{ systemMoneyFormat($assetValue) }}
				</td>
				<td class="text-right">
					{{ systemMoneyFormat($depreciated) }}
				</td>
				<td class="text-right" rowspan="{{ $subAssets->count()+1 }}">
					{{ systemMoneyFormat($current_value+$sub_asset_value) }}
				</td>
			</tr>

				@if($subAssets->count() > 0)
				@foreach($subAssets as $key => $subAsset)
				@php
					$assetValue = assetValue($subAsset);

				    $depreciated = $subAsset->depreciations->sum('amount');
				    $current_value = $assetValue-$depreciated;
				    $gain_loss = $assetValue-($depreciated+$subAsset->disposal_amount);

				    $total_price += $assetValue;
					$total_depreciated += $depreciated;
					$total_current_value += $current_value;
				@endphp
					<tr>
						<td>
							{{ isset($subAsset->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->name) ? $subAsset->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct->name.' '.getProductAttributesFaster($subAsset->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relProduct) : '' }}
						</td>
						<td>{{ $subAsset->batch->batch }}</td>
						<td>{{ $subAsset->asset_code }}</td>
						<td>
							{{ isset($asset->currentUser->fixedAssetLocation->name) ? $asset->currentUser->fixedAssetLocation->name : '' }}
						</td>
						<td>
							{{ isset($subAsset->currentUser->user->name) ? $subAsset->currentUser->user->name.' ('.$subAsset->currentUser->user->phone.')' : '' }}
						</td>
						<td class="text-right">
							{{ systemMoneyFormat($assetValue) }}
						</td>
						<td class="text-right">
							{{ systemMoneyFormat($depreciated) }}
						</td>
					</tr>
				@endforeach
				@endif
			@endif
			@endforeach
			@endif

			<tr>
				<td class="text-right" colspan="6"><strong>Total:</strong></td>
				<td class="text-right"><strong>{{ systemMoneyFormat($total_price) }}</strong></td>
				<td class="text-right"><strong>{{ systemMoneyFormat($total_depreciated) }}</strong></td>
				<td class="text-right"><strong>{{ systemMoneyFormat($total_current_value) }}</strong></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="9">&nbsp;</td>
			</tr>
		@endforeach
		@endif
	</tbody>
</table>
