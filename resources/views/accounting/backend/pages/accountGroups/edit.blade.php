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
                    <form action="{{ route('accounting.account-groups.update', $group->id) }}" method="post" accept-charset="utf-8">
                    @csrf
                    @method('PUT')
                        <div class="row pr-3">
                            <div class="col-md-2">
                                <label for="account_class_id"><strong>{{ __('Class') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="account_class_id" id="account_class_id" class="form-control rounded">
                                        @if(isset($classes[0]))
                                        @foreach($classes as $key => $this_class)
                                        <option value="{{ $this_class->id }}" {{ $this_class->id == $group->account_class_id ? 'selected' : '' }}>{{ $this_class->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="parent_id"><strong>{{ __('Parent Group') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="parent_id" id="parent_id" class="form-control rounded" onchange="getCode();">
                                        <option value="0">No Parent Group</option>
                                        {!! $accountGroupOptions !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="code"><strong>{{ __('Group Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="code" id="code" class="form-control rounded" value="{{ $group->code }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="name"><strong>{{ __('Group Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name', $group->name) }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row pr-3">
                            <div class="col-md-2">
                                <label for="group_code_starts_at"><strong>{{ __('Group Code Starts From') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="group_code_starts_at" id="group_code_starts_at" value="{{ old('group_code_starts_at', $group->group_code_starts_at) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="group_code_ends_at"><strong>{{ __('Group Code Ends at') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="group_code_ends_at" id="group_code_ends_at" value="{{ old('group_code_ends_at', $group->group_code_ends_at) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="coa_code_starts_at"><strong>{{ __('Ledger Code Starts From') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="coa_code_starts_at" id="coa_code_starts_at" value="{{ old('coa_code_starts_at', $group->coa_code_starts_at) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="coa_code_ends_at"><strong>{{ __('Ledger Code Ends at') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="coa_code_ends_at" id="coa_code_ends_at" value="{{ old('coa_code_ends_at', $group->coa_code_ends_at) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-4 text-right pt-4">
                                <a class="btn btn-dark btn-md mt-2" href="{{ url('accounting/chart-of-accounts') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md mt-2"><i class="la la-save"></i>&nbsp;Update Account Group</button>
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
            url: '{{ url('accounting/account-groups') }}/'+$('#parent_id').val()+"?group_id={{ $group->id }}",
            type: 'GET',
            data: {},
        })
        .done(function(code) {
            $('#code').val(code);
        });
    }
</script>
@endsection