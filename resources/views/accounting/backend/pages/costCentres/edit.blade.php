@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
</style>
@endsection
@section('main-content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li><a href="#">PMS</a></li>
                <li class="active">Accounts</li>
                <li class="active">{{__($title)}}</li>
                <li class="top-nav-btn">
                    <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3">
                    <form action="{{ route('accounting.cost-centres.update', $costCentre->id) }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        <div class="row pr-3">
                            <div class="col-md-3">
                                <label for="company_id"><strong>{{ __('Company') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="company_id" id="company_id" class="form-control rounded" onchange="getProfitCentres()">
                                        @foreach($companies as $key => $company)
                                            <option value="{{ $company->id }}" {{ $costCentre->profitCentre->company_id == $company->id ? 'selected' : '' }}>[{{ $company->code }}] {{ $company->name }}</option>
                                       @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="profit_centre_id"><strong>{{ __('Profit Centre') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="profit_centre_id" id="profit_centre_id" class="form-control rounded">
                                        
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="hr_department_id"><strong>{{ __('Department') }}:</strong><span class="text-danger">&nbsp;*</span></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="hr_department_id" id="hr_department_id" class="form-control rounded">
                                       
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="code"><strong>{{ __('Cost Centre Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <input type="text" name="code" id="code" class="form-control" value="{{ $costCentre->code }}">
                            </div>
                            <div class="col-md-3">
                                <label for="name"><strong>{{ __('Cost Centre Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name', $costCentre->name) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="phone"><strong>{{ __('Phone') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $costCentre->phone) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="email"><strong>{{ __('Email') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="email" name="email" id="email" value="{{ old('email', $costCentre->email) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="is_profit_centre"><strong>Is Profit Centre ?:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="is_profit_centre" id="is_profit_centre" class="form-control">
                                        <option value="0" {{ $costCentre->is_profit_centre == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ $costCentre->is_profit_centre == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="chart_of_account_id"><strong>{{ __('Ledger') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="chart_of_account_id" id="chart_of_account_id" class="form-control select-me rounded">
                                        {!! chartOfAccountsOptions([], $costCentre->chart_of_account_id, 0, getAllGroupAndLedgers()) !!}
                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-md-12">
                                <label for="users"><strong>User Control:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="users[]" id="users" class="form-control rounded" multiple data-placeholder="Choose Users...">
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row pr-3">
                            <div class="col-md-7">
                                <label for="address"><strong>{{ __('Address') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <textarea name="address" id="address" class="form-control rounded" style="min-height: 130px">{{ old('address', $costCentre->address) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="logo_file"><strong>{{ __('Logo') }}:</strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="file" name="logo_file" id="logo_file" class="form-control rounded"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="banner_file"><strong>{{ __('Banner') }}:</strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="file" name="banner_file" id="banner_file" class="form-control rounded"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/cost-centres') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Update Cost Centre</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
    getProfitCentres();
    function getProfitCentres() {
        $.ajax({
            url: "{{ url('accounting/cost-centres/create') }}?get-company-information&company_id="+$('#company_id').val(),
            type: 'GET',
            dataType: 'json',
            data: {},
        })
        .done(function(response) {
            var profit_centre_id = "{{ $costCentre->profit_centre_id }}";
            var profit_centres = '<option value="{{ null }}">Choose Profit Centre</option>';
            $.each(response.profit_centres, function(index, val) {
                profit_centres += '<option value="'+val.id+'" '+(profit_centre_id == val.id ? 'selected' : '')+'>['+val.code+'] '+val.name+'</option>';
            });

            $('#profit_centre_id').html(profit_centres).select2();

            var hr_department_id = "{{ $costCentre->hr_department_id }}";
            var departments = '<option value="{{ null }}">Choose Department</option>';
            $.each(response.departments, function(index, val) {
                departments += '<option value="'+val.hr_department_id+'" '+(hr_department_id == val.hr_department_id ? 'selected' : '')+'>['+val.hr_department_code+'] '+val.hr_department_name+'</option>';
            });
            $('#hr_department_id').html(departments).select2();

            var existedUsers = <?php echo json_encode($costCentre->costCentreUsers->pluck('user_id')->toArray()); ?>;
            var users = '';
            $.each(response.users, function(index, val) {
                users += '<option value="'+val.id+'" '+($.inArray(val.id, existedUsers) != -1 ? 'selected' : '')+'>'+val.name+'</option>';
            });
            $('#users').html(users).select2();
        });
    }
</script>
@endsection