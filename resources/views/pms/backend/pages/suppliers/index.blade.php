@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('page-css')
@include('yajra.css')
@endsection

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

                <a href="{{ url('pms/supplier') }}?products" class="btn btn-sm btn-dark" data-toggle="tooltip" title="Suppliers with Product List"> <i class="la la-tasks"></i>&nbsp;Suppliers with Product List</a>

                @can('supplier-create')
                  <a href="{{route('pms.supplier.create')}}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Supplier"> <i class="las la-plus"></i>Add</a>
                @endcan
                @if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Purchase-Employee'))
                <a href="javascript:void(0)" class="btn btn-sm btn-info text-white" data-toggle="tooltip" title="Upload Supplier by xlsx file" id="uploadFile"> <i class="las la-cloud-upload-alt"></i>Upload Supplier</a>
                @endif

                @if(isset($decline) && $decline)
                    @if(auth()->user()->hasRole('Management') || auth()->user()->hasRole('Purchase-Employee') || auth()->user()->hasRole('Purchase-Department'))
                    <a href="{{url('pms/supplier')}}" class="btn btn-sm btn-success text-white" data-toggle="tooltip" title="Suppliers"> <i class="las la-list"></i>Suppliers</a>
                    @endif
                @else
                    @if(auth()->user()->hasRole('Management') || auth()->user()->hasRole('Purchase-Employee') || auth()->user()->hasRole('Purchase-Department'))
                    <a href="{{url('pms/supplier/decline-list')}}" class="btn btn-sm btn-danger text-white" data-toggle="tooltip" title="Decline Suppliers"> <i class="las la-list"></i>Declined Suppliers</a>
                    @endif
                @endif
            </li>
            
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="table-responsive">
            <div class="panel panel-info mt-2 p-2">
                <div class="panel-body">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- END Modal ------------------------------------------------------------------------->

<!-- Supplier Upload Modal Start-->
<div class="modal fade bd-example-modal-lg" id="supplierUploadModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="brandAddModalLabel">{{ __('Upload Suppliers') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <form id="brandForm"  enctype="multipart/form-data" action="{{route('pms.suppliers.import-excel')}}" method="POST">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-12 pb-4">
                            <a href="{{URL::to('upload/excel/supplier-sample.xlsx')}}" download class="btn btn-link"><i class="las la-download"></i>{{__('Click Here To Download Format File')}}</a>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold"><label for="code">{{ __('Select File for Upload') }}:</label> <code>{{ __('Expected file size is .xls , .xslx') }}</code> <span class="text-danger"></span></p>
                            <div class="input-group input-group-md mb-3">
                                <input type="file" name="supplier_file" class="form-control" required id="excelFile" placeholder="Browse Excel file"
                                       accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>
                        </div>
                        <div class="col-3">

                            <button type="submit" class="btn btn-sm btn-success text-white" style="margin-top:32px"><i class="las la-cloud-upload-alt"></i>Upload Xls File</i></button>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded pull-left" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- Supplier Upload Modal End -->
@endsection

@section('page-script')
@include('yajra.js')
<script>
    function toggleSupplierStatus(button) {
        which = button.attr('data-which');
        supplier_id = button.attr('data-supplier-id');
        approved_by = button.attr('data-approved-by');
        status = button.attr('data-status');

        var text = "Are you Sure to Proceed?";
        var buttonText = "Yes";
        var buttonClass = "bg-success";
        var buttonVisible = true;
        var buttonValue = 'confirm';
        var reverseButtonText = "No";
        var reverseButtonClass = "bg-danger";
        var reverseButtonVisible = true;
        var reverseButtonValue = 'decline';

        if(which == "status"){
            if(status == "Active"){
                text = "{{__('Once you Inactive a Supplier, It will be sent to Purchase for re-approval.')}}";
                buttonText = "Inactive";
                reverseButtonText = "Active";
                buttonClass = "bg-danger";
                buttonValue = 'decline';
                reverseButtonVisible = false;
            }else if(status == "Inactive"){
                text = "{{__('Once you Active a Supplier, It will be accesible by the system.')}}";
                buttonText = "Active";
                reverseButtonText = "Inactive";
                buttonClass = "bg-success";
                reverseButtonClass = "bg-danger";
            }
        }

        if(which == "approval"){
            if(approved_by == "sent-to-purchase" || approved_by == "purchase-declined"){
                text = "{{__('Once you Approved a Supplier, It will be sent to Management for Final approval.')}}";
                buttonText = "Approve";
                reverseButtonText = "Decline";
                buttonClass = "bg-success";
                reverseButtonClass = "bg-danger";
            }else if(approved_by == "purchase-approved" || approved_by == "management-declined"){
                text = "{{__('Once you Decline a Supplier, It will be withdrawn from the list of Management Final Approval.')}}";
                buttonText = "Approve";
                reverseButtonText = "Decline";
                buttonClass = "bg-success";
                reverseButtonClass = "bg-danger";
            }
        }

        swal({
            title: "{{__('Are you sure?')}}",
            text: text,
            icon: "warning",
            buttons: {
                cancel: {
                    text: 'Close',
                    value: 'close',
                    visible: true,
                    closeModal: true,
                    className: 'bg-dark text-white',
                },
                confirm: {
                    text: buttonText,
                    value: buttonValue,
                    visible: buttonVisible,
                    closeModal: true,
                    className:buttonClass,
                },
                deny: {
                    text: reverseButtonText,
                    value: reverseButtonValue,
                    visible: reverseButtonVisible,
                    closeModal: true,
                    className:reverseButtonClass,
                },
            },
        }).then((value) => {
            if(value == "confirm" || value == "decline"){
                $.ajax({
                    type: 'POST',
                    url: "{{ url('pms/supplier') }}/"+(supplier_id)+"/toggle?value="+value+"&which="+which,
                    dataType: 'json',
                })
                .done(function(response) {
                    console.log(response);
                    if(value == "decline"){
                        button.parent().parent().remove();
                    }else{
                        var purchase_actions = button.parent().parent().find('.purchase-actions');
                        var management_actions = button.parent().parent().find('.management-actions');
                        purchase_actions.html(response.approval_button);
                        management_actions.html(response.status_button);
                    }

                    swal({
                        icon: 'success',
                        text: response.message,
                        button: false
                    });
                    setTimeout(()=>{
                        swal.close();
                    }, 1500);
                });
            }
        });
    }
</script>
@endsection
