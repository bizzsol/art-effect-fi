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
                    <form action="{{ route('accounting.cost-centre-allocations.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="allocation-form">
                    @csrf
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name"><strong>Allocation Name <span class="text-danger">*</span></strong></label>
                                    <input type="text" name="name" id="name" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="description"><strong>Allocation Description <span class="text-danger">*</span></strong></label>
                                    <input type="text" name="description" id="description" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">Type</th>
                                            <th style="width: 10%">Company</th>
                                            <th style="width: 15%">Cost Centre</th>
                                            <th style="width: 25%">Chart of Account</th>
                                            <th style="width: 25%">Counter Chart of Account</th>
                                            <th style="width: 10%">Allocation</th>
                                            <th style="width: 5%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="destinations">
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7" class="text-right">
                                                <a class="btn btn-success btn-sm text-white" onclick="addNewDestination()"><i class="las la-plus"></i>&nbsp;Add New Destination</a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/cost-centre-allocations') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md allocation-button"><i class="la la-save"></i>&nbsp;Save Cost Centre Allocation</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display: none" id="destination-company">
    @if(isset($companies[0]))
    @foreach($companies as $company)
    <option value="{{ $company->id }}">{{ $company->code }}</option>
    @endforeach
    @endif
</div>
@endsection

@section('page-script')
<script type="text/javascript">
    addNewDestination();
    function addNewDestination() {
        $('#destinations').append('<tr>'+
                                    '<td style="width: 10%">'+
                                        '<select name="types[]" class="form-control select2">'+
                                            '<option value="source">Source</option>'+
                                            '<option value="destination">Destination</option>'+
                                        '</select>'+
                                    '</td>'+
                                    '<td style="width: 10%">'+
                                        '<div>'+
                                            '<select name="companies[]" onchange="getCompanyInformation($(this))" class="form-control company select2">'+($('#destination-company').html())+'</select>'+
                                        '</div>'+
                                    '</td>'+
                                    '<td style="width: 15%">'+
                                        '<select name="cost_centres[]" class="form-control cost-centres select2"></select>'+
                                    '</td>'+
                                    '<td style="width: 25%">'+
                                        '<select name="ledgers[]" class="form-control ledgers select2"></select>'+
                                    '</td>'+
                                    '<td style="width: 25%">'+
                                        '<select name="counter_ledgers[]" class="form-control counter-ledgers select2"></select>'+
                                    '</td>'+
                                    '<td style="width: 10%">'+
                                        '<input type="number" name="allocations[]" id="allocations" value="0" min="1" max="100" class="form-control destination-allocations" />'+
                                    '</td>'+
                                    '<td style="width: 5%" class="text-center">'+
                                        '<a class="btn btn-danger btn-sm text-white" onclick="removeDestination($(this))"><i class="las la-trash"></i></a>'+
                                    '</td>'+
                                '</tr>');
        getCompanyInformation($("#destinations tr:last-child").find('.company'));
        $('.select2').select2();
    }

    function removeDestination(element) {
        element.parent().parent().remove();
    }

    function getCompanyInformation(element) {
        $.ajax({
            url: "{{ url('accounting/cost-centre-allocations/create') }}?get-company-information&company_id="+element.val(),
            type: 'GET',
            dataType: 'json',
            data: {},
        })
        .done(function(response) {
            element.parent().parent().parent().find('.cost-centres').html(response.cost_centres).change();
            element.parent().parent().parent().find('.ledgers').html(response.ledgers).change();
            element.parent().parent().parent().find('.counter-ledgers').html(response.ledgers).change();
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var form = $('#allocation-form');
        var button = form.find('.allocation-button');
        var buttonContent = button.html();

        form.submit(function(event) {
            event.preventDefault();
            button.prop('disabled', true).html('<i class="las la-spinner fa-spin"></i>&nbsp;Please wait...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                processData: false,
                contentType: false,
                data: new FormData(form[0]),
            })
            .done(function(response) {
                if(response.success){
                    location.reload();
                }else{
                    toastr.error(response.message);
                }
                button.prop('disabled', false).html(buttonContent);
            })
            .fail(function(response) {
                $.each(response.responseJSON.errors, function(index, val) {
                    toastr.error(val[0]);
                });

                button.prop('disabled', false).html(buttonContent);
            });
        });
    });
</script>
@endsection