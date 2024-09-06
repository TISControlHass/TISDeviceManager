@php
    $field['type'] = 'relationship.morphTo_select';
    $optionsForModels = [];
    foreach ($field['morphOptions'] as $model => $modelOptions) {
        if (isset($modelOptions['data_source']) || ($modelOptions['ajax'] ?? false)) {
            continue;
        }

        if (!is_a($model, 'Illuminate\Database\Eloquent\Model', true)) {
            $model = $field['morphMap'][$model];
        }

        $modelInstance = new $model();
        $modelAttribute = $modelOptions['attribute'] ?? $modelInstance->identifiableAttribute();
        if (isset($modelOptions['options']) && is_array($modelOptions['options'])) {
            $optionsForModels[$model] = $modelOptions;
            continue;
        }

        if (is_callable($modelOptions['query'] ?? [])) {
            $optionsForModels[$model] = $modelOptions['query']($modelInstance->toBase())->pluck($modelAttribute, $modelInstance->getKeyName())->toArray();
            continue;
        }

        $optionsForModels[$model] = $modelInstance->toBase()->pluck($modelAttribute, $modelInstance->getKeyName())->toArray();
    }

    $currentValue = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));
@endphp

@include('crud::fields.inc.wrapper_start')
<select name="{{ $field['name'] }}" data-init-function="bpFieldInitMorphToSelectElement"
    data-field-is-inline="{{ var_export($inlineCreate ?? false) }}" data-placeholder="{{ $field['placeholder'] }}"
    data-current-value="{{ $currentValue }}" data-morph-map={{ json_encode($field['morphMap']) }}
    data-tmp-name="{{ $field['name'] }}"
    @foreach ($optionsForModels as $key => $ids) 
            morph-model-options-{{ $key }}="{{ json_encode($ids) }}" @endforeach
    @include('crud::fields.inc.attributes', ['default_class' => 'form-control'])>
    <option value="">-</option>
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
    <!-- include select2 css-->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap.min.css') }}">
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    <!-- include select2 js-->
    <script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
    @if (app()->getLocale() !== 'en')
        @basset('https://unpkg.com/select2@4.0.13/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
    @endif
@endpush

@push('crud_fields_scripts')
    @bassetBlock('backpack/pro/fields/relationship-morphto-select-field-' . app()->getLocale() . '.js')
        <script>
            if (typeof processItemText !== 'function') {
                function processItemText(item, $morphIdFieldAttribute = null) {
                    var $appLang = '{{ app()->getLocale() }}';
                    var $appLangFallback = '{{ Lang::getFallback() }}';
                    var $emptyTranslation = '{{ trans('backpack::crud.empty_translations') }}';
                    var $itemField = $morphIdFieldAttribute !== null ? item[$morphIdFieldAttribute] ?? item : item;
                    if (typeof $itemField === 'string') {
                        try {
                            $itemField = JSON.parse($itemField);
                        } catch (e) {
                            return $itemField
                        }
                    }
                    // try to retreive the item in app language; then fallback language; then first entry; if nothing found empty translation string
                    return typeof $itemField === 'object' && $itemField !== null ?
                        $itemField[$appLang] ? $itemField[$appLang] : $itemField[$appLangFallback] ? $itemField[
                            $appLangFallback] : Object.values($itemField)[0] ? Object.values($itemField)[0] :
                        $emptyTranslation :
                        $itemField;
                }
            }
            /**
             *
             * This method gets called automatically by Backpack:
             *
             * @param  node element The jQuery-wrapped "select" element.
             * @return void
             */
            function bpFieldInitMorphToSelectElement(element) {

                let isFieldInline = element.data('field-is-inline');
                let placeholder = element.data('placeholder');
                let morphTypeSelect = $('[' + element.data('morph-select') + ']');

                const addOptionsInSelectFor = function(select, model) {
                    let options = JSON.parse(select.attr('morph-model-options-' + model))
                    let attribute = select.attr('morph-model-attribute-' + model)
                    let elementCurrentValue = select.data('current-value');

                    for (const [index, value] of Object.entries(options)) {
                        let optionText = processItemText(value);
                        var selected = false;

                        if (elementCurrentValue == index) {
                            selected = true;
                        }
                        select.append(new Option(optionText, index, false, selected));
                    }
                    select.trigger('change');
                }

                const handleSelectOptionState = function(element, modelName) {
                    let morphMap = element.data('morph-map');
                    if (modelName.indexOf('\\') === -1) {
                        modelName = morphMap[modelName];
                    }
                    if (typeof element.attr('morph-model-options-' + modelName) !== 'undefined') {
                        element.attr('name', element.data('tmp-name'));
                        element.parent().show();
                        addOptionsInSelectFor(element, modelName);
                    } else {
                        element.parent().hide();
                        element.removeAttr('name');

                    }
                }

                let select2Settings = {
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: placeholder,
                    allowClear: true,
                    dropdownParent: isFieldInline ? $('#inline-create-dialog .modal-content') : $(document.body)
                };

                if (!$(element).hasClass("select2-hidden-accessible")) {
                    $(element).select2(select2Settings);
                }

                if (morphTypeSelect.val()) {
                    let modelName = morphTypeSelect.val().toLowerCase();
                    handleSelectOptionState(element, modelName)
                }

                morphTypeSelect.on('change', function(e) {
                    element.find('option:not(:first)').remove();
                    element.data('current-value', '');
                    let modelName = e.target.value.toLowerCase();
                    handleSelectOptionState(element, modelName);
                });
            }
        </script>
    @endBassetBlock
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
