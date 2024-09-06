@php
   
   //this should be uncomented later when we move away or do something to fix bs-datepicker
   //because they don't use ISO format for dates, so we cannot reliably convert from their format.
   //$column['format'] = $column['format'] ?? $column['date_picker_options']['format'];
   
@endphp
@include('crud::columns.date')