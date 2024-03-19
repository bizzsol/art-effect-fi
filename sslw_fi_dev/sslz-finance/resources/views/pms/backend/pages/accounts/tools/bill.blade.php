<div style="width: 125px;" class="text-center">
	<strong>{{ ucwords(str_replace('-', ' ', $payment->bill_type)) }}</strong>
	<br>
	Bill <strong>#{{ $payment->bill_number }}</strong>
	<br>
	{{ date('Y-m-d', strtotime($payment->transection_date)) }}
	<br>
	{{ $payment->updated_at->diffForHumans() }}
</div>