@if(isset($stocks[0]))
@foreach($stocks as $key => $stocks)
<option value="{{ $stocks->id }}">{{ $stocks->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no }} ({{ date('Y-m-d', strtotime($stocks->relGoodsReceivedItems->relGoodsReceivedNote->received_date)) }})</option>
@endforeach
@endif