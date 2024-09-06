@php
    $entryValue = $entry->{$column['name']};
    $cellViews = [];     
    $columnsToDisplay = [];

    foreach($column['subfields'] as $subfield) {
        if($subfield['type'] !== 'hidden') {
            $columnsToDisplay[] = $subfield;
        }
    }

    $column['columns'] = array_combine(array_column($columnsToDisplay, 'name'), array_column($columnsToDisplay, 'label'));

    // make sure we decode the value if it's a string (not casted in model)
    if(!empty($entryValue) && is_string($entryValue))
    {
        $entryValue = json_decode($entryValue, true);
    }

    $classEntryType = !is_null($entryValue) && is_object($entryValue) ? get_class($entryValue) : false;
    
    foreach ($columnsToDisplay as $displayColumn) {
        if ($classEntryType && is_a($classEntryType, 'Illuminate\Database\Eloquent\Collection', true)) {
            if (count($columnsToDisplay) === 1) {
                $displayColumn['type'] = 'select_multiple';
                $displayColumn['model'] = $column['model'];
                $displayColumn['entity'] = $column['entity'];
                $displayColumn['value'] = $entryValue;
                $column = $displayColumn;
            } else {
                foreach ($entryValue as $key => $value) {
                    foreach ($columnsToDisplay as $displayColumn) {
                        switch ($column['relation_type']) {
                            case 'BelongsToMany':
                            case 'MorphToMany':
                                $isPivotSelect = isset($displayColumn['is_pivot_select']) && $displayColumn['is_pivot_select'];
                                $relation = $isPivotSelect ? false : $entry->{$column['name']}();
                                $displayColumn['type'] = $isPivotSelect ? 'text' : $displayColumn['type'];
                                $displayColumn['value'] = $isPivotSelect ? $value->{($displayColumn['attribute'] ?? $value->identifiableAttribute())} : $value->{$relation->getPivotAccessor()}->{$displayColumn['name']};
                                $cellViews[$key][$displayColumn['name']] = $crud->getCellView($displayColumn, $value);
                                break;
                            case 'HasMany':
                            case 'MorphMany':
                                $cellViews[$key][$displayColumn['name']] = $crud->getCellView($displayColumn, $value);
                                break;
                            default:
                                $cellViews[$key][$displayColumn['name']] = $crud->getCellView($displayColumn, $entry);
                        }
                    }
                }
            }
        } elseif ($classEntryType && is_a($classEntryType, 'Illuminate\Database\Eloquent\Model', true)) {
            if (count($columnsToDisplay) === 1) {
                $displayColumn['model'] = $column['model'];
                $displayColumn['entity'] = $column['entity'];
                $displayColumn['value'] = $crud->getCellView($displayColumn, $entryValue);
                $column = $displayColumn;
            } else {
                $cellViews[$displayColumn['name']] = $crud->getCellView($displayColumn, $entryValue);
            }
        } else {
            if (!empty($entryValue)) {
                foreach ($entryValue as $key => $value) {
                    foreach ($columnsToDisplay as $displayColumn) {
                        $displayColumn['value'] = $value[$displayColumn['name']];
                        $cellViews[$key][$displayColumn['name']] = $crud->getCellView($displayColumn, $entryValue);
                    }
                }
            } else {
                $column['value'] = array_column($columnsToDisplay, 'name');
                $column['type'] = 'select_from_array';
            }
        }
    }
    $column['escaped'] = false;
    $column['value'] = count($columnsToDisplay) === 1 ? $column['value'] : (!empty($cellViews) && !is_multidimensional_array($cellViews) ? array($cellViews) : $cellViews);
@endphp

@if(count($columnsToDisplay) === 1)
@includeFirst(\Backpack\CRUD\ViewNamespaces::getViewPathsFor('columns', $column['type']))
@else
@includeFirst(\Backpack\CRUD\ViewNamespaces::getViewPathsFor('columns', 'table'))
@endif
