<table class="table table-bordered">
	<tr>
		<td class="text-center" colspan="{{ count(ageingDays())+2 }}">
			<h4>Customer Ageing Summery for <strong>{{ $this_company->name }}</strong></h4>
			<br>
			<h5>
				Date: <strong>{{ date('F jS, Y') }}</strong>
				&nbsp;&nbsp;|&nbsp;&nbsp;
				Code Searched: <strong>{{ implode(', ', array_keys($companyEntries)) }}</strong>
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

	@php
		$grandAmounts = [];
	@endphp
	@foreach($companyEntries as $code => $entries)
	@if(isset($entries[0]))
	@php
		$companyInformation = getCompanyInformation($entries->first()->entry);
		$amounts = [];
		foreach(ageingDays() as $key => $days){
			if($days[1]){
				$thisEntries = $entries->where('entry.date', '>=', date('Y-m-d', strtotime('- '.$days[1].' days')));
			}
			$thisEntries = $entries->where('entry.date', '<', date('Y-m-d', strtotime('- '.$days[0].' days')));
			
			$amount = $thisEntries->where('debit_credit', 'D')->sum('amount');
			$amounts[$days[0]] = $amount;
			if(!isset($grandAmounts[$days[0]])){
				$grandAmounts[$days[0]] = 0;
			}
			$grandAmounts[$days[0]] += $amount;
		}
	@endphp
	<tr>
		<td>
			<strong>{{ $companyInformation['code'] }} :: {{ $companyInformation['name'] }}</strong>
		</td>
		@foreach($amounts as $amount)
		<td class="text-right">
			<strong>{{ systemMoneyFormat($amount) }}</strong>
		</td>
		@endforeach
		<td class="text-right">
			<strong>{{ systemMoneyFormat(array_sum(array_values($amounts))) }}</strong>
		</td>
	</tr>

	@if($this_company->profitCentres->count() > 0)
	@foreach($this_company->profitCentres as $profitCentre)
	@php
		$profitCentreEntries = $entries->where('costCentre.profit_centre_id', $profitCentre->id);
	@endphp
	@if($profitCentreEntries->count() > 0)
	@php
		$amounts = [];
		foreach(ageingDays() as $key => $days){
			if($days[1]){
				$thisEntries = $profitCentreEntries->where('entry.date', '>=', date('Y-m-d', strtotime('- '.$days[1].' days')));
			}
			$thisEntries = $profitCentreEntries->where('entry.date', '<', date('Y-m-d', strtotime('- '.$days[0].' days')));
			
			$amounts[$days[0]] = $thisEntries->where('debit_credit', 'D')->sum('amount');
		}
	@endphp
	<tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $profitCentre->name }}</td>
		@foreach($amounts as $amount)
		<td class="text-right">{{ systemMoneyFormat($amount) }}</td>
		@endforeach
		<td class="text-right">{{ systemMoneyFormat(array_sum(array_values($amounts))) }}</strong></td>
	</tr>
	@endif
	@endforeach
	@endif

	@endif
	@endforeach

	<tr>
		<td style="width: 22%;" class="text-right"><strong>Total</strong></td>
		@foreach(ageingDays() as $key => $days)
		<td style="width: 8.5%;" class="text-right"><strong>{{ isset($grandAmounts[$days[0]]) ? systemMoneyFormat($grandAmounts[$days[0]]) : 0 }}</strong></td>
		@endforeach
		<td style="width: 10%;" class="text-right"><strong>{{ systemMoneyFormat(array_sum(array_values($grandAmounts))) }}</strong></td>
	</tr>
</table>