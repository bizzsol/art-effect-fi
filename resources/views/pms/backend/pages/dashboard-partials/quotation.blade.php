@php
    $managementData = managementData();
    $total = implode(',', array_values($managementData['total']));
    $processing = implode(',', array_values($managementData['processing']));
    $approved = implode(',', array_values($managementData['approved']));
    $halt = implode(',', array_values($managementData['halt']));
@endphp
<div class="col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between p-0 bg-white">
            <div class="iq-header-title">
                <h4 class="card-title text-primary border-left-heading"><a href="{{ url('pms/quotation/approval-list') }}">{{strtoupper('Quotation Requests stats')}}</a></h4>
            </div>
        </div>
        <div class="iq-card-body p-0">
            <div class="hr-section">
                <section id="features">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-12 pr-0">
                                <canvas class="bar-charts" id="30-days-transactions" data-data="{{ $total.'|'.$processing.'|'.$approved.'|'.$halt }}" data-labels="{{ implode(',', array_values(dateRange(date('Y-m-d', strtotime('-30 days')), date('Y-m-d'), 'd-M')))  }}" data-legend-position="top" data-title-text="Total,Processing,Approved,Halt" width="200" height="65"></canvas>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>