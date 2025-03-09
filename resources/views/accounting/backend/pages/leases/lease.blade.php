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
                    <a href="{{ url('accounting/leases/'.$lease->id.'?pdf') }}" class="btn btn-sm btn-success text-white" data-toggle="tooltip" title="View Leases"> <i class="las la-download"></i>&nbsp;Download Lease Details</i></a>
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
                                    Monthly Installment Amount: <strong>{{ $lease->exchangeRate->currency->symbol.systemMoneyFormat($lease->installment_amount) }}</strong>
                                </td>

                                <td style="width: 33%;">
                                    Total Lease Payable Amount: <strong>{{ $lease->exchangeRate->currency->symbol.systemMoneyFormat($lease->total_payable_amount) }}</strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <h5 class="mt-3 mb-1"><strong>{{ ucwords($lease->pay_interval) }} Amortization Schedule</strong></h5>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%" class="text-center">{{ ucwords(str_replace('ly', '', $lease->pay_interval)) }}</th>
                                    <th style="width: 10%" class="text-center">Date</th>
                                    <th style="width: 12%" class="text-right">Beginning</th>
                                    <th style="width: 12%" class="text-right">PMT</th>
                                    <th style="width: 12%" class="text-right">Interest</th>
                                    <th style="width: 12%" class="text-right">Principal</th>
                                    <th style="width: 12%" class="text-right">Ending balance</th>
                                    <th style="width: 10%" class="text-center">Status</th>
                                    <th style="width: 10%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-right" colspan="7">{{ systemMoneyFormat($lease->amount) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                                @if($lease->schedules->count() > 0)
                                @foreach($lease->schedules as $key => $schedule)
                                <tr>
                                    <td class="text-center">{{ $schedule->serial }}</td>
                                    <td class="text-center">{{ $schedule->date }}</td>
                                    <td class="text-right">{{ systemMoneyFormat($schedule->balance) }}</td>
                                    <td class="text-right">{{ systemMoneyFormat($schedule->principle+$schedule->interest) }}</td>
                                    <td class="text-right">{{ systemMoneyFormat($schedule->interest) }}</td>
                                    <td class="text-right">{{ systemMoneyFormat($schedule->principle) }}</td>
                                    <td class="text-right">{{ systemMoneyFormat($schedule->balance-$schedule->principle) }}</td>
                                    <td class="text-center">{{ ucwords($schedule->status) }}</td>
                                    <td class="text-center">
                                        @if($schedule->status == 'planned')
                                            <a class="btn btn-xs btn-primary" onclick="Post($(this))" data-id="{{ $schedule->id }}">Post</a>
                                        @elseif($schedule->status == 'posted')
                                        @php
                                            $entry = $schedule->entries->where('log', 'installment-posted')->first()->entry;
                                        @endphp
                                            <a class="btn btn-xs btn-success" onclick="getShortDetails($(this))" data-id="{{ $entry->id }}" data-entry-type="{{ $entry->entryType->name }}" data-code="{{ $entry->code }}">Posted</a>
                                            <a class="btn btn-xs btn-primary" onclick="Pay($(this))" data-id="{{ $schedule->id }}">Pay</a>
                                        @elseif($schedule->status == 'paid')
                                        @php
                                            $post_entry = $schedule->entries->where('log', 'installment-posted')->first()->entry;
                                            $pay_entry = $schedule->entries->where('log', 'installment-paid')->first()->entry;
                                        @endphp
                                            <a class="btn btn-xs btn-success" onclick="getShortDetails($(this))" data-id="{{ $post_entry->id }}" data-entry-type="{{ $post_entry->entryType->name }}" data-code="{{ $post_entry->code }}">Posted</a>
                                            <a class="btn btn-xs btn-success" onclick="getShortDetails($(this))" data-id="{{ $pay_entry->id }}" data-entry-type="{{ $pay_entry->entryType->name }}" data-code="{{ $pay_entry->code }}">Paid</a>
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
    function Post(element) {
        var content = element.html();
        element.html('<i class="las la-spinner la-spin"></i>').prop('disabled', true);

        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure to Post ?',
            buttons: {
                yes: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function(){
                        $.ajax({
                            url: "{{ url('accounting/leases') }}/"+element.attr('data-id')+"?post",
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

    function Pay(element) {
        $.dialog({
            title: 'Pay Installment',
            content: "url:{{ url('accounting/leases') }}/"+element.attr('data-id')+"/edit",
            animation: 'scale',
            columnClass: 'col-md-12',
            closeAnimation: 'scale',
        });
    }

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
</script>
@endsection