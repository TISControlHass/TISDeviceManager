@php
    [$startDateInputName, $endDateInputName] = explode(',', $column['name']);

    $startValue = $entry->{$startDateInputName};
    $endValue = $entry->{$endDateInputName};

@endphp
@if($startValue && $endValue)
{!!$crud->getCellView(['name' => $startDateInputName, 'type' => 'date'], $entry)!!} - {!!$crud->getCellView(['name' => $endDateInputName, 'type' => 'date'], $entry)!!}
@else
{!! $column['default'] ?? '-' !!}
@endif