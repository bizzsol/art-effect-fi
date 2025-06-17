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

<div class="panel-body">
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
                        <p class="mb-0 font-weight-bold"><label class="mb-0">Tin:</label></p>
                        <h6>{{ $supplier->tin }}</h6>
                    </div>

                    <div class="col-md-2">
                        <p class="mb-0 font-weight-bold"><label class="mb-0">Trade:</label></p>
                        <h6>{{ $supplier->trade }}</h6>
                    </div>

                    <div class="col-md-2">
                        <p class="mb-0 font-weight-bold"><label class="mb-0">Bin:</label></p>
                        <h6>{{ $supplier->bin }}</h6>
                    </div>

                    <div class="col-md-2">
                        <p class="mb-0 font-weight-bold"><label class="mb-0">Vat:</label></p>
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
                    <div class="row mb-3">
                        <div class="col-md-12 mb-4">
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
                    <table class="table table-striped table-bordered miw-500 dac_table mb-3" cellspacing="0" width="100%" id="dataTable">
                        <thead>
                            <tr>
                                <th>{{ __('Payment Term') }}</th>
                                <th  width="15%">{{ __('Payment Percent') }}</th>
                                <th width="15%">{{__('Day Duration')}}</th>
                                <th  width="15%">{{__('Type')}}</th>
                            </tr>
                        </thead>
                        <tbody class="field_wrapper">
                            @forelse($supplier->relPaymentTerms as $relPaymentTerm)
                            <tr>
                                <td>{{ $relPaymentTerm->relPaymentTerm->term }}</td>
                                <td>{{$relPaymentTerm->payment_percent}}</td>
                                <td>{{$relPaymentTerm->day_duration}}</td>
                                <td>
                                    {{ ($relPaymentTerm->type==\App\Models\PmsModels\SupplierPaymentTerm::ADVANCE) == 1 ? "Advance" : "Cash" }}
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

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

    </div>

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

    </div>

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
                            <p class="mb-0 font-weight-bold"><label class="mb-0">Swift Code:</label></p>
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

        </div>
    </div>
</div>

