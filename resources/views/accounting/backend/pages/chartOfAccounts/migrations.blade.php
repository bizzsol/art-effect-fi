@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
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
                    <li class="active"><a href="{{ url('accounting/chart-of-accounts') }}">Chart of Accounts</a></li>
                    <li class="active">{{__('Ledger Migrations')}}</li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-3">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-head" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 11%">Reference</th>
                                        <th style="width: 7%">Company</th>
                                        <th style="width: 20%">From &rarr; To</th>
                                        <th class="text-right" style="width: 7%">Items</th>
                                        <th class="text-right" style="width: 14%">Debit / Credit</th>
                                        <th style="width: 12%">Re-close span</th>
                                        <th style="width: 10%">Status</th>
                                        <th style="width: 10%">By</th>
                                        <th style="width: 9%">When</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($migrations as $migration)
                                    <tr>
                                        <td><small><strong>{{ $migration->reference }}</strong></small></td>
                                        <td><small>{{ $migration->company_code }}</small></td>
                                        <td>
                                            <small>
                                                <strong>{{ $migration->source_code }}</strong> {{ \Illuminate\Support\Str::limit($migration->source_name, 22) }}
                                                <br>&rarr; <strong>{{ $migration->target_code }}</strong> {{ \Illuminate\Support\Str::limit($migration->target_name, 22) }}
                                            </small>
                                        </td>
                                        <td class="text-right">
                                            <small>
                                                {{ number_format($migration->items_moved) }}
                                                @if($migration->items_residue > 0)
                                                    <br><span class="text-danger" title="Posted after the row set was frozen">
                                                        +{{ number_format($migration->items_residue) }} left</span>
                                                @endif
                                            </small>
                                        </td>
                                        <td class="text-right">
                                            <small>
                                                {{ number_format($migration->total_debit_moved, 2) }}<br>
                                                {{ number_format($migration->total_credit_moved, 2) }}
                                            </small>
                                        </td>
                                        <td>
                                            <small>
                                                {{ optional($migration->recloseFromFiscalYear)->title ?: '?' }}
                                                &ndash; {{ optional($migration->recloseToFiscalYear)->title ?: '?' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($migration->status == 'completed')
                                                <span class="badge badge-success">Completed</span>
                                            @elseif($migration->status == 'awaiting_reclose')
                                                <span class="badge badge-warning" title="Entries moved, carry-forward balances not yet rebuilt">Awaiting re-close</span>
                                            @elseif($migration->status == 'failed')
                                                <span class="badge badge-danger" title="{{ $migration->error }}">Failed</span>
                                            @else
                                                <span class="badge badge-info">Running</span>
                                            @endif
                                        </td>
                                        <td><small>{{ optional($migration->creator)->name ?: '-' }}</small></td>
                                        <td><small>{{ $migration->created_at ? $migration->created_at->format('d M Y') : '-' }}</small></td>
                                    </tr>
                                    @if($migration->reason)
                                        <tr class="bg-light">
                                            <td colspan="9" class="py-1">
                                                <small class="text-muted"><em>Reason: {{ $migration->reason }}</em></small>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">No ledger migrations have been run.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            {{ $migrations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
