@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('page-css')

@endsection
<style type="text/css" media="screen">
    .bordered{
        border: 1px #ccc solid
    }
    .floating-title{
        position: absolute;
        top: -13px;
        left: 15px;
        background: white;
        padding: 0px 5px 5px 5px;
        font-weight: 500;
    }
    .card-body{
        padding-top: 20px !important;
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
@section('main-content')

<!-- WRAPPER CONTENT ----------------------------------------------------------------------------->
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
          <ul class="breadcrumb">
              <li>
                  <i class="ace-icon fa fa-home home-icon"></i>
                  <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
              </li>
              <li>
                  <a href="#">PMS</a>
              </li>
              <li class="active">{{__($title)}} List</li>
              <li class="top-nav-btn">
                  <a href="{{route('pms.supplier.index')}}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Supplier List"> <i class="las la-list"></i>List</a>
            </li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <div class="panel-body">
                    <form method="post" action="{{ route('pms.supplier.store') }}">
                    {{ csrf_field() }}

                    <nav>
                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-basic-tab" data-toggle="tab" href="#nav-basic" role="tab" aria-controls="nav-basic" aria-selected="true">Basic Information</a>
                        <a class="nav-item nav-link disabled" id="nav-address-tab" data-toggle="tab" href="#nav-address" role="tab" aria-controls="nav-address" aria-selected="false">Address</a>
                        <a class="nav-item nav-link disabled" id="nav-contact-person-tab" data-toggle="tab" href="#nav-contact-person" role="tab" aria-controls="nav-contact-person" aria-selected="false">Contact person</a>
                        <a class="nav-item nav-link disabled" id="nav-bank-account-tab" data-toggle="tab" href="#nav-bank-account" role="tab" aria-controls="nav-bank-account" aria-selected="false">Bank Account</a>
                      </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                      <div class="tab-pane fade show active" id="nav-basic" role="tabpanel" aria-labelledby="nav-basic-tab">
                          <div class="form-row mb-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Supplier Information</h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="name">{{ __('Name') }}:</label> {!! $errors->has('name')? '<span class="text-danger text-capitalize">'. $errors->first('name').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="name" id="name" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier Name')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('name') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="phone">{{ __('Phone') }}:</label> {!! $errors->has('phone')? '<span class="text-danger text-capitalize">'. $errors->first('phone').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="tel" name="phone" id="phone" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier Phone')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('phone') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="mobile_no">{{ __('Mobile No') }}:</label> {!! $errors->has('mobile_no')? '<span class="text-danger text-capitalize">'. $errors->first('mobile_no').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="tel" name="mobile_no" id="mobile_no" class="form-control rounded" aria-label="Large" placeholder="{{__('Ex: 88017********')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('mobile_no') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="email">{{ __('Email') }}:</label> {!! $errors->has('email')? '<span class="text-danger text-capitalize">'. $errors->first('email').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="email" name="email" id="email" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier Email')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('email') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <p class="mb-0 font-weight-bold"><label for="tin">{{ __('Tin') }}:</label> {!! $errors->has('tin')? '<span class="text-danger text-capitalize">'. $errors->first('tin').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="tin" id="tin" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier Tin')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('tin') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <p class="mb-0 font-weight-bold"><label for="Trade">{{ __('Trade') }}:</label> {!! $errors->has('Trade')? '<span class="text-danger text-capitalize">'. $errors->first('Trade').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="Trade" id="Trade" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier Trade')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('Trade') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <p class="mb-0 font-weight-bold"><label for="bin">{{ __('Bin') }}:</label> {!! $errors->has('bin')? '<span class="text-danger text-capitalize">'. $errors->first('bin').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="bin" id="bin" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier Bin')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('bin') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <p class="mb-0 font-weight-bold"><label for="vat">{{ __('Vat') }}:</label> {!! $errors->has('vat')? '<span class="text-danger text-capitalize">'. $errors->first('vat').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="vat" id="vat" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier Vat')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('vat') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-0 font-weight-bold"><label for="website">{{ __('Website') }}:</label> {!! $errors->has('website')? '<span class="text-danger text-capitalize">'. $errors->first('website').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="website" id="website" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier Website')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('website') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <p class="mb-0 font-weight-bold"><label for="road">{{ __('Road') }}:</label> {!! $errors->has('road')? '<span class="text-danger text-capitalize">'. $errors->first('road').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="road" id="road" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier Road')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('road') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <p class="mb-0 font-weight-bold"><label for="village">{{ __('Village') }}:</label> {!! $errors->has('village')? '<span class="text-danger text-capitalize">'. $errors->first('village').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="village" id="village" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier Village')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('village') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <p class="mb-0 font-weight-bold"><label for="city">{{ __('City') }}:</label> {!! $errors->has('city')? '<span class="text-danger text-capitalize">'. $errors->first('city').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="city" id="city" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier City')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('city') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <p class="mb-0 font-weight-bold"><label for="state">{{ __('State') }}:</label> {!! $errors->has('state')? '<span class="text-danger text-capitalize">'. $errors->first('state').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="state" id="state" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier state')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('state') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <p class="mb-0 font-weight-bold"><label for="zipcode">{{ __('Zip Code') }}:</label> {!! $errors->has('zipcode')? '<span class="text-danger text-capitalize">'. $errors->first('zipcode').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="zipcode" id="zipcode" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier zipcode')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('zipcode') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <p class="mb-0 font-weight-bold"><label for="country">{{ __('Country') }}:</label> {!! $errors->has('country')? '<span class="text-danger text-capitalize">'. $errors->first('country').'</span>':'' !!}</p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="country" id="country" class="form-control rounded" aria-label="Large" placeholder="{{__('Supplier country')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('country') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p class="mb-0 font-weight-bold"><label for="address">{{ __('Address') }}:</label> {!! $errors->has('address')? '<span class="text-danger text-capitalize">'. $errors->first('address').'</span>':'' !!}</p>
                                                        <div class="form-group form-group-lg mb-3 d-">
                                                            <textarea name="address" id="address" class="form-control rounded" rows="5" placeholder="{{__('Supplier Address')}}">{!! old('address') !!}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Owner Information</h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="owner_name">{{ __('Owner Name') }}:</label> {!! $errors->has('owner_name')? '<span class="text-danger text-capitalize">'. $errors->first('owner_name').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="owner_name" id="owner_name" class="form-control rounded" aria-label="Large" placeholder="{{__('Owner Name')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('owner_name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="owner_nid">{{ __('Owner NID') }}:</label> {!! $errors->has('owner_nid')? '<span class="text-danger text-capitalize">'. $errors->first('owner_nid').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="owner_nid" id="owner_nid" class="form-control rounded" aria-label="Large" placeholder="{{__('Owner NID')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('owner_nid') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="owner_email">{{ __('Owner Phone') }}:</label> {!! $errors->has('owner_email')? '<span class="text-danger text-capitalize">'. $errors->first('owner_email').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="owner_email" id="owner_email" class="form-control rounded" aria-label="Large" placeholder="{{__('Owner Phone')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('owner_email') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="owner_contact_no">{{ __('Owner Contact No.') }}:</label> {!! $errors->has('owner_contact_no')? '<span class="text-danger text-capitalize">'. $errors->first('owner_contact_no').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="owner_contact_no" id="owner_contact_no" class="form-control rounded" aria-label="Large" placeholder="{{__('Owner Contact No.')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('owner_contact_no') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Others</h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="mb-0 font-weight-bold"><label for="term_condition">{{ __('Term & Condition') }}:</label> {!! $errors->has('term_condition')? '<span class="text-danger text-capitalize">'. $errors->first('term_condition').'</span>':'' !!}</p>
                                                <div class="form-group form-group-lg mb-3 d-">
                                                    <textarea name="term_condition" id="term_condition" class="form-control rounded summernote" rows="5" placeholder="{{__('Term & Condition Here')}}">{!! old('term_condition') !!}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="mb-0 font-weight-bold"><label for="auth_person_letter_file">{{ __('Authorization letter(pdf or jpg)') }}:</label> {!! $errors->has('auth_person_letter_file')? '<span class="text-danger text-capitalize">'. $errors->first('auth_person_letter_file').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="file" name="auth_person_letter_file" id="auth_person_letter_file" class="form-control rounded" aria-label="Large" placeholder="{{__('Authorization letter')}}" aria-describedby="inputGroup-sizing-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Payment Terms</h5>
                                        <table class="table table-striped table-bordered miw-500 dac_table mb-1" cellspacing="0" width="100%" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Payment Term') }}</th>
                                                    <th  width="15%">{{ __('Payment Percent') }}</th>
                                                    <th width="15%">{{__('Day Duration')}}</th>
                                                    <th  width="15%">{{__('Type')}}</th>
                                                    <th class="text-center">{{__('Action')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="field_wrapper">
                                            <tr>
                                                <td>
                                                    <div class="input-group input-group-md mb-12 d-">

                                                        <select name="payment_term_id[]" id="paymentTermId_1" class="form-control" style="width: 100%;">
                                                            <option value="{{ null }}">Select One</option>
                                                            @foreach($paymentTerms as $paymentTerm)
                                                                <option value="{{ $paymentTerm->id }}">{{ $paymentTerm->term}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-md mb-12 d-">

                                                        <input type="number" name="payment_percent[]" id="paymentPercent_1" class="form-control" min="1" placeholder="%" onkeypress="if(this.value.length==3) return false;" />
                                                    </div>
                                                </td>
                                                <td>

                                                    <div class="input-group input-group-md mb-12 d-">
                                                        <input type="number" name="day_duration[]" id="dayDuration_1" class="form-control" min="1" placeholder="Day" onkeypress="if(this.value.length==3) return false;" />
                                                    </div>

                                                </td>
                                                <td>
                                                    <div class="input-group input-group-md mb-12 d-">

                                                        <select name="type[]" id="type_1" class="form-control" >
                                                            <option value="{{ null }}">Select One</option>
                                                            <option value="{{\App\Models\PmsModels\SupplierPaymentTerm::ADVANCE}}">{{\App\Models\PmsModels\SupplierPaymentTerm::ADVANCE}}
                                                            </option>
                                                            <option value="{{\App\Models\PmsModels\SupplierPaymentTerm::DUE}}">{{\App\Models\PmsModels\SupplierPaymentTerm::DUE}}
                                                            </option>
                                                        </select>
                                                    </div>

                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0);" id="remove_1" class="remove_button btn btn-sm btn-danger" title="Remove" style="color:#fff;">
                                                        <i class="las la-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <a href="javascript:void(0);" class="add_button btn btn-sm btn-success mb-2" style="float: right;" title="Add More Term">
                                            <i class="las la-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="nav-address" role="tabpanel" aria-labelledby="nav-address-tab">
                          <div class="form-row mb-5">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Corporate Address</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="corporate_road">{{ __('Road') }}:</label> {!! $errors->has('corporate_road')? '<span class="text-danger text-capitalize">'. $errors->first('corporate_road').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="corporate_road" id="corporate_road" class="form-control rounded" aria-label="Large" placeholder="{{__('Road')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('corporate_road') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="corporate_village">{{ __('Village') }}:</label> {!! $errors->has('corporate_village')? '<span class="text-danger text-capitalize">'. $errors->first('corporate_village').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="corporate_village" id="corporate_village" class="form-control rounded" aria-label="Large" placeholder="{{__('Village')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('corporate_village') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label for="corporate_city">{{ __('City') }}:</label> {!! $errors->has('corporate_city')? '<span class="text-danger text-capitalize">'. $errors->first('corporate_city').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="corporate_city" id="corporate_city" class="form-control rounded" aria-label="Large" placeholder="{{__('City')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('corporate_city') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label for="corporate_zip">{{ __('Zipcode') }}:</label> {!! $errors->has('corporate_zip')? '<span class="text-danger text-capitalize">'. $errors->first('corporate_zip').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="corporate_zip" id="corporate_zip" class="form-control rounded" aria-label="Large" placeholder="{{__('Zipcode')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('corporate_zip') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label for="corporate_country">{{ __('Country') }}:</label> {!! $errors->has('corporate_country')? '<span class="text-danger text-capitalize">'. $errors->first('corporate_country').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="corporate_country" id="corporate_country" class="form-control rounded" aria-label="Large" placeholder="{{__('Country')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('corporate_country') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="mb-0 font-weight-bold"><label for="corporate_address">{{ __('Address') }}:</label> {!! $errors->has('corporate_address')? '<span class="text-danger text-capitalize">'. $errors->first('corporate_address').'</span>':'' !!}</p>
                                                <div class="form-group form-group-lg mb-3 d-">
                                                    <textarea name="corporate_address" id="Address" class="form-control rounded" rows="2" placeholder="{{__('Address')}}">{!! old('corporate_address') !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Factory Address</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="factory_road">{{ __('Road') }}:</label> {!! $errors->has('factory_road')? '<span class="text-danger text-capitalize">'. $errors->first('factory_road').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="factory_road" id="factory_road" class="form-control rounded" aria-label="Large" placeholder="{{__('Road')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('factory_road') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="factory_village">{{ __('Village') }}:</label> {!! $errors->has('factory_village')? '<span class="text-danger text-capitalize">'. $errors->first('factory_village').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="factory_village" id="factory_village" class="form-control rounded" aria-label="Large" placeholder="{{__('Village')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('factory_village') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label for="factory_city">{{ __('City') }}:</label> {!! $errors->has('factory_city')? '<span class="text-danger text-capitalize">'. $errors->first('factory_city').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="factory_city" id="factory_city" class="form-control rounded" aria-label="Large" placeholder="{{__('City')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('factory_city') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label for="factory_zip">{{ __('Zipcode') }}:</label> {!! $errors->has('factory_zip')? '<span class="text-danger text-capitalize">'. $errors->first('factory_zip').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="factory_zip" id="factory_zip" class="form-control rounded" aria-label="Large" placeholder="{{__('Zipcode')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('factory_zip') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label for="factory_country">{{ __('Country') }}:</label> {!! $errors->has('factory_country')? '<span class="text-danger text-capitalize">'. $errors->first('factory_country').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="factory_country" id="factory_country" class="form-control rounded" aria-label="Large" placeholder="{{__('Country')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('factory_country') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="mb-0 font-weight-bold"><label for="factory_address">{{ __('Address') }}:</label> {!! $errors->has('factory_address')? '<span class="text-danger text-capitalize">'. $errors->first('factory_address').'</span>':'' !!}</p>
                                                <div class="form-group form-group-lg mb-3 d-">
                                                    <textarea name="factory_address" id="Address" class="form-control rounded" rows="2" placeholder="{{__('Address')}}">{!! old('factory_address') !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="nav-contact-person" role="tabpanel" aria-labelledby="nav-contact-person-tab">
                          <div class="form-row mb-5">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Contact person (Sales)</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="contact_person_sales_name">{{ __('Name') }}:</label> {!! $errors->has('contact_person_sales_name')? '<span class="text-danger text-capitalize">'. $errors->first('contact_person_sales_name').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="contact_person_sales_name" id="contact_person_sales_name" class="form-control rounded" aria-label="Large" placeholder="{{__('Name')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('contact_person_sales_name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="contact_person_sales_designation">{{ __('Designation') }}:</label> {!! $errors->has('contact_person_sales_designation')? '<span class="text-danger text-capitalize">'. $errors->first('contact_person_sales_designation').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="contact_person_sales_designation" id="contact_person_sales_designation" class="form-control rounded" aria-label="Large" placeholder="{{__('Designation')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('contact_person_sales_designation') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="contact_person_sales_mobile">{{ __('Mobile') }}:</label> {!! $errors->has('contact_person_sales_mobile')? '<span class="text-danger text-capitalize">'. $errors->first('contact_person_sales_mobile').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="contact_person_sales_mobile" id="contact_person_sales_mobile" class="form-control rounded" aria-label="Large" placeholder="{{__('Mobile')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('contact_person_sales_mobile') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="contact_person_sales_email">{{ __('Email') }}:</label> {!! $errors->has('contact_person_sales_email')? '<span class="text-danger text-capitalize">'. $errors->first('contact_person_sales_email').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="contact_person_sales_email" id="contact_person_sales_email" class="form-control rounded" aria-label="Large" placeholder="{{__('Email')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('contact_person_sales_email') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Contact person (After Sales)</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="contact_person_after_sales_name">{{ __('Name') }}:</label> {!! $errors->has('contact_person_after_sales_name')? '<span class="text-danger text-capitalize">'. $errors->first('contact_person_after_sales_name').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="contact_person_after_sales_name" id="contact_person_after_sales_name" class="form-control rounded" aria-label="Large" placeholder="{{__('Name')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('contact_person_after_sales_name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="contact_person_after_sales_designation">{{ __('Designation') }}:</label> {!! $errors->has('contact_person_after_sales_designation')? '<span class="text-danger text-capitalize">'. $errors->first('contact_person_after_sales_designation').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="contact_person_after_sales_designation" id="contact_person_after_sales_designation" class="form-control rounded" aria-label="Large" placeholder="{{__('Designation')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('contact_person_after_sales_designation') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="contact_person_after_sales_mobile">{{ __('Mobile') }}:</label> {!! $errors->has('contact_person_after_sales_mobile')? '<span class="text-danger text-capitalize">'. $errors->first('contact_person_after_sales_mobile').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="contact_person_after_sales_mobile" id="contact_person_after_sales_mobile" class="form-control rounded" aria-label="Large" placeholder="{{__('Mobile')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('contact_person_after_sales_mobile') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label for="contact_person_after_sales_email">{{ __('Email') }}:</label> {!! $errors->has('contact_person_after_sales_email')? '<span class="text-danger text-capitalize">'. $errors->first('contact_person_after_sales_email').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="contact_person_after_sales_email" id="contact_person_after_sales_email" class="form-control rounded" aria-label="Large" placeholder="{{__('Email')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('contact_person_after_sales_email') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="nav-bank-account" role="tabpanel" aria-labelledby="nav-bank-account-tab">
                          <div class="form-row mb-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Bank Account</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label for="bank_account_name">{{ __('Account Name') }}:</label> {!! $errors->has('bank_account_name')? '<span class="text-danger text-capitalize">'. $errors->first('bank_account_name').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="bank_account_name" id="bank_account_name" class="form-control rounded" aria-label="Large" placeholder="{{__('Account Name')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('bank_account_name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label for="bank_account_number">{{ __('Account Number') }}:</label> {!! $errors->has('bank_account_number')? '<span class="text-danger text-capitalize">'. $errors->first('bank_account_number').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="bank_account_number" id="bank_account_number" class="form-control rounded" aria-label="Large" placeholder="{{__('Account Number')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('bank_account_number') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label for="bank_swift_code">{{ __('Swift Code') }}:</label> {!! $errors->has('bank_swift_code')? '<span class="text-danger text-capitalize">'. $errors->first('bank_swift_code').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="bank_swift_code" id="bank_swift_code" class="form-control rounded" aria-label="Large" placeholder="{{__('Swift Code')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('bank_swift_code') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="bank_name">{{ __('Bank Name') }}:</label> {!! $errors->has('bank_name')? '<span class="text-danger text-capitalize">'. $errors->first('bank_name').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="bank_name" id="bank_name" class="form-control rounded" aria-label="Large" placeholder="{{__('Bank Name')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('bank_name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="bank_branch">{{ __('Branch') }}:</label> {!! $errors->has('bank_branch')? '<span class="text-danger text-capitalize">'. $errors->first('bank_branch').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="text" name="bank_branch" id="bank_branch" class="form-control rounded" aria-label="Large" placeholder="{{__('Branch')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('bank_branch') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <p class="mb-0 font-weight-bold"><label for="bank_currency">{{ __('Currency') }}:</label> {!! $errors->has('bank_currency')? '<span class="text-danger text-capitalize">'. $errors->first('bank_currency').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="bank_currency" id="bank_currency" class="form-control rounded">
                                                        <option>USD</option>
                                                        <option>BDT</option>
                                                        <option>EURO</option>
                                                        <option>YEN</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label for="bank_security_check_file">{{ __('Bank Guarantee (Security Check)') }}:</label> {!! $errors->has('bank_security_check_file')? '<span class="text-danger text-capitalize">'. $errors->first('bank_security_check_file').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="file" name="bank_security_check_file" id="bank_security_check_file" class="form-control rounded" aria-label="Large" placeholder="{{__('Bank Guarantee (Security Check)')}}" aria-describedby="inputGroup-sizing-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary rounded" >{{ __('Save Supplier') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- END WRAPPER CONTENT ------------------------------------------------------------------------->

<!-- END Modal ------------------------------------------------------------------------->

@endsection

@section('page-script')
<script>
    (function ($) {
        "use script";


        $(document).ready(function(){
            var maxField = "{{count($paymentTerms)}}";
            var addButton = $('.add_button');
            var x = 1;
            var wrapper = $('.field_wrapper');

            $(addButton).click(function(){
                if(x < maxField) {
                    x++;
                    var fieldHTML = '<tr>\n' +
                        '                                    <td>\n' +
                        '                                        <div class="input-group input-group-md mb-12 d-">\n' +
                        '\n' +
                        '                                            <select name="payment_term_id[]" id="paymentTermId_' + x + '" class="form-control" style="width: 100%;">\n' +
                        '                                                <option value="{{ null }}">Select One</option>\n' +
                        '                                                @foreach($paymentTerms as $paymentTerm)\n' +
                        '                                                    <option value="{{ $paymentTerm->id }}">{{ $paymentTerm->term}}</option>\n' +
                        '                                                @endforeach\n' +
                        '                                            </select>\n' +
                        '\n' +
                        '                                        </div>\n' +
                        '                                    </td>\n' +
                        '                                    <td>\n' +
                        '\n' +
                        '                                        <div class="input-group input-group-md mb-12 d-">\n' +

                        '<input type="number" name="payment_percent[]" id="paymentPercent_'+x+'" class="form-control" min="1" placeholder="%" onkeypress="if(this.value.length==2) return false;" /></div>\n' +
                        '                                    </td>\n' +
                        '                                    <td>\n' +
                        '                                        <div class="input-group input-group-md mb-12 d-">\n' +
                        '<input type="number" name="day_duration[]" id="dayDuration_'+x+'" class="form-control" min="1" placeholder="Day" onkeypress="if(this.value.length==3) return false;" /></div>\n' +
                        '                                    </td>\n' +
                        '                                    <td>\n' +
                        '                                        <div class="input-group input-group-md mb-12 d-">\n' +
                        '\n' +
                        '                                            <select name="type[]" id="type_' + x + '" class="form-control">\n' +
                        '                                                <option value="{{ null }}">Select One</option>\n' +
                        '\n' +
                        '                                                    <option value="{{\App\Models\PmsModels\SupplierPaymentTerm::ADVANCE}}">{{\App\Models\PmsModels\SupplierPaymentTerm::ADVANCE}}\n' +
                        '                                                    </option>\n' +
                        '                                                    <option value="{{\App\Models\PmsModels\SupplierPaymentTerm::DUE}}">{{\App\Models\PmsModels\SupplierPaymentTerm::DUE}}\n' +
                        '                                                    </option>\n' +
                        '                                            </select>\n' +
                        '\n' +
                        '                                        </div>\n' +
                        '\n' +
                        '                                    </td>\n' +
                        '                                    <td class="text-center">\n' +
                        '                                        <a href="javascript:void(0);" id="remove_1" class="remove_button btn btn-sm btn-danger" title="Remove" style="color:#fff;">\n' +
                        '                                            <i class="las la-trash"></i>\n' +
                        '                                        </a>\n' +
                        '                                    </td>\n' +
                        '                                </tr>';

                    $(wrapper).append(fieldHTML);
                    $('#paymentTermId_' + x, wrapper).select2();
                }

            });

            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();

//                var incrementNumber = $(this).attr('id').split("_")[1];
//                var productVal=$('#product_'+incrementNumber).val()
//
//                const index = selectedProductIds.indexOf(productVal);
//                if (index > -1) {
//                    selectedProductIds.splice(index, 1);
//                }

                $(this).parent('td').parent('tr').remove();

            });

        });


        showEmptyTable();

        const showAlert = (status, error) => {
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
                if(value) form.reset();
            });
        };

    })(jQuery)
</script>
@endsection
