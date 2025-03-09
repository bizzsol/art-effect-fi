<option value="0">All Batches</option>
@if(isset($batches[0]))
@foreach($batches as $key => $batch)
<option value="{{ $batch->id }}">Batch: {{ $batch->batch }} ({{ $batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no }})</option>
@endforeach
@endif