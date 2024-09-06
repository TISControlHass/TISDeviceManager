<?php
    [$morphTypeField, $morphIdField] = $field['subfields'];
    
    $field['value'] = old_empty_or_null($field['name'], []) ??  $field['value'] ?? $field['default'] ?? [];

    if(is_object($field['value'])) {
        $morphTypeField['value'] = in_array(get_class($field['value']), $morphTypeField['morphMap']) ? array_search(get_class($field['value']), $morphTypeField['morphMap']) : get_class($field['value']);
        $morphIdField['value'] = $field['value']->getKey();
    }

    $field['wrapper']['class']  = isset($field['wrapper']['class']) ? $field['wrapper']['class'].' no-error-display' : 'form-group col-sm-12 no-error-display';

    if($crud->isRequired($field['name'].'.'.$morphTypeField['name']) || $crud->isRequired($field['name'].'.'.$morphIdField['name'])) {
        $field['showAsterisk'] = true;
    }
    
    $morphTypeField['name'] = $field['name'].'['.$morphTypeField['name'].']';
    $morphIdField['name'] = $field['name'].'['.$morphIdField['name'].']';
    
    [$hasAjaxMorphOptions, $allOptionsAjax] = (function() use ($morphIdField) {
        $ajaxOptions = array_filter($morphIdField['morphOptions'], function($item) {
            if(isset($item['data_source']) || (isset($item['ajax']) && $item['ajax'] === true)) {
                return true;
            }
            return false;
        });

        return [empty($ajaxOptions) ? false : true, count($ajaxOptions) === count($morphIdField['morphOptions'])];
    })();
?>

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    <div class="row">
        
        @include($crud->getFirstFieldView('relationship.morphTo_type_select', $morphTypeField['view_namespace'] ?? false),  ['field' => $morphTypeField])
        
        @if(!$allOptionsAjax)
            @include($crud->getFirstFieldView('relationship.morphTo_select', $morphIdField['view_namespace'] ?? false),  ['field' => $morphIdField])
        @endif
        @if($hasAjaxMorphOptions)
            @include($crud->getFirstFieldView('relationship.morphTo_select_ajax', $morphIdField['view_namespace'] ?? false), ['field' => $morphIdField])
        @endif
    </div>
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@include('crud::fields.inc.wrapper_end')

