@extends('accounting.backend.layouts.master-layout')

@section('title', $title)
@section('page-css')
<style>
.log-entry {
    border-left: 4px solid #ccc;
    padding: 10px 15px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
}

.log-entry.EMERGENCY,
.log-entry.ALERT,
.log-entry.CRITICAL,
.log-entry.ERROR {
    border-left-color: #dc3545;
    background: #fff5f5;
}

.log-entry.WARNING {
    border-left-color: #ffc107;
    background: #fffbf0;
}

.log-entry.INFO {
    border-left-color: #17a2b8;
    background: #f0f9ff;
}

.log-entry.DEBUG {
    border-left-color: #6c757d;
    background: #f8f9fa;
}

.log-entry.NOTICE {
    border-left-color: #28a745;
    background: #f0fff4;
}

.log-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.log-timestamp {
    color: #6c757d;
    font-weight: bold;
}

.log-level {
    padding: 2px 8px;
    border-radius: 3px;
    font-weight: bold;
    font-size: 11px;
}

.log-level.EMERGENCY,
.log-level.ALERT,
.log-level.CRITICAL,
.log-level.ERROR {
    background: #dc3545;
    color: white;
}

.log-level.WARNING {
    background: #ffc107;
    color: #000;
}

.log-level.INFO {
    background: #17a2b8;
    color: white;
}

.log-level.DEBUG {
    background: #6c757d;
    color: white;
}

.log-level.NOTICE {
    background: #28a745;
    color: white;
}

.log-message {
    margin-bottom: 8px;
    white-space: pre-wrap;
    word-break: break-word;
}

.log-context {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 3px;
    padding: 10px;
    margin-top: 8px;
    font-size: 12px;
}

.log-context pre {
    margin: 0;
    white-space: pre-wrap;
    word-break: break-word;
}

.toggle-raw {
    cursor: pointer;
    color: #007bff;
    text-decoration: underline;
    font-size: 11px;
}

.log-raw {
    display: none;
    background: #2d2d2d;
    color: #f8f8f2;
    padding: 10px;
    border-radius: 3px;
    margin-top: 8px;
    white-space: pre-wrap;
    word-break: break-word;
    font-size: 11px;
}

.log-details {
    display: none;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #dee2e6;
}

.log-summary {
    cursor: pointer;
    user-select: none;
}

.log-summary:hover {
    opacity: 0.8;
}

.expand-btn {
    cursor: pointer;
    padding: 2px 8px;
    font-size: 11px;
    border-radius: 3px;
    background: #6c757d;
    color: white;
    border: none;
    margin-left: 10px;
}

.expand-btn:hover {
    background: #5a6268;
}

.expand-btn.expanded {
    background: #007bff;
}

.log-message-preview {
    display: inline;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $title }}</h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-primary" onclick="refreshLogs()">
                            <i class="las la-sync"></i> Refresh
                        </button>
                        <button type="button" class="btn btn-sm btn-success" onclick="downloadLog()">
                            <i class="las la-download"></i> Download
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="clearLog()">
                            <i class="las la-trash"></i> Clear Log
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Log File</label>
                            <select class="form-control" id="log_file" onchange="refreshLogs()">
                                @foreach($logFiles as $file)
                                    <option value="{{ $file['name'] }}" {{ $loop->first ? 'selected' : '' }}>
                                        {{ $file['name'] }} ({{ $file['size'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Level</label>
                            <select class="form-control" id="level" onchange="refreshLogs()">
                                <option value="all">All Levels</option>
                                <option value="emergency">Emergency</option>
                                <option value="alert">Alert</option>
                                <option value="critical">Critical</option>
                                <option value="error">Error</option>
                                <option value="warning">Warning</option>
                                <option value="notice">Notice</option>
                                <option value="info">Info</option>
                                <option value="debug">Debug</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Date</label>
                            <input type="date" class="form-control" id="date" onchange="refreshLogs()">
                        </div>
                        <div class="col-md-3">
                            <label>Search</label>
                            <input type="text" class="form-control" id="search" placeholder="Search in logs..." onkeyup="debounceSearch()">
                        </div>
                        <div class="col-md-2">
                            <label>Limit</label>
                            <select class="form-control" id="limit" onchange="refreshLogs()">
                                <option value="50">50 entries</option>
                                <option value="100" selected>100 entries</option>
                                <option value="200">200 entries</option>
                                <option value="500">500 entries</option>
                                <option value="1000">1000 entries</option>
                                <option value="0">All entries</option>
                            </select>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row mb-3" id="stats-container" style="display: none;">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <div class="row text-center">
                                        <div class="col">
                                            <span class="badge badge-secondary" style="font-size: 14px;">
                                                Total: <strong id="stat-total">0</strong>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="badge badge-danger" style="font-size: 14px;">
                                                Emergency: <strong id="stat-emergency">0</strong>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="badge badge-danger" style="font-size: 14px;">
                                                Alert: <strong id="stat-alert">0</strong>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="badge badge-danger" style="font-size: 14px;">
                                                Critical: <strong id="stat-critical">0</strong>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="badge badge-danger" style="font-size: 14px;">
                                                Error: <strong id="stat-error">0</strong>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="badge badge-warning" style="font-size: 14px;">
                                                Warning: <strong id="stat-warning">0</strong>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="badge badge-success" style="font-size: 14px;">
                                                Notice: <strong id="stat-notice">0</strong>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="badge badge-info" style="font-size: 14px;">
                                                Info: <strong id="stat-info">0</strong>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="badge badge-secondary" style="font-size: 14px;">
                                                Debug: <strong id="stat-debug">0</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Log Entries -->
                    <div id="log-container">
                        <div class="text-center py-5">
                            <i class="las la-spinner la-spin la-3x"></i>
                            <p>Loading logs...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script>
let searchTimeout;

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        refreshLogs();
    }, 500);
}

function refreshLogs() {
    const logFile = $('#log_file').val();
    const level = $('#level').val();
    const search = $('#search').val();
    const date = $('#date').val();
    const limit = $('#limit').val();

    $('#log-container').html(`
        <div class="text-center py-5">
            <i class="las la-spinner la-spin la-3x"></i>
            <p>Loading logs...</p>
        </div>
    `);

    $.ajax({
        url: '{{ route("accounting.logs.get") }}',
        method: 'GET',
        data: {
            log_file: logFile,
            level: level,
            search: search,
            date: date,
            limit: limit
        },
        success: function(response) {
            if (response.success) {
                displayLogs(response.logs, response.total, response.stats);
            } else {
                $('#log-container').html(`
                    <div class="alert alert-danger">
                        ${response.message}
                    </div>
                `);
            }
        },
        error: function(xhr) {
            $('#log-container').html(`
                <div class="alert alert-danger">
                    Error loading logs: ${xhr.responseText}
                </div>
            `);
        }
    });
}

function displayStats(stats) {
    if (!stats) return;
    
    $('#stats-container').show();
    $('#stat-total').text(stats.total || 0);
    $('#stat-emergency').text(stats.emergency || 0);
    $('#stat-alert').text(stats.alert || 0);
    $('#stat-critical').text(stats.critical || 0);
    $('#stat-error').text(stats.error || 0);
    $('#stat-warning').text(stats.warning || 0);
    $('#stat-notice').text(stats.notice || 0);
    $('#stat-info').text(stats.info || 0);
    $('#stat-debug').text(stats.debug || 0);
}

function displayLogs(logs, total, stats) {
    // Display statistics
    displayStats(stats);
    
    if (logs.length === 0) {
        $('#log-container').html(`
            <div class="alert alert-info">
                No log entries found matching your criteria.
            </div>
        `);
        return;
    }

    let html = `<div class="mb-3"><strong>Showing ${logs.length} of ${total} entries</strong></div>`;

    logs.forEach((log, index) => {
        // Truncate message for preview (first 100 characters)
        const messagePreview = log.message.length > 100 
            ? log.message.substring(0, 100) + '...' 
            : log.message;
        
        html += `
            <div class="log-entry ${log.level}">
                <div class="log-summary" onclick="toggleLogDetails(${index})">
                    <div class="log-header">
                        <span class="log-timestamp">${log.timestamp}</span>
                        <div>
                            <span class="log-level ${log.level}">${log.level}</span>
                            <button class="expand-btn" id="expand-btn-${index}" onclick="event.stopPropagation(); toggleLogDetails(${index})">
                                <i class="las la-angle-down"></i> Expand
                            </button>
                        </div>
                    </div>
                    <div class="log-message-preview mt-2">
                        ${escapeHtml(messagePreview)}
                    </div>
                </div>
                
                <div class="log-details" id="details-${index}">
                    <div class="log-message">
                        <strong>Full Message:</strong><br>
                        ${escapeHtml(log.message)}
                    </div>
        `;

        if (log.context) {
            html += `
                    <div class="log-context">
                        <strong>Context:</strong>
                        <pre>${JSON.stringify(log.context, null, 2)}</pre>
                    </div>
            `;
        }

        html += `
                    <div class="mt-2">
                        <span class="toggle-raw" onclick="toggleRaw(${index})">Show Raw Log</span>
                        <div class="log-raw" id="raw-${index}">${escapeHtml(log.raw)}</div>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <button class="expand-btn expanded" onclick="toggleLogDetails(${index})">
                            <i class="las la-angle-up"></i> Collapse
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    $('#log-container').html(html);
}

function toggleLogDetails(index) {
    const details = $(`#details-${index}`);
    const btn = $(`#expand-btn-${index}`);
    
    if (details.is(':visible')) {
        details.slideUp(200);
        btn.removeClass('expanded');
        btn.html('<i class="las la-angle-down"></i> Expand');
    } else {
        details.slideDown(200);
        btn.addClass('expanded');
        btn.html('<i class="las la-angle-up"></i> Collapse');
    }
}

function toggleRaw(index) {
    $(`#raw-${index}`).slideToggle();
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function downloadLog() {
    const logFile = $('#log_file').val();
    window.location.href = '{{ route("accounting.logs.download") }}?log_file=' + logFile;
}

function clearLog() {
    if (!confirm('Are you sure you want to clear this log file? This action cannot be undone.')) {
        return;
    }

    const logFile = $('#log_file').val();

    $.ajax({
        url: '{{ route("accounting.logs.clear") }}',
        method: 'POST',
        data: {
            log_file: logFile,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
                refreshLogs();
            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            alert('Error clearing log: ' + xhr.responseText);
        }
    });
}

// Load logs on page load
$(document).ready(function() {
    refreshLogs();
});
</script>
@endsection