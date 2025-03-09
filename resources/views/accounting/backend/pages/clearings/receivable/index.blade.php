@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
</style>
@include('yajra.css')
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
            <div class="panel panel-info mt-2 p-2">
                <div class="panel-body">
                    <form action="{{ route('accounting.receivable-clearings.update', 0) }}" method="post" accept-charset="utf-8" id="search-form">
                    @csrf
                    @method('PUT')
                        <div class="row">
                            <div class="col-md-2">
                                <label for="date"><strong>Date</strong></label>
                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control">
                            </div>
                            <div class="col-md-8">
                                <label for="ledgers"><strong>Ledgers</strong></label>
                                <select name="ledgers[]" multiple class="form-control ledgers" data-placeholder="Choose Ledgers...">
                                    {!! chartOfAccountsOptions($groups, 0, 0, getAllGroupAndLedgers()) !!}
                                </select>
                            </div>
                            <div class="col-md-2 pt-4">
                                <button type="submit" class="btn btn-success btn-block mt-2 search-button"><i class="la la-search"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body form-view">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {
        var form = $('#search-form');
        var button = $('.search-button');
        var content = button.html();

        form.submit(function(event) {
            event.preventDefault();
            button.html('<i class="las la-spinner la-spin"></i>&nbsp;&nbsp;Please wait...').prop('disabled', true);
            $('.form-view').html('<h2 class="text-center"><strong><i class="las la-spinner la-spin"></i>&nbsp;&nbsp;Please Wait...</strong></h2>');
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serializeArray(),
            })
            .done(function(response) {
                $('.form-view').html(response);
                button.html(content).prop('disabled', false);
            });
        });
    });
</script>
@endsection