<div class="col-lg-3 row m-0 p-0">
        <div class="col-sm-12 mb-3">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height iq-user-profile-block" style="height: 200px;">
                <div class="iq-card-body">
                    <div class="user-details-block">
                        <div class="user-profile text-center">

                            <img class="avatar-130 img-fluid" src="{{ asset('assets/images/user/09.jpg') }} ">

                        </div>
                        <div class="text-center mt-3">
                            <h4><b>{{ auth()->user()->name }}</b></h4>
                            @if($user->employee)
                            <p class="mb-0">
                                {{ $user->employee->designation['hr_designation_name']??''}}</p>
                                <p class="mb-0">Joined {{ $user->employee['as_doj']}}</p>
                                @else
                                <p class="mb-0">Joined in ERP {{ $user->created_at}}</p>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            </div>
           
        </div>