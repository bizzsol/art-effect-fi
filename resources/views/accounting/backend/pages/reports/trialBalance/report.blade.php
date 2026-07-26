<span style="display: none" id="export-title">{{ $title }}</span>
<table class="table table-bordered table-striped table-hover">
   <thead>
       <tr>
           <th style="width: 10%">Code</th>
           <th style="width: 30%">Ledger</th>
           {{-- Amounts are in the selected reporting currency; name it so an exported file is unambiguous. --}}
           @php($currencyLabel = isset($currency->code) ? ' ('.$currency->code.')' : '')
           <th style="width: 15%" class="text-right opening_balance_column">Opening Balance{{ $currencyLabel }}</th>
           <th style="width: 15%" class="text-right debit_column">Debit{{ $currencyLabel }}</th>
           <th style="width: 15%" class="text-right credit_column">Credit{{ $currencyLabel }}</th>
           <th style="width: 15%" class="text-right closing_balance_column">Closing Balance{{ $currencyLabel }}</th>
       </tr>
   </thead>
   <tbody class="report-tbody">
       {!! $trialBalance !!}
   </tbody>
</table>