@if(isset($batches[0]))
@foreach($batches as $key => $batch)
<option value="{{ $batch->id }}">Batch: {{ $batch->batch }} (Delivery: {{ $batch->requisitionDeliveryItem->relRequisitionDelivery->reference_no }}) (Requisition: {{ $batch->requisitionDeliveryItem->relRequisitionDelivery->relRequisition->reference_no }})</option>
@endforeach
@endif