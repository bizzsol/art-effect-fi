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
                    <form action="{{ route('accounting.entry-types.store') }}" method="post" accept-charset="utf-8">
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-2">
                                <label for="label"><strong>{{ __('Label') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="label" id="label" value="{{ old('label') }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="name"><strong>{{ __('Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="prefix"><strong>{{ __('Prefix') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="prefix" id="prefix" value="{{ old('prefix') }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="suffix"><strong>{{ __('Suffix') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="suffix" id="suffix" value="{{ old('suffix') }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row pr-3">
                            <div class="col-md-4">
                                <label for="restriction"><strong>{{ __('Restrictions') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="restriction" id="restriction" class="form-control rounded" onchange="getCode();">
                                        @foreach(entryTypeRestrictions() as $key => $restriction)
                                        <option value="{{ $key }}">{{ $restriction['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label for="description"><strong>{{ __('Description') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="companies"><strong>Companies :</strong></label>
                                  <br>
                                  @if(isset($companies[0]))
                                  @foreach($companies as $company)
                                  <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="companies_{{ $company->id }}" name="companies[]" value="{{ $company->id }}">
                                    <label for="companies_{{ $company->id }}" class="text-primary">
                                      {{ $company->name }}&nbsp;&nbsp;&nbsp;
                                    </label>
                                  </div>
                                  @endforeach
                                  @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/entry-types') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Save Entry Type</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection