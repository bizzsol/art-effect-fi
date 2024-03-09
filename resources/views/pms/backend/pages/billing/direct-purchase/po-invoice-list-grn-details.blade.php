<table class="table table-bordered table-hover">
    <thead>
        <tr class="text-center">
            <th>SL</th>
            <th>Category</th>
            <th>Product</th>
            <th>UOM</th>
            <th>Unit Price</th>
            <th>Qty</th>
            <th>Item Total</th>
            <th>Vat(%)</th>
            <th>Vat</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @if($grn->relGoodsReceivedItems->count())
        @foreach($grn->relGoodsReceivedItems as $key=>$grnItem)
        <tr>
            <td class="text-center">{{$key+1}}</td>
            <td>{{isset($grnItem->relProduct->category->name)?$grnItem->relProduct->category->name:''}}</td>
            <td>{{isset($grnItem->relProduct->name)?$grnItem->relProduct->name:''}} {{ getProductAttributes($grnItem->product_id) }}</td>
            <td>{{isset($grnItem->relProduct->productUnit->unit_name)?$grnItem->relProduct->productUnit->unit_name:''}}</td>
            <td class="text-center">
                <input type="text" step="any"  class="form-control text-right mask-money" min="0" name="unit_amount[{{$grnItem->goods_received_note_id}}][{{$grnItem->id}}]" value="{{$grnItem->unit_amount}}" onkeyup="calculateSubtotal({{$grnItem->id}},{{$grnItem->qty}})" onchange="calculateSubtotal({{$grnItem->id}},{{$grnItem->qty}})" id="unitAmount{{$grnItem->id}}">
            </td>
            <td class="text-center">{{$grnItem->qty}}</td>
            <td class="text-right"><span id="subTotalPrice_{{$grnItem->id}}" class="calculateSumOfSubtotal">{{$grnItem->sub_total}}</span></td>
            <td class="text-center" id="item_vat_percentage-{{$grnItem->id}}">{{$grnItem->vat_percentage}}</td>
            <td class="text-center" id="item_vat-{{$grnItem->id}}">{{$grnItem->vat}}</td>
            <td class="text-right"><span id="totalPrice_{{$grnItem->id}}" class="calculateSumOfTotal">{{$grnItem->total_amount}}</span></td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>