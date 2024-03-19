<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="iq-card">
                                    <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                                        <div class="iq-header-title">
                                            <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/grn/po-list') }}">PO</a></h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body p-0">
                                        <canvas class="bar-charts" id="po-list-chart" data-data="{{ implode(',', array_values($gateManagerData['po'])) }}" data-labels="{{ implode(',', array_keys($gateManagerData['po'])) }}" data-legend-position="top" data-title-text="Total Count" width="200" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="iq-card">
                                    <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                                        <div class="iq-header-title">
                                            <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/grn/grn-process') }}">GATE IN</a></h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body p-0">
                                        <canvas class="bar-charts" id="gate-in-list-chart" data-data="{{ implode(',', array_values($gateManagerData['gate-in'])) }}" data-labels="{{ implode(',', array_keys($gateManagerData['gate-in'])) }}" data-legend-position="top" data-title-text="Total Count" width="200" height="100"></canvas>
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