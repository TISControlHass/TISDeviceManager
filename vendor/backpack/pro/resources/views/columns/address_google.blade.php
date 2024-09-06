@php
    /**
    * 'value' => Lisbon, Portugal
    * 'latLng' => [
    *    'lat' => 9.342334,
    *    'lng' => -32.43434
    * ],
    * 'country' => Portugal
    * (not always present) 'locality' => Lisbon 
    * (not always present) 'administrative_area_level_1' => Lisbon
    */
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);

    if(!empty($column['value'])) {
        $column['value'] = (function() use ($column) {
            if (is_callable($column['value'])) {
                return $column['value']($entry);
            }

            if(is_string($column['value'])) {
                 $column['value'] = json_decode($column['value'], true);
            }

            $column['value'] = (array)$column['value'];

            return $column['value']['value'];
        })();
    }
@endphp

@include('crud::columns.text')