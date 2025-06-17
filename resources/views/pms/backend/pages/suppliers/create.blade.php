@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('page-css')
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
@endsection
@section('main-content')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
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
        </ul>
    </div>

    <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <div class="panel-body">
                    <form method="post" action="{{ route('pms.supplier.store') }}" enctype="multipart/form-data" id="supplier-form">
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
                                        <h5 class="floating-title">Organization Information</h5>
                                        <div class="row">
                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-3', 'slug' => 'name', 'text' => ucwords('Name'), 'placeholder' => ucwords('Supplier Name'), 'required' => true
                                            ])

                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-3', 'slug' => 'phone', 'text' => ucwords('Phone'), 'placeholder' => ucwords('Supplier Phone')
                                            ])

                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-3', 'slug' => 'mobile_no', 'text' => ucwords('Mobile No'), 'placeholder' => ucwords('Supplier Mobile No'), 'required' => true
                                            ])

                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-3', 'slug' => 'email', 'text' => ucwords('Email'), 'placeholder' => ucwords('Supplier Email')
                                            ])
                                        </div>
                                        <div class="row">
                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-2', 'slug' => 'tin', 'text' => strtoupper('Tin'), 'placeholder' => ucwords('Supplier TIN')
                                            ])

                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-2', 'slug' => 'trade', 'text' => ucwords('Trade'), 'placeholder' => ucwords('Supplier Trade'), 'required' => true
                                            ])

                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-2', 'slug' => 'bin', 'text' => strtoupper('Bin'), 'placeholder' => ucwords('Supplier BIN'),
                                            ])

                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-2', 'slug' => 'vat', 'text' => strtoupper('Vat'), 'placeholder' => ucwords('Supplier VAT')
                                            ])

                                            @include('pms.backend.pages.suppliers.element',[
                                                    'div' => 'col-md-4', 'slug' => 'website', 'text' => ucwords('website'), 'placeholder' => ucwords('Supplier website'),
                                                ])
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
                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-3', 'slug' => 'owner_name', 'text' => ucwords('name'), 'placeholder' => ucwords('Owner Name')
                                            ])

                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-3', 'slug' => 'owner_nid', 'text' => ucwords('NID'), 'placeholder' => ucwords('Owner NID')
                                            ])

                                            @include('pms.backend.pages.suppliers.element',[
                                                'div' => 'col-md-3', 'slug' => 'owner_contact_no', 'text' => ucwords('contact no'), 'placeholder' => ucwords('Owner contact no')
                                            ])

                                            <div class="col-md-3">
                                                <p class="mb-0 font-weight-bold"><label for="owner_photo_file">{{ __('Photo') }}:</label> {!! $errors->has('owner_photo_file')? '<span class="text-danger text-capitalize">'. $errors->first('owner_photo_file').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="file" name="owner_photo_file" id="owner_photo_file" class="form-control rounded" aria-label="Large" placeholder="{{__('Photo')}}" aria-describedby="inputGroup-sizing-sm">
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
                                        <h5 class="floating-title">Product Information</h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><strong>Sub Categories</strong></label>
                                                    {!! $errors->has('sub_categories')? '<span class="text-danger text-capitalize">'. $errors->first('sub_categories').'</span>':'' !!}
                                                    <br>
                                                    @if(isset($subCategories[0]))
                                                    @foreach($subCategories as $key => $subCategory)
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="sub_categories[]" value="{{ $subCategory->id }}" id="sub-category-{{ $subCategory->id }}" class="sub_categories">
                                                        <label class="text-primary" for="sub-category-{{ $subCategory->id }}">
                                                          {{ $subCategory->name }}&nbsp;&nbsp;&nbsp;
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="row">
                                            <div class="col-md-12">
                                                <p class="mb-0 font-weight-bold"><label for="products">{{ __('Products') }}:</label> {!! $errors->has('products')? '<span class="text-danger text-capitalize">'. $errors->first('products').'</span>':'' !!}</p>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="products[]" id="products" class="form-control" multiple>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div> --}}
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
                                                    <textarea name="term_condition" id="term_condition" class="form-control rounded" rows="5" placeholder="{{__('Term & Condition Here')}}">{!! old('term_condition') !!}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="mb-0 font-weight-bold"><label for="auth_person_letter_file">{{ __('Authorization letter(pdf or jpg, Max 2 MB)') }}:</label> {!! $errors->has('auth_person_letter_file')? '<span class="text-danger text-capitalize">'. $errors->first('auth_person_letter_file').'</span>':'' !!}</p>
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
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="currencies"><strong>Currencies</strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        @if(isset($currencies[0]))
                                                        @foreach($currencies as $key => $currency)
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" name="currencies[]" value="{{ $currency->id }}" id="currency-{{ $currency->id }}" class="currencies">
                                                            <label class="text-primary" for="currency-{{ $currency->id }}">
                                                              {{ $currency->name }}&nbsp;&nbsp;&nbsp;
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                        @endif
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
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
                                                                <select name="payment_term_id[]" id="paymentTermId_1" class="form-control" style="width: 100%;" onchange="getPaymentPercentage($(this))">
                                                                    <option value="{{ null }}" data-percentage="0">Select One</option>
                                                                    @foreach($paymentTerms as $paymentTerm)
                                                                        <option value="{{ $paymentTerm->id }}" data-percentage="{{ $paymentTerm->percentage }}" data-days="{{ $paymentTerm->days }}" data-type="{{ $paymentTerm->type }}">{{ $paymentTerm->term}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-md mb-12 d-">
                                                                <input type="number" name="payment_percent[]" id="paymentPercent_1" class="form-control payment-percentages" min="1" max="100" value="0" placeholder="%" onchange="validatePaymentTerms()" onkeyup="validatePaymentTerms()" />
                                                            </div>
                                                        </td>
                                                        <td>

                                                            <div class="input-group input-group-md mb-12 d-">
                                                                <input type="number" name="day_duration[]" id="dayDuration_1" class="form-control day-durations" min="1" max="9999" placeholder="Day" onchange="validatePaymentTerms()" onkeyup="validatePaymentTerms()" />
                                                            </div>

                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-md mb-12 d-">

                                                                <select name="type[]" id="type_1" class="form-control" >
                                                                    <option value="{{ null }}">Select One</option>
                                                                    <option value="{{\App\Models\PmsModels\SupplierPaymentTerm::ADVANCE}}">Advance</option>
                                                                    <option value="{{\App\Models\PmsModels\SupplierPaymentTerm::DUE}}">Due</option>
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
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <a href="javascript:void(0);" class="add_button btn btn-sm btn-success mb-2" style="float: right;" title="Add More Term">
                                                    <i class="las la-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary rounded supplier-form-button">
                        <i class="la la-check"></i>&nbsp;Save Supplier
                    </button>
                    </form>
                </div>
            </div>
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
            $('#term_condition').summernote();

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
                        '                                            <select name="payment_term_id[]" id="paymentTermId_' + x + '" class="form-control" style="width: 100%;" onchange="getPaymentPercentage($(this))">\n' +
                        '                                                <option value="{{ null }}" data-percentage="0">Select One</option>\n' +
                        '                                                @foreach($paymentTerms as $paymentTerm)\n' +
                        '                                                    <option value="{{ $paymentTerm->id }}" data-percentage="{{ $paymentTerm->percentage }}" data-days="{{ $paymentTerm->days }}" data-type="{{ $paymentTerm->type }}">{{ $paymentTerm->term}}</option>\n' +
                        '                                                @endforeach\n' +
                        '                                            </select>\n' +
                        '\n' +
                        '                                        </div>\n' +
                        '                                    </td>\n' +
                        '                                    <td>\n' +
                        '\n' +
                        '                                        <div class="input-group input-group-md mb-12 d-">\n' +

                        '<input type="number" name="payment_percent[]" id="paymentPercent_'+x+'" class="form-control payment-percentages" min="1" max="100" placeholder="%" value="0" onchange="validatePaymentTerms()" onkeyup="validatePaymentTerms()" /></div>\n' +
                        '                                    </td>\n' +
                        '                                    <td>\n' +
                        '                                        <div class="input-group input-group-md mb-12 d-">\n' +
                        '<input type="number" name="day_duration[]" id="dayDuration_'+x+'" class="form-control day-durations" min="1" max="9999" placeholder="Day" onchange="validatePaymentTerms()" onkeyup="validatePaymentTerms()" /></div>\n' +
                        '                                    </td>\n' +
                        '                                    <td>\n' +
                        '                                        <div class="input-group input-group-md mb-12 d-">\n' +
                        '\n' +
                        '                                            <select name="type[]" id="type_' + x + '" class="form-control">\n' +
                        '                                                <option value="{{ null }}">Select One</option>\n' +
                        '\n' +
                        '                                                    <option value="{{\App\Models\PmsModels\SupplierPaymentTerm::ADVANCE}}">Advance</option>\n' +
                        '                                                    <option value="{{\App\Models\PmsModels\SupplierPaymentTerm::DUE}}">Due</option>\n' +
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
                x--;
                $(this).parent('td').parent('tr').remove();
            });

        });

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

    })(jQuery);

    function checkAllSubCategory(element, unit){
        if(element.is(':checked')){
            $('.sub-category-'+unit).prop('checked', true);
        }else{
            $('.sub-category-'+unit).prop('checked', false);
        }
    }

    validatePaymentTerms();
    function validatePaymentTerms(){
        var percentages = 0;
        $.each($('.payment-percentages'), function(index, val) {
            var max = parseInt($(this).attr('max'));

            if($(this).val().length > 3){
                $(this).val($(this).val().substring(0,3));
            }

            // if($(this).val() > max){
            //     $(this).val(0);
            // }

            percentages += parseInt($(this).val());

            // if(percentages > max){
            //     $(this).val(0);
            // }
        });

        $.each($('.day-durations'), function(index, val) {
            var max = parseInt($(this).attr('max'));

            if($(this).val().length > 4){
                $(this).val($(this).val().substring(0,4));
            }

            if($(this).val() > max){
                $(this).val(0);
            }
        });
    }

    function getPaymentPercentage(element){
        element.parent().parent().next().find('input').val(element.find(':selected').attr('data-percentage'));
        element.parent().parent().next().next().find('input').val(element.find(':selected').attr('data-days'));
        element.parent().parent().next().next().next().find('select').val(element.find(':selected').attr('data-type')).select2();
    }

    function getProducts() {
        var sub_categories = $('.sub_categories:checkbox:checked').map(function () {
          return this.value;
        }).get();
        var selected_products = $('#products option:selected').map(function(){
            return this.value;
        }).get();

        $('#products').html('').change();
        $.ajax({
            url: "{{ url('pms/supplier-category-wise-products') }}",
            type: 'POST',
            data: {sub_categories:sub_categories, selected_products:selected_products},
        })
        .done(function(response) {
            $('#products').html(response).change();
        });
    }

    $(document).ready(function() {
        var form = $('#supplier-form');
        var button = form.find('.supplier-form-button');
        form.on('submit', function(e){
            e.preventDefault();

            button.html('<i class="las la-spinner"></i>&nbsp;Please wait...').prop('disabled', true);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                data: new FormData(this),
                contentType: false,
                processData: false, 
            })
            .done(function(response) {
                if(response.success){
                    window.open(response.url, '_parent');
                }else{
                    toastr.error(response.message);
                    button.html('<i class="la la-check"></i>&nbsp;Save Supplier').prop('disabled', false);
                }
            })
            .fail(function(response) {
                $.each(response.responseJSON.errors, function(index, val) {
                    toastr.error(val[0]);
                });
                button.html('<i class="la la-check"></i>&nbsp;Save Supplier').prop('disabled', false);
            });
        });
    });
</script>
@endsection
