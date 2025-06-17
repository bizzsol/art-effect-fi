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
                  <a href="{{url('pms/supplier/'.$supplier->id.'/edit'.(request()->has('tab') ? '?tab='.request()->get('tab') : ''))}}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Edit Supplier"> <i class="las la-edit"></i>Edit</a>
                  <a href="{{route('pms.supplier.index')}}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Supplier List"> <i class="las la-list"></i>List</a>
            </li>
        </ul>
    </div>

    <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <div class="panel-body">
                    <nav>
                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-basic-tab" data-toggle="tab" href="#nav-basic" role="tab" aria-controls="nav-basic" aria-selected="true">Basic Information</a>
                        <a class="nav-item nav-link" id="nav-address-tab" data-toggle="tab" href="#nav-address" role="tab" aria-controls="nav-address" aria-selected="false">Address</a>
                        <a class="nav-item nav-link" id="nav-contact-person-tab" data-toggle="tab" href="#nav-contact-person" role="tab" aria-controls="nav-contact-person" aria-selected="false">Contact person</a>
                        <a class="nav-item nav-link" id="nav-bank-account-tab" data-toggle="tab" href="#nav-bank-account" role="tab" aria-controls="nav-bank-account" aria-selected="false">Bank Account</a>
                        <a class="nav-item nav-link" id="nav-ledgers-tab" data-toggle="tab" href="#nav-ledgers" role="tab" aria-controls="nav-ledgers" aria-selected="false">Ledgers</a>
                        <a class="nav-item nav-link" id="nav-logs-tab" data-toggle="tab" href="#nav-logs" role="tab" aria-controls="nav-logs" aria-selected="false">Logs</a>
                      </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                      <div class="tab-pane fade show active" id="nav-basic" role="tabpanel" aria-labelledby="nav-basic-tab">
                          <div class="form-row mb-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Organization Information</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Name:</label></p>
                                                <h6>{{ $supplier->name }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Phone:</label></p>
                                                <h6>{{ $supplier->phone }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Mobile No:</label></p>
                                                <h6>{{ $supplier->mobile_no }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Email:</label></p>
                                                <h6>{{ $supplier->email }}</h6>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">TIN:</label></p>
                                                <h6>{{ $supplier->tin }}</h6>
                                            </div>

                                            <div class="col-md-2">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Trade:</label></p>
                                                <h6>{{ $supplier->trade }}</h6>
                                            </div>

                                            <div class="col-md-2">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">BIN:</label></p>
                                                <h6>{{ $supplier->bin }}</h6>
                                            </div>

                                            <div class="col-md-2">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">VAT:</label></p>
                                                <h6>{{ $supplier->vat }}</h6>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Website:</label></p>
                                                <h6>{{ $supplier->website }}</h6>
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
                                        <div class="row mb-3 pt-2">
                                            @if(!empty($supplier->owner_photo))
                                            <div class="col-md-1">
                                                <img src="{{ asset($supplier->owner_photo)  }}" style="width: 100%">
                                            </div>
                                            @endif

                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Name:</label></p>
                                                <h6>{{ $supplier->owner_name }}</h6>
                                            </div>

                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">NID:</label></p>
                                                <h6>{{ $supplier->owner_nid }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Contact No:</label></p>
                                                <h6>{{ $supplier->owner_contact_no }}</h6>
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
                                        <div class="row mb-3">                                            <div class="col-md-12 mb-4">
                                                <h5 class="mb-2">Term & Condition:</h5>
                                                <div>
                                                    {!! $supplier->term_condition !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Authorization letter(pdf or jpg):</label></p>
                                                <h6 class="text-success">@if(!empty($supplier->auth_person_letter)) <a href="{{ asset($supplier->auth_person_letter)  }}" target="_blank">View Existing File</a> @endif</h6>
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
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <h6 class="mb-2">Currencies:</h6>
                                                <ul>
                                                    @if($supplier->suppleierCurrencies->count() > 0)
                                                    @foreach($supplier->suppleierCurrencies as $currency)
                                                    <li>{{ $currency->currency->name }}</li>
                                                    @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="mb-2">Payment Terms:</h6>
                                                <ul>
                                                    @if($supplier->relPaymentTerms->where('parent_id', 0)->where('source', 'profile')->count() > 0)
                                                    @foreach($supplier->relPaymentTerms->where('parent_id', 0)->where('source', 'profile') as $key => $term)
                                                        <li>{{ makePaymentTermsString($term->id) }}</li>
                                                    @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ url('pms/supplier/'.$supplier->id.'/edit') }}" class="btn btn-primary rounded ml-1 mt-4" >{{ __('Edit Supplier') }}</a>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="nav-address" role="tabpanel" aria-labelledby="nav-address-tab">
                          <div class="form-row mb-5">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Corporate Address</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Village:</label></p>
                                                <h6>{{ (isset($corporateAddress->id) ? $corporateAddress->village : '') }}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Road:</label></p>
                                                <h6>{{ (isset($corporateAddress->id) ? $corporateAddress->road : '') }}</h6>
                                            </div>

                                            
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">City:</label></p>
                                                <h6>{{ (isset($corporateAddress->id) ? $corporateAddress->city : '') }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Zip:</label></p>
                                                <h6>{{ (isset($corporateAddress->id) ? $corporateAddress->zip : '') }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Country:</label></p>
                                                <h6>{{ (isset($corporateAddress->id) ? $corporateAddress->country : '') }}</h6>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Address:</label></p>
                                                <h6>{{ (isset($corporateAddress->id) ? $corporateAddress->address : '') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Factory Address</h5>
                                        <div class="row mb-3">
                                           
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Village:</label></p>
                                                <h6>{{ (isset($factoryAddress->id) ? $factoryAddress->village : '') }}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Road:</label></p>
                                                <h6>{{ (isset($factoryAddress->id) ? $factoryAddress->road : '') }}</h6>
                                            </div>

                                            
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">City:</label></p>
                                                <h6>{{ (isset($factoryAddress->id) ? $factoryAddress->city : '') }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Zip:</label></p>
                                                <h6>{{ (isset($factoryAddress->id) ? $factoryAddress->zip : '') }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Country:</label></p>
                                                <h6>{{ (isset($factoryAddress->id) ? $factoryAddress->country : '') }}</h6>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Address:</label></p>
                                                <h6>{{ (isset($factoryAddress->id) ? $factoryAddress->address : '') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ url('pms/supplier/'.$supplier->id.'/edit') }}?tab=address" class="btn btn-primary rounded ml-1 mt-4" >{{ __('Edit Supplier Address') }}</a>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="nav-contact-person" role="tabpanel" aria-labelledby="nav-contact-person-tab">
                          <div class="form-row mb-5">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Contact person (Sales)</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Name:</label></p>
                                                <h6>{{ (isset($contactPersonSales->id) ? $contactPersonSales->name : '') }}</h6>
                                            </div>

                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Designation:</label></p>
                                                <h6>{{ (isset($contactPersonSales->id) ? $contactPersonSales->designation : '') }}</h6>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Mobile:</label></p>
                                                <h6>{{ (isset($contactPersonSales->id) ? $contactPersonSales->mobile : '') }}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Email:</label></p>
                                                <h6>{{ (isset($contactPersonSales->id) ? $contactPersonSales->email : '') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Contact person (After Sales)</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Name:</label></p>
                                                <h6>{{ (isset($contactPersonAfterSales->id) ? $contactPersonAfterSales->name : '') }}</h6>
                                            </div>

                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Designation:</label></p>
                                                <h6>{{ (isset($contactPersonAfterSales->id) ? $contactPersonAfterSales->designation : '') }}</h6>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Mobile:</label></p>
                                                <h6>{{ (isset($contactPersonAfterSales->id) ? $contactPersonAfterSales->mobile : '') }}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Email:</label></p>
                                                <h6>{{ (isset($contactPersonAfterSales->id) ? $contactPersonAfterSales->email : '') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ url('pms/supplier/'.$supplier->id.'/edit') }}?tab=contact-person" class="btn btn-primary rounded ml-1 mt-4" >{{ __('Edit Contact Person') }}</a>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="nav-bank-account" role="tabpanel" aria-labelledby="nav-bank-account-tab">
                          <div class="form-row mb-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Bank Account</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Account Name:</label></p>
                                                <h6>{{ (isset($bankAccount->id) ? $bankAccount->account_name : '') }}</h6>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Account Number:</label></p>
                                                <h6>{{ (isset($bankAccount->id) ? $bankAccount->account_number : '') }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Routing Number:</label></p>
                                                <h6>{{ (isset($bankAccount->id) ? $bankAccount->swift_code : '') }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Bank Guarantee (pdf or jpg):</label></p>
                                                <h6 class="text-success">@if(isset($bankAccount->id) && !empty($bankAccount->security_check)) <a href="{{ asset($bankAccount->security_check)  }}" target="_blank">View Existing File</a> @endif</h6>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Bank Name:</label></p>
                                                <h6>{{ (isset($bankAccount->id) ? $bankAccount->bank_name : '') }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Branch:</label></p>
                                                <h6>{{ (isset($bankAccount->id) ? $bankAccount->branch : '') }}</h6>
                                            </div>

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Currency:</label></p>
                                                <h6>{{ (isset($bankAccount->id) ? $bankAccount->currency : '') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ url('pms/supplier/'.$supplier->id.'/edit') }}?tab=bank-account" class="btn btn-primary rounded ml-1 mt-4" >{{ __('Edit Bank Account') }}</a>
                            </div>
                        </div>
                      </div>

                      <div class="tab-pane fade" id="nav-ledgers" role="tabpanel" aria-labelledby="nav-ledgers-tab">
                          <div class="form-row mb-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body bordered">
                                        <h5 class="floating-title">Supplier Ledger Accounts</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Payable Account:</label></p>
                                                @php
                                                    $payable = \App\Models\PmsModels\Accounts\ChartOfAccount::find($accountDefaultSettings['supplier_payable_account']);
                                                @endphp
                                                <h6 class="mt-2"><strong>{{ $supplier->payableAccount ? '['.$supplier->payableAccount->code.'] '.$supplier->payableAccount->name : ($payable ? '['.$payable->code.'] '.$payable->name : '') }}</strong></h6>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-0 font-weight-bold"><label class="mb-0">Advance Account:</label></p>
                                                @php
                                                    $advance = \App\Models\PmsModels\Accounts\ChartOfAccount::find($accountDefaultSettings['supplier_advance_account']);
                                                @endphp
                                                <h6 class="mt-2"><strong>{{ $supplier->advanceAccount ? '['.$supplier->advanceAccount->code.'] '.$supplier->advanceAccount->name : ($advance ? '['.$advance->code.'] '.$advance->name : '') }}</strong></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ url('pms/supplier/'.$supplier->id.'/edit') }}?tab=ledgers" class="btn btn-primary rounded ml-1 mt-4" >{{ __('Edit Supplier Ledgers') }}</a>
                            </div>
                        </div>
                      </div>

                        <div class="tab-pane fade" id="nav-logs" role="tabpanel" aria-labelledby="nav-tags-tab">
                            <div class="form-row mb-5">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body bordered">
                                            <h5 class="floating-title">Supplier Logs</h5>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <a class="btn btn-md btn-success pull-right mb-2" onclick="openAModal('New Log','{{ url('pms/supplier/'.$supplier->id.'/create-supplier-log') }}')"><i class="las la-plus-circle"></i>&nbsp;New Log</a>
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th style="width: 15%">Date</th>
                                                                <th style="width: 25%">Topic</th>
                                                                <th style="width: 50%">Log</th>
                                                                <th style="width: 10%">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(isset($logs[0]))
                                                            @foreach($logs as $key => $log)
                                                            <tr>
                                                                <td>{{ date('d-M-Y', strtotime($log->date)) }}</td>
                                                                <td>{{ $log->topic }}</td>
                                                                <td>{{ $log->log }}</td>
                                                                <td class="text-center">
                                                                    <a class="btn btn-primary btn-xs" onclick="openAModal('Edit Log','{{ url('pms/supplier/'.$log->id.'/edit-supplier-log') }}')"><i class="fa fa-edit"></i></a>
                                                                    <a class="btn btn-danger btn-xs" onclick="deleteLog($(this))" data-src="{{ url('pms/supplier/'.$log->id.'/delete-supplier-log') }}"><i class="fa fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="log-modal" tabindex="-1" role="dialog" aria-labelledby="log-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="log-modal-label"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
    (function ($) {
        "use script";

        $(document).ready(function(){
            var tab = location.href.split('?tab=')[1];
            if(tab !== undefined){
                $('.nav-tabs').find('.nav-link').removeClass('active');
                $('.nav-tabs').find('#nav-'+(tab)+'-tab').addClass('active');

                $('.tab-content').find('.tab-pane').removeClass('show active');
                $('.tab-content').find('#nav-'+(tab)).addClass('show active');
            }
        });
    })(jQuery);


    function openAModal(title, link) {
        var modal = $('#log-modal');
        modal.modal('toggle');
        modal.find('.modal-title').html(title);
        modal.find('.modal-body').html('<h3 class="text-center">Please Wait...</h3>');

        $.ajax({
            url: link,
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            modal.find('.modal-body').html(response);
        });
    }

    function deleteLog(element) {
        swal({
            title: "{{__('Are you sure?')}}",
            text: "{{__('Once you delete, You can not recover this data and related files.')}}",
            icon: "warning",
            dangerMode: true,
            buttons: {
                cancel: true,
                confirm: {
                    text: "Delete",
                    value: true,
                    visible: true,
                    closeModal: true
                },
            },
        }).then((value) => {
            if(value){
                $.ajax({
                    url: element.attr('data-src'),
                    type: 'POST',
                    data: {},
                })
                .done(function(response) {
                    if(response.success){
                        element.parent().parent().remove();
                        swal({
                            icon: 'success',
                            text: response.message,
                            button: false
                        });
                        setTimeout(()=>{
                            swal.close();
                        }, 1500);
                    }else{
                        $.notify(response.message, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
