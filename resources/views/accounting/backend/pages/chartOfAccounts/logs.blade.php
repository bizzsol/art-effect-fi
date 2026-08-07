@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .log-detail { font-size: 12px; }
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
                    <li class="active"><a href="{{ url('accounting/chart-of-accounts') }}">Chart of Accounts</a></li>
                    <li class="active">{{__('Logs')}}</li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-3 p-3">
                    <form action="{{ url('accounting/chart-of-accounts-logs') }}" method="get">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><small><strong>Search</strong></small></label>
                                    <input type="text" name="search" class="form-control" placeholder="Code, name or value"
                                           value="{{ request()->get('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><small><strong>Action</strong></small></label>
                                    <select name="action" class="form-control">
                                        <option value="">All actions</option>
                                        @foreach($actions as $key => $label)
                                            <option value="{{ $key }}" {{ request()->get('action') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><small><strong>Company</strong></small></label>
                                    <select name="company_id" class="form-control">
                                        <option value="">All companies</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ request()->get('company_id') == $company->id ? 'selected' : '' }}>{{ $company->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><small><strong>User</strong></small></label>
                                    <select name="caused_by" class="form-control">
                                        <option value="">All users</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request()->get('caused_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><small><strong>From</strong></small></label>
                                            <input type="date" name="date_from" class="form-control" value="{{ request()->get('date_from') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><small><strong>To</strong></small></label>
                                            <input type="date" name="date_to" class="form-control" value="{{ request()->get('date_to') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <a class="btn btn-sm btn-dark" href="{{ url('accounting/chart-of-accounts-logs') }}">
                                    <i class="las la-times"></i>&nbsp;Reset</a>
                                <button class="btn btn-sm btn-success"><i class="las la-search"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="panel panel-info mt-3">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-head" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 13%">When</th>
                                        <th style="width: 18%">Ledger</th>
                                        <th style="width: 15%">Action</th>
                                        <th style="width: 8%">Company</th>
                                        <th style="width: 13%">By</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td><small>{{ $log->created_at ? $log->created_at->format('d M Y g:i a') : '-' }}</small></td>
                                        <td>
                                            <small>
                                                <strong>{{ $log->account_code }}</strong><br>
                                                <span class="text-muted">{{ \Illuminate\Support\Str::limit($log->account_name, 32) }}</span>
                                            </small>
                                        </td>
                                        <td><span class="badge badge-{{ $log->action_colour }}">{{ $log->action_label }}</span></td>
                                        <td><small>{{ $log->company_code ?: '-' }}</small></td>
                                        <td><small>{{ optional($log->causer)->name ?: $log->causer_name ?: 'System' }}</small></td>
                                        <td class="log-detail">
                                            @if($log->field)
                                                <strong>{{ $log->field }}:</strong>
                                            @endif

                                            @if($log->old_value !== null && $log->new_value !== null)
                                                <span class="text-danger">{{ \Illuminate\Support\Str::limit($log->old_value, 50) }}</span>
                                                &rarr;
                                                <span class="text-success">{{ \Illuminate\Support\Str::limit($log->new_value, 50) }}</span>
                                            @elseif($log->new_value !== null)
                                                <span class="text-success">{{ \Illuminate\Support\Str::limit($log->new_value, 50) }}</span>
                                            @elseif($log->old_value !== null)
                                                <span class="text-danger">{{ \Illuminate\Support\Str::limit($log->old_value, 50) }}</span>
                                            @endif

                                            @if($log->reason)
                                                <br><em class="text-muted">{{ \Illuminate\Support\Str::limit($log->reason, 90) }}</em>
                                            @endif

                                            @if(isset($log->context['reference']))
                                                <br><span class="text-muted">Migration {{ $log->context['reference'] }}
                                                    &mdash; {{ number_format($log->context['items_moved'] ?? 0) }} item(s)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No log entries match these filters.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
