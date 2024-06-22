<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th style="width: 3%" class="text-center"><i class="fa fa-check"></i></th>
					<th style="width: 20%">Asset</th>
					<th style="width: 10%">Purchase Price</th>
					<th style="width: 10%">Depreciated</th>
					<th style="width: 10%">Book Value</th>
					<th style="width: 47%">Disposal Infomartion</th>
				</tr>
			</thead>
			<tbody>
				@php
					$grand_price = 0;
					$grand_depreciated = 0;
					$grand_current_value = 0;
				@endphp
				@if(isset($finalAssets[0]))
				@foreach($finalAssets as $key => $finalAsset)
					@php
						$total_price = 0;
						$total_depreciated = 0;
						$total_current_value = 0;

						if($items->where('asset_code', $finalAsset->asset_code)->count() > 0){
							foreach($items->where('asset_code', $finalAsset->asset_code) as $key => $item){
								$assetValue = assetValue($item);

							    $depreciated = $item->depreciations->sum('amount');
							    $current_value = $assetValue-$depreciated;

							    $total_price += $assetValue;
								$total_depreciated += $depreciated;
								$total_current_value += $current_value;

								$grand_price += $assetValue;
								$grand_depreciated += $depreciated;
								$grand_current_value += $current_value;
							}
						}
					@endphp

					<tr>
						<td class="text-center">
							<input type="checkbox" class="item-checkboxes" onchange="calculateDisposalAmount()" name="assets[]" value="{{ $finalAsset->asset_code }}">
						</td>
						<td>
							<ul class="mb-0">
							    <li><strong>Asset:</strong> {{ $finalAsset->finalAsset->name }} {{ getProductAttributesFaster($finalAsset->finalAsset) }}</li>
							    <li><strong>Asset Code:</strong> {{ $finalAsset->asset_code }}</li>
							</ul>
						</td>
						<td class="text-right">
							{{ systemMoneyFormat($total_price) }}
						</td>
						<td class="text-right">
							{{ systemMoneyFormat($total_depreciated) }}
						</td>
						<td class="text-right">
							{{ systemMoneyFormat($total_current_value) }}
						</td>
						<td>
							<div class="form-group row">
								<div class="col-md-3">
									<label class="mb-0 pb-0" for="disposal_type_{{ $finalAsset->asset_code }}"><strong>Type</strong></label>
									<select name="disposal_type[{{ $finalAsset->asset_code }}]" id="disposal_type_{{ $finalAsset->asset_code }}" class="form-control">
										<option value="disposed">Disposal</option>
										<option value="sold">Sale</option>
									</select>
								</div>
								<div class="col-md-3">
									<label class="mb-0 pb-0" for="disposed_at_{{ $finalAsset->asset_code }}"><strong>Date</strong></label>
									<input type="date" name="disposed_at[{{ $finalAsset->asset_code }}]" id="disposed_at_{{ $finalAsset->asset_code }}" value="{{ date('Y-m-d') }}" class="form-control">
								</div>
								<div class="col-md-3">
									<label class="mb-0 pb-0" for="disposal_amount_{{ $finalAsset->asset_code }}"><strong>Amount</strong></label>
									<input type="number" name="disposal_amount[{{ $finalAsset->asset_code }}]" id="disposal_amount_{{ $finalAsset->asset_code }}" min="0" value="0.0000" step="0.0001" class="form-control disposal-amounts text-right" onkeyup="calculateDisposalAmount()" onchange="calculateDisposalAmount()">
								</div>
								<div class="col-md-3">
									<label class="mb-0 pb-0" for="disposal_tax_amount_{{ $finalAsset->asset_code }}"><strong>TAX Amount</strong></label>
									<input type="number" name="disposal_tax_amount[{{ $finalAsset->asset_code }}]" id="disposal_tax_amount_{{ $finalAsset->asset_code }}" min="0" value="0.0000" step="0.0001" class="form-control disposal-tax-amounts text-right" onkeyup="calculateDisposalAmount()" onchange="calculateDisposalAmount()">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-12">
									<label class="mb-0 pb-0" for="remarks_{{ $finalAsset->asset_code }}"><strong>Remarks</strong></label>
									<textarea name="remarks[{{ $finalAsset->asset_code }}]" id="remarks_{{ $finalAsset->asset_code }}" class="form-control" style="resize: none;height: 50px"></textarea>
								</div>
							</div>
						</td>
					</tr>

				@endforeach
				@endif

				<tr>
					<td colspan="2" class="text-right"><strong>Total:</strong></td>
					<td class="text-right"><strong>{{ systemMoneyFormat($grand_price) }}</strong></td>
					<td class="text-right"><strong>{{ systemMoneyFormat($grand_depreciated) }}</strong></td>
					<td class="text-right"><strong>{{ systemMoneyFormat($grand_current_value) }}</strong></td>
					<td class="text-right">
						Total Disposal Amount: <strong id="total-disposal-amount">0.00</strong>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						Total TAX Amount: <strong id="total-disposal-tax-amount">0.00</strong>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="row mt-3">
	<div class="col-md-10">
		@include('payment', [
			'currency_id' => $currency_id,
			'select2' => true,
			'company_id' => false
		])
	</div>
	<div class="col-md-2 pt-5 mt-2">
		<button type="submit" class="mt-5 btn btn-md btn-block btn-success pull-right"><i class="las la-dumpster-fire"></i>&nbsp;Process Disposal</button>
	</div>
</div>
