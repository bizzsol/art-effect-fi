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
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-2 export-table">
                <table class="table table-bordered" cellspacing="0" width="100%" id="dataTable">
                    <thead>
                        <tr class="text-center">
                         <th style="width: 10%">{{__('Date')}}</th>
                         <th style="width: 10%">{{__('Type')}}</th>
                         <th style="width: 15%">{{__('Receipt Number')}}</th>
                         <th style="width: 30%">{{__('Ledger')}}</th>
                         <th style="width: 10%">{{__('Type')}}</th>
                         <th style="width: 10%">{{__('Currency')}}</th>
                         
                         <th style="width: 7.5%">{{__('Debit')}}</th>
                         <th style="width: 7.5%">{{__('Credit')}}</th>
                         <th style="width: 20%">{{__('Actions')}}</th>
                     </tr>
                 </thead>
                 <tbody>
                    @php
                        $total_debit = 0;
                        $total_credit = 0;
                    @endphp
                    @if(isset($entryList[0]))
                    @foreach($entryList as $key => $values)
                    @php
                        $total_debit += $values->entry->debit;
                        $total_credit += $values->entry->credit;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $values->entry->date }}</td>
                        <td>{{ ucwords(str_replace('-', ' ', $values->type)) }}</td>
                        <td class="text-center">{{ $values->entry->number }}</td>
                        <td>
                            <p>Debit: {{ $values->entry->items->where('debit_credit', 'D')->pluck('chartOfAccount.code')->implode(', ') }}</p>
                            <p>Credit: {{ $values->entry->items->where('debit_credit', 'C')->pluck('chartOfAccount.code')->implode(', ') }}</p>
                        </td>
                        <td>{{ isset($values->entry->entryType) ? $values->entry->entryType->name : '' }}</td>
                        <td class="text-center">{{ $values->entry->exchangeRate->currency->code }}</td>
                        <td class="text-right">{{ systemMoneyFormat($values->entry->debit) }}</td>
                        <td class="text-right">{{ systemMoneyFormat($values->entry->credit) }}</td>
                        <td class="text-center">
                            @can('entry-view')
                            <a class="btn btn-xs btn-primary" href="{{ url('accounting/entries/'.$values->entry->id) }}"><i class="lar la-eye"></i></a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total:</strong></td>
                        <td class="text-right"><strong>{{ systemMoneyFormat($total_debit) }}</strong></td>
                        <td class="text-right"><strong>{{ systemMoneyFormat($total_credit) }}</strong></td>
                        <td class="text-right"><strong></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection

@section('page-script')
<script>
    (function ($) {
        "use script";
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
                if(value)form.reset();
            });
        };
    })(jQuery)
</script>
@endsection