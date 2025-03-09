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
                <li class="top-nav-btn">
                    <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3">
                    <form action="{{ route('accounting.sub-processes.store') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-2">
                                <label for="code"><strong>{{ __('Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="code" id="code" value="{{ $code }}" readonly class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="name"><strong>{{ __('Sub-Process Name') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control rounded">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="type"><strong>{{ __('Sub-Process Type') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="type" id="type" class="form-control">
                                        <option value="automatic">Automatic</option>
                                        <option value="manual">Manual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="description"><strong>{{ __('Description') }}:</strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control rounded">
                                </div>
                            </div>
                        </div>

                        <div class="row pr-3">
                            <div class="col-md-4">
                                <label for="process_id"><strong>{{ __('Process') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="process_id" id="process_id" class="form-control">
                                        @if(isset($processes[0]))
                                        @foreach($processes as $process)
                                        <option value="{{ $process->id }}">{{ $process->code }} | {{ $process->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="source_id"><strong>{{ __('Source') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="source_id" id="source_id" class="form-control">
                                        @if(isset($sources[0]))
                                        @foreach($sources as $source)
                                        <option value="{{ $source->id }}">{{ $source->code }} | {{ $source->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="control_point_id"><strong>{{ __('Control Point') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="control_point_id" id="control_point_id" class="form-control">
                                        @if(isset($controlPoints[0]))
                                        @foreach($controlPoints as $controlPoint)
                                        <option value="{{ $controlPoint->id }}">{{ $controlPoint->code }} | {{ $controlPoint->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="requirement_id"><strong>{{ __('Requirement') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="requirement_id" id="requirement_id" class="form-control">
                                        @if(isset($requirements[0]))
                                        @foreach($requirements as $requirement)
                                        <option value="{{ $requirement->id }}">{{ $requirement->code }} | {{ $requirement->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="entry_point_id"><strong>{{ __('Entry Point') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="entry_point_id" id="entry_point_id" class="form-control" onchange="getLedgers()">
                                        @if(isset($entryPoints[0]))
                                        @foreach($entryPoints as $entryPoint)
                                        <option value="{{ $entryPoint->id }}">{{ $entryPoint->code }} | {{ $entryPoint->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row ledgers mt-4">
                            
                        </div>
                            
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-dark btn-md" href="{{ url('accounting/sub-processes') }}"><i class="la la-times"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Save Sub-Process</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
    getLedgers();
    function getLedgers() {
        $('.ledgers').html('<div class="col-md-12"><h4 class="text-center"><i class="las la-spinner la-spin"></i>&nbsp;Please wait...</h4></div>');
        $.ajax({
            url: "{{ url('accounting/sub-processes') }}/"+$('#entry_point_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('.ledgers').html(response);
        });
    }
</script>
@endsection