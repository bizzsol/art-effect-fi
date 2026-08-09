@extends('accounting.backend.layouts.master-layout')

@section('title', $title)
@section('page-css')
<style>
    .dl-note {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 12px;
    }
    .dl-group + .dl-group {
        margin-top: 24px;
    }
    .dl-group h5 {
        font-weight: 700;
        margin-bottom: 4px;
    }
    table.dl-table td, table.dl-table th {
        vertical-align: middle;
        font-size: 13px;
    }
    table.dl-table code {
        font-size: 12px;
        background: #f1f3f5;
        padding: 2px 6px;
        border-radius: 3px;
        white-space: nowrap;
    }
    .dl-severity {
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .03em;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 3px;
        white-space: nowrap;
    }
    .dl-severity.critical { background: #f8d7da; color: #842029; }
    .dl-severity.elevated { background: #fff3cd; color: #997404; }
    .dl-severity.orphaned { background: #e2e3e5; color: #41464b; }
    .dl-severity.broken    { background: #e2e3e5; color: #41464b; }
    .dl-severity.info      { background: #d1ecf1; color: #0c5460; }
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning d-flex align-items-start" style="gap:10px;">
                <i class="las la-user-secret" style="font-size:22px; line-height:1;"></i>
                <div>
                    <strong>Restricted page.</strong>
                    You're seeing this because you're a Super Admin or have been explicitly granted the
                    <code>developer-links</code> permission. Everything below is a working accounting-module
                    URL that has no entry in the left sidebar — most are reachable by anyone who guesses or
                    is given the link, regardless of whether they can see this page.
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $title }}</h4>
                </div>
                <div class="card-body">
                    <p class="dl-note">
                        Compiled by reading <code>routes/modules/accounting.php</code> / <code>routes/web.php</code>
                        against the live <code>menus</code> / <code>sub_menus</code> tables — the actual source the
                        sidebar renders from, not the placeholder <code>MenuSeeder.php</code>. Re-check this list
                        whenever routes change; it's a static snapshot, not a live diff.
                    </p>

                    @foreach($groups as $group)
                        <div class="dl-group">
                            <h5>{{ $group['name'] }}</h5>
                            @if(!empty($group['note']))
                                <p class="dl-note">{{ $group['note'] }}</p>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped dl-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width:16%">Name</th>
                                            <th style="width:20%">URL</th>
                                            <th style="width:32%">Purpose</th>
                                            <th style="width:24%">Why it's hidden</th>
                                            <th style="width:8%">Severity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group['rows'] as $row)
                                            <tr>
                                                <td><strong>{{ $row['name'] }}</strong></td>
                                                <td><code>{{ $row['url'] }}</code></td>
                                                <td>{{ $row['purpose'] }}</td>
                                                <td class="text-muted">{{ $row['why'] }}</td>
                                                <td><span class="dl-severity {{ $row['severity'] }}">{{ $row['severity'] }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
