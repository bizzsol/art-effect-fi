<table class="table table-bordered">
	<tbody>
		<tr>
			<td class="text-center" colspan="{{ count(ageingDays())+2 }}">
				<h4>Customer Ageing Summery for <strong>{{ $this_company->name }}</strong></h4>
				<br>
				<h5>
					Date: <strong>{{ date('F jS, Y') }}</strong>
					&nbsp;&nbsp;|&nbsp;&nbsp;
					<strong>{{ empty(request()->get('customer_code')) ? 'All Customers' : collect($customers)->pluck('name')->implode(', ') }}</strong>
				</h5>
			</td>
		</tr>
		<tr>
			<td style="width: 22%;"><strong>Sum of Net Receivable</strong></td>
			@foreach(ageingDays() as $key => $days)
			<td style="width: 8.5%;" class="text-right"><strong>{{ $days[1] ? $days[1] : $days[0].'+' }}</strong></td>
			@endforeach
			<td style="width: 10%;" class="text-right"><strong>Grand Total</strong></td>
		</tr>
	</tbody>
	<tbody class="report-tbody">
		@php
			$searchAccountsArray = $searchAccounts->pluck('id')->toArray();
			$grandAmounts = [];
		@endphp

		@if(isset($this_company->profitCentres[0]))
		@foreach($this_company->profitCentres as $profitCentre)
		@php
			$profitCentreEntries = $items->where('profit_centre_id', $profitCentre->id)->whereIn('chart_of_account_id', $searchAccountsArray);
		@endphp
		@if($profitCentreEntries->count() > 0)
		@php
			$amounts = [];
			foreach(ageingDays() as $key => $days){
				$from = $days[1] ? date('Y-m-d', strtotime($date.' -'.($days[1] == 1 ? 0 : $days[1]).' days')) : false;
				$to = $days[0] ? date('Y-m-d', strtotime($date.' -'.($days[0] == 1 ? 0 : $days[0]).' days')) : false;
				$thisEntries = $profitCentreEntries->when($from, function($query) use($from){
					return $query->where('date', '>', $from);
				})
				->when($to, function($query) use($to){
					return $query->where('date', '<=', $to);
				});
				
				$amounts[$days[0]] = $thisEntries->where('debit_credit', 'D')->sum('amount')-$thisEntries->where('debit_credit', 'C')->sum('amount');

				if(!isset($grandAmounts[$days[0]])){
					$grandAmounts[$days[0]] = 0;
				}
				$grandAmounts[$days[0]] += $amounts[$days[0]];
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
				$profitCentreCustomers = $profitCentreEntries->whereNotNull('company_code')->pluck('company_code')->unique()->toArray();
			@endphp
			@if(isset($profitCentreCustomers[0]))
			@foreach($profitCentreCustomers as $code)
			@php
				$customer = $customers->where('code', isset(json_decode($code, true)[0]) ? json_decode($code, true)[0] : '')->first();
				$amounts = [];
				foreach(ageingDays() as $key => $days){
					$from = $days[1] ? date('Y-m-d', strtotime($date.' -'.($days[1] == 1 ? 0 : $days[1]).' days')) : false;
					$to = $days[0] ? date('Y-m-d', strtotime($date.' -'.($days[0] == 1 ? 0 : $days[0]).' days')) : false;

					$thisEntries = $profitCentreEntries->when($from, function($query) use($from){
						return $query->where('date', '>', $from);
					})
					->when($to, function($query) use($to){
						return $query->where('date', '<=', $to);
					})
					->where('company_code', $code);

					$amounts[$days[0]] = $thisEntries->where('debit_credit', 'D')->sum('amount')-$thisEntries->where('debit_credit', 'C')->sum('amount');
				}
			@endphp
			<tr>
				<td>&nbsp;&nbsp;&nbsp;{{ isset($customer['name']) ? $customer['name'] : '' }}</td>
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
	{!! $paginate->render('pagination::bootstrap-4') !!}

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

