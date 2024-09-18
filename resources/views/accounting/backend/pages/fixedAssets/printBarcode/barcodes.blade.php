<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{$title}}</title>
        <style>
            @page {
                margin-top: 1.85in;
                margin-bottom: 1.25in;
                header: page-header;
                footer: page-footer;

                background: url({{ getUnitPad(isset($batch->requisitionDeliveryItem->relRequisitionDelivery->relRequisition->purchaseOrders[0]->id) ? $batch->requisitionDeliveryItem->relRequisitionDelivery->relRequisition->purchaseOrders[0]->purchaseOrder->Unit : 0) }}) no-repeat 0 0;
                background-image-resize: 6;
            }
            
            html, body, p  {
                font-size:  10px !important;
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

            .table-no-bordered td {
                border: none !important;
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

            .text-center{
                text-align:  center;
            }
            .text-right{
                text-align:  right;
            }           
        </style>    
    </head>
    
    <body>
        <htmlpageheader name="page-header">
            <h3 style="padding-left: 10px;padding-top: 140px">{{ $title }}</h3>
        </htmlpageheader>

        <htmlpagefooter name="page-footer">
            <table class="table-bordered">
                <tbody>
                    <tr>
                        <td colspan="2" style="text-align: center;border: none !important">
                            Barcode Printed by <strong>{{ auth()->user()->name  }}</strong>
                        </td>
                    </tr>
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
                    </tr> --}}
                    <tr style="border: none !important">
                        <td style="height: 50px; !important;border: none !important;border-right: none !important">
                            
                        </td>
                        <td style="height: 50px; !important;border: none !important;border-left: none !important">
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;border: none !important">
                            <small>Page {PAGENO} of {nb}</small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </htmlpagefooter>
        
        <div class="container">
            @if(isset($batches[0]))
            @foreach($batches as $key => $batch)
            <div class="row">
                <div style="width: 100%">
                    @if($batch->items->count() > 0)
                    <table class="table">
                        <tbody>
                            @foreach($batch->items->where('is_disposed', 0)->chunk(3) as $key => $chunk)
                            <tr>
                                @foreach($chunk as $key => $item)
                                <td style="padding: 15px">
                                    {!! printBarcode($item->asset_code) !!}
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </body>
</html>                                                                                                                                                                                                                             