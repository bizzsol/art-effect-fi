@if(isset($finalAssets[0]))
@foreach($finalAssets as $key => $item)
<option value="{{ $item->id }}" {{ $selected == $item->id ? 'selected' : '' }}>{{ $item->finalAsset->name }} {{ getProductAttributesFaster($item->finalAsset) }} :: {{ $item->asset_code }}</option>
@endforeach
@endif