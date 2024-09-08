@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
    .select2-container--default .select2-results__option[aria-disabled=true] {
        color: #000 !important;
        font-weight:  bold !important;
    }

    .bordered{
        border: 1px #ccc solid
    }

    .floating-title{
        position: absolute;
        top: -18px;
        left: 15px;
        padding: 5px 20px 5px 5px;
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .card{
        margin-top: 35px !important;
    }
    .card-body{
        padding-top: 25px !important;
        padding-bottom: 0px !important;
    }

    .label{
        font-weight:  bold !important;
    }

    .tab-pane{
        padding-top: 15px;
    }

    .select2-container{
        width:  100% !important;
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
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3">
                    <form action="{{ route('accounting.cost-centres-access.store') }}" method="post" accept-charset="utf-8">
                    @csrf
                        <div class="row pr-3 pt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_id"><strong>Choose User</strong></label>
                                    <select name="user_id" id="user_id" class="form-control" onchange="getCompanies();">
                                        <option value="{{ null }}">Choose User</option>
                                        @if(isset($users[0]))
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request()->get('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="company_id"><strong>Choose Company</strong></label>
                                    <select name="company_id" id="company_id" class="form-control">
                                        <option value="{{ null }}">Choose Company</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group pt-4">
                                    <button class="btn btn-success btn-sm btn-block mt-2" type="button" onclick="window.open('{{ url('accounting/cost-centres-access') }}?user_id='+$('#user_id').val()+'&company_id='+$('#company_id').val(), '_parent')"><i class="las la-search"></i>&nbsp;Search</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group pt-4">
                                    <a class="btn btn-danger btn-sm btn-block mt-2" href="{{ url('accounting/cost-centres-access') }}"><i class="las la-times"></i>&nbsp;Reset</a>
                                </div>
                            </div>
                        </div>
                        @php
                            $thisUserCostCentres = isset($thisUser->id) ? $thisUser->costCentres->pluck('cost_centre_id')->toArray() : [];
                        @endphp
                        @if(isset($company->profitCentres[0]))
                        <div class="row pt-4">
                            <div class="col-md-12">
                                <label style="cursor: pointer;">
                                    <input type="checkbox" onchange="checkAll($(this))"  style="cursor: pointer;">&nbsp;<strong>All Cost Centres</strong>
                                </label>
                            </div>
                            @foreach($company->profitCentres as $profitCentre)
                            <div class="col-md-4">
                                <label style="cursor: pointer;">
                                    <input type="checkbox" class="profit-centres"  onchange="checkAllProfitCentre($(this), '{{ $profitCentre->id }}')" style="cursor: pointer;" {{ $profitCentre->costCentres->count() > 0 && $profitCentre->costCentres->count() == $profitCentre->costCentres->whereIn('id', $thisUserCostCentres)->count() ? 'checked' : '' }}><strong>&nbsp;[{{ $profitCentre->code }}] {{ $profitCentre->name }}</strong>
                                </label>
                                <ul class="pl-0">
                                @foreach($profitCentre->costCentres as $costCentre)
                                <li style="list-style: none;line-height: 20px">
                                    <label style="cursor: pointer;">
                                        <input type="checkbox" name="cost_centres[]" class="cost-centres cost-centres-{{ $profitCentre->id }}" value="{{ $costCentre->id }}" style="cursor: pointer;" {{ in_array($costCentre->id, $thisUserCostCentres) ? 'checked' : '' }}>&nbsp;[{{ $costCentre->code }}] {{ $costCentre->name }}
                                    </label>
                                </li>
                                @endforeach
                                </ul>
                            </div>
                            @endforeach

                            <div class="col-md-12">
                                <button class="btn btn-success btn-sm" type="submit"><i class="las la-check"></i>&nbsp;Update Cost Centre Access</button>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    getCompanies();
    function getCompanies() {
        $.ajax({
            url: "{{ url('accounting/cost-centres-access') }}?get-companies&user_id="+$('#user_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            var companies = '<option value="{{ null }}">Choose Company</option>';
            var company_id = "{{ request()->get('company_id') }}";
            $.each(response, function(index, val) {
                companies += '<option value="'+val.id+'" '+(val.id == company_id ? 'selected' : '')+'>['+val.code+'] '+val.name+'</option>';
            });

            $('#company_id').html(companies);
        });
    }

    function checkAll(element) {
        if(element.is(':checked')){
            $('.profit-centres').prop('checked', true).change();
        }else{
            $('.profit-centres').prop('checked', false).change();
        }
    }

    function checkAllProfitCentre(element, profit_centre_id) {
        if(element.is(':checked')){
            $('.cost-centres-'+profit_centre_id).prop('checked', true);
        }else{
            $('.cost-centres-'+profit_centre_id).prop('checked', false);
        }
    }
</script>
@endsection