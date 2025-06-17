@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('page-css')


<script src="{{asset('assets/rating/jquery.js')}}"></script>

<link rel="stylesheet" href="{{asset('assets/rating/star-rating.min.css')}}" />

<script src="{{asset('assets/rating/star-rating.min.js')}}"></script>

<style>

    body{
        font-size: 15px !important;
    }

</style>

@endsection

@section('main-content')
<!-- WRAPPER CONTENT ----------------------------------------------------------------------------->
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <br>
            <ul class="breadcrumb">
              <li>
                  <i class="ace-icon fa fa-home home-icon"></i>
                  <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
              </li>
              <li>
                  <a href="#">PMS</a>
              </li>
              <li class="active">{{__('Supplier')}}</li>
              <li class="active">{{__($title)}}</li>
              <li class="top-nav-btn">
                <a href="{{route('pms.grn.grn-process.index')}}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Back Warehouse" id="addSupplierBtn"> <i class="las la-angle-left">Back</i></a>
            </li>
        </ul><!-- /.breadcrumb -->

    </div>

    <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <div class="panel-body">
                    <div class="form-row table-responsive">
                        <div class="col-md-12 mb-3">
                            {!! Form::open(array('route' => 'pms.supplier.rating.store','method'=>'POST','class'=>'','files'=>true)) !!}
                            <input type="hidden" name="supplier_id" value="{{$supplierData->id}}">
                            <input type="hidden" name="grn_id" value="{{$grn->id}}">
                            <input type="hidden" name="key" value="{{request()->get('table')}}">
                            <input type="hidden" name="value" value="{{request()->get('field')}}">
                            <input type="hidden" name="type" value="{{request()->get('type')}}">
                            
                            <table class="table table-bordered table-hover table-table-responsive">
                                <thead>
                                    <tr class="bg-primary">
                                        <td colspan="9" class="text-center">Rate this ({{$supplierData->name}}) Supplier
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="5%"> SL</th>
                                        <th width="30%"> Criteria</th>
                                        <th> Rating</th>
                                        {{--<th> Point</th>--}}
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i=1 ?> 
                                    @foreach($supplierCriteriaColumns as $key=>$column)

                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{ucwords(str_replace('_',' ',$column)) }}</td>
                                        <td>
                                            <input id="input-3" name="rating[{{$column}}]" class="rating rating-loading" data-min="0" data-max="5" data-step="0.5" value="0">

                                        </td>
                                    </tr>
                                    <?php $i++ ?>
                                    @endforeach
                                </tbody>
                            </table>

                            <a href="{{URL::to('/')}}" class="btn btn-danger btn-sm pull-right"> Skip </a>
                            <input type="submit" class="btn btn-success btn-sm pull-left" value="Submit">

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-row table-responsive">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover table-table-responsive">
                                <thead class="bg-primary">
                                    <tr>
                                        <td colspan="2" class="text-center">Supplier Basic Information</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td class="text-center">Name</td>
                                        <td>{{$supplierData->name}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Email</td>
                                        <td>{{$supplierData->email}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Phone</td>
                                        <td>{{$supplierData->phone}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Address</td>
                                        <td>
                                            <?php
                                            echo $supplierData->address;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Term&Condition</td>
                                    <td>
                                        <div>{!! $supplierData->term_condition !!}</div>
                                    </td>
                            </tr>
                            <tr>
                                <tr>
                                    <td class="text-center">City</td>
                                    <td>{{$supplierData->city}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">State</td>
                                    <td>{{$supplierData->state}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">Zip Code</td>
                                    <td>{{$supplierData->zipcode}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">Country</td>
                                    <td>{{$supplierData->country}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>




                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


<!-- END Modal ------------------------------------------------------------------------->
@endsection

@section('page-script')


@endsection
