@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
</style>
@include('yajra.css')
@endsection
@section('main-content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('pms.dashboard') }}">Home</a>
                </li>
                <li><a href="#">PMS</a></li>
                <li class="active">Accounts</li>
                <li class="active">{{ $title }}</li>

                <li class="top-nav-btn">
                    <a href="{{ url('accounting/lease-depreciations/'.$lease->id.'?pdf') }}" class="btn btn-sm btn-success text-white" data-toggle="tooltip" title="View Leases"> <i class="las la-download"></i>&nbsp;Download Lease Depreciations</i></a>
                    <a href="{{ url('accounting/leases') }}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="View Leases"> <i class="las la-eye"></i>&nbsp;View Leases</i></a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-2">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <td style="width: 33%;">
                                    Company: <strong>{{ '['.$lease->costCentre->profitCentre->company->code.'] '.$lease->costCentre->profitCentre->company->name }}</strong>
                                </td>

                                <td style="width: 33%;">
                                    Profit Centre: <strong>{{ '['.$lease->costCentre->profitCentre->code.'] '.$lease->costCentre->profitCentre->name }}</strong>
                                </td>

                                <td style="width: 33%;">
                                    Cost Centre: <strong>{{ '['.$lease->costCentre->code.'] '.$lease->costCentre->name }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 67%;" colspan="2">
                                    Vendor: <strong>{{ '['.$lease->supplier->code.'] '.$lease->supplier->name }}</strong>, Contract ID: <strong>{{ $lease->contract_id }}</strong>, Reference: <strong>{{ $lease->contract_reference }}</strong>
                                </td>
                                <td style="width: 33%;">
                                    Interest Rate: <strong>{{ $lease->rate.'%' }}</strong> for <strong>{{ $lease->year }}</strong> years, <strong>{{ ucwords($lease->pay_interval) }}</strong> Installments
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 33%;">
                                    Lease Amount: <strong>{{ $lease->exchangeRate->currency->symbol.systemMoneyFormat($lease->amount) }}</strong>
                                </td>

                                <td style="width: 33%;">
                                    Depreciated Amount: <strong>{{ $lease->exchangeRate->currency->symbol.systemMoneyFormat($lease->depreciations->where('status', 'depreciated')->sum('amount')) }}</strong>
                                </td>

                                <td style="width: 33%;">
                                    Lease Book Value: <strong>{{ $lease->exchangeRate->currency->symbol.systemMoneyFormat($lease->amount-$lease->depreciations->where('status', 'depreciated')->sum('amount')) }}</strong>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-12">
                        <h5 class="mt-3 mb-1"><strong>Lease Depreciations</strong></h5>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 15%" class="text-center">Date</th>
                                    <th style="width: 15%" class="text-center">From</th>
                                    <th style="width: 15%" class="text-center">To</th>
                                    <th style="width: 15%" class="text-center">Amount</th>
                                    <th style="width: 40%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($lease->depreciations->count() > 0)
                                @foreach($lease->depreciations as $key => $depreciation)
                                <tr>
                                    <td class="text-center">{{ $depreciation->date }}</td>
                                    <td class="text-center">{{ $depreciation->from }}</td>
                                    <td class="text-center">{{ $depreciation->to }}</td>
                                    <td class="text-center">{{ systemMoneyFormat($depreciation->amount) }}</td>
                                    <td class="text-center">
                                        @if($depreciation->status == 'pending')
                                            @if(strtotime(date('Y-m-d')) >= strtotime($depreciation->date))
                                                <a class="btn btn-xs btn-primary" onclick="Post($(this))" data-id="{{ $depreciation->id }}">Post Depreciation</a>
                                            @else
                                                <a class="btn btn-xs btn-warning">Pending</a>
                                            @endif
                                        @elseif($depreciation->status == 'depreciated')
                                            <a class="btn btn-xs btn-success" onclick="getShortDetails($(this))" data-id="{{ $depreciation->entry->id }}" data-entry-type="{{ $depreciation->entry->entryType->name }}" data-code="{{ $depreciation->entry->code }}">Depreciated</a>
                                        @endif
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
@endsection

@section('page-script')
<script type="text/javascript">
    function getShortDetails(element) {
        $.dialog({
            title: (element.attr('data-entry-type'))+" Voucher #"+(element.attr('data-code')),
            content: "url:{{ url('accounting/entries') }}/"+(element.attr('data-id'))+"?short-details",
            animation: 'scale',
            columnClass: 'col-md-12',
            closeAnimation: 'scale',
            backgroundDismiss: true
        });
    }

    function Post(element) {
        var content = element.html();
        element.html('<i class="las la-spinner la-spin"></i>').prop('disabled', true);

        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure to Post Depreciation ?',
            buttons: {
                yes: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function(){
                        $.ajax({
                            url: "{{ url('accounting/lease-depreciations') }}/"+element.attr('data-id'),
                            type: 'PUT',
                            dataType: 'json',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                        })
                        .done(function(response) {
                            if(response.success){
                                location.reload();
                            }else{
                                toastr.error(response.message);
                                element.html(content).prop('disabled', false);
                            }
                        });
                    }
                },
                no: {
                    text: 'No',
                    btnClass: 'btn-dark',
                    action: function(){
                        element.html(content).prop('disabled', false);
                    }
                }
            }
        });
    }
</script>
@endsection