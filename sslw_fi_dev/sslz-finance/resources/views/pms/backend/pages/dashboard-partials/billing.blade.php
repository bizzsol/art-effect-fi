

@php
    $topSuppliers = topSuppliers(8);
@endphp
<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">BILLING MANAGEMENT STATS&nbsp;&nbsp;|&nbsp;&nbsp;TOP ({{ isset($topSuppliers[0]) ? count($topSuppliers) : 0 }}) SUPPLIER</h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            
            <div class="container-fluid p-0">
                
                <div class="row">
                    @if(isset($topSuppliers[0]))
                    @foreach($topSuppliers as $key => $supplier)
                    @php
                        $purchaseOrders = \App\Models\PmsModels\Purchase\PurchaseOrder::where('is_send','yes')
                        ->whereHas('relQuotation', function ($query) use($supplier){
                            return $query->where('supplier_id', $supplier->id);
                        })
                        ->whereHas('relGoodReceiveNote', function ($query){
                            $query->whereRaw('purchase_orders.id=goods_received_notes.purchase_order_id');
                        })->pluck('id')->toArray();

                        $po = \App\Models\PmsModels\Purchase\PurchaseOrder::whereIn('id', $purchaseOrders)->sum('gross_price');
                        $grn = \App\Models\PmsModels\Grn\GoodsReceivedItemStockIn::whereIn('purchase_order_id', $purchaseOrders)->where('is_grn_complete','yes')->sum('total_amount');
                        $bill = \App\Models\PmsModels\Purchase\PurchaseOrderAttachment::whereIn('purchase_order_id', $purchaseOrders)->sum('bill_amount');
                    @endphp
                        <div class="col-md-3 pr-0">
                            <div class="project-card" style="height: auto !important">
                                <div class="project-card-header">
                                    <h6 class="mb-0">
                                        <i class="la la-user-secret"></i>&nbsp;&nbsp;{{ $supplier->name }}
                                    </h6>
                                </div>
                                <div class="project-card-body pb-3">
                                    <canvas class="bar-charts" id="supplier-{{ $supplier->id }}-billing-chart" data-data="{{ $po.','.$grn.','.$bill }}" data-labels="PO,GRN,BILL" data-legend-position="top" data-title-text="Amount" width="200" height="150"></canvas>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $from = date('Y-m-d', strtotime('-30 days'));
    $to = date('Y-m-d');
    $billingData = billingData($from, $to);
@endphp
<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/billing-audit/po-list') }}">MONTHLY PO STATS&nbsp;&nbsp;|&nbsp;&nbsp;{{ date('d-M-Y', strtotime($from)) }} to {{ date('d-M-Y', strtotime($to)) }}</a></h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-md-12">
                        <canvas class="bar-charts" id="monthly-po-amount-chart" data-data="{{ implode(',', array_values($billingData['po']))  }}" data-labels="{{ implode(',', array_keys($billingData['po']))  }}" data-legend-position="top" data-title-text="Amount" width="200" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/billing-audit/po-list') }}">MONTHLY GRN STATS&nbsp;&nbsp;|&nbsp;&nbsp;{{ date('d-M-Y', strtotime($from)) }} to {{ date('d-M-Y', strtotime($to)) }}</a></h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-md-12">
                        <canvas class="bar-charts" id="monthly-grn-amount-chart" data-data="{{ implode(',', array_values($billingData['grn']))  }}" data-labels="{{ implode(',', array_keys($billingData['grn']))  }}" data-legend-position="top" data-title-text="Amount" width="200" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/billing-audit/po-list') }}">MONTHLY BILL STATS&nbsp;&nbsp;|&nbsp;&nbsp;{{ date('d-M-Y', strtotime($from)) }} to {{ date('d-M-Y', strtotime($to)) }}</a></h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-md-12">
                        <canvas class="bar-charts" id="monthly-bill-amount-chart" data-data="{{ implode(',', array_values($billingData['bill']))  }}" data-labels="{{ implode(',', array_keys($billingData['bill']))  }}" data-legend-position="top" data-title-text="Amount" width="200" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>