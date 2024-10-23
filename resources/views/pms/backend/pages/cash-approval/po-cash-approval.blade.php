@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
.list-unstyled .ratings {
	display: none;
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
			<li>
				<a href="#">PMS</a>
			</li>
			<li>
				<a href="#">Accounts</a>
			</li>
			<li class="active">{{__($title)}}</li>
			<li class="top-nav-btn">
			</li>
		</ul>
	</div>

	<div class="page-content">
		<div class="">
			<div class="panel panel-info">
				<div class="panel-body">
					<form action="{{ route('pms.po.cash.approval.list') }}" method="get" accept-charset="utf-8">
						<div class="row mb-3">
							<div class="col-md-2">
								<div class="form-group">
									<label for="company_id"><strong>Company</strong></label>
									<select class="form-control" name="company_id" id="company_id">
										<option value="{{ null }}">All Company</option>
										@if(isset($companies[0]))
										@foreach($companies as $company)
										<option value="{{ $company->id }}" {{ request()->get('company_id') == $company->id ? 'selected' : '' }}>{{ $company->code }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="cash_status"><strong>Status</strong></label>
									<select class="form-control" name="cash_status" id="cash_status">
										@if(stringStatusArray())
										@foreach(stringStatusArray() as $key => $status)
										<option value="{{ $key }}" {{ $cash_status == $key ? 'selected' : '' }}>{{ $status }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-2 pt-4">
								<button class="btn btn-success btn-md mt-2 btn-block">Search</button>
							</div>
						</div>
					</form>
					
					<div class="page-content">
						<div class="">
							<div class="panel panel-info">
								@include('yajra.datatable')
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="POdetailsModel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Purchase Order</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body" id="body">

			</div>
			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>
@endsection
@section('page-script')
@include('yajra.js')
<script>
	function updateCashStatus(element){
		$.dialog({
            title: 'Cash Approval',
            content: 'url:{{ url('pms/po-cash-approval/create') }}?purchase_order_id='+(element.attr('data-id'))+'&po_cash_status='+(element.attr('data-status')),
            animation: 'scale',
            columnClass: 'medium',
            closeAnimation: 'scale',
            backgroundDismiss: false,
        });
	}
</script>
@endsection