<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">PURCHASE MANAGE </h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-3 pr-0">
                                <a href="{{route('pms.rfp.requisitions.list')}}">
                                    <div class="feature-effect-box wow fadeInUp bg-primary" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-primary">
                                            <i class="las la-receipt"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Total Requisition</h5>
                                        </div>
                                        <div class="feature-i iq-bg-primary pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['rfp-requistions'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="{{route('pms.quotation.quotations.index')}}">
                                    <div class="feature-effect-box wow fadeInUp bg-dark" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-dark">
                                            <i class="las la-file-invoice-dollar"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Total Quotations</h5>
                                        </div>
                                        <div class="feature-i iq-bg-dark pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['quotations'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 pr-0">
                                <a href="{{route('pms.quotation.quotations.generate.po.list')}}">
                                    <div class="feature-effect-box wow fadeInUp bg-success" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-success">
                                            <i class="lar la-check-square"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Total Approved</h5>
                                        </div>
                                        <div class="feature-i iq-bg-success pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['proposals'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 pr-0">
                                <a href="{{route('pms.purchase.order-index')}}">
                                    <div class="feature-effect-box wow fadeInUp bg-primary" data-wow-duration="0.4s">
                                          <div class="feature-i iq-bg-primary">
                                            <i class="lar la-file-alt"></i>
                                          </div>
                                        <div class="feature-icon">
                                          <h5 class="text-white">Total PO</h5>
                                        </div>
                                        <div class="feature-i iq-bg-primary pull-right counter mr-0" style="border-radius: 25% !important; font-weight: bold;margin-top: 2px">
                                            {{ $purchaseStats['purchase-orders'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">TOP SUPPLIER </h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-users" style="transform: scale(1.5,1.5)"></i>&nbsp;&nbsp;&nbsp;Top 10 Supplier
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-0">
                                        <table class="table table-striped table-bordered miw-500 dac_table pb-0 mb-0" cellspacing="0" width="100%" id="dataTable">
                                            @php
                                                $topSuppliers = topSuppliers(10);
                                            @endphp
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" class="text-center">
                                                        <h5><strong>Top</strong></h5>
                                                    </td>
                                                </tr>
                                                @if(isset($topSuppliers[0]))
                                                @foreach($topSuppliers as $key => $topSupplier)
                                                <tr>
                                                    <td style="width: 65%">
                                                        <a href="{{ url('pms/supplier/profile/'.$topSupplier->id) }}">{{ $topSupplier->name }}</a>
                                                    </td>
                                                    <td style="width: 35%" class="text-right">
                                                        {{ $topSupplier->pay_amount > 0 ? $topSupplier->pay_amount : '0.00' }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/purchase/order-list') }}">PURCHASE STATS</a></h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <canvas class="bar-charts" id="purchase-stat-chart" data-data="{{ $purchaseStats['rfp-requistions'] }},{{ $purchaseStats['quotations'] }},{{ $purchaseStats['proposals'] }},{{ $purchaseStats['purchase-orders'] }}" data-labels="Total Requisition,Total Quotations,Total Approved List,Total PO List" data-legend-position="top" data-title-text="Total Count" width="200" height="110"></canvas>
        </div>
    </div>
</div>