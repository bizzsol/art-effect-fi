@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
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
                        <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i
                                    class="las la-arrow-left"></i> Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-3">
                    <div class="panel-boby p-3">
                        <div class="row export-table">
                            <div class="col-md-12">
                                <div class="row pr-3 pl-2">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            @if(isset($entry->purchaseOrder->id))
                                                <td style="width:  20%;border-top: none !important">Supplier:</td>
                                                <td style="width:  30%;border-top: none !important" colspan="2">Purchase
                                                    Order:
                                                </td>
                                            @endif
                                            <td style="width:  30%;border-top: none !important">Type:</td>
                                            <td style="width:  20%;border-top: none !important">
                                                @if($entry->is_advance == 1)
                                                    Advance Category:
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            @if(isset($entry->purchaseOrder->id))
                                                <td style="width:  20%;border-top: none !important">
                                                    <strong>{{ $entry->purchaseOrder->purchaseOrder->relQuotation->relSuppliers->name.' ('.$entry->purchaseOrder->purchaseOrder->relQuotation->relSuppliers->phone.')' }}</strong>
                                                </td>
                                                <td style="width:  30%;border-top: none !important" colspan="2">
                                                    <strong>{{ $entry->purchaseOrder->purchaseOrder->reference_no.' ('.date('Y-m-d', strtotime($entry->purchaseOrder->purchaseOrder->po_date)).')' }}</strong>
                                                </td>
                                            @endif
                                            <td style="width:  30%;border-top: none !important">
                                                <strong>{{ $entry->entryType ? $entry->entryType->name : '' }} @if(isset($entry->purchaseOrder->type))
                                                        ({{ ucwords(str_replace('-', ' ', $entry->purchaseOrder->type)) }}
                                                        )
                                                    @endif</strong>
                                            </td>
                                            <td style="width:  20%;border-top: none !important">
                                                @if($entry->is_advance == 1)
                                                    <strong>{{ $entry->advanceCategory ? '['.$entry->advanceCategory->code.'] '.$entry->advanceCategory->name : '' }}</strong>
                                                @endif
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td style="width: 15%;border-top: none !important">Code:</td>
                                            @if($entry->number != $entry->code)
                                                <td style="width: 15%;border-top: none !important">Reference:</td>
                                            @endif
                                            <td style="width: 15%;border-top: none !important">Date:</td>
                                            <td style="width: 15%;border-top: none !important">Company:</td>
                                            <td style="width: 20%;border-top: none !important">Fiscal Year:</td>
                                            <td style="width: 20%;border-top: none !important">Status:</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;border-top: none !important">
                                                <strong>{{ $entry->code }}</strong>
                                            </td>
                                            @if($entry->number != $entry->code)
                                                <td style="width: 15%;border-top: none !important">
                                                    <strong>{{ $entry->number }}</strong>
                                                </td>
                                            @endif
                                            <td style="width: 15%;border-top: none !important">
                                                <strong>{{ $entry->date }}</strong>
                                            </td>
                                            <td style="width: 15%;border-top: none !important">
                                                <strong>{{ entryCompanies($entry) }}</strong>
                                            </td>
                                            <td style="width: 20%;border-top: none !important">
                                                <strong>{{ isset($entry->fiscalYear->title)?$entry->fiscalYear->title:'' }}
                                                    &nbsp;|&nbsp;{{ date('d-M-y', strtotime($entry->fiscalYear->start)).' to '.date('d-M-y', strtotime($entry->fiscalYear->end)) }}
                                                    )</strong>
                                            </td>
                                            <td style="width: 20%;border-top: none !important">
                                                @include('accounting.backend.pages.entry-approval-stage',[
                                                    'object' => $entry,
                                                    'userCostCentres' => auth()->user()->costCentres->pluck('cost_centre_id')->toArray()
                                                ])
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5 class="mb-2"><strong><i class="lar la-hand-point-right"></i>&nbsp;Original
                                                Transactions</strong></h5>
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="width: 15%">Cost Centre</th>
                                                <th style="width: 15%">Group</th>
                                                <th style="width: 20%">Ledger</th>
                                                <th style="width: 10%">Currency</th>
                                                @if(!$same)
                                                    <th style="width: 10%">Rate</th>
                                                    <th style="width: 10%">Debit</th>
                                                    <th style="width: 10%">Credit</th>
                                                @endif
                                                <th style="width: 10%">Debit ({{ $systemCurrency->code }})</th>
                                                <th style="width: 10%">Credit ({{ $systemCurrency->code }})</th>
                                                <th style="width: 10%">Narration</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($entry->items->count() > 0)
                                                @foreach($entry->items as $key => $item)
                                                    <tr>
                                                        <td>{{ isset($item->costCentre) ? '['.$item->costCentre->code.'] '.$item->costCentre->name : '' }}</td>
                                                        <td>{{ isset($item->chartOfAccount) ? '['.$item->chartOfAccount->accountGroup->code.'] '.$item->chartOfAccount->accountGroup->name : '' }}</td>
                                                        <td>
                                                            @if(transactionSource($item))
                                                                <a class="text-primary" style="cursor: pointer"
                                                                   onclick="ShowTransactionSource('{{ $item->id }}')">
                                                                    {{ isset($item->chartOfAccount) ? '['.$item->chartOfAccount->code.'] '.$item->chartOfAccount->name : '' }}
                                                                </a>
                                                            @else
                                                                {{ isset($item->chartOfAccount) ? '['.$item->chartOfAccount->code.'] '.$item->chartOfAccount->name : '' }}
                                                            @endif

                                                            {{ showSubLedger($item) }}

                                                            {!! transactionVendor($item) ? transactionVendor($item) : '' !!}
                                                        </td>
                                                        <td class="text-center">{{ $currency }}</td>
                                                        @if(!$same)
                                                            <td class="text-right">{{ systemMoneyFormat($exchangeRate) }}</td>
                                                            <td class="text-right">{{ $item->debit_credit == "D" ? systemMoneyFormat($item->amount) : '' }}</td>
                                                            <td class="text-right">{{ $item->debit_credit == "C" ? systemMoneyFormat($item->amount) : '' }}</td>
                                                        @endif
                                                        <td class="text-right">{{ $item->debit_credit == "D" ? systemMoneyFormat($item->amount*$exchangeRate) : '' }}</td>
                                                        <td class="text-right">{{ $item->debit_credit == "C" ? systemMoneyFormat($item->amount*$exchangeRate) : '' }}</td>
                                                        <td class="text-left">{{ $item->narration }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                            @php
                                                $total_debit = $entry->items->where('debit_credit', 'D')->sum('amount');
                                                $total_credit = $entry->items->where('debit_credit', 'C')->sum('amount');
                                                $d_deference = $total_credit > $total_debit ? (systemDoubleValue($total_credit, 2)-systemDoubleValue($total_debit, 2)) : 0;
                                                $c_deference = $total_debit > $total_credit ? (systemDoubleValue($total_debit, 2)-systemDoubleValue($total_credit, 2)) : 0;
                                            @endphp
                                            <tfoot>
                                            <tr>
                                                <td colspan="{{ !$same ? 5 : 4 }}">
                                                    <h5><strong>Total ({{ $entry->exchangeRate->currency->code }}
                                                            )</strong></h5>
                                                </td>
                                                @if(!$same)
                                                    <td style="font-weight: bold;"
                                                        class="text-right total-debit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                                        {{ systemMoneyFormat($total_debit) }}
                                                    </td>
                                                    <td style="font-weight: bold;"
                                                        class="text-right total-credit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                                        {{ systemMoneyFormat($total_credit) }}
                                                    </td>
                                                @endif
                                                <td style="font-weight: bold;"
                                                    class="text-right total-debit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                                    {{ systemMoneyFormat($total_debit*$exchangeRate) }}
                                                </td>
                                                <td style="font-weight: bold;"
                                                    class="text-right total-credit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                                    {{ systemMoneyFormat($total_credit*$exchangeRate) }}
                                                </td>
                                            </tr>
                                            @if($d_deference > 0 || $c_deference > 0)
                                                <tr>
                                                    <td colspan="{{ !$same ? 5 : 4 }}">
                                                        <h5><strong>Difference
                                                                ({{ $entry->exchangeRate->currency->code }})</strong>
                                                        </h5>
                                                    </td>
                                                    @if(!$same)
                                                        <td style="font-weight: bold;"
                                                            class="text-right debit-difference">
                                                            {{ $d_deference > 0 ? systemMoneyFormat($d_deference) : '' }}
                                                        </td>
                                                        <td style="font-weight: bold;"
                                                            class="text-right credit-difference">
                                                            {{ $c_deference > 0 ? systemMoneyFormat($c_deference) : '' }}
                                                        </td>
                                                    @endif
                                                    <td style="font-weight: bold;" class="text-right debit-difference">
                                                        {{ $d_deference > 0 ? systemMoneyFormat($d_deference*$exchangeRate) : '' }}
                                                    </td>
                                                    <td style="font-weight: bold;" class="text-right credit-difference">
                                                        {{ $c_deference > 0 ? systemMoneyFormat($c_deference*$exchangeRate) : '' }}
                                                    </td>
                                                </tr>
                                            @endif
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <p>In words ({{ $entry->exchangeRate->currency->code }}):
                                                        <strong>{{ inWordBn($entry->debit, true, $entry->exchangeRate->currency->name, $entry->exchangeRate->currency->hundreds) }}
                                                            only.</strong></p>

                                                    @if(!$same)
                                                        <p>In words ({{ $systemCurrency->code }}):
                                                            <strong>{{ inWordBn($entry->debit*$exchangeRate, true, $systemCurrency->name, $systemCurrency->hundreds) }}
                                                                only.</strong></p>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Narration:</strong></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>{{ $entry->notes }}</p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <form action="{{ route('accounting.entries.update', $entry->id) }}?reverse"
                                      method="post" accept-charset="utf-8" id="reverse-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mt-5">
                                        <div class="col-md-12">
                                            <h5 class="mb-2"><strong><i class="las la-retweet"></i>&nbsp;Reverse
                                                    Transactions</strong></h5>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    <th style="width: 15%">Cost Centre</th>
                                                    <th style="width: 15%">Group</th>
                                                    <th style="width: 20%">Ledger</th>
                                                    <th style="width: 10%">Currency</th>
                                                    @if(!$same)
                                                        <th style="width: 10%">Rate</th>
                                                        <th style="width: 10%">Debit</th>
                                                        <th style="width: 10%">Credit</th>
                                                    @endif
                                                    <th style="width: 10%">Debit ({{ $systemCurrency->code }})</th>
                                                    <th style="width: 10%">Credit ({{ $systemCurrency->code }})</th>
                                                    <th style="width: 10%">Narration</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($entry->items->count() > 0)
                                                    @foreach($entry->items as $key => $item)
                                                        <tr>
                                                            <td>{{ isset($item->costCentre) ? '['.$item->costCentre->code.'] '.$item->costCentre->name : '' }}</td>
                                                            <td>{{ isset($item->chartOfAccount) ? '['.$item->chartOfAccount->accountGroup->code.'] '.$item->chartOfAccount->accountGroup->name : '' }}</td>
                                                            <td>
                                                                @if(transactionSource($item))
                                                                    <a class="text-primary" style="cursor: pointer"
                                                                       onclick="ShowTransactionSource('{{ $item->id }}')">
                                                                        {{ isset($item->chartOfAccount) ? '['.$item->chartOfAccount->code.'] '.$item->chartOfAccount->name : '' }}
                                                                    </a>
                                                                @else
                                                                    {{ isset($item->chartOfAccount) ? '['.$item->chartOfAccount->code.'] '.$item->chartOfAccount->name : '' }}
                                                                @endif

                                                                {{ showSubLedger($item) }}

                                                                {!! transactionVendor($item) ? transactionVendor($item) : '' !!}
                                                            </td>
                                                            <td class="text-center">{{ $currency }}</td>
                                                            @if(!$same)
                                                                <td class="text-right">{{ systemMoneyFormat($exchangeRate) }}</td>
                                                                <td class="text-right">{{ $item->debit_credit == "C" ? systemMoneyFormat($item->amount) : '' }}</td>
                                                                <td class="text-right">{{ $item->debit_credit == "D" ? systemMoneyFormat($item->amount) : '' }}</td>
                                                            @endif
                                                            <td class="text-right">{{ $item->debit_credit == "C" ? systemMoneyFormat($item->amount*$exchangeRate) : '' }}</td>
                                                            <td class="text-right">{{ $item->debit_credit == "D" ? systemMoneyFormat($item->amount*$exchangeRate) : '' }}</td>
                                                            <td class="text-left">
                                                                <input type="text" name="narration[{{$item->id}}]"
                                                                       class="form-control narration"
                                                                       value="{{ $item->narration }}">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                                @php
                                                    $total_debit = $entry->items->where('debit_credit', 'D')->sum('amount');
                                                    $total_credit = $entry->items->where('debit_credit', 'C')->sum('amount');
                                                    $d_deference = $total_credit > $total_debit ? (systemDoubleValue($total_credit, 2)-systemDoubleValue($total_debit, 2)) : 0;
                                                    $c_deference = $total_debit > $total_credit ? (systemDoubleValue($total_debit, 2)-systemDoubleValue($total_credit, 2)) : 0;
                                                @endphp
                                                <tfoot>
                                                <tr>
                                                    <td colspan="{{ !$same ? 5 : 4 }}">
                                                        <h5><strong>Total ({{ $entry->exchangeRate->currency->code }}
                                                                )</strong></h5>
                                                    </td>
                                                    @if(!$same)
                                                        <td style="font-weight: bold;"
                                                            class="text-right total-credit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                                            {{ systemMoneyFormat($total_credit) }}
                                                        </td>
                                                        <td style="font-weight: bold;"
                                                            class="text-right total-debit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                                            {{ systemMoneyFormat($total_debit) }}
                                                        </td>
                                                    @endif
                                                    <td style="font-weight: bold;"
                                                        class="text-right total-credit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                                        {{ systemMoneyFormat($total_credit*$exchangeRate) }}
                                                    </td>
                                                    <td style="font-weight: bold;"
                                                        class="text-right total-debit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                                        {{ systemMoneyFormat($total_debit*$exchangeRate) }}
                                                    </td>
                                                </tr>
                                                @if($d_deference > 0 || $c_deference > 0)
                                                    <tr>
                                                        <td colspan="{{ !$same ? 5 : 4 }}">
                                                            <h5><strong>Difference
                                                                    ({{ $entry->exchangeRate->currency->code }}
                                                                    )</strong>
                                                            </h5>
                                                        </td>
                                                        @if(!$same)
                                                            <td style="font-weight: bold;"
                                                                class="text-right credit-difference">
                                                                {{ $c_deference > 0 ? systemMoneyFormat($c_deference) : '' }}
                                                            </td>
                                                            <td style="font-weight: bold;"
                                                                class="text-right debit-difference">
                                                                {{ $d_deference > 0 ? systemMoneyFormat($d_deference) : '' }}
                                                            </td>
                                                        @endif
                                                        <td style="font-weight: bold;"
                                                            class="text-right credit-difference">
                                                            {{ $c_deference > 0 ? systemMoneyFormat($c_deference*$exchangeRate) : '' }}
                                                        </td>
                                                        <td style="font-weight: bold;"
                                                            class="text-right debit-difference">
                                                            {{ $d_deference > 0 ? systemMoneyFormat($d_deference*$exchangeRate) : '' }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <p>In words ({{ $entry->exchangeRate->currency->code }}):
                                                    <strong>{{ inWordBn($entry->debit, true, $entry->exchangeRate->currency->name, $entry->exchangeRate->currency->hundreds) }}
                                                        only.</strong></p>

                                                @if(!$same)
                                                    <p>In words ({{ $systemCurrency->code }}):
                                                        <strong>{{ inWordBn($entry->debit*$exchangeRate, true, $systemCurrency->name, $systemCurrency->hundreds) }}
                                                            only.</strong></p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="notes">Narration</label>
                                                <textarea name="notes" id="notes" class="form-control"
                                                          style="width: 100%">{{ $entry->notes }} :: Reversed</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-success reverse-button"><i
                                                        class="la la-check"></i>&nbsp;Post Reverse Transactions
                                            </button>
                                            <a class="btn btn-danger" href="{{ url('accounting/entries') }}"><i
                                                        class="la la-times"></i>&nbsp;Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            var form = $('#reverse-form');
            var button = $('.reverse-button');
            content = button.html();
            form.on('submit', function (e) {
                e.preventDefault();
                button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);
                $.confirm({
                    title: 'Confirm!',
                    content: '<hr class="pt-0 mt-0"><h5>Are you sure to reverse the transactions ?</h5>',
                    buttons: {
                        no: {
                            text: '<i class="la la-times"></i>&nbsp;No',
                            btnClass: 'btn-red',
                            action: function () {
                                button.html(content).prop('disabled', false);
                            }
                        },
                        yes: {
                            text: '<i class="la la-check"></i>&nbsp;Yes',
                            btnClass: 'btn-success',
                            action: function () {
                                $.ajax({
                                    url: form.attr('action'),
                                    type: form.attr('method'),
                                    dataType: 'json',
                                    data: form.serializeArray(),
                                })
                                    .done(function (response) {
                                        if (response.success) {
                                            window.open("{{ url('accounting/entries') }}", "_parent");
                                        } else {
                                            toastr.error(response.message);
                                        }

                                        button.html(content).prop('disabled', false);
                                    })
                                    .fail(function (response) {
                                        $.each(response.responseJSON.errors, function (index, error) {
                                            toastr.error(error[0]);
                                        });

                                        button.html(content).prop('disabled', false);
                                    });
                            }
                        }
                    }
                });
            });
        });
    </script>
    @include('accounting.backend.pages.approval-scripts')
@endsection
