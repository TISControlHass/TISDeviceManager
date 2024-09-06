{{-- relationships (switchboard; supports both single and multiple: 1-1, 1-n, n-n) --}}
@php
    $allowsMultiple = $crud->guessIfFieldHasMultipleFromRelationType($column['relation_type']);
    switch($column['relation_type']) {
        case 'HasOne':
        case 'MorphOne': 
            $column['type'] =  isset($column['subfields']) ? 'repeatable' : 'text';
        break;
        case 'HasMany':
        case 'MorphMany':
        case 'BelongsToMany':
        case 'MorphToMany':
            $column['type'] = isset($column['subfields']) ? 'repeatable' : ($allowsMultiple ? 'select_multiple' : 'select');
        break;
        case 'BelongsTo':
            $column['type'] = 'select';
        break;
        default: 
            $column['type'] = 'text';
    }
@endphp

@includeFirst(\Backpack\CRUD\ViewNamespaces::getViewPathsFor('columns', $column['type']))

