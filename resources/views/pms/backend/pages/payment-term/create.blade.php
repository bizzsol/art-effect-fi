@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('main-content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#">PMS</a>
                    </li>
                    <li class="active">{{__($title)}} List</li>
                    <li class="top-nav-btn">
                        <a href="{{ url('pms/payment-terms') }}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Payment Term List">Go Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info">
                    <div class="panel-body">
                        {!! Form::open(array('route' => 'pms.payment-terms.store','id'=>'paymentTermsForm','class'=>'form-horizontal','method'=>'POST','role'=>'form')) !!}

                        <div class="modal-body">

                            <div class="form-group row">
                                <label for="term" class="control-label col-md-12"><strong>Payment Term <span class="text-danger">&nbsp;*</span></strong></label>
                                <div class="col-md-12">
                                    <input type="text" name="term" id="term" class="form-control" value="{{ old('term') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="percentage"><strong>Payment Percentage<span class="text-danger">&nbsp;*</span></strong></label>
                                    <input id="percentage" required class="form-control rounded" name="percentage" type="number" min="1" max="100" value="{{ old('percentage', 100) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="type"><strong>Payment Type<span class="text-danger">&nbsp;*</span></strong></label>
                                    <select name="type" id="type" class="form-control">
                                        @foreach($types as $key => $type)
                                        <option value="{{ $key }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="remarks"><strong>Payment Mode<span class="text-danger">&nbsp;*</span></strong></label>
                                    <input id="remarks" class="form-control rounded" name="remarks" type="text" value="{{ old('remarks') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="status"><strong>Status<span class="text-danger">&nbsp;*</span></strong></label>
                                    <select name="status" id="status" class="form-control">
                                        @foreach($status as $key => $st)
                                        <option value="{{ $key }}">{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary text-white rounded">Save Payment Term</button>
                        </div>
                        {!! Form::close(); !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection