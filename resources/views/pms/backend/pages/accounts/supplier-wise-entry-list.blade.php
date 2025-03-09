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
						<div class="row">
							<div class="col-md-12 mb-4">
								<form action="{{ url('pms/accounts/supplier-wise-entry-list') }}" method="get" accept-charset="utf-8">
									<div class="row">
										<div class="col-md-2 col-sm-6">
											<p class="mb-1 font-weight-bold"><label for="from_date">{{ __('From Date') }}:</label></p>
											<div class="input-group input-group-md mb-3 d-">
												<input type="date" name="from_date" id="from_date" class="form-control rounded" required value="{{ $from_date }}">
											</div>
										</div>
										<div class="col-md-2 col-sm-6">
											<p class="mb-1 font-weight-bold"><label for="to_date">{{ __('To Date') }}:</label></p>
											<div class="input-group input-group-md mb-3 d-">
												<input type="date" name="to_date" id="to_date" class="form-control zing-sm" required value="{{ $to_date }}">
											</div>
										</div>

										<div class="col-md-2">
											<p class="font-weight-bold"><label for="company_id"><strong>Company:</strong></label></p>
											<div class="input-group input-group-md mb-3 d-">
												<select name="company_id" id="company_id" class="form-control rounded" onchange="getSuppliers()">
													@if(isset($companies[0]))
													@foreach($companies as $company)
													<option value="{{ $company->id }}" {{ $company_id == $company->id ? 'selected' : '' }}>{{ $company->code }}</option>
													@endforeach
													@endif
												</select>
											</div>
										</div>

										<div class="col-md-3 col-sm-6">
											<p class="mb-1 font-weight-bold"><label for="supplier_id">{{ __('Supplier') }}:</label></p>
											<div class="input-group input-group-md mb-3 d-">
												<select name="supplier_id" id="supplier_id" class="form-control rounded">
													
												</select>
											</div>
										</div>

										<div class="col-md-2 col-sm-6">
											<p class="mb-1 font-weight-bold"><label for="searchRequisitonBtn"></label></p>
											<div class="input-group input-group-md">
												<button class="btn btn-success rounded mt-8"><i class="las la-search"></i>Search</button>
											</div>
										</div>
									</div>
								</form>
							</div>
							
							@if(isset($selected_supplier->name))
							<div class="col-md-12 mb-4">
								<h4 class="text-center">
									Ledger of <strong>{{ $selected_supplier->name.' ('.$selected_supplier->code.')'  }}</strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong>{{ date('F jS Y', strtotime($from_date)) }}</strong> to <strong>{{ date('F jS Y', strtotime($to_date)) }}</strong>
								</h4>
								<hr>
							</div>
							@endif

							<div class="col-md-12 mb-4">
								@include('yajra.datatable')
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
<script type="text/javascript">
	getSuppliers();
	function  getSuppliers(){
		$.ajax({
			url: "{{ url('pms/accounts/supplier-wise-entry-list') }}?get-suppliers&company_id="+$('#company_id').val(),
			type: 'GET',
			dataType: 'json',
			data: {},
		})
		.done(function(response) {
			console.log(response);
			var suppliers = '<option value="{{ null }}">{{ __('Choose a Suppliers') }}</option>';
			var supplier_id = "{{ $supplier_id }}";
			$.each(response, function(index, val) {
				suppliers += '<option value="'+val.id+'" '+(supplier_id == val.id ? 'selected' : '')+'>'+val.name+' ('+val.code+')</option>';
			});
			$('#supplier_id').html(suppliers).change();
		});
	}
</script>
@endsection