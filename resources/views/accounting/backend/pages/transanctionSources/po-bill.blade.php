<div class="pl-4">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 35%">Purchase Order</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->relPurchaseOrder->reference_no }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Supplier</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->relPurchaseOrder->relQuotation->relSuppliers->name }} ({{ $model->relPurchaseOrder->relQuotation->relSuppliers->code }})</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Products</td>
                <td style="width: 5%" class="text-center">:</td>
                <td>
                    <strong>
                    @if($model->relPurchaseOrder->relPurchaseOrderItems->count() > 0)
                    @foreach($model->relPurchaseOrder->relPurchaseOrderItems as $key => $item)
                        {{ $key > 0 ? ', ' : '' }}
                        {{ $item->relProduct->name }} {{ getProductAttributesFaster($item->relProduct) }}
                    @endforeach
                    @endif
                    </strong>
                </td>
            </tr>
            <tr>
                <td style="width: 35%">Bill Type</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->bill_type }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Bill Number</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->bill_number }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Bill Amount</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ systemMoneyFormat($model->bill_amount) }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Remarks</td>
                <td style="width: 5%" class="text-center">:</td>
                <td><strong>{{ $model->remarks }}</strong></td>
            </tr>
            <tr>
                <td style="width: 35%">Invoice File</td>
                <td style="width: 5%" class="text-center">:</td>
                <td>
                    <strong>
                        @if(!empty($model->invoice_file))
                            <a href="{{ asset($model->invoice_file) }}" target="__blank" class="text-success">View Invoice File</a>
                        @endif
                    </strong>
                </td>
            </tr>
            <tr>
                <td style="width: 35%">Vat File</td>
                <td style="width: 5%" class="text-center">:</td>
                <td>
                    <strong>
                    @if(!empty($model->vat_challan_file))
                        <a href="{{ asset($model->vat_challan_file) }}" target="__blank" class="text-success">View Invoice File</a>
                    @endif
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>
</div>