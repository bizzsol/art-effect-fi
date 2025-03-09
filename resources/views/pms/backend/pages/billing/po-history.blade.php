@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .list-unstyled .ratings {
        display: none;
    }

    .dropdown-toggle::after{
        display: none !important;
    }
</style>
@endsection
@section('main-content')
@php
use App\Models\PmsModels\PurchaseReturn;
@endphp
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li>
                    <a href="#">PMS</a>
                </li>
                <li class="active">{{__($title)}}</li>

            </ul>
        </div>

        <div class="page-content">
            <div class="">
                <div class="panel panel-info">
                   <div class="panel-body">
                    <h4>Requisition List</h4>
                    <table class="table table-striped table-bordered table-head " cellspacing="0" width="100%" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th width="2%">{{__('SL')}}</th>
                                <th>{{__('Unit')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Ref: No')}}</th>
                                <th>{{__('Requisition By')}}</th>
                                <th>{{__('Qty')}}</th>
                                <th>{{__('Status')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($proposals->requestProposalRequisition[0]))
                            @foreach($proposals->requestProposalRequisition as $key => $values)
                            <tr>
                                <td width="5%" class="text-center">{{$key + 1 }}</td>
                                <td>
                                    {{isset($values->relRequisition->relUsersList->employee->unit->hr_unit_short_name)?$values->relRequisition->relUsersList->employee->unit->hr_unit_short_name:''}}
                                </td>
                                <td>
                                    {{isset($values->relRequisition->relUsersList->employee->department->hr_department_name)?$values->relRequisition->relUsersList->employee->department->hr_department_name:''}}
                                </td>
                                <td>
                                    {{ date('d-m-Y',strtotime($values->relRequisition->requisition_date)) }}
                                </td>

                                <td><a href="javascript:void(0)" data-src="{{route('pms.requisition.list.view.show',$values->relRequisition->id)}}" class="btn btn-link requisition btn-xs m-1 rounded showRequistionDetails">{{ isset($values->relRequisition->reference_no)?$values->relRequisition->reference_no:'' }}</a></td>
                                <td>{{ isset($values->relRequisition->relUsersList->name)?$values->relRequisition->relUsersList->name:'' }}</td>
                                <td class="text-center">{{isset($values->relRequisition->items)? $values->relRequisition->items->sum('qty'):0}}</td>
                                <td id="status{{isset($values->relRequisition->id)?$values->relRequisition->id:0}}">
                                    @if($values->relRequisition->status==0)
                                    <span class="btn btn-xs btn-warning">Pending</span>
                                    @elseif($values->relRequisition->status==1)
                                    <span class="btn btn-xs btn-success">Approved</span>
                                    @elseif($values->relRequisition->status==2)
                                    <span class="btn btn-xs btn-danger">Halt</span>
                                    @elseif($values->relRequisition->status==3)
                                    <span class="btn btn-xs btn-warning">Draft</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-body">
                    <h4>Quotation List Based On Proposal</h4>
                    <table class="table table-striped table-bordered table-head " cellspacing="0" width="100%" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">{{__('SL')}}</th>
                                <th>{{__('Request Proposal')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Reference No')}}</th>
                                <th>{{__('Supplier')}}</th>
                                <th>{{__('Total Price')}}</th>
                                <th>{{__('Vat ')}}</th>
                                <th>{{__('Gross Price')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Quotation Type')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($proposals->relQuotations[0]))

                            @if($proposals->relQuotations->count() > 0)
                            @foreach($proposals->relQuotations as $rkey => $values)
                            <tr>
                                <td class="text-center">{{ $rkey+ 1 }}</td>
                                @if($rkey == 0)
                                <td rowspan="{{ $proposals->relQuotations->count() }}"><a href="javascript:void(0)" onclick="openRequestProposalModal({{$values->request_proposal_id}})"  class="btn btn-link">{{$values->relRequestProposal->reference_no}}</a></td>
                                @endif

                                <td>{{date('d-m-Y',strtotime($values->quotation_date))}}</td>
                                <td>
                                    <a href="javascript:void(0)" onclick="openModal({{$values->id}})"  class="btn btn-link">{{$values->reference_no}}</a>

                                    @if(isset($purchaseOrder->relQuotation->id) && $values->id==$purchaseOrder->relQuotation->id)
                                    <a href="javascript:void(0)" data-title="Quotation CS" class="btn btn-success btn-xs">Approved</a>
                                    @endif

                                    <a target="__blank" href="{{url('pms/quotation/cs-compare-view-pdf/'.$values->request_proposal_id)}}" class="btn btn-warning btn-xs" title="Quotation CS History"><i class="las la-list"></i></a>
                                </td>
                                <td>{{isset($values->relSuppliers->name)?$values->relSuppliers->name:''}}</td>

                                <td class="text-right">{{ systemMoneyFormat($values->total_price)}}</td>
                                {{-- <td class="text-right">{{$values->discount}} </td> --}}
                                <td class="text-right">{{ systemMoneyFormat($values->vat)}} </td>
                                <td class="text-right">{{ systemMoneyFormat($values->gross_price)}}</td>
                                <td>{{ucfirst($values->status)}}</td>
                                <td>{{ucfirst($values->type)}}</td>
                            </tr>
                            @endforeach
                            @endif

                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-info">
                @if(isset($purchaseOrder->id))
                <div class="panel-body">
                    <h4>Purchase Order</h4>
                    <table class="table table-striped table-bordered table-head " cellspacing="0" width="100%" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">{{__('SL')}}</th>
                                <th>{{__('Reference No')}}</th>
                                <th>{{__('PO Date')}}</th>
                                <th>{{__('Supplier')}}</th>
                                <th>{{__('P.O Qty')}}</th>
                                <th class="text-center">{{__('Po Amount')}}</th>
                                <th class="text-center">{{__('Status')}}</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td> <a href="javascript:void(0)" class=" btn-link showPODetails" data-src="{{route('pms.purchase.order-list.show',$purchaseOrder->id)}}">{{$purchaseOrder->reference_no}}</a></td>
                                <td>{{date('d-m-Y',strtotime($purchaseOrder->po_date))}}</td>
                                <td>{{isset($purchaseOrder->relQuotation->relSuppliers->name)?$purchaseOrder->relQuotation->relSuppliers->name:''}}</td>
                                

                                <td class="text-center">{{isset($purchaseOrder->relPurchaseOrderItems)? $purchaseOrder->relPurchaseOrderItems->sum('qty'):0}}</td>

                                <td class="text-right">{{number_format($purchaseOrder->gross_price,2)}}</td>

                                <td class="text-center text-left">
                                    @if($purchaseOrder->relPurchaseOrderItems->sum('qty')==$purchaseOrder->total_grn_qty??0)
                                    {{__('Full Received')}}
                                    @else
                                    {{__('Partial Received')}}
                                    @endif
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            <div class="panel panel-info">
                @if(isset($purchaseOrder->relGoodReceiveNote) && count($purchaseOrder->relGoodReceiveNote)>0)
                <div class="panel-body">
                    <h4>Gate In List Based on Purchase Order</h4>
                    <table class="table table-striped table-bordered table-head " cellspacing="0" width="100%" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">{{__('SL')}}</th>
                                <th>{{__('PO Referance')}}</th>
                                <th>{{__('PO Date')}}</th>
                                <th>{{__('Challan')}}</th>
                                <th>{{__('Reference No')}}</th>
                                <th>{{__('Gate In Date')}}</th>
                                <th>{{__('Gate In Qty')}}</th>
                                <th class="text-center">{{__('Status')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrder->relGoodReceiveNote as $key=> $grn)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                @if($key == 0)
                                <td rowspan="{{ $purchaseOrder->relGoodReceiveNote->count() }}"><a href="javascript:void(0)" class="btn btn-link" onclick="purchaseOrderDetails($(this))" data-src="{{route('pms.purchase.order-list.show', $grn->relPurchaseOrder->id)}}" data-title="Purchase Order Details">{{$grn->relPurchaseOrder->reference_no}}
                                </a>
                            </td>
                            @endif
                            <td>
                                {{date('Y-m-d', strtotime($grn->relPurchaseOrder->po_date))}}
                            </td>
                            <td>
                                {{$grn->challan}}
                            </td>
                            <td>
                                <div style="width: 100%">
                                    <div style="width: 90%;float: left;clear:right">
                                        <a href="javascript:void(0)" class="btn btn-link" onclick="showGateInPODetails($(this))" data-src="{{route('pms.grn.grn-process.show',$grn->id)}}" data-title="Gate In Details">{{$grn->reference_no}}</a>
                                    </div>
                                    <div style="width: 10%;float: left;clear:right">
                                        <a class="btn btn-primary btn-xs" href="{{ url('pms/grn/gate-in-slip/'.$grn->purchase_order_id.'?grn='.$grn->id) }}" target="_blank" style="margin-top: 7.5px"><i class="la la-print"></i></a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{date('Y-m-d', strtotime($grn->received_date))}}
                            </td>
                            <td class="text-center">{{$grn->relGoodsReceivedItems->sum('qty')}}</td>
                            <td class="text-center">

                                @if($grn->received_status == 'partial')
                                <a class="btn btn-warning btn-xs">Partial Received</a>
                                @elseif($grn->received_status == 'full')
                                <a class="btn btn-success btn-xs">Full Received</a>
                                @else
                                <a class="btn btn-dark btn-xs">{{ucwords($grn->received_status)}}</a>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-info">
            @php
            $qualityEnsure = collect(isset($purchaseOrder->relGoodReceiveNote) ? $purchaseOrder->relGoodReceiveNote : []);
            @endphp
            <div class="panel-body">
                <h4>Quality Ensure Approved List</h4>
                <table class="table table-striped table-bordered table-head " cellspacing="0" width="100%" id="dataTable">
                    <thead>
                        <tr class="text-center">
                            <th width="5%">{{__('SL')}}</th>
                            <th>{{__('PO Referance')}}</th>
                            <th>{{__('PO Date')}}</th>
                            <th>{{__('Challan')}}</th>
                            <th>{{__('Reference No')}}</th>
                            <th>{{__('Gate In Date')}}</th>
                            <th>{{__('Gate In Qty')}}</th>
                            <th class="text-center">{{__('Approved Qty')}}</th>
                            <th class="text-center">{{__('Status')}}</th>
                            <th class="text-center">{{__('Option')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php

                        $qualityEnsure = $qualityEnsure->filter(function ($qe, $key) {
                            return $qe->relGoodsReceivedItems->filter(function ($query, $key) {
                                return $query->quality_ensure == 'approved';
                            });
                        });

                        $qualityEnsure->all();
                        @endphp
                        @foreach($qualityEnsure as $key=> $grn)
                        @if($grn->relGoodsReceivedItems->where('quality_ensure','approved')->sum('qty')>0)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            
                            <td><a href="javascript:void(0)" class="btn btn-link" onclick="purchaseOrderDetails($(this))" data-src="{{route('pms.purchase.order-list.show', $grn->relPurchaseOrder->id)}}" data-title="Purchase Order Details">{{$grn->relPurchaseOrder->reference_no}}
                            </a>
                        </td>
                       
                        <td>
                            {{date('Y-m-d', strtotime($grn->relPurchaseOrder->po_date))}}
                        </td>
                        <td>
                            {{$grn->challan}}
                        </td>
                        <td>
                            <div style="width: 100%">
                                <div style="width: 90%;float: left;clear:right">
                                    <a href="javascript:void(0)" class="btn btn-link" onclick="showGateInPODetails($(this))" data-src="{{route('pms.grn.grn-process.show',$grn->id)}}" data-title="Gate In Details">{{$grn->reference_no}}</a>
                                </div>
                                <div style="width: 10%;float: left;clear:right">
                                    <a class="btn btn-primary btn-xs" href="{{ url('pms/grn/gate-in-slip/'.$grn->purchase_order_id.'?grn='.$grn->id) }}" target="_blank" style="margin-top: 7.5px"><i class="la la-print"></i></a>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{date('Y-m-d', strtotime($grn->received_date))}}
                        </td>
                        <td class="text-center">{{$grn->relGoodsReceivedItems->sum('qty')}}</td>
                        <td class="text-center">
                            {{$grn->relGoodsReceivedItems->where('quality_ensure','approved')->sum('qty')}}
                        </td>
                        <td class="text-center">

                            @if($grn->received_status == 'partial')
                            <a class="btn btn-warning btn-xs">Partial Received</a>
                            @elseif($grn->received_status == 'full')
                            <a class="btn btn-success btn-xs">Full Received</a>
                            @else
                            <a class="btn btn-dark btn-xs">{{ucwords($grn->received_status)}}</a>
                            @endif

                        </td>
                        
                        <td>
                            @php
                            $count = $grn->relGoodsReceivedItems()->where('quality_ensure','approved')->count();
                            @endphp

                            @if($count > 0)
                            <a href="{{route('pms.quality.ensure.approved.single.list',$grn->id)}}" class="btn btn-xs btn-info">Items ({{$count}})</a>
                            @endif
                            <a target="_blank" href="{{route('pms.quality.approved.item.print',['id'=>$grn->id,'type'=>'approved'])}}" title="Approved List" class="btn btn-xs btn-success"><i class="las la-print"></i></a>'
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-info">

        <div class="panel-body">
            <h4>Quality Ensure Return List</h4>
            <table class="table table-striped table-bordered table-head " cellspacing="0" width="100%" id="dataTable">
                <thead>
                    <tr class="text-center">
                        <th width="5%">{{__('SL')}}</th>
                        <th>{{__('PO Referance')}}</th>
                        <th>{{__('PO Date')}}</th>
                        <th>{{__('Challan')}}</th>
                        <th>{{__('Reference No')}}</th>
                        <th>{{__('Gate In Date')}}</th>
                        <th>{{__('Gate In Qty')}}</th>
                        <th class="text-center">{{__('Return Qty')}}</th>
                        <th class="text-center">{{__('Status')}}</th>
                        <th class="text-center">{{__('Option')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php

                    $qualityEnsure = $qualityEnsure->filter(function ($qe, $key) {
                        return $qe->relGoodsReceivedItems->filter(function ($query, $key) {
                            return $query->quality_ensure == 'return';
                        });
                    });

                    $qualityEnsure->all();
                    @endphp
                    @foreach($qualityEnsure as $key=> $grn)
                    @if($grn->relGoodsReceivedItems->where('quality_ensure','return')->sum('qty')>0)
                    <tr>
                        <td class="text-center">{{$key+1}}</td>
                        
                        <td><a href="javascript:void(0)" class="btn btn-link" onclick="purchaseOrderDetails($(this))" data-src="{{route('pms.purchase.order-list.show', $grn->relPurchaseOrder->id)}}" data-title="Purchase Order Details">{{$grn->relPurchaseOrder->reference_no}}
                        </a>
                    </td>
                    <td>
                        {{date('Y-m-d', strtotime($grn->relPurchaseOrder->po_date))}}
                    </td>
                    <td>
                        {{$grn->challan}}
                    </td>
                    <td>
                        <div style="width: 100%">
                            <div style="width: 90%;float: left;clear:right">
                                <a href="javascript:void(0)" class="btn btn-link" onclick="showGateInPODetails($(this))" data-src="{{route('pms.grn.grn-process.show',$grn->id)}}" data-title="Gate In Details">{{$grn->reference_no}}</a>
                            </div>
                            <div style="width: 10%;float: left;clear:right">
                                <a class="btn btn-primary btn-xs" href="{{ url('pms/grn/gate-in-slip/'.$grn->purchase_order_id.'?grn='.$grn->id) }}" target="_blank" style="margin-top: 7.5px"><i class="la la-print"></i></a>
                            </div>
                        </div>
                    </td>
                    <td>
                        {{date('Y-m-d', strtotime($grn->received_date))}}
                    </td>
                    <td class="text-center">{{$grn->relGoodsReceivedItems->sum('qty')}}</td>
                    <td class="text-center">
                        {{$grn->relGoodsReceivedItems->where('quality_ensure','return')->sum('qty')}}
                    </td>
                    <td class="text-center">

                        @if($grn->received_status == 'partial')
                        <a class="btn btn-warning btn-xs">Partial Received</a>
                        @elseif($grn->received_status == 'full')
                        <a class="btn btn-success btn-xs">Full Received</a>
                        @else
                        <a class="btn btn-dark btn-xs">{{ucwords($grn->received_status)}}</a>
                        @endif

                    </td>
                    
                    <td>
                        @php
                        $returned = PurchaseReturn::whereHas('relGoodsReceivedItems', function($query) use($grn){
                            return $query->where('goods_received_note_id', $grn->id);
                        })->where('status', 'return')->sum('return_qty');

                        $returnedCount = PurchaseReturn::whereHas('relGoodsReceivedItems', function($query) use($grn){
                            return $query->where('goods_received_note_id', $grn->id);
                        })->where('status', 'return')->count();

                        $approved = PurchaseReturn::whereHas('relGoodsReceivedItems', function($query) use($grn){
                            return $query->where('goods_received_note_id', $grn->id);
                        })->where('status', 'return')->sum('received_qty');
                        @endphp

                        @if($returned > 0)
                        <a href="'.route('pms.quality.ensure.return.single.list',$grn->id).'" class="btn btn-xs btn-info">Items ({{$returnedCount}})</a>
                        <a target="__blank" href="{{route('pms.quality.return.item.print',['id'=>$grn->id,'type'=>'return'])}}" class="btn btn-xs btn-warning" title="Return List"><i class="las la-print"></i></a>
                        @endif

                        @if($approved > 0)
                        <a target="__blank" href="{{route('pms.quality.return.item.print',['id'=>$grn->id,'type'=>'return-approved-list'])}}" class="btn btn-xs btn-success" title="Return Approved List"><i class="las la-print"></i></a>
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="panel panel-info">

    <div class="panel-body">
        <h4>Quality Ensure Return Replace List</h4>
        <table class="table table-striped table-bordered table-head " cellspacing="0" width="100%" id="dataTable">
            <thead>
                <tr class="text-center">
                    <th width="5%">{{__('SL')}}</th>
                    <th>{{__('PO Referance')}}</th>
                    <th>{{__('PO Date')}}</th>
                    <th>{{__('Challan')}}</th>
                    <th>{{__('Reference No')}}</th>
                    <th>{{__('Gate In Date')}}</th>
                    <th>{{__('Gate In Qty')}}</th>
                    <th class="text-center">{{__('Return  Qty')}}</th>
                    <th class="text-center">{{__('Status')}}</th>
                    <th class="text-center">{{__('Option')}}</th>
                </tr>
            </thead>
            <tbody>
                @php

                $qualityEnsure = $qualityEnsure->filter(function ($qe, $key) {
                    return $qe->relGoodsReceivedItems->filter(function ($query, $key) {
                        return $query->quality_ensure == 'return-change';
                    });
                });

                $qualityEnsure->all();
                @endphp
                @foreach($qualityEnsure as $key=> $grn)
                @if($grn->relGoodsReceivedItems->where('quality_ensure','return-change')->sum('qty')>0)
                <tr>
                    <td class="text-center">{{$key+1}}</td>
                    
                    <td><a href="javascript:void(0)" class="btn btn-link" onclick="purchaseOrderDetails($(this))" data-src="{{route('pms.purchase.order-list.show', $grn->relPurchaseOrder->id)}}" data-title="Purchase Order Details">{{$grn->relPurchaseOrder->reference_no}}
                    </a>
                </td>
                
                <td>
                    {{date('Y-m-d', strtotime($grn->relPurchaseOrder->po_date))}}
                </td>
                <td>
                    {{$grn->challan}}
                </td>
                <td>
                    <div style="width: 100%">
                        <div style="width: 90%;float: left;clear:right">
                            <a href="javascript:void(0)" class="btn btn-link" onclick="showGateInPODetails($(this))" data-src="{{route('pms.grn.grn-process.show',$grn->id)}}" data-title="Gate In Details">{{$grn->reference_no}}</a>
                        </div>
                        <div style="width: 10%;float: left;clear:right">
                            <a class="btn btn-primary btn-xs" href="{{ url('pms/grn/gate-in-slip/'.$grn->purchase_order_id.'?grn='.$grn->id) }}" target="_blank" style="margin-top: 7.5px"><i class="la la-print"></i></a>
                        </div>
                    </div>
                </td>
                <td>
                    {{date('Y-m-d', strtotime($grn->received_date))}}
                </td>
                <td class="text-center">{{$grn->relGoodsReceivedItems->sum('qty')}}</td>
                <td class="text-center">
                    {{$grn->relGoodsReceivedItems->where('quality_ensure','return-change')->sum('qty')-$grn->relGoodsReceivedItems->where('quality_ensure','return-change')->sum('received_qty')}}
                </td>
                <td class="text-center">

                    @if($grn->received_status == 'partial')
                    <a class="btn btn-warning btn-xs">Partial Received</a>
                    @elseif($grn->received_status == 'full')
                    <a class="btn btn-success btn-xs">Full Received</a>
                    @else
                    <a class="btn btn-dark btn-xs">{{ucwords($grn->received_status)}}</a>
                    @endif

                </td>

                <td>
                    @php
                    $returned = PurchaseReturn::whereHas('relGoodsReceivedItems', function($query) use($grn){
                            return $query->where('goods_received_note_id', $grn->id);
                        })->where('status', 'return-change')->sum('return_qty');

                        $returnedCount = PurchaseReturn::whereHas('relGoodsReceivedItems', function($query) use($grn){
                            return $query->where('goods_received_note_id', $grn->id);
                        })->where('status', 'return-change')->count();

                        $approved = PurchaseReturn::whereHas('relGoodsReceivedItems', function($query) use($grn){ return $query->where('goods_received_note_id', $grn->id);
                    })->where('status', 'return-change')->sum('received_qty');

                    $count = $grn->relGoodsReceivedItems()->where('quality_ensure','return-change')->count();
                    @endphp

                    @if(auth()->user()->hasPermissionTo('quality-ensure-return-change-received-list') && $count > 0)
                    <a href="{{route('pms.quality.ensure.return.change.single.list',$grn->id)}}" class="btn btn-xs btn-info">{{__('Items')}} ({{$count}})</a>
                    @endif

                    @if($returned > 0)
                    <a target="__blank" href="{{route('pms.quality.return.replace.item.print',['id'=>$grn->id,'type'=>'return-change-list'])}}" title="Return Replace List" class="btn btn-xs btn-warning"><i class="las la-print"></i></a>
                    @endif

                    @if($approved > 0)
                    <a target="__blank" href="{{route('pms.quality.return.replace.item.print',['id'=>$grn->id,'type'=>'return-change'])}}" title="Return Replace Approved List" class="btn btn-xs btn-success"><i class="las la-print"></i></a>
                    @endif

                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
</div>
<div class="panel panel-info">
    @endif
    @if(isset($billManage) && !empty($billManage))
    <div class="panel-body">
        <h4>Bill Manage</h4>
        <table class="table table-striped table-bordered table-head " cellspacing="0" width="100%" id="dataTable">
            <thead>
                <tr class="text-center">
                    <th width="5%">{{__('SL')}}</th>
                    <th>{{__('Referance No')}}</th>
                    <th>{{__('PO Date')}}</th>
                    <th>{{__('Supplier')}}</th>
                    <th>{{__('PO Qty')}}</th>
                    <th>{{__('GRN Qty')}}</th>
                    <th>{{__('Currency')}}</th>
                    <th>{{__('PO Amount')}}</th>
                    <th>{{__('Advance Amount')}}</th>
                    <th>{{__('GRN Amount')}}</th>
                    <th>{{__('Bill Amount')}}</th>
                    <th>{{__('Bill No')}}</th>
                    <th class="text-center">{{__('Status')}}</th>
                    <th class="text-center">{{__('Invoice')}}</th>
                    <th class="text-center">{{__('Vat')}}</th>
                    <th class="text-center">{{__('Option')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td><a href="javascript:void(0)" class="btn-link showPODetails" onclick="showPODetails($(this))" data-src="{{route('pms.purchase.order-list.show',$billManage->id)}}">{{$billManage->reference_no}}</a></td>
                    <td class="text-center">{{date('Y-m-d',strtotime($billManage->po_date))}}</td>
                    <td>{{(isset($billManage->relQuotation->relSuppliers->name) ? $billManage->relQuotation->relSuppliers->name.' ('.$billManage->relQuotation->relSuppliers->code.')' : '')}}</td>
                    
                    <td>{{isset($billManage->relPurchaseOrderItems) ? $billManage->relPurchaseOrderItems->sum('qty') : 0}}</td>
                    <td>
                        @php
                        $billManage->relGoodReceiveNote->each(function ($it,$i){
                            $it['grn_qty'] = $it->relGoodsReceivedItems->sum('qty');
                        });

                        @endphp

                        {{$billManage->relGoodReceiveNote->sum('grn_qty')}}
                    </td>
                    <td>
                        {{isset($billManage->relQuotation->exchangeRate->currency->code) ? $billManage->relQuotation->exchangeRate->currency->code : ''}}
                    </td>
                    <td>{{systemMoneyFormat($billManage->gross_price)}}</td>
                    <td>
                        @php
                        $poAdvance = poAdvance($billManage->id);

                        @endphp
                        {{$poAdvance['advance']}}
                    </td>

                    <td>
                        @php
                        $grnTotalAmount = $billManage->relGoodsReceivedItemStockIn()->where('is_grn_complete','yes')->sum('total_amount');

                        @endphp
                        {{systemMoneyFormat($grnTotalAmount)}}
                    </td>
                    <td>
                        @php
                        if(isset($billManage->relPoAttachment[0])){
                            $grnTotalAmount = $billManage->relPoAttachment()->sum('bill_amount');

                        }
                        @endphp
                        {{($grnTotalAmount > 0) ? systemMoneyFormat($grnTotalAmount)  : 'Not Updated Yet'}}
                    </td>
                    <td>
                        @php
                        if(isset($billManage->relPoAttachment[0])){
                            $poAttachment = $billManage->relPoAttachment()->where('bill_type','po')->first();
                            ;
                        }

                        @endphp
                        {{isset($poAttachment->bill_number) ? $poAttachment->bill_number : 'Not Uploaded yet'}}
                    </td>

                    <td>
                        @php
                        $billManage->relGoodReceiveNote->each(function ($it,$i){
                            $it['grn_qty'] = $it->relGoodsReceivedItems->sum('qty');
                        });
                        $total_grn_qty = $billManage->relGoodReceiveNote->sum('grn_qty');

                        $statuss ='';
                        if($billManage->relPurchaseOrderItems->sum('qty') == $total_grn_qty??0){
                            $statuss .='<a class="btn btn-success btn-xs">Full Received</a>';
                        }else{
                            $statuss.=' <a class="btn btn-warning btn-xs">Partial Received</a>';
                        }

                        @endphp
                        {!!$statuss!!}
                    </td>

                    <td>
                        @if(isset($billManage->relPoAttachment[0]))
                        {{-- @php
                        $poAttachment=$billManage->relPoAttachment()->where('bill_type','po')->first();
                        @endphp --}}
                        @if(!empty($poAttachment->invoice_file))
                        <a href="{{asset($poAttachment->invoice_file)}}" target="__blank" class="btn btn-success btn-xs"><i class="las la-file-invoice"></i></a>
                        @endif
                        @endif
                    </td>

                    <td>
                        @if(isset($billManage->relPoAttachment[0]))
                        {{-- @php
                        $poAttachment = $billManage->relPoAttachment()->where('bill_type','po')->first();
                        @endphp --}}
                        @if(!empty($poAttachment->vat_challan_file))
                        <a href="{{asset($poAttachment->vat_challan_file)}}" target="__blank"  class="btn btn-success btn-xs" title="Click here to view vat chalan"><i class="las la-money-check-alt"></i></a>

                        @endif
                        @endif
                    </td>
                    <td>
                        @php
                        $actions = '';
                        $actions .='<div class="btn-group"><button class="btn dropdown-toggle" data-toggle="dropdown"><span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/></svg></span></button><ul class="dropdown-menu">';

                        $actions.='<li><a target="__blank" href="'.route('pms.billing-audit.po.invoice.print',$billManage->id).'"><i class="las la-print"></i> Print</a></li>';

                        if(isset($billManage->relPoAttachment[0])){
                            //$poAttachment = $billManage->relPoAttachment()->where('bill_type','po')->first();

                            if(!empty($poAttachment->status) &&  !empty($poAttachment->remarks)){

                                $actions .= '<li><a po-attachment="'.strip_tags($poAttachment->remarks).'" class="viewRemarks" onClick="viewRemarks($(this))"><i class="las la-eye"></i> View Remarks</a></li>';
                            }
                        }


                        $actions .= '</ul></div>';
                        @endphp
                        {!!$actions!!}
                    </td>

                </tr>

            </tbody>
        </table>
    </div>

    @endif
</div>
</div>
</div>
</div>
</div>

<div class="modal" id="requisitionDetailModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Quotations Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="tableData">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
@endsection
@section('page-script')
<script>

   function openModal(quotation_id) {
    $('#tableData').load('{{URL::to("pms/quotation/quotation-items")}}/'+quotation_id);
    $('#requisitionDetailModal').find('.modal-title').html(`Quotation Details`);
    $('#requisitionDetailModal').modal('show');
}

function openRequestProposalModal(id) {
    $('#tableData').load('{{URL::to('pms/rfp/request-proposal')}}/'+id);
    $('#requisitionDetailModal').find('.modal-title').html(`Proposal Details`);
    $('#requisitionDetailModal').modal('show')
}

function showGateInPODetails(element) {
    var modalTitle= element.attr('data-title');
    $.ajax({
        url: element.attr('data-src'),
        type: 'get',
        dataType: 'json',
        data: '',
    })
    .done(function(response) {
        if (response.result=='success') {
            $('#POdetailsModel .modal-title').html(modalTitle);
            $('#POdetailsModel').find('#body').html(response.body);
            $('#POdetailsModel').modal('show');
        }
    })
    .fail(function(response){
        notify('Something went wrong!','error');
    });
}

function viewRemarks(element){
    let model = element.attr('po-attachment');
    $('#POdetailsModel').find('#body').html(model);
    $('#POdetailsModel').find('.modal-title').html(`Notes From Audit`);
    $('#POdetailsModel').modal('show');
}
</script>
@endsection