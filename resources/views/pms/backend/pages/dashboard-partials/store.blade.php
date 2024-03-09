<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-8">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                            <div class="iq-header-title">
                                <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/store-manage/store-requisition') }}">STORE MANAGE STATS</a></h4>
                            </div>
                        </div>
                        <div class="iq-card-body p-0">
                            <canvas class="bar-charts" id="store-manage-chart" data-data="{{ implode(',', array_values($storeData['store-manage'])) }}" data-labels="{{ implode(',', array_map(function($value){
                                return ucwords(str_replace('-', ' ', $value));
                            }, array_keys($storeData['store-manage']))) }}" data-legend-position="top" data-title-text="Total Count" width="200" height="105"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                            <div class="iq-header-title">
                                <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/grn-list') }}">GRN STATS</a></h4>
                            </div>
                        </div>
                        <div class="iq-card-body p-0">
                            <canvas class="bar-charts" id="grn-chart" data-data="{{ implode(',', array_values($storeData['grn'])) }}" data-labels="{{ implode(',', array_map(function($value){
                                return ucwords(str_replace('-', ' ', $value));
                            }, array_keys($storeData['grn']))) }}" data-legend-position="top" data-title-text="Total Count" width="200" height="225"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                    <div class="iq-header-title">
                        <h4 class="card-title text-primary border-left-heading">
                            <a href="{{ url('pms/inventory/inventory-logs') }}">INVENTORY IN/OUT STATS</a></h4>
                    </div>
                </div>
                <div class="iq-card-body p-0">
                    <canvas class="charts" data-data="{{ inventoryStatus('in').','.inventoryStatus('out') }}" data-labels="In,Out" data-chart="pie" data-legend-position="top" data-title-text="All Warehouses" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">STORE Manage </h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-4 pr-0">
                                <a href="{{route('pms.store-manage.store-requisition-list')}}">
                                    <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                        <div class="feature-i iq-bg-warning">
                                            <i class="las la-list"></i>
                                        </div>
                                        <div class="feature-icon">
                                            <h5>Requisition List</h5>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4 pr-0">
                                <a href="{{route('pms.store-manage.rfp.requisition.list')}}">
                                    <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                        <div class="feature-i iq-bg-warning">
                                            <i class="lab la-buffer"></i>
                                        </div>
                                        <div class="feature-icon">
                                            <h5>RFP Requisition List</h5>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4 pr-0">
                                <a href="{{route('pms.store-manage.delivered-requisition')}}">
                                    <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                        <div class="feature-i iq-bg-warning">
                                            <i class="lab la-buffer"></i>
                                        </div>
                                        <div class="feature-icon">
                                            <h5>Pending Delivery ({{isset($pendingDelivery)?$pendingDelivery:0}})</h5>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4 pr-0">
                                <a href="{{url('pms/store-manage/delivered-requisition/delivered')}}">
                                    <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                        <div class="feature-i iq-bg-warning">
                                            <i class="lab la-buffer"></i>
                                        </div>
                                        <div class="feature-icon">
                                            <h5>Complete Delivery ({{isset($completeDelivery)?$completeDelivery:0}})</h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 pr-0">
                                <a href="{{url('pms/qce-list')}}">
                                    <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                        <div class="feature-i iq-bg-warning">
                                            <i class="lab la-buffer"></i>
                                        </div>
                                        <div class="feature-icon">
                                            <h5>QCE List ({{isset($qceList)?$qceList:0}})</h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 pr-0">
                                <a href="{{url('pms/grn-list')}}">
                                    <div class="feature-effect-box wow fadeInUp" data-wow-duration="0.6s">
                                        <div class="feature-i iq-bg-warning">
                                            <i class="lab la-buffer"></i>
                                        </div>
                                        <div class="feature-icon">
                                            <h5>GRN List ({{isset($grnList)?$grnList:0}})</h5>
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
</div> --}}

<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/inventory/inventory-stock-report') }}">INVENTORY STATS</a></h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 d-flex justify-content-center pl-0">
                                    <div class="project-card" style="height: auto !important">
                                        <div class="project-card-header">
                                            <h5><i class="las la-store-alt"></i>&nbsp;All Warehouses</h5>
                                        </div>
                                        <div class="project-card-body pb-3">
                                            <canvas class="charts" data-data="{{ inventoryStatus('in').','.inventoryStatus('out') }}" data-labels="In,Out" data-chart="pie" data-legend-position="top" data-title-text="" width="200" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $warehouses = \App\Models\PmsModels\Warehouses::has('inventoryLogs')->get();
                                @endphp
                                @if(isset($warehouses[0]))
                                @foreach($warehouses as $key => $warehouse)
                                    <div class="col-md-3 d-flex justify-content-center">
                                        <div class="project-card" style="height: auto !important">
                                            <div class="project-card-header">
                                                <h5><i class="las la-store-alt"></i>&nbsp;{{ $warehouse->name }}</h5>
                                            </div>
                                            <div class="project-card-body pb-3">
                                                <canvas class="charts" data-data="{{ inventoryStatus('in', $warehouse->id).','.inventoryStatus('out', $warehouse->id) }}" data-labels="In,Out" data-chart="pie" data-legend-position="top" data-title-text="" width="200" height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>