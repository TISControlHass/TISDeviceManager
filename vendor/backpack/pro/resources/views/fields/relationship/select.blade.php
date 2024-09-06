@php
    $connected_entity = new ($field['model'])();
    $connected_entity_key_name = $connected_entity->getKeyName();
    $field['multiple'] = $field['multiple'] ?? $crud->relationAllowsMultiple($field['relation_type']);
    $field['attribute'] = $field['attribute'] ?? $connected_entity->identifiableAttribute();
    $field['include_all_form_fields'] = $field['include_all_form_fields'] ?? true;
    $field['allows_null'] = $field['allows_null'] ?? $crud->model::isColumnNullable($field['name']);
    // Note: isColumnNullable returns true if column is nullable in database, also true if column does not exist.

    // this field can be used as a pivot select for n-n relationships
    $field['is_pivot_select'] = $field['is_pivot_select'] ?? false;

    if (!isset($field['options'])) {
        $field['options'] = $connected_entity::all()->pluck($field['attribute'], $connected_entity_key_name);
    } else {
        $field['options'] = call_user_func($field['options'], $field['model']::query())->pluck($field['attribute'], $connected_entity_key_name);
    }

    // make sure the $field['value'] takes the proper value
    $current_value = old_empty_or_null($field['name'], []) ?? ($field['value'] ?? ($field['default'] ?? []));

    if (!empty($current_value) || is_int($current_value)) {
        switch (gettype($current_value)) {
            case 'array':
                $current_value = $connected_entity
                    ->whereIn($connected_entity_key_name, $current_value)
                    ->get()
                    ->pluck($field['attribute'], $connected_entity_key_name);
                break;

            case 'object':
                if (is_subclass_of(get_class($current_value), 'Illuminate\Database\Eloquent\Model')) {
                    $current_value = [$current_value->{$connected_entity_key_name} => $current_value->{$field['attribute']}];
                } else {
                    $current_value = $current_value->pluck($field['attribute'], $connected_entity_key_name);
                }
                break;

            case 'NULL':
                $current_value = [];
                break;

            default:
                $value = $field['options']
                    ->where($connected_entity_key_name, $current_value)
                    ->pluck($field['attribute'], $connected_entity_key_name)
                    ->first();

                $current_value = $value ?? [$current_value => $field['options'][$current_value]];
        }
    }

    $current_value = !is_array($current_value) ? $current_value->toArray() : $current_value;
@endphp

@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
{{-- To make sure a value gets submitted even if the "select multiple" is empty, we need a hidden input --}}
@if ($field['multiple'])
    <input type="hidden" name="{{ $field['name'] }}" value="" @if (in_array('disabled', $field['attributes'] ?? [])) disabled @endif />
@endif
<select style="width:100%" name="{{ $field['name'] . ($field['multiple'] ? '[]' : '') }}"
    data-init-function="bpFieldInitRelationshipSelectElement"
    data-field-is-inline="{{ var_export($inlineCreate ?? false) }}"
    data-column-nullable="{{ var_export($field['allows_null']) }}" data-placeholder="{{ $field['placeholder'] }}"
    data-field-multiple="{{ var_export($field['multiple']) }}"
    data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
    data-is-pivot-select={{ var_export($field['is_pivot_select']) }} bp-field-main-input @include('crud::fields.inc.attributes', ['default_class' => 'form-control'])
    @if ($field['multiple']) multiple @endif>

    @if ($field['allows_null'] && !$field['multiple'])
        <option value="">-</option>
    @endif

    @if (count($field['options']))
        @foreach ($field['options'] as $key => $option)
            @php
                $selected = '';
                if (!empty($current_value)) {
                    if (in_array($key, array_keys($current_value))) {
                        $selected = 'selected';
                    }
                }
            @endphp
            <option value="{{ $key }}" {{ $selected }}>{{ $option }}</option>
        @endforeach
    @endif
</select>

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
{{-- FIELD CSS - will be loaded in the after_styles section --}}
@push('crud_fields_styles')
    {{-- include select2 css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap.min.css') }}">
    <style type="text/css">
        .select2-search__field {
            width: 100% !important;
        }
    </style>
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    {{-- include select2 js --}}
    <script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
    @if (app()->getLocale() !== 'en')
        @basset('https://unpkg.com/select2@4.0.13/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
    @endif

    @bassetBlock('backpack/pro/fields/relationship-select-field-' . app()->getLocale() . '.js')
        <script>
            // if nullable, make sure the Clear button uses the translated string
            document.styleSheets[0].addRule('.select2-selection__clear::after',
                'content:  "{{ trans('backpack::crud.clear') }}";');


            /**
             *
             * This method gets called automatically by Backpack:
             *
             * @param  node element The jQuery-wrapped "select" element.
             * @return void
             */
            function bpFieldInitRelationshipSelectElement(element) {
                var $placeholder = element.attr('data-placeholder');
                var $multiple = element.attr('data-field-multiple') == 'false' ? false : true;
                var $allows_null = (element.attr('data-column-nullable') == 'true') ? true : false;
                var $allowClear = $allows_null;
                var $isFieldInline = element.data('field-is-inline');
                var $isPivotSelect = element.data('is-pivot-select');

                const changePivotOptionState = function(pivotSelector, enable = true) {
                    let containerName = getPivotContainerName(pivotSelector);
                    let pivotsContainer = pivotSelector.closest('div[data-repeatable-holder="' + containerName + '"]');

                    $(pivotsContainer).children().each(function(i, container) {
                        $(container).find('select').each(function(i, el) {

                            if (typeof $(el).attr('data-is-pivot-select') !== 'undefined' && $(el).attr(
                                    'data-is-pivot-select')) {
                                if (pivotSelector.val()) {
                                    if (enable) {
                                        $(el).find('option[value="' + pivotSelector.val() + '"]').prop(
                                            'disabled', false);
                                    } else {
                                        if ($(el).val() !== pivotSelector.val()) {
                                            $(el).find('option[value="' + pivotSelector.val() + '"]').prop(
                                                'disabled', true);
                                        }
                                    }
                                }
                            }
                        });
                    });
                };

                const getPivotContainerName = function(pivotSelector) {
                    let containerName = pivotSelector.data('repeatable-input-name')
                    return containerName.substring(0, containerName.indexOf('['));
                }

                const disablePreviouslySelectedPivots = function(pivotSelector) {

                    let containerName = getPivotContainerName(pivotSelector);
                    let pivotsContainer = pivotSelector.closest('div[data-repeatable-holder="' + containerName + '"]');

                    let selectedValues = [];
                    let selectInputs = [];

                    $(pivotsContainer).children().each(function(i, container) {
                        $(container).find('select').each(function(i, el) {
                            if (typeof $(el).attr('data-is-pivot-select') !== 'undefined' && $(el).attr(
                                    'data-is-pivot-select') != "false") {
                                selectInputs.push(el);
                                if ($(el).val()) {
                                    selectedValues.push($(el).val());
                                }
                            }
                        });
                    });

                    selectInputs.forEach(function(input) {
                        selectedValues.forEach(function(value) {
                            if (value !== $(input).val()) {
                                $(input).find('option[value="' + value + '"]').prop('disabled', true);
                            }
                        });
                    });
                };

                var $select2Settings = {
                    theme: 'bootstrap',
                    multiple: $multiple,
                    placeholder: $placeholder,
                    allowClear: $allowClear,
                    dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : $(document.body)
                };
                if (!$(element).hasClass("select2-hidden-accessible")) {
                    $(element).select2($select2Settings);

                    if ($isPivotSelect) {
                        disablePreviouslySelectedPivots($(element));
                    }
                }

                if ($isPivotSelect) {
                    $(element).on('select2:selecting', function(e) {
                        if ($(this).val()) {
                            changePivotOptionState($(this));
                        }
                        return true;
                    });

                    $(element).on('select2:select', function(e) {
                        changePivotOptionState($(this), false);
                        return true;
                    });

                    $(element).on('CrudField:delete', function(e) {
                        changePivotOptionState($(this));
                        return true;
                    });
                }

            }
        </script>
    @endBassetBlock
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
