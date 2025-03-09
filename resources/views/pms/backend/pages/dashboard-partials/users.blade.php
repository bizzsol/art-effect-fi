<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            @can('requisition-list')
                            <div class="col-md-{{(Auth::user()->hasPermissionTo('project-manage'))?3:4}}">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-receipt"></i>&nbsp;&nbsp;<a href="{{ url('pms/requisition/requisition') }}">Requisitions</a>
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-3">
                                        <canvas id="requisition-progress" class="charts" data-data="{{ implode(',', array_values($userData['requisitions'])) }}" data-labels="Draft,Pending,Approved,Processing,Delivered,Received" data-chart="pie" data-legend-position="top" data-title-text="" width="300" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            @endcan

                           

                            @can('requisition-delivered-list')
                            <div class="col-md-{{(Auth::user()->hasPermissionTo('project-manage'))?3:4}}">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-truck-loading"></i>&nbsp;&nbsp;<a href="{{ url('pms/requisition/delivered-requistion-list') }}">Delivered Requisitions</a>
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-3">
                                        <canvas id="delivered-requisitions" class="charts" data-data="{{ implode(',', array_values($userData['delivered-requisitions'])) }}" data-labels="Pending,Acknowledge,Delivered" data-chart="pie" data-legend-position="top" data-title-text="" width="300" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            @endcan
                             @can('notification-list')
                            <div class="col-md-{{(Auth::user()->hasPermissionTo('project-manage'))?3:4}}">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-bell"></i>&nbsp;&nbsp;<a href="{{ url('pms/requisition/notification-all') }}">Notifications</a>
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-3">
                                        <canvas id="notifications" class="charts" data-data="{{ implode(',', array_values($userData['notifications'])) }}" data-labels="Read,Unread" data-chart="pie" data-legend-position="top" data-title-text="" width="300" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            @endcan

                            @can('project-manage')
                            <div class="col-md-3">
                                <div class="project-card" style="height: auto !important">
                                    <div class="project-card-header">
                                        <h5 class="mb-0">
                                            <i class="las la-project-diagram"></i>&nbsp;&nbsp;<a href="{{ url('pms/dashboard-grid') }}">Projects (%)</a>
                                        </h5>
                                    </div>
                                    <div class="project-card-body pb-3">
                                        <canvas id="projects" class="charts" data-data="{{ implode(',', array_values($projectData['progresses'])) }}" data-labels="{{ implode(',', array_values($projectData['names'])) }}" data-chart="pie" data-legend-position="top" data-title-text="" width="300" height="300"></canvas>
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


