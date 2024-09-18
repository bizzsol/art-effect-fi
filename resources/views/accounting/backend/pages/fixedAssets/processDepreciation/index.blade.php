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
                    <form action="{{ route('accounting.process-depreciation.store') }}" method="post" accept-charset="utf-8">
                    @csrf
                        <div class="row pr-3">
                            <div class="col-md-3 col-sm-12">
                                <label for="company_id"><strong>{{ __('Company') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="company_id" id="company_id" class="form-control rounded" onchange="getProducts()">
                                        @if(isset($companies[0]))
                                        @foreach($companies as $key => $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="product_id"><strong>{{ __('Assets') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="product_id" id="product_id" class="form-control rounded" onchange="getBatches()">
                                        
                                    </select>
                                </div>
                            </div>
                            
                            {{-- <div class="col-md-3 col-sm-12">
                                <label for="fixed_asset_batch_id"><strong>{{ __('Batches') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="fixed_asset_batch_id" id="fixed_asset_batch_id" class="form-control rounded">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <label for="asset_code"><strong>Asset Code:<span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="asset_code" id="asset_code" class="form-control">
                                </div>
                            </div> --}}

                            <div class="col-md-3 col-sm-12">
                                <div class="row">
                                    <div class="col-md-5 col-sm-12">
                                        <label for="year"><strong>Year:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="year" id="year" class="form-control rounded">
                                                @for($i=2000;$i<=date('Y');$i++)
                                                <option {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-sm-12">
                                        <label for="month"><strong>Month:<span class="text-danger">&nbsp;*</span></strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="month" id="month" class="form-control rounded">
                                                @for($i=1;$i<=12;$i++)
                                                <option value="{{ $i < 10 ? '0'.$i : $i }}" {{ (int)(date('m')) == $i ? 'selected' : '' }}>{{ date('F', strtotime(date('Y-').($i < 10 ? '0'.$i : $i))) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 pt-4">
                                <button type="button" class="mt-2 btn btn-success btn-md btn-block" onclick="getItems()"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="row pr-3">
                            <div class="col-md-12 items">
                                
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
    getProducts();
    function getProducts(){
        var company_id = $('#company_id').val();
        $.ajax({
            url: "{{ url('accounting/process-depreciation/create') }}?get-products&company_id="+$('#company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#product_id').html(response).change();
        });
    }

    getBatches();
    function getBatches(){
        // var product_id = $('#product_id').val();
        // $.ajax({
        //     url: "{{ url('accounting/process-depreciation/create') }}?action=batches&product_id="+$('#product_id').val(),
        //     type: 'GET',
        //     data: {},
        // })
        // .done(function(response) {
        //     $('#fixed_asset_batch_id').html(response);
        // });
    }

    function getItems() {
        $('.items').html('<h3 class="text-center"><strong>Please wait...</strong></h3>');
        $.ajax({
            url: "{{ url('accounting/process-depreciation/create') }}?action=items&product_id="+$('#product_id').val()+"&fixed_asset_batch_id="+$('#fixed_asset_batch_id').val()+"&asset_code="+$('#asset_code').val()+"&year="+$('#year').val()+"&month="+$('#month').val()+"&company_id="+$('#company_id').val(),
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('.items').html(response);
        });
    }
</script>
@endsection