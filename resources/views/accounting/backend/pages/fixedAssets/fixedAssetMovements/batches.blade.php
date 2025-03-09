<option value="0">All Batches</option>
@if(isset($batches[0]))
@foreach($batches as $key => $batch)
<option value="{{ $batch->id }}" {{ $selected == $batch->id ? 'selected' : '' }}>Batch: {{ $batch->batch }} (Delivery: {{ $batch->requisitionDeliveryItem->relRequisitionDelivery->reference_no }}) (Requisition: {{ $batch->requisitionDeliveryItem->relRequisitionDelivery->relRequisition->reference_no }})</option>
@endforeach
@endif