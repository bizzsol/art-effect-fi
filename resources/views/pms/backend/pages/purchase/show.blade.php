@if(isset($purchaseOrder))
<div class="row">
@php 
    $TS = systemDoubleValue($purchaseOrder->relQuotation->relSuppliers->SupplierRatings->sum('total_score'),2);
    $TC = $purchaseOrder->relQuotation->relSuppliers->SupplierRatings->count();

    $totalScore = isset($TS)?$TS:0;
    $totalCount = isset($TC)?$TC:0;
@endphp

<div class="col-md-12">
    <div class="panel panel-info">

        <div class="col-lg-12 invoiceBody">
            <div class="invoice-details mt25 row">

                <div class="well col-6">
                    <ul class="list-unstyled mb0">
                        <li>
                            <div class="ratings">
                                <a href="{{route('pms.supplier.profile',$purchaseOrder->relQuotation->relSuppliers->id)}}" target="_blank"><span>Rating:</span></a> 

                                @if(in_array($purchaseOrder->relQuotation->type,['manual','online']))
                                ({!!ratingGenerate($totalScore,$totalCount)!!})
                                @endif
                            </div>
                            <h5 class="review-count"></h5>
                        </li>
                        <li><strong>Supplier :</strong> {{isset($purchaseOrder->relQuotation->relSuppliers->name)?$purchaseOrder->relQuotation->relSuppliers->name:''}}</li>
                        <li><strong>Code :</strong> {{isset($purchaseOrder->relQuotation->relSuppliers->code)?$purchaseOrder->relQuotation->relSuppliers->code:''}}</li>
                        <li><strong>Email :</strong> {{isset($purchaseOrder->relQuotation->relSuppliers->email)?$purchaseOrder->relQuotation->relSuppliers->email:''}}</li>
                        <li><strong>Phone :</strong> {{isset($purchaseOrder->relQuotation->relSuppliers->phone)?$purchaseOrder->relQuotation->relSuppliers->phone:''}}</li>
                        <li><strong>Mobile :</strong> {{isset($purchaseOrder->relQuotation->relSuppliers->mobile_no)?$purchaseOrder->relQuotation->relSuppliers->mobile_no:''}}</li>
                    </ul>
                </div>
                <div class="col-6">
                    <ul class="list-unstyled mb0 pull-right">
                        <li><strong>Date :</strong> {{date('d-m-Y',strtotime($purchaseOrder->po_date))}}</li>
                        @if(isset($purchaseOrder->relQuotation->delivery_date))
                        <li><strong>Delivery Date :</strong> {{date('d-m-Y',strtotime($purchaseOrder->relQuotation->delivery_date))}}</li>
                        @endif
                        <li><strong>Reference No:</strong> {{$purchaseOrder->reference_no}}</li>
                        <li><strong>Quotation No:</strong> {{isset($purchaseOrder->relQuotation->reference_no)?$purchaseOrder->relQuotation->reference_no:''}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="table-responsive">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th>SL</th>
                        <th>Product</th>
                        <th>Description</th>
                        <th>UOM</th>
                        <th>Unit Price ({{ $currency }})</th>
                        <th>Qty</th>
                        <th>Price ({{ $currency }})</th>
                        @if(!$same)
                        <th>Price ({{ $systemCurrency->code }})</th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                    @foreach($purchaseOrder->relPurchaseOrderItems as $key=>$item)
                    <tr>
                        <td class="text-center">{{$key+1}}</td>
                        <td>{{isset($item->relProduct->name)?$item->relProduct->name:''}} ({{isset($item->relProduct->sku)?$item->relProduct->sku:''}}) {{ getProductAttributesFaster($item->relProduct) }} {{ getProductAttributesFaster($requisitionItems->where('uid', $item->uid)->first()) }}</td>
                        <td>{{ $purchaseOrder->relQuotation->relQuotationItems->where('uid', $item->uid)->first()->description }}</td>
                        <td>{{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}</td>
                        <td class="text-right">{{systemMoneyFormat($item->unit_price)}}</td>
                        <td class="text-center">{{systemMoneyFormat($item->qty)}}</td>
                        <td class="text-right">{{systemMoneyFormat($item->sub_total_price)}}</td>
                        @if(!$same)
                        <td class="text-right">{{systemMoneyFormat($item->sub_total_price*$exchangeRate)}}</td>
                        @endif
                    </tr>
                    @endforeach

                    <tr>
                        <td colspan="5" class="text-right">Total</td>
                        <td class="text-center">{{systemDoubleValue($purchaseOrder->relPurchaseOrderItems->sum('qty'),2)}}</td>
                        <td class="text-right">{{systemMoneyFormat($purchaseOrder->relPurchaseOrderItems->sum('sub_total_price'))}}</td>
                        @if(!$same)
                        <td class="text-right">{{systemMoneyFormat($purchaseOrder->relPurchaseOrderItems->sum('sub_total_price')*$exchangeRate)}}</td>
                        @endif
                    </tr>

                    {{-- <tr>
                        <td colspan="6" class="text-right">(-) Discount</td>
                        <td><?= $purchaseOrder->discount?></td>
                    </tr> --}}
                    
                    <tr>
                        <td colspan="6" class="text-right">(+) Vat</td>
                        <td class="text-right">{{systemMoneyFormat($purchaseOrder->vat)}}</td>
                        @if(!$same)
                        <td class="text-right">{{systemMoneyFormat($purchaseOrder->vat*$exchangeRate)}}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total Amount</strong></td>
                        <td class="text-right"><strong>{{systemMoneyFormat($purchaseOrder->gross_price)}}</strong></td>
                        @if(!$same)
                        <td class="text-right"><strong>{{systemMoneyFormat($purchaseOrder->gross_price*$exchangeRate)}}</strong></td>
                        @endif
                    </tr>
                </tbody>
            </table>

        </div>
        <div class="form-group">
            <label for="remarks"><strong>Notes</strong>:</label>

            <span>{!! isset($purchaseOrder->remarks)?$purchaseOrder->remarks:'' !!}</span>

        </div>
        @if($purchaseOrder->cash_note !=null)
        <div class="form-group">
            <label for="remarks"><strong>Cash Notes</strong>:</label>

            <span>{!! isset($purchaseOrder->cash_note)?$purchaseOrder->cash_note:'' !!}</span>
        </div>
        @endif
    </div>
</div>
</div>
@endif