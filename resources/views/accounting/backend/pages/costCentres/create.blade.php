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
                    <form action="{{ route('accounting.cost-centres.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-2">
                                <label for="profit_centre_id"><strong>{{ __('Profit Centre') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="profit_centre_id" id="profit_centre_id" class="form-control rounded" onchange="getCode()">
                                        @foreach($companies as $key => $company)
                                           <optgroup label="[{{ $company->code }}] {{ $company->name }}">
                                               @if($company->profitCentres->count() > 0)
                                               @foreach($company->profitCentres as $profitCentre)
                                               <option value="{{ $profitCentre->id }}">&nbsp;&nbsp;{{ $profitCentre->name }}</option>
                                               @endforeach
                                               @endif
                                           </optgroup>
                                       @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="hr_unit_id"><strong>{{ __('Unit') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="hr_unit_id" id="hr_unit_id" class="form-control rounded">
                                        @foreach($units as $key => $unit)
                                        <option value="{{ $unit->hr_unit_id }}">{{ $unit->hr_unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="hr_department_id"><strong>{{ __('Department') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="hr_department_id" id="hr_department_id" class="form-control rounded" onchange="checkUnitDepartment()">
                                        <option value="0">N/A</option>
                                        @foreach($departments as $key => $department)
                                        <option value="{{ $department->hr_department_id }}">{{ $department->hr_department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="code"><strong>{{ __('Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <input type="text" name="code" id="code" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="name"><strong>{{ __('Cost Centre Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="phone"><strong>{{ __('Phone') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="email"><strong>{{ __('Email') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="chart_of_account_id"><strong>{{ __('Ledger') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="chart_of_account_id" id="chart_of_account_id" class="form-control select-me rounded">
                                        {!! chartOfAccountsOptions([], accountDefaultSettings()['cogs_account'], 0, getAllGroupAndLedgers()) !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row pr-3">
                            <div class="col-md-7">
                                <label for="address"><strong>{{ __('Address') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <textarea name="address" id="address" class="form-control rounded" style="min-height: 130px">{{ old('address') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="logo_file"><strong>{{ __('Logo') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="file" name="logo_file" id="logo_file" class="form-control rounded"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="banner_file"><strong>{{ __('Banner') }}:<span class="text-danger">&nbsp;*</span></strong></label>
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
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Save Cost Centre</button>
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
    getCode();
    function getCode() {
        $.ajax({
            url: '{{ url('accounting/cost-centres') }}/'+$('#profit_centre_id').val()+"?profit_centre_id="+$('#profit_centre_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(code) {
            $('#code').val(code);
        });
    }

    function showAlert(status, error){
        swal({
            icon: status,
            text: error,
            dangerMode: true,
            buttons: {
                cancel: false,
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    closeModal: true
                },
            },
        }).then((value) => {
                // if(value) form.reset();
            });
    }

    function checkUnitDepartment() {
        $.ajax({
            url: '{{ url('accounting/cost-centres/check-unit-department') }}/'+$('#profit_centre_id').val()+'/'+$('#hr_unit_id').val()+'/'+$('#hr_department_id').val(),
            type: 'GET',
            data: {},
            dataType: 'json',
            success:function (response) {
                if(!response.success){
                    showAlert('error', 'Sorry! This Company, Unit & Department have already been added. Try another combination.');
                    return;
                }
            },
        });
    }
</script>
@endsection