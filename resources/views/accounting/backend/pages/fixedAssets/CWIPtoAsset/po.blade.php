@if(isset($purchaseOrders[0]))
@foreach($purchaseOrders as $key => $purchaseOrder)
    <option value="{{ $purchaseOrder->id }}">{{ $purchaseOrder->reference_no }}</option>
@endforeach
@endif