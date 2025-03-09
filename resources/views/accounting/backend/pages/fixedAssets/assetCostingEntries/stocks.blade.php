@php
	$array = [];
@endphp
<option value="0">Choose a GRN</option>
@if(isset($stocks[0]))
@foreach($stocks as $key => $stocks)

@if(!in_array($stocks->relGoodsReceivedItems->relGoodsReceivedNote->id, $array))
	@php
		array_push($array, $stocks->relGoodsReceivedItems->relGoodsReceivedNote->id);
	@endphp	
	<option value="{{ $stocks->relGoodsReceivedItems->relGoodsReceivedNote->id }}">{{ $stocks->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no }} ({{ date('Y-m-d', strtotime($stocks->relGoodsReceivedItems->relGoodsReceivedNote->received_date)) }})</option>
@endif
@endforeach
@endif