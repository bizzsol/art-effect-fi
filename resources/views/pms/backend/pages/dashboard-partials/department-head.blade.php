<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading">USER REQUISITION REQUEST </h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            @can('project-manage')
                            <div class="col-md-3">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-truck-loading"></i>&nbsp;&nbsp;<a href="{{ url('pms/requisition/list-view') }}">USERS REQUISITIONS</a>
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-3">
                                        <canvas id="delivered-requisitions" class="charts" data-data="{{ implode(',', array_values($userData['user-requisitions'])) }}" data-labels="Pending,Acknowledge,Halt" data-chart="doughnut" data-legend-position="top" data-title-text="" width="300" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            @endcan
                        
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
