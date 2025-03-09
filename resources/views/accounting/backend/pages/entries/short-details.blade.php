<div style="overflow: hidden">
    <div class="row" style="overflow: hidden">
        <div class="col-md-12 p-3">
            <hr class="pt-0 mt-0">
            <div class="export-table">
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
                            <strong>{{ isset($entry->fiscalYear->title)?$entry->fiscalYear->title:'' }}
                                &nbsp;|&nbsp;{{ date('d-M-y', strtotime($entry->fiscalYear->start)).' to '.date('d-M-y', strtotime($entry->fiscalYear->end)) }}
                                )</strong>
                        </td>
                        <td style="width: 20%;border-top: none !important">
                            <strong>
                                @include('accounting.backend.pages.entry-approval-stage',[
                                    'object' => $entry,
                                    'userCostCentres' => auth()->user()->costCentres->pluck('cost_centre_id')->toArray()
                                ])
                            </strong>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <br>

                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td style="width: 12.5%"><strong>Cost Centre</strong></td>
                        <td style="width: 12.5%"><strong>Group</strong></td>
                        <td style="width: 17.5%"><strong>Ledger</strong></td>
                        <td style="width: 7.5%"><strong>Currency</strong></td>
                        @if(!$same)
                            <td style="width: 10%"><strong>Rate</strong></td>
                            <td style="width: 10%"><strong>Debit</strong></td>
                            <td style="width: 10%"><strong>Credit</strong></td>
                        @endif
                        <td style="width: 10%"><strong>Debit ({{ $systemCurrency->code }})</strong></td>
                        <td style="width: 10%"><strong>Credit ({{ $systemCurrency->code }})</strong></td>
                        <td style="width: 10%"><strong>Narration</strong></td>
                    </tr>
                    @if($entry->items->count() > 0)
                        @foreach($entry->items as $key => $item)
                            <tr>
                                <td>{{ $item->costCentre ? '['.$item->costCentre->code.'] '.$item->costCentre->name : '' }}</td>
                                <td>{{ $item->chartOfAccount ? '['.$item->chartOfAccount->accountGroup->code.'] '.$item->chartOfAccount->accountGroup->name : '' }}</td>
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
                            <h5><strong>Total</strong></h5>
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
                                <h5><strong>Difference</strong></h5>
                            </td>
                            @if(!$same)
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

                <p class="mb-2">In words ({{ $entry->exchangeRate->currency->code }}):
                    <strong>{{ inWordBn($entry->debit, true, $entry->exchangeRate->currency->name, $entry->exchangeRate->currency->hundreds) }}
                        only.</strong></p>

                @if(!$same)
                    <p class="mb-2">In words ({{ $systemCurrency->code }}):
                        <strong>{{ inWordBn($entry->debit*$exchangeRate, true, $systemCurrency->name, $systemCurrency->hundreds) }}
                            only.</strong></p>
                @endif

                <h6><strong>Narration</strong></h6>
                <p>{{ $entry->notes }}</p>

                @if($entry->attachments->count() > 0)
                <h6><strong>Attachments</strong></h6>
                <ol>
                @foreach($entry->attachments as $attachment)
                <li>
                    <a href="{{ asset($attachment->path) }}" target="_blank" style="text-decoration: none">{{ $attachment->name }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ $attachment->type}}&nbsp;&nbsp;|&nbsp;&nbsp;{{ formatBytes($attachment->size) }}</a>
                </li>
                @endforeach
                </ol>
                @endif
                

                <table class="table mt-5">
                    <tbody>
                        @include('accounting.backend.pages.entries.authors', [
                            'entry' => $entry,
                        ])
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-8 pl-3">
                    <div class="row">
                        <div class="col-md-3 pt-2">
                            <a class="btn btn-sm btn-block btn-success mt-1"
                               href="{{ url('accounting/entries/'.$entry->id.'?print') }}" target="_blank"><i
                                        class="la la-print"></i>&nbsp;Print</a>
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
        </div>
    </div>
</div>

@include('accounting.backend.pages.approval-scripts')