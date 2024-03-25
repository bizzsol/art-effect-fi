@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
</style>
@include('yajra.css')
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
                <li class="active">{{__($title)}} </li>
            </ul>
        </div>

        <div class="page-content">
{{--             <div class="panel panel-info mt-2 p-3" style="padding-bottom: 0 !important;">
                <form action="{{ url('accounting/cost-centres') }}" method="get" accept-charset="utf-8">
                    <div class="row mb-0">
                        <div class="col-md-3">
                            <div class="input-group input-group-md mb-3 d-">
                                <select name="company_id" id="company_id" class="form-control rounded">
                                   <option value="0">-All Profit Centres-</option>
                                   @foreach($companies as $key => $company)
                                       <optgroup label="[{{ $company->code }}] {{ $company->name }}">
                                           @if($company->profitCentres->count() > 0)
                                           @foreach($company->profitCentres as $profitCentre)
                                           <option value="{{ $profitCentre->id }}" {{ request()->get('profit_centre_id') == $profitCentre->id ? 'selected' : '' }}>&nbsp;&nbsp;[{{ $profitCentre->code }}] {{ $profitCentre->name }}</option>
                                           @endforeach
                                           @endif
                                       </optgroup>
                                   @endforeach
                               </select>
                           </div>
                       </div>
                       <div class="col-md-2">
                            <div class="input-group input-group-md mb-3 d-">
                                <select name="hr_unit_id" id="hr_unit_id" class="form-control rounded">
                                    <option value="0">-All Unit-</option>
                                    @foreach($units as $key => $unit)
                                    <option value="{{ $unit->hr_unit_id }}" {{ request()->get('hr_unit_id') == $unit->hr_unit_id ? 'selected' : '' }}>{{ $unit->hr_unit_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-md mb-3 d-">
                            <select name="hr_department_id" id="hr_department_id" class="form-control rounded">
                               <option value="0">-All Department-</option>
                               @foreach($departments as $key => $department)
                               <option value="{{ $department->hr_department_id }}" {{ request()->get('hr_department_id') == $department->hr_department_id ? 'selected' : '' }}>{{ $department->hr_department_name }}</option>
                               @endforeach
                           </select>
                       </div>
                   </div>
                   <div class="col-md-3">
                        <button type="submit" class="btn btn-md btn-success"><i class="la la-search"></i>&nbsp;Search</button>
                        <a href="{{ url('accounting/cost-centres') }}" class="btn btn-md btn-danger"><i class="la la-times"></i>&nbsp;Clear</a>
                    </div>
                    <div class="col-md-2 text-right">
                        @can('cost-centre-create')
                        <a class="btn btn-sm btn-success pull-right" href="{{ url('accounting/cost-centres/create') }}" style="float: right"><i class="la la-plus"></i>&nbsp;New Cost Centre</a>
                        @endcan
                    </div>
            </div> --}}
        </form>
    </div>
    <div class="panel panel-info mt-2 p-2">
        <div class="row mb-3">
            <div class="col-md-2 offset-md-10">
                @can('cost-centre-create')
                <a class="btn btn-sm btn-success pull-right" href="{{ url('accounting/cost-centres/create') }}" style="float: right"><i class="la la-plus"></i>&nbsp;New Cost Centre</a>
                @endcan
            </div>
        </div>
        
       @include('yajra.datatable')
   </div>

</div>
</div>
</div>
@endsection

@section('page-script')
@include('yajra.js')

@endsection