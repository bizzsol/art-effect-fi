 @php
 $userData = userData();
 $projectData = projectData();
 $storeData = storeData();
 $purchaseStats = purchaseStats();
 $gateManagerData = gateManagerData();
 $gateQualityControllerData = gateQualityControllerData();
 @endphp

 <div class="col-lg-12 pl-0">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body pb-0">
            <div class="row"> 
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-body bg-primary rounded pt-2 pb-2 pr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0">Welcome to {{ session()->get('system-information')['name'] }}, Stay connected!</p>
                                <div class="rounded iq-card-icon bg-white">
                                    <img src="{{ asset('assets/images/page-img/37.png') }}" class="img-fluid" alt="icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                

                @if(!Auth::user()->hasRole('Super Admin') )
                @include('pms.backend.pages.dashboard-partials.users')
                @endif

                @if(Auth::user()->hasRole('Department-Head') || Auth::user()->hasRole('Super Admin'))
                @include('pms.backend.pages.dashboard-partials.department-head')
                @endif

                @if(Auth::user()->hasRole('Store-Manager')|| Auth::user()->hasRole('Store-Department') || Auth::user()->hasRole('Super Admin'))
                @include('pms.backend.pages.dashboard-partials.store')
                @include('pms.backend.pages.dashboard-partials.quality-controller')
                @endif

                @if(Auth::user()->hasRole('Purchase-Department') || Auth::user()->hasRole('Super Admin'))
                @include('pms.backend.pages.dashboard-partials.purchase')
                @include('pms.backend.pages.dashboard-partials.billing')
                @endif

                @if(Auth::user()->hasRole('Management') || Auth::user()->hasRole('Super Admin'))
                @include('pms.backend.pages.dashboard-partials.quotation')
                @endif
                @if(Auth::user()->hasRole('Gate Permission') || Auth::user()->hasRole('Super Admin'))
                @include('pms.backend.pages.dashboard-partials.gate-manager')
                @endif

                @if(Auth::user()->hasRole('Quality-Ensure') || Auth::user()->hasRole('Super Admin'))
                @include('pms.backend.pages.dashboard-partials.quality-controller')
                @endif

                @if(Auth::user()->hasRole('Billing') || Auth::user()->hasRole('Super Admin'))
                @include('pms.backend.pages.dashboard-partials.billing')
                @endif

                @if(Auth::user()->hasRole('Audit') || Auth::user()->hasRole('Super Admin'))
                @include('pms.backend.pages.dashboard-partials.audit')
                @endif
                @if(Auth::user()->hasRole('Accounts') || Auth::user()->hasRole('Super Admin') )
                @include('pms.backend.pages.dashboard-partials.accounts')
                @endif

                @include('pms.backend.pages.dashboard-partials.scripts')
            </div>
        </div>
    </div>
</div>