@php
    $directPurchase = ($purchaseOrder->relQuotation->type == "direct-purchase" ? true : false);
    $corporateAddress = \App\Models\PmsModels\SupplierAddress::where(['supplier_id' => isset($purchaseOrder->relQuotation->relSuppliers->id) ? $purchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'corporate'])->first();
    $factoryAddress = \App\Models\PmsModels\SupplierAddress::where(['supplier_id' => isset($purchaseOrder->relQuotation->relSuppliers->id) ? $purchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'factory'])->first();
    $contactPersonSales = \App\Models\PmsModels\SupplierContactPerson::where(['supplier_id' => isset($purchaseOrder->relQuotation->relSuppliers->id) ? $purchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'sales'])->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $directPurchase ? 'Direct Purchase' : 'Purchase Order' }}</title>
    <style>
        @page {
            margin-top: 1.25in;
            margin-bottom: 1.25in;
            header: page-header;
            footer: page-footer;

            background: url({{ getUnitPad($purchaseOrder->Unit) }}) no-repeat 0 0;
            background-image-resize: 6;
        }

        html, body, p {
            font-size: 12px !important;
            color: #000000;
        }

        table {
            width: 100% !important;
            border-spacing: 0px !important;
            margin-top: 10px !important;
            margin-bottom: 15px !important;
        }

        table caption {
            color: #000000 !important;
        }

        table td {
            padding-top: 1px !important;
            padding-bottom: 1px !important;
            padding-left: 7px !important;
            padding-right: 7px !important;
        }

        .table-non-bordered {
            padding-left: 0px !important;
        }

        .table-bordered {
            border-collapse: collapse;
        }

        .table-bordered td {
            border: 1px solid #000000;
            padding: 5px;
        }

        .table-bordered tr:first-child td {
            border-top: 0;
        }

        .table-bordered tr td:first-child {
            border-left: 0;
        }

        .table-bordered tr:last-child td {
            border-bottom: 0;
        }

        .table-bordered tr td:last-child {
            border-right: 0;
        }

        .mt-0 {
            margin-top: 0;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .image-space {
            white-space: wrap !important;
            padding-top: 45px !important;
        }

        .break-before {
            page-break-before: always;
            break-before: always;
        }

        .break-after {
            break-after: always;
        }

        .break-inside {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .break-inside-auto {
            page-break-inside: auto;
            break-inside: auto;
        }

        .space-top {
            margin-top: 10px;
        }

        .space-bottom {
            margin-bottom: 10px;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }
    </style>
</head>

<body>
<htmlpageheader name="page-header">
    @if(!$directPurchase)
        {{-- <div class="row mb-3 print-header">
            <div class="col-md-6" style="width: 50%;float:left;padding-top: 135px">
                <h2><strong>Purchase Order</strong></h2>
            </div>
            <div class="col-md-6 text-right" style="width: 50%;float:left;padding-top: 50px">
                @if(!empty($purchaseOrder->Unit->hr_unit_logo) && file_exists(public_path($purchaseOrder->Unit->hr_unit_logo)))
                    <img src="{{ str_replace('/assets','assets', $purchaseOrder->Unit->hr_unit_logo) }}" alt="logo" style="float: right !important;height: 15mm; width:  35mm; margin: 0;" />
                @endif
            </div>
        </div> --}}
    @endif
</htmlpageheader>

<htmlpagefooter name="page-footer">
    <table class="table-bordered">
        <tbody>
        {{-- <tr>
            <td colspan="2" style="text-align: right;border: none !important">
                {{ $directPurchase ? 'IJO Approved' : 'PO Issued' }} by <strong>{{ $purchaseOrder->creator->name }},
                    Time & Date: {{ date('g.i a, d.m.Y', strtotime($purchaseOrder->created_at)) }}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: none !important">
                <small>(Note: This {{ $directPurchase ? 'Internal Job Order' : 'Purchase Order' }} doesn’t require signature as it is automatically generated from MBM Group’s ERP)</small>
            </td>
        </tr> --}}
        @if(!$directPurchase)
            {{-- <tr>
                <td style="border-left: none !important;border-bottom: none !important">
                    <small>
                        Factory: M-19 & M-14, Section-14, Mirpur, Dhaka-1206
                        <br>
                        Phone: +8809678-411412, Mail: info@mbm.group
                    </small>
                </td>
                <td style="padding-left: 25px;border-right: none !important;border-bottom: none !important">
                    <small>
                        Corporate Office: Plot: 1358, Road: 50 (Old), 9 (New)
                        <br>
                        Avenue: 11, DOHS, Mirpur-12, Dhaka-1216
                        <br>
                        Website: www.mbm.group
                    </small>
                </td>
            </tr>
            <tr style="border: none !important">
                <td style="height: 50px; !important;border: none !important;border-right: none !important">

                </td>
                <td style="height: 50px; !important;border: none !important;border-left: none !important">

                </td>
            </tr> --}}
        @endif
        <tr>
            <td colspan="2" style="text-align: right;border: none !important;">
                <small>{PAGENO} of {nb}</small>
            </td>
        </tr>
        </tbody>
    </table>
</htmlpagefooter>

<div class="container">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <td colspan="2" class="text-center">
                <h3>Purchase Order</h3>
            </td>
        </tr>
        <tr>
            <td style="width: 50% !important">
                <strong>To: {{isset($purchaseOrder->relQuotation->relSuppliers->name) ? $purchaseOrder->relQuotation->relSuppliers->name : ''}}</strong>
                <br>
                <p>
                    @if(isset($corporateAddress->id))
                        {{ $corporateAddress->village.' '.$corporateAddress->road.', '.$corporateAddress->city.'-'.$corporateAddress->zip.', '.$corporateAddress->country }}
                        <br>
                        {{ $corporateAddress->adddress }}
                    @endif
                </p>
                <br>
                <br>
                @if(isset($contactPersonSales->id))
                    Attn: {{ $contactPersonSales->name.', '.$contactPersonSales->designation }}
                    <br>
                    Mob:&nbsp;{{ $contactPersonSales->mobile }}
                    <br>
                    Email Address:&nbsp;{{ $contactPersonSales->email }}
                @endif
            </td>
            <td style="width: 50% !important;">
                <div style="text-align: right !important">
                    {!! printBarcode($purchaseOrder->reference_no, 'float: right !important') !!}
                </div>
                <br>
                <br>
                <strong>PO Reference:</strong> {{ $purchaseOrder->reference_no }}
                <br>
                <strong>Date:</strong> {{ date('d-M-y', strtotime($purchaseOrder->po_date)) }}
                <br>
                <strong>Div./Dept:</strong> {{ $purchaseOrder->purchaseOrderRequisitions->first()->requisition->relUsersList->employee->department->hr_department_name }}
                <br>
                <strong>Contact Person:</strong> {!! isset($deliveryContact->id) ? $deliveryContact->name.'&nbsp;&nbsp;|&nbsp;&nbsp;'.isset($deliveryContact->employee) ? isset($deliveryContact->employee->designation) ? $deliveryContact->employee->designation->hr_designation_name : '' : ''.'&nbsp;&nbsp;|&nbsp;&nbsp;'.$deliveryContact->phone : '' !!}
                <br>
                <strong>Order/Requisition Ref: {{ $purchaseOrder->purchaseOrderRequisitions->pluck('requisition.reference_no')->implode(', ') }}</strong> 
            </td>
        </tr>
        <tr>
            <td style="width: 50% !important">
                <strong>Your vendor no. with us:</strong> {{isset($purchaseOrder->relQuotation->relSuppliers->name) ? $purchaseOrder->relQuotation->relSuppliers->code : ''}}
                <br>
                <br>
                <strong>Invoice Address:</strong>
                <br>
                @if(isset($factoryAddress->id))
                    {{ $factoryAddress->village.' '.$factoryAddress->road.', '.$factoryAddress->city.'-'.$factoryAddress->zip.', '.$factoryAddress->country }}
                    <br>
                    {{ $factoryAddress->adddress }}
                @endif
            </td>
            <td style="width: 50% !important">
                <strong>Your quot. ref.: {{ isset($purchaseOrder->relQuotation->id) ? $purchaseOrder->relQuotation->reference_no : '' }}</strong>
                <br>
                <strong>Date:</strong> {{ isset($purchaseOrder->relQuotation->quotation_date) ? date('d-M-y', strtotime($purchaseOrder->relQuotation->quotation_date)) : '' }}
                <br>
                <strong>Deliver to:</strong> {{isset($purchaseOrder->Unit->hr_unit_name)?$purchaseOrder->Unit->hr_unit_name:''}}
                <div>
                    {!! isset($purchaseOrder->Unit->hr_unit_address)?$purchaseOrder->Unit->hr_unit_address:'' !!}
                </div>
                <br>
                <strong>Order is valid till: {{ isset($purchaseOrder->relQuotation->delivery_date) ? date('d-M-y', strtotime($purchaseOrder->relQuotation->delivery_date)) : '' }}</strong>
                <br>
                <strong>Delivery Date: {{ isset($purchaseOrder->relQuotation->delivery_date) ? date('d-M-y', strtotime($purchaseOrder->relQuotation->delivery_date)) : '' }}</strong> 
            </td>
        </tr>

        {{-- @if($directPurchase)
            <tr>
                <td style="width: 50% !important;">
                    {{ $directPurchase ? 'IJO' : 'PO' }} Ref. No:&nbsp;{{ $purchaseOrder->reference_no }}
                    <br>
                    {{ $directPurchase ? 'IJO' : 'PO' }}
                    Date:&nbsp;{{ date('jS F Y', strtotime($purchaseOrder->po_date)) }}
                    <br>
                    {{ $directPurchase ? 'Estimate' : 'Quotation' }} Ref.
                    No:&nbsp;{{ isset($purchaseOrder->relQuotation->id) ? $purchaseOrder->relQuotation->reference_no : '' }}
                </td>
                <td style="width: 50% !important;" class="text-right">
                    Delivery Location:&nbsp;
                    {{isset($purchaseOrder->Unit->hr_unit_name)?$purchaseOrder->Unit->hr_unit_name:''}}
                    <div>
                        {!! isset($purchaseOrder->Unit->hr_unit_address)?$purchaseOrder->Unit->hr_unit_address:'' !!}
                    </div>
                </td>
            </tr>
        @else
            <tr>
                <td style="width: 50% !important;">
                    Address:&nbsp;
                    @if(isset($corporateAddress->id))
                        {{ $corporateAddress->village.' '.$corporateAddress->road.', '.$corporateAddress->city.'-'.$corporateAddress->zip.', '.$corporateAddress->country }}
                        <br>
                        {{ $corporateAddress->adddress }}
                    @endif
                </td>
                <td style="width: 50% !important;" class="text-right">
                    PO Ref. No:&nbsp;{{ $purchaseOrder->reference_no }}
                    <br>
                    PO Date:&nbsp;{{ date('jS F Y', strtotime($purchaseOrder->po_date)) }}
                    <br>
                    Quotation Ref.
                    No:&nbsp;{{ isset($purchaseOrder->relQuotation->reference_no) ? $purchaseOrder->relQuotation->reference_no : '' }}
                </td>
            </tr>
            <tr>
                <td style="width: 50% !important;">
                    Attention:&nbsp;
                    @if(isset($contactPersonSales->id))
                        {{ $contactPersonSales->name.', '.$contactPersonSales->designation }},
                        <br>
                        Mobile:&nbsp;{{ $contactPersonSales->mobile }},
                        <br>
                        Mail:&nbsp;{{ $contactPersonSales->email }}
                    @endif
                </td>
                <td style="width: 50% !important;" class="text-right">
                    Delivery Location:&nbsp;
                    {{isset($purchaseOrder->Unit->hr_unit_name)?$purchaseOrder->Unit->hr_unit_name:''}}
                    <div>
                        {!! isset($purchaseOrder->Unit->hr_unit_address)?$purchaseOrder->Unit->hr_unit_address:'' !!}
                    </div>
                </td>
            </tr>
        @endif

        <tr>
            <td style="width: 50% !important;font-weight: bold !important">
                <strong>Payment
                    Mode:&nbsp;{{ makePaymentTermsString($purchaseOrder->relQuotation->supplier_payment_terms_id) }}</strong>
            </td>
            <td style="width: 50% !important;" class="text-right">
                Delivery Contact:&nbsp; {!! isset($deliveryContact->id) ? $deliveryContact->name.'&nbsp;&nbsp;|&nbsp;&nbsp;'.isset($deliveryContact->employee) ? isset($deliveryContact->employee->designation) ? $deliveryContact->employee->designation->hr_designation_name : '' : ''.'&nbsp;&nbsp;|&nbsp;&nbsp;'.$deliveryContact->phone : '' !!}
            </td>
        </tr>
        <tr>
            <td style="width: 50% !important;font-weight: bold !important">
                Delivery Date:&nbsp;
                <strong>{{ isset($purchaseOrder->relQuotation->delivery_date) ? date('jS F Y', strtotime($purchaseOrder->relQuotation->delivery_date)) : '' }}</strong>
            </td>
            <td style="width: 50% !important;font-weight: bold !important">
                Currency:&nbsp;
                <strong>{{ isset($purchaseOrder->relQuotation->exchangeRate->currency->name)?$purchaseOrder->relQuotation->exchangeRate->currency->name:'' }}
                    ({{ isset($purchaseOrder->relQuotation->exchangeRate->currency->code)?$purchaseOrder->relQuotation->exchangeRate->currency->code:'' }}
                    )</strong>
            </td>
        </tr> --}}
        </tbody>
    </table>

    <table class="table table-bordered">
        <thead>
        <tr class="text-center">
            <td style="text-align:  center;width: 5% !important"><strong>Item No.</strong></td>
            <td style="text-align:  center;width: 15% !important"><strong>Product</strong></td>
            <td style="text-align:  center;width: 30% !important"><strong>Description</strong></td>
            <td style="text-align:  center;width: 10% !important"><strong>Quantity</strong></td>
            <td style="text-align:  center;width: 10% !important"><strong>Unit Price</strong></td>
            {{-- @if($purchaseOrder->relPurchaseOrderItems->sum('vat') > 0)
                <td style="text-align:  center;width: 7.5% !important"><strong>Item Total</strong></td>
                <td style="text-align:  center;width: 7.5% !important"><strong>Vat Type</strong></td>
                <td style="text-align:  center;width: 7.5% !important"><strong>Vat(%)</strong></td>
                <td style="text-align:  center;width: 7.5% !important"><strong>Vat</strong></td>
            @endif --}}
            <td style="text-align:  center;width: 15% !important"><strong>Total Amount</strong></td>
        </tr>
        </thead>
        <tbody>
        @foreach($purchaseOrder->relPurchaseOrderItems as $key=>$item)
            <tr>
                <td class="text-center">{{ $key+1}}</td>
                <td>
                    {{ isset($item->relProduct->name) ? $item->relProduct->name : '' }} {{ getProductAttributesFaster($item->relProduct) }} {{ getProductAttributesFaster($requisitionItems->where('product_id', $item->product_id)->first()) }} ({{ isset($item->relProduct->productUnit->unit_name) ? $item->relProduct->productUnit->unit_name : '' }})
                </td>
                <td>{{ $purchaseOrder->relQuotation->relQuotationItems->where('product_id', $item->product_id)->first()->description }}</td>
                <td style="text-align: center">{{ $item->qty }}</td>
                <td class="text-right">{{ systemMoneyFormat($item->unit_price) }}</td>
                {{-- @if($purchaseOrder->relPurchaseOrderItems->sum('vat') > 0)
                    <td class="text-right">{{systemMoneyFormat($item->sub_total_price)}}</td>
                    <td class="text-center">{{ucwords($item->vat_type)}}</td>
                    <td class="text-right">{{systemMoneyFormat($item->vat_percentage)}}</td>
                    <td class="text-right">{{systemMoneyFormat($item->vat, 2)}}</td>
                @endif --}}
                <td class="text-right">{{ systemMoneyFormat($item->sub_total_price) }}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="5" class="text-right">
                <strong>Total</strong>
            </td>
            <td class="text-right"><strong>{{ systemMoneyFormat($purchaseOrder->relPurchaseOrderItems->sum('sub_total_price')) }}</strong></td>
        </tr>
        <tr>
            <td colspan="5" class="text-right">
                {{-- <strong>VAT ({{ ucwords($purchaseOrder->relPurchaseOrderItems->first()->vat_type) }} {{ $purchaseOrder->relPurchaseOrderItems->first()->vat_percentage > 0 ? ', '.$purchaseOrder->relPurchaseOrderItems->first()->vat_percentage.'%' : '' }})</strong> --}}
                <strong>VAT ({{ $purchaseOrder->relPurchaseOrderItems->first()->vat_percentage.'%' }})</strong>
            </td>
            <td class="text-right"><strong>{{ systemMoneyFormat($purchaseOrder->vat) }}</strong></td>
        </tr>
        <tr>
            <td colspan="4">
                Total In word: <strong>{{ inWordBn(systemDoubleValue($purchaseOrder->relPurchaseOrderItems->sum('total_price'), 2), true, $purchaseOrder->relQuotation->exchangeRate->currency->name, $purchaseOrder->relQuotation->exchangeRate->currency->hundreds) }} only</strong>
            </td>
            <td class="text-right">
                <strong>Grand Total</strong>
            </td>
            <td class="text-right"><strong>{{ systemMoneyFormat($purchaseOrder->gross_price) }}</strong></td>
        </tr>

        {{-- <tr>
            <td colspan="4">
                Total In word: <strong>{{ inWordBn(systemDoubleValue($purchaseOrder->relPurchaseOrderItems->sum('total_price'), 2), true, $purchaseOrder->relQuotation->exchangeRate->currency->name, $purchaseOrder->relQuotation->exchangeRate->currency->hundreds) }} only</strong>
            </td>
            <td class="text-right">
                <strong>Total Amount</strong>
            </td>

            @if($purchaseOrder->relPurchaseOrderItems->sum('vat') > 0)
                <td class="text-right">
                    <strong>{{number_format($purchaseOrder->relPurchaseOrderItems->sum('sub_total_price'), 2)}}</strong>
                </td>
                <td></td>
                <td></td>
                <td class="text-right">
                    <strong>{{number_format($purchaseOrder->relPurchaseOrderItems->sum('vat'), 2)}}</strong>
                </td>
            @endif
            <td class="text-right">
                <strong>{{isset($purchaseOrder->relPurchaseOrderItems)? number_format($purchaseOrder->relPurchaseOrderItems->sum('total_price'), 2):0}}</strong>
            </td>
        </tr> --}}
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 75% !important">
                    <strong>Payment Mode:&nbsp;{{ makePaymentTermsString($purchaseOrder->relQuotation->supplier_payment_terms_id) }}</strong>
                </td>
                <td style="width: 25% !important" class="text-right">
                    Currency:&nbsp;<strong>{{ isset($purchaseOrder->relQuotation->exchangeRate->currency->name)?$purchaseOrder->relQuotation->exchangeRate->currency->name:'' }} ({{ isset($purchaseOrder->relQuotation->exchangeRate->currency->code)?$purchaseOrder->relQuotation->exchangeRate->currency->code:'' }})</strong>
                </td>
            </tr>
        </tbody>
    </table>

    @if($purchaseOrder->milestones->count() > 0)
    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <td style="width: 10% !important" class="text-center"><strong>SL#</strong></td>
                <td style="width: 70% !important" class="text-center"><strong>Work Progress</strong></td>
                <td style="width: 20% !important" class="text-center"><strong>% of Payment</strong></td>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->milestones as $key => $milestone)
            <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td class="text-center">{{ $milestone->name }}</td>
                <td class="text-center">{{ $milestone->percentage }}%</td>
            </tr>
            @endforeach
            <tr>
                <td class="text-right" colspan="2"><strong>Total Payment</strong></td>
                <td class="text-center"><strong>{{ $purchaseOrder->milestones->sum('percentage') }}%</strong></td>
            </tr>
        </tbody>
    </table>
    @endif

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 50% !important;vertical-align: top !important" rowspan="2">
                    <h4 class="text-center"><strong>Remarks:</strong></h4>
                    <div>
                        {!! $purchaseOrder->remarks !!}
                    </div>
                </td>
                <td style="width: 50% !important;border-bottom: none !important" class="text-center">
                    <br>
                    <div>
                        <small style="text-decoration: underline;">N.B.: This is an electronic version of the document, therefore no signature is required.</small>
                    </div>
                    <br>
                </td>
            </tr>
            <tr>
                <td style="width: 50% !important;background: #ccfefe;border-top: none !important" class="text-center">
                    <div>
                        <br>
                        <h4><strong>Authorized Signature</strong></h4>
                        <br>
                        <br>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
    @if(!$directPurchase)
        <h3 class="text-center"><strong>Terms & Conditions:</strong></h3>
        <div>
            {!! isset($purchaseOrder->terms) ? $purchaseOrder->terms : '' !!}
        </div>
    @endif

    <pagebreak></pagebreak>
    <h3 class="text-center"><strong>General Conditions:</strong></h3>
    <div>
        {!! session()->get('system-information')['general_conditions'] !!}
    </div>

</div>
</body>
</html>																																																								