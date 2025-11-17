@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)

@endsection
@section('main-content')
    <div class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $title }}</h5>
                <div>
                    <a href="{{ route('accounting.failed-logs.index') }}" class="btn btn-secondary btn-sm">
                        <i class="las la-arrow-left"></i> Back to List
                    </a>

                    @if($log->status == 'pending')
                        @can('entry-edit')
                            <a href="{{ route('accounting.failed-logs.edit', $log->id) }}" class="btn btn-primary btn-sm">
                                <i class="las la-edit"></i> Edit & Fix
                            </a>
                        @endcan

                        <button onclick="fixEntry({{ $log->id }})" class="btn btn-success btn-sm">
                            <i class="las la-check"></i> Auto Fix
                        </button>

                        <button onclick="ignoreEntry({{ $log->id }})" class="btn btn-warning btn-sm">
                            <i class="las la-times"></i> Ignore
                        </button>
                    @endif

                    @can('entry-delete')
                        <button onclick="deleteFailedLog({{ $log->id }})" class="btn btn-danger btn-sm">
                            <i class="las la-trash"></i> Delete
                        </button>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <!-- Status Alert -->
                @if($log->status == 'pending')
                    <div class="alert alert-warning">
                        <i class="las la-exclamation-triangle"></i>
                        <strong>Pending:</strong> This entry failed validation and needs to be fixed or ignored.
                    </div>
                @elseif($log->status == 'fixed')
                    <div class="alert alert-success">
                        <i class="las la-check-circle"></i>
                        <strong>Fixed:</strong> This entry has been successfully fixed and saved.
                    </div>
                @elseif($log->status == 'ignored')
                    <div class="alert alert-secondary">
                        <i class="las la-times-circle"></i>
                        <strong>Ignored:</strong> This entry has been marked as ignored.
                    </div>
                @endif

                <!-- Failure Reason -->
                @if($log->failure_reason)
                    <div class="alert alert-danger">
                        <h6 class="alert-heading"><i class="las la-exclamation-circle"></i> Failure Reason</h6>
                        <p class="mb-0">{{ $log->failure_reason }}</p>
                    </div>
                @endif

                <!-- Entry Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="las la-info-circle"></i> Entry Information</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <th width="40%">Code:</th>
                                        <td><strong>{{ $log->code ?? 'N/A' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Number:</th>
                                        <td>{{ $log->number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Entry Type:</th>
                                        <td>
                                            @if($log->entryType)
                                                <span class="badge badge-primary">{{ $log->entryType->name }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date:</th>
                                        <td>{{ $log->date ? date('d-m-Y', strtotime($log->date)) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time:</th>
                                        <td>{{ $log->time ? date('h:i A', strtotime($log->time)) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Source:</th>
                                        <td>
                                            @php
                                                $badges = [
                                                    'Manual' => 'info',
                                                    'API' => 'primary',
                                                    'Import' => 'warning',
                                                    'Fixed from Failed Log' => 'secondary'
                                                ];
                                                $badge = $badges[$log->source] ?? 'dark';
                                            @endphp
                                            <span class="badge badge-{{ $badge }}">{{ $log->source ?? 'Unknown' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @php
                                                $statusBadges = [
                                                    'pending' => 'warning',
                                                    'fixed' => 'success',
                                                    'ignored' => 'secondary'
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $statusBadges[$log->status] ?? 'info' }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $log->created_at ? $log->created_at->format('d-m-Y h:i A') : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="las la-building"></i> Company & Fiscal Information</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <th width="40%">Company:</th>
                                        <td>
                                            @if($log->company)
                                                {{ $log->company->code }} - {{ $log->company->name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Fiscal Year:</th>
                                        <td>
                                            @if($log->fiscalYear)
                                                {{ $log->fiscalYear->title }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Exchange Rate:</th>
                                        <td>
                                            @if($log->exchangeRate)
                                                {{ $log->exchangeRate->name ?? 'N/A' }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Is Manual:</th>
                                        <td>
                                            @if($log->is_manual)
                                                <span class="badge badge-warning">Yes</span>
                                            @else
                                                <span class="badge badge-info">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Is Advance:</th>
                                        <td>
                                            @if($log->is_advance)
                                                <span class="badge badge-warning">Yes</span>
                                            @else
                                                <span class="badge badge-info">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created By:</th>
                                        <td>
                                            @if($log->creator)
                                                {{ $log->creator->name }}
                                            @else
                                                <span class="text-muted">System</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amount Summary -->
                <div class="card border mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="las la-calculator"></i> Amount Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <h6 class="text-muted mb-2">Total Debit</h6>
                                    <h4 class="text-primary mb-0">{{ number_format($log->debit, 2) }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <h6 class="text-muted mb-2">Total Credit</h6>
                                    <h4 class="text-success mb-0">{{ number_format($log->credit, 2) }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded bg-danger text-white">
                                    <h6 class="mb-2">Difference</h6>
                                    <h4 class="mb-0">{{ number_format(abs($log->debit_difference), 2) }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <h6 class="text-muted mb-2">Balanced</h6>
                                    <h4 class="mb-0">
                                        @if($log->debit == $log->credit)
                                            <span class="badge badge-success"><i class="las la-check"></i> Yes</span>
                                        @else
                                            <span class="badge badge-danger"><i class="las la-times"></i> No</span>
                                        @endif
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($log->notes)
                    <div class="card border mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="las la-sticky-note"></i> Notes</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $log->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Entry Items -->
                <div class="card border">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="las la-list"></i> Entry Items</h6>
                        <span class="badge badge-primary">{{ count($log->items ?? []) }} Items</span>
                    </div>
                    <div class="card-body">
                        @if($log->items && count($log->items) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead class="bg-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Chart of Account</th>
                                        <th width="15%">Cost Centre</th>
                                        <th width="15%">Sub Ledger</th>
                                        <th width="10%" class="text-right">Debit</th>
                                        <th width="10%" class="text-right">Credit</th>
                                        <th width="25%">Narration</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                    @endphp

                                    @foreach($log->items as $index => $item)
                                        @php
                                            $debit = $item['debit'] ?? 0;
                                            $credit = $item['credit'] ?? 0;
                                            $totalDebit += $debit;
                                            $totalCredit += $credit;

                                            // Get related data
                                            $chartOfAccount = null;
                                            $costCentre = null;
                                            $subLedger = null;

                                            if(isset($item['chart_of_account_id'])) {
                                                $chartOfAccount = \App\Models\PmsModels\Accounts\ChartOfAccount::find($item['chart_of_account_id']);
                                            }

                                            if(isset($item['cost_centre_id'])) {
                                                $costCentre = \App\Models\PmsModels\Accounts\CostCentre::find($item['cost_centre_id']);
                                            }

                                            if(isset($item['sub_ledger_id'])) {
                                                $subLedger = \App\Models\PmsModels\Accounts\SubLedger::find($item['sub_ledger_id']);
                                            }
                                        @endphp

                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @if($chartOfAccount)
                                                    <small>{{ $chartOfAccount->code }}</small><br>
                                                    <strong>{{ $chartOfAccount->name }}</strong>
                                                @else
                                                    <span class="text-muted">ID: {{ $item['chart_of_account_id'] ?? 'N/A' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($costCentre)
                                                    {{ $costCentre->code }} - {{ $costCentre->name }}
                                                @else
                                                    <span class="text-muted">{{ $item['cost_centre_id'] ?? 'N/A' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($subLedger)
                                                    {{ $subLedger->code }} - {{ $subLedger->name }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($debit > 0)
                                                    <strong class="text-primary">{{ number_format($debit, 2) }}</strong>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($credit > 0)
                                                    <strong class="text-success">{{ number_format($credit, 2) }}</strong>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $item['narration'] ?? '' }}</small>
                                                @if(isset($item['type']) && $item['type'])
                                                    <br><span class="badge badge-info badge-sm">{{ $item['type'] }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot class="bg-light font-weight-bold">
                                    <tr>
                                        <td colspan="4" class="text-right">Total:</td>
                                        <td class="text-right">
                                            <strong class="text-primary">{{ number_format($totalDebit, 2) }}</strong>
                                        </td>
                                        <td class="text-right">
                                            <strong class="text-success">{{ number_format($totalCredit, 2) }}</strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right">Difference:</td>
                                        <td colspan="2" class="text-center">
                                            @if($totalDebit == $totalCredit)
                                                <span class="badge badge-success">Balanced <i class="las la-check"></i></span>
                                            @else
                                                <span class="badge badge-danger">
                                                    Unbalanced: {{ number_format(abs($totalDebit - $totalCredit), 2) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="las la-info-circle"></i> No items found in this failed entry.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Information -->
                @if($log->reporting_debit || $log->reporting_credit)
                    <div class="card border mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="las la-exchange-alt"></i> Reporting Currency</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Reporting Debit:</strong> {{ number_format($log->reporting_debit, 2) }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Reporting Credit:</strong> {{ number_format($log->reporting_credit, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="las la-clock"></i> Created: {{ $log->created_at ? $log->created_at->format('d-m-Y h:i A') : 'N/A' }}
                        </small>
                    </div>
                    <div class="col-md-6 text-right">
                        <small class="text-muted">
                            <i class="las la-clock"></i> Updated: {{ $log->updated_at ? $log->updated_at->format('d-m-Y h:i A') : 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
{{--        <script>--}}
{{--            function fixEntry(id) {--}}
{{--                if(!confirm('Are you sure you want to try to fix this entry automatically?')) return;--}}

{{--                $.ajax({--}}
{{--                    url: "{{ url('accounting/failed-logs') }}/" + id + "/fix",--}}
{{--                    method: 'POST',--}}
{{--                    data: {--}}
{{--                        _token: "{{ csrf_token() }}"--}}
{{--                    },--}}
{{--                    beforeSend: function() {--}}
{{--                        $('button').prop('disabled', true);--}}
{{--                    },--}}
{{--                    success: function(response) {--}}
{{--                        if(response.success) {--}}
{{--                            toastr.success(response.message);--}}
{{--                            setTimeout(function() {--}}
{{--                                window.location.reload();--}}
{{--                            }, 1500);--}}
{{--                        } else {--}}
{{--                            toastr.error(response.message);--}}
{{--                            $('button').prop('disabled', false);--}}
{{--                        }--}}
{{--                    },--}}
{{--                    error: function() {--}}
{{--                        toastr.error('An error occurred while fixing the entry.');--}}
{{--                        $('button').prop('disabled', false);--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}

{{--            function ignoreEntry(id) {--}}
{{--                if(!confirm('Are you sure you want to ignore this entry?')) return;--}}

{{--                $.ajax({--}}
{{--                    url: "{{ url('accounting/failed-logs') }}/" + id + "/ignore",--}}
{{--                    method: 'POST',--}}
{{--                    data: {--}}
{{--                        _token: "{{ csrf_token() }}"--}}
{{--                    },--}}
{{--                    beforeSend: function() {--}}
{{--                        $('button').prop('disabled', true);--}}
{{--                    },--}}
{{--                    success: function(response) {--}}
{{--                        if(response.success) {--}}
{{--                            toastr.success(response.message);--}}
{{--                            setTimeout(function() {--}}
{{--                                window.location.reload();--}}
{{--                            }, 1500);--}}
{{--                        } else {--}}
{{--                            toastr.error(response.message);--}}
{{--                            $('button').prop('disabled', false);--}}
{{--                        }--}}
{{--                    },--}}
{{--                    error: function() {--}}
{{--                        toastr.error('An error occurred while ignoring the entry.');--}}
{{--                        $('button').prop('disabled', false);--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}

{{--            function deleteFailedLog(id) {--}}
{{--                if(!confirm('Are you sure you want to delete this failed log? This action cannot be undone.')) return;--}}

{{--                $.ajax({--}}
{{--                    url: "{{ url('accounting/failed-logs') }}/" + id,--}}
{{--                    method: 'DELETE',--}}
{{--                    data: {--}}
{{--                        _token: "{{ csrf_token() }}"--}}
{{--                    },--}}
{{--                    beforeSend: function() {--}}
{{--                        $('button').prop('disabled', true);--}}
{{--                    },--}}
{{--                    success: function(response) {--}}
{{--                        if(response.success) {--}}
{{--                            toastr.success(response.message);--}}
{{--                            setTimeout(function() {--}}
{{--                                window.location.href = "{{ route('accounting.failed-logs.index') }}";--}}
{{--                            }, 1500);--}}
{{--                        } else {--}}
{{--                            toastr.error(response.message);--}}
{{--                            $('button').prop('disabled', false);--}}
{{--                        }--}}
{{--                    },--}}
{{--                    error: function() {--}}
{{--                        toastr.error('An error occurred while deleting the log.');--}}
{{--                        $('button').prop('disabled', false);--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}
{{--        </script>--}}
    @endpush
@endsection