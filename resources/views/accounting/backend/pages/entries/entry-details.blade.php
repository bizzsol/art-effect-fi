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
                                <td style="width:  30%;border-top: none !important" colspan="2">Purchase Order:</td>
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
                                    <strong>{{ $entry->entryType ? $entry->entryType->name : '' }} @if(isset($entry->purchaseOrder->type)) ({{ ucwords(str_replace('-', ' ', $entry->purchaseOrder->type)) }}) @endif</strong>
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
                                <td style="width: 10%;border-top: none !important">Date:</td>
                                <td style="width: 15%;border-top: none !important">Company:</td>
                                <td style="width: 30%;border-top: none !important">Fiscal Year:</td>
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
                                <td style="width: 10%;border-top: none !important">
                                    <strong>{{ $entry->date }}</strong>
                                </td>
                                <td style="width: 15%;border-top: none !important">
                                    <strong>{{ entryCompanies($entry) }}</strong>
                                </td>
                                <td style="width: 30%;border-top: none !important">
                                    <strong>{{ isset($entry->fiscalYear->title)?$entry->fiscalYear->title:'' }}&nbsp;|&nbsp;{{ date('d-M-y', strtotime($entry->fiscalYear->start)).' to '.date('d-M-y', strtotime($entry->fiscalYear->end)) }})</strong>
                                </td>
                                <td style="width: 20%;border-top: none !important">
                                    <strong>
                                        @include('accounting.backend.pages.entry-approval-stage',[
                                            'object' => $entry
                                        ])
                                    </strong>
                                </td>   
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 15%">Cost Centre</th>
                                    <th style="width: 15%">Group</th>
                                    <th style="width: 20%">Ledger</th>
                                    <th style="width: 10%">Currency</th>
                                    @if(!$same)
                                    <th style="width: 7%">Rate</th>
                                    <th style="width: 7%">Debit</th>
                                    <th style="width: 7%">Credit</th>
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
                                            <a class="text-primary" style="cursor: pointer" onclick="ShowTransactionSource('{{ $item->id }}')">
                                                {{ isset($item->chartOfAccount) ? '['.$item->chartOfAccount->code.'] '.$item->chartOfAccount->name : '' }}
                                            </a>
                                        @else
                                            {{ isset($item->chartOfAccount) ? '['.$item->chartOfAccount->code.'] '.$item->chartOfAccount->name : '' }}
                                        @endif

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
                                $d_deference = $total_credit > $total_debit ? ($total_credit-$total_debit) : 0;
                                $c_deference = $total_debit > $total_credit ? ($total_debit-$total_credit) : 0;
                            @endphp
                            <tfoot>
                                <tr>
                                    <td colspan="{{ !$same ? 5 : 4 }}">
                                        <h5><strong>Total</strong></h5>
                                    </td>
                                    @if(!$same)
                                    <td style="font-weight: bold;" class="text-right total-debit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                        {{ systemMoneyFormat($total_debit) }}
                                    </td>
                                    <td style="font-weight: bold;" class="text-right total-credit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                        {{ systemMoneyFormat($total_credit) }}
                                    </td>
                                    @endif
                                    <td style="font-weight: bold;" class="text-right total-debit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                        {{ systemMoneyFormat($total_debit*$exchangeRate) }}
                                    </td>
                                    <td style="font-weight: bold;" class="text-right total-credit {{ $d_deference > 0 || $c_deference > 0 ? 'bg-danger' : 'bg-success' }}">
                                        {{ systemMoneyFormat($total_credit*$exchangeRate) }}
                                    </td>
                                </tr>
                                @if($d_deference > 0 || $c_deference > 0)
                                <tr>
                                    <td colspan="{{ !$same ? 5 : 4 }}">
                                        <h5><strong>Difference</strong></h5>
                                    </td>
                                    @if(!$same)
                                    <td></td>
                                    <td style="font-weight: bold;" class="text-right debit-difference">
                                        {{ $d_deference > 0 ? systemMoneyFormat($d_deference) : '' }}
                                    </td>
                                    <td style="font-weight: bold;" class="text-right credit-difference">
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
                                        <p>In words ({{ $entry->exchangeRate->currency->code }}): <strong>{{ inWordBn($entry->debit, true, $entry->exchangeRate->currency->name, $entry->exchangeRate->currency->hundreds) }} only.</strong></p>
                                        
                                        @if(!$same)
                                        <p>In words ({{ $systemCurrency->code }}): <strong>{{ inWordBn($entry->debit*$exchangeRate, true, $systemCurrency->name, $systemCurrency->hundreds) }} only.</strong></p>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <table>
                            <tbody>
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

                @if($entry->attachments->count() > 0)
                <div class="row mt-3">
                    <div class="col-md-12">
                        <table>
                            <tbody>
                                <tr>
                                    <td><strong>Attachments:</strong></td>
                                </tr>
                                <tr>
                                    <td>
                                        <ol>
                                        @foreach($entry->attachments as $attachment)
                                        <li>
                                            <a href="{{ asset($attachment->path) }}" target="_blank">{{ $attachment->name }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ $attachment->type}}&nbsp;&nbsp;|&nbsp;&nbsp;{{ formatBytes($attachment->size) }}</a>
                                        </li>
                                        @endforeach
                                        </ol>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <table class="table mt-5">
            <tbody>
                @include('accounting.backend.pages.entries.authors', [
                    'entry' => $entry,
                ])
            </tbody>
        </table>
        
        @php
            $differentView = isset($differentView) && $differentView ? true : false;
        @endphp

        @if($differentView)
            <div class="row">
                <div class="col-md-8 pl-3">
                    <div class="row">
                        <div class="col-md-3 pt-2">
                            <a class="btn btn-sm btn-block btn-success mt-1" href="{{ url('accounting/entries/'.$entry->id) }}" target="_blank"><i class="la la-print"></i>&nbsp;View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-8 pl-3">
                    <div class="row">
                        <div class="col-md-3 pt-2">
                            <a class="btn btn-sm btn-block btn-success mt-1" href="{{ url('accounting/entries/'.$entry->id.'?print') }}" target="_blank"><i class="la la-print"></i>&nbsp;Print</a>
                        </div>
                        <div class="col-md-9">
                            @can('entry-report-xls-file')
                                @include('accounting.backend.pages.reports.buttons', [
                                    'title' => $title,
                                    'url' => url('accounting/entries/'.$entry->id),
                                    'searchHide' => true,
                                    'clearHide' => true,
                                ])
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endif
 </div>
 @include('accounting.backend.pages.approval-scripts')