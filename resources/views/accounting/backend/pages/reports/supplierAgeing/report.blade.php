<table class="table table-bordered">
	<tbody>
		<tr>
			<td class="text-center" colspan="{{ count(ageingDays())+2 }}">
				<h4>Supplier Ageing Summery for <strong>{{ $this_company->name }}</strong></h4>
				<br>
				<h5>
					Date: <strong>{{ date('F jS, Y') }}</strong>
					&nbsp;&nbsp;|&nbsp;&nbsp;
					<strong>{{ empty(request()->get('supplier_id')) ? 'All Suppliers' : $suppliers->pluck('name')->implode(', ') }}</strong>
				</h5>
			</td>
		</tr>
		<tr>
			<td style="width: 22%;"><strong>Sum of Net Payable</strong></td>
			@foreach(ageingDays() as $key => $days)
			<td style="width: 8.5%;" class="text-right"><strong>{{ $days[1] ? $days[1] : $days[0].'+' }}</strong></td>
			@endforeach
			<td style="width: 10%;" class="text-right"><strong>Grand Total</strong></td>
		</tr>
	</tbody>
	<tbody class="report-tbody">
		@php
			$grandAmounts = [];
		@endphp

		@if(isset($this_company->profitCentres[0]))
		@foreach($this_company->profitCentres as $profitCentre)
		@php
			$profitCentreEntryLedgers = $entryLedgers->where('profit_centre_id', $profitCentre->id);
			$profitCentreNonEntryLedgers = $nonEntryLedgers->where('profit_centre_id', $profitCentre->id);
		@endphp
		@if($profitCentreEntryLedgers->count() > 0 || $profitCentreNonEntryLedgers->count() > 0)
		@php
		$dates = [];
			$amounts = [];
			foreach(ageingDays() as $key => $days){
				$from = $days[1] ? date('Y-m-d', strtotime($date.' -'.($days[1] == 1 ? 0 : $days[1]).' days')) : false;
				$to = $days[0] ? date('Y-m-d', strtotime($date.' -'.($days[0] == 1 ? 0 : $days[0]).' days')) : false;
				$thisEntryLedgers = $profitCentreEntryLedgers->when($from, function($query) use($from){
					return $query->where('date', '>=', $from);
				})
				->when($to, function($query) use($to){
					return $query->where('date', '<=', $to);
				});

				$thisNonEntryLedgers = $profitCentreNonEntryLedgers->when($from, function($query) use($from){
					return $query->where('date', '>=', $from);
				})
				->when($to, function($query) use($to){
					return $query->where('date', '<=', $to);
				});
				
				$amounts[$days[0]] = ($thisEntryLedgers->sum('debit')-$thisEntryLedgers->sum('credit'))+($thisNonEntryLedgers->sum('debit')-$thisNonEntryLedgers->sum('credit'));

				if(!isset($grandAmounts[$days[0]])){
					$grandAmounts[$days[0]] = 0;
				}
				$grandAmounts[$days[0]] += $amounts[$days[0]];

				array_push($dates, [
					'from' => $from,
					'to' => $to,
				]);
			}
		@endphp
			<tr>
				<td><strong>{{ $profitCentre->name }}</strong></td>
				@foreach($amounts as $amount)
				<td class="text-right"><strong>{{ systemMoneyFormat($amount) }}</strong></td>
				@endforeach
				<td class="text-right"><strong>{{ systemMoneyFormat(array_sum(array_values($amounts))) }}</strong></strong></td>
			</tr>

			@php
				$profitCentreSuppliers = $suppliers->whereIn('id', array_merge($profitCentreEntryLedgers->pluck('supplier_id')->unique()->toArray(), $profitCentreNonEntryLedgers->pluck('supplier_id')->unique()->toArray()));
			@endphp
			@if($profitCentreSuppliers->count() > 0)
			@foreach($profitCentreSuppliers as $supplier)
			@php
				$amounts = [];
				foreach(ageingDays() as $key => $days){
					$from = $days[1] ? date('Y-m-d', strtotime($date.' -'.($days[1] == 1 ? 0 : $days[1]).' days')) : false;
					$to = $days[0] ? date('Y-m-d', strtotime($date.' -'.($days[0] == 1 ? 0 : $days[0]).' days')) : false;

					$thisEntryLedgers = $profitCentreEntryLedgers->when($from, function($query) use($from){
						return $query->where('date', '>', $from);
					})
					->when($to, function($query) use($to){
						return $query->where('date', '<=', $to);
					})
					->where('supplier_id', $supplier->id);
					$thisNonEntryLedgers = $profitCentreNonEntryLedgers->when($from, function($query) use($from){
						return $query->where('date', '>', $from);
					})
					->when($to, function($query) use($to){
						return $query->where('date', '<=', $to);
					})
					->where('supplier_id', $supplier->id);
					
					$amounts[$days[0]] = ($thisEntryLedgers->sum('debit')-$thisEntryLedgers->sum('credit'))+($thisNonEntryLedgers->sum('debit')-$thisNonEntryLedgers->sum('credit'));
				}
			@endphp
			<tr>
				<td>&nbsp;&nbsp;&nbsp;{{ $supplier->name }}</td>
				@foreach($amounts as $amount)
				<td class="text-right">{{ systemMoneyFormat($amount) }}</td>
				@endforeach
				<td class="text-right">{{ systemMoneyFormat(array_sum(array_values($amounts))) }}</strong></td>
			</tr>
			@endforeach
			@endif
		@endif
		@endforeach
		@endif
	</tbody>
	<tbody>
		<tr>
			<td style="width: 22%;" class="text-right"><strong>Total</strong></td>
			@foreach(ageingDays() as $key => $days)
			<td style="width: 8.5%;" class="text-right"><strong>{{ isset($grandAmounts[$days[0]]) ? systemMoneyFormat($grandAmounts[$days[0]]) : 0 }}</strong></td>
			@endforeach
			<td style="width: 10%;" class="text-right"><strong>{{ systemMoneyFormat(array_sum(array_values($grandAmounts))) }}</strong></td>
		</tr>
	</tbody>
</table>

@if(request()->get('report_type') == 'report')
	{!! $suppliers->render('pagination::bootstrap-4') !!}

	<script type="text/javascript">
		$(document).ready(function() {
			$.each($('a.page-link'), function(index, val) {
				var link = $(this);
				link.click(function(event) {
					event.preventDefault();
					$('#page').val(link.text());
					$('#report-form').submit();
				});
			});
		});
	</script>
@endif

