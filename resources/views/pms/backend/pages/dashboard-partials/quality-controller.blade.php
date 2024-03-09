<div class="col-lg-12">
    <div class="iq-card">
        {{-- <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">Quality Controller</h4>
            </div>
        </div> --}}
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="iq-card">
                                    <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                                        <div class="iq-header-title">
                                            <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/grn/grn-process') }}">GATE-IN STATS</a></h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body p-0">
                                        <canvas class="bar-charts" id="gate-in-list_-chart" data-data="{{ implode(',', array_values($gateQualityControllerData['gate-in'])) }}" data-labels="{{ implode(',', array_keys($gateQualityControllerData['gate-in'])) }}" data-legend-position="top" data-title-text="Total Count" width="200" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="iq-card">
                                    <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                                        <div class="iq-header-title">
                                            <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/quality-ensure/approved-list') }}">QUALITY ENSURE APPROVED STATS</a></h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body p-0">
                                        <canvas class="bar-charts" id="approved-chart" data-data="{{ implode(',', array_values($gateQualityControllerData['approved'])) }}" data-labels="{{ implode(',', array_keys($gateQualityControllerData['approved'])) }}" data-legend-position="top" data-title-text="Total Count" width="200" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="iq-card">
                                    <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                                        <div class="iq-header-title">
                                            <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/quality-ensure/return-list') }}">QUALITY ENSURE RETURN STATS</a></h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body p-0">
                                        <canvas class="bar-charts" id="returned-chart" data-data="{{ implode(',', array_values($gateQualityControllerData['returned'])) }}" data-labels="{{ implode(',', array_keys($gateQualityControllerData['returned'])) }}" data-legend-position="top" data-title-text="Total Count" width="200" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="iq-card">
                                    <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
                                        <div class="iq-header-title">
                                            <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/quality-ensure/return-change-list') }}">QUALITY ENSURE RETRUN REPLACE STATS</a></h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body p-0">
                                        <canvas class="bar-charts" id="return-changed-chart" data-data="{{ implode(',', array_values($gateQualityControllerData['return-changed'])) }}" data-labels="{{ implode(',', array_keys($gateQualityControllerData['return-changed'])) }}" data-legend-position="top" data-title-text="Total Count" width="200" height="100"></canvas>
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