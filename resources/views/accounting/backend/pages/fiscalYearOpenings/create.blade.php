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
                    <form action="{{ route('accounting.fiscal-year-openings.store') }}" method="post" accept-charset="utf-8">
                    @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="company_id"><strong>Company</strong></label>
                                    <select name="company_id" id="company_id" class="form-control" onchange="getFiscalYears()">
                                        @if(isset($companies[0]))
                                        @foreach($companies as $key => $company)
                                        <option value="{{ $company->id }}">[{{ $company->code }}] {{ $company->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fiscal_year_id"><strong>Fiscal Year</strong></label>
                                    <select name="fiscal_year_id" id="fiscal_year_id" class="form-control">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="datetime"><strong>Datetime</strong></label>
                                    <input type="datetime-local" name="datetime" value="{{ old('datetime', date('Y-m-d H:i:s')) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reason"><strong>Reason</strong></label>
                                    <textarea name="reason" id="reason" class="form-control" rows="3" style="resize: none">{{ old('reason') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="users"><strong>User Access</strong></label>
                                    <select name="users[]" id="users" class="form-control" multiple data-placeholder="Choose Users...">
                                        @if(isset($users[0]))
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->associate_id }})</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Open this Fiscal Year</button>
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/fiscal-year-openings') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
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
    getFiscalYears();
    function getFiscalYears() {
        $.ajax({
            url: '{{ url('accounting/fiscal-year-openings/create') }}?get-fiscal-years&company_id='+$('#company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#fiscal_year_id').html(response);
        });
    }
</script>
@endsection