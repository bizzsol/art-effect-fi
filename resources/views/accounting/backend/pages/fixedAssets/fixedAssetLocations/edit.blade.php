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
                    <form action="{{ route('accounting.fixed-asset-locations.update', $faLocation->id) }}" method="post" accept-charset="utf-8">
                    @csrf
                    @method('PUT')
                        <div class="row pr-3">
                            <div class="col-md-2 col-sm-12">
                                <label for="code"><strong>{{ __('Location Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="code" id="code" value="{{ old('code',$faLocation->code) }}" class="form-control rounded" readonly>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="unit_id"><strong>{{ __('Unit') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        @if(isset($units[0]))
                                        @foreach($units as $key => $unit)
                                        <option value="{{ $unit->hr_unit_id }}" {{ $unit->hr_unit_id == $faLocation->unit_id ? 'selected' : ''  }}>{{ $unit->hr_unit_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="category_id"><strong>{{ __('Category') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="category_id" id="category_id" class="form-control">
                                        @if(isset($categories[0]))
                                        @foreach($categories as $key => $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $faLocation->category_id ? 'selected' : ''  }}>{{ $category->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="name"><strong>{{ __('Location Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name',$faLocation->name) }}" class="form-control rounded">
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <label for="address"><strong>{{ __('Address') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <textarea name="address" id="address" class="form-control rounded">{{ old('address',$faLocation->address) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="contact_for_deliveries"><strong>{{ __('Contact For Deliveries') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="contact_for_deliveries" id="contact_for_deliveries" value="{{ old('contact_for_deliveries',$faLocation->contact_for_deliveries) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="phone"><strong>{{ __('Telephone No.') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="phone" id="phone" value="{{ old('phone',$faLocation->phone) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="mobile"><strong>{{ __('Secondary Phone No') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="mobile" id="mobile" value="{{ old('mobile',$faLocation->mobile) }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="email"><strong>{{ __('Email') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="email" name="email" id="email" value="{{ old('email',$faLocation->email) }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/fixed-asset-locations') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Update Fixed Asset Locations</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection