<span style="display: none" id="export-title">{{ $title }}</span>
<table class="table table-bordered table-striped table-hover">
   <thead>
       <tr>
           <th style="width: 10%">Code</th>
           <th style="width: 30%">Ledger</th>
           <th style="width: 15%" class="text-right opening_balance_column">Opening Balance</th>
           <th style="width: 15%" class="text-right debit_column">Debit</th>
           <th style="width: 15%" class="text-right credit_column">Credit</th>
           <th style="width: 15%" class="text-right closing_balance_column">Closing Balance</th>
       </tr>
   </thead>
   <tbody class="report-tbody">
       {!! $trialBalance !!}
   </tbody>
</table>