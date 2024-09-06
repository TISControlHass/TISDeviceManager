@php
    $field['type'] = 'relationship.morphTo_select_ajax';
    $optionsForModels = [];
    foreach ($field['morphOptions'] as $model => $options) {
        if (!isset($options['data_source'])) {
            continue;
        }

        if (!is_a($model, 'Illuminate\Database\Eloquent\Model', true)) {
            $model = $field['morphMap'][$model];
        }

        $modelInstance = new $model();

        $optionsForModels[$model]['attribute'] = $options['attribute'] ?? $modelInstance->identifiableAttribute();
        $optionsForModels[$model]['minimumInputLength'] = $options['minimum_input_length'] ?? 0;
        $optionsForModels[$model]['ajax']['url'] = $options['data_source'];
        $optionsForModels[$model]['ajax']['method'] = $options['method'] ?? 'POST';
        $optionsForModels[$model]['placeholder'] = $options['placeholder'] ?? $field['placeholder'];
        $optionsForModels[$model]['connectedKey'] = $modelInstance->getKeyName();
    }

    $currentValue = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));

    if (!empty($currentValue)) {
        $currentValue = (function () use ($currentValue, $field, $optionsForModels) {
            $getValueFrom = function ($modelName, $value) use ($optionsForModels, $field) {
                if (!is_a($modelName, 'Illuminate\Database\Eloquent\Model', true)) {
                    $modelName = $field['morphMap'][$modelName];
                }

                // it's not an ajax morph option
            if (!array_key_exists($modelName, $optionsForModels)) {
                return;
            }

            $relatedModel = (new $modelName())->find($value);
            if ($relatedModel) {
                return [$relatedModel->getKey() => $relatedModel->{$optionsForModels[$modelName]['attribute']}];
            }
        };

        if (session()->has('morphTypeFieldValue')) {
            $modelName = session()->get('morphTypeFieldValue');
            if (!empty($modelName)) {
                session()->remove('morphTypeFieldValue');
                    return $getValueFrom($modelName, $currentValue);
                }
            }
        })();
    }
    //dd($optionsForModels);
@endphp

@include('crud::fields.inc.wrapper_start')
<select style="width:100%;" name="{{ $field['name'] }}" data-init-function="bpFieldInitMorphToSelectAjaxElement"
    data-field-is-inline="{{ var_export($inlineCreate ?? false) }}" data-morph-map={{ json_encode($field['morphMap']) }}
    data-tmp-name="{{ $field['name'] }}"
    @foreach ($optionsForModels as $key => $modelOptions) 
            data-morph-model-options-{{ $key }}="{{ json_encode($modelOptions) }}" @endforeach
    @include('crud::fields.inc.attributes', ['default_class' => 'form-control'])>
    <option value="">-</option>
    @if (!empty($currentValue))
        @foreach ($currentValue as $key => $item)
            <option value="{{ $key }}" selected>
                {{ $item }}
            </option>
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
    @bassetBlock('backpack/pro/fields/relationship-morphto-select-ajax-field-' . app()->getLocale() . '.js')
        <script>
            // helper function to merge two objects recursively. 
            // some libraries like loadash and similar already handle this.
            // got it sometime ago from web, been using it for some time, no issues.
            // TODO: maybe move this as a core crud js function ?
            if (typeof deepMerge !== 'function') {
                function deepMerge(target, source) {
                    if (typeof target !== 'object' || typeof source !== 'object')
                        return false; // target or source or both ain't objects, merging doesn't make sense
                    for (var prop in source) {
                        if (!source.hasOwnProperty(prop)) continue; // take into consideration only object's own properties.
                        if (prop in target) { // handling merging of two properties with equal names
                            if (typeof target[prop] !== 'object') {
                                target[prop] = source[prop];
                            } else {
                                if (typeof source[prop] !== 'object') {
                                    target[prop] = source[prop];
                                } else {
                                    target[prop] = deepMerge(target[prop], source[prop]);
                                }
                            }
                        } else { // add the new properties on the target
                            target[prop] = source[prop];
                        }
                    }
                    return target;
                }
            }

            if (typeof processItemText !== 'function') {
                function processItemText(item, fieldAttribute = null) {
                    var appLang = '{{ app()->getLocale() }}';
                    var appLangFallback = '{{ Lang::getFallback() }}';
                    var emptyTranslation = '{{ trans('backpack::crud.empty_translations') }}';
                    var itemField = fieldAttribute !== null ? item[fieldAttribute] ?? item : item;
                    if (typeof itemField === 'string') {
                        try {
                            itemField = JSON.parse(itemField);
                        } catch (e) {
                            return itemField
                        }
                    }
                    // try to retreive the item in app language; then fallback language; then first entry; if nothing found empty translation string
                    return typeof itemField === 'object' && itemField !== null ?
                        itemField[appLang] ? itemField[appLang] : itemField[appLangFallback] ? itemField[appLangFallback] :
                        Object.values(itemField)[0] ? Object.values(itemField)[0] : emptyTranslation :
                        itemField;
                }
            }
            /**
             *
             * This method gets called automatically by Backpack:
             *
             * @param  node element The jQuery-wrapped "select" element.
             * @return void
             */
            function bpFieldInitMorphToSelectAjaxElement(element) {

                let isFieldInline = element.data('field-is-inline');
                let placeholder = element.data('placeholder');
                let morphTypeSelect = $('[' + element.data('morph-select') + ']');

                var form = element.closest('form');
                var includeAllFormFields = element.attr('data-include-all-form-fields') == 'false' ? false : true;
                var ajaxDelay = element.attr('data-ajax-delay');
                var fieldCleanName = element.data('repeatable-input-name') ?? element.attr('name');

                let select2Options = {
                    theme: 'bootstrap',
                    multiple: false,
                    allowClear: true,
                    dropdownParent: isFieldInline ? $('#inline-create-dialog .modal-content') : $(document.body),
                    ajax: {
                        dataType: 'json',
                        data: function(params) {
                            if (includeAllFormFields) {
                                return {
                                    q: params.term, // search term
                                    page: params.page, // pagination
                                    form: form.serializeArray(), // all other form inputs
                                    triggeredBy: {
                                        'rowNumber': element.attr('data-row-number') !== 'undefined' ? element.attr(
                                            'data-row-number') - 1 : false,
                                        'fieldName': fieldCleanName
                                    }
                                };
                            } else {
                                return {
                                    q: params.term, // search term
                                    page: params.page, // pagination
                                };
                            }
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;

                            //if we have data.data here it means we returned a paginated instance from controller.
                            //otherwise we returned one or more entries unpaginated.
                            let paginate = false;

                            if (data.data) {
                                paginate = data.current_page < data.last_page;
                                data = data.data;
                            }

                            return {
                                results: $.map(data, function(item) {
                                    var itemText = processItemText(item, select2Options['attribute']);
                                    return {
                                        text: itemText,
                                        id: item[select2Options['connectedKey']],
                                    }
                                }),
                                pagination: {
                                    more: paginate,
                                }
                            };
                        },
                        cache: true
                    },
                }

                if (typeof handleAjaxSelect2Init !== 'function') {
                    function handleAjaxSelect2Init(element, modelName) {
                        let morphMap = element.data('morph-map');

                        if (modelName.indexOf('\\') === -1) {
                            modelName = morphMap[modelName];
                        }
                        if (typeof element.data('morph-model-options-' + modelName) !== 'undefined') {
                            let optionsForSelect = element.data('morph-model-options-' + modelName);
                            optionsForSelect = deepMerge(select2Options, optionsForSelect);
                            element.attr('name', element.data('tmp-name'));
                            element.select2(optionsForSelect);
                            element.parent().show();
                        } else {
                            if (element.hasClass("select2-hidden-accessible")) {
                                element.select2('destroy');
                                element.removeClass("select2-hidden-accessible")
                            }
                            element.parent().hide();
                            element.removeAttr('name');
                        }
                    }
                }
                if (typeof morphTypeSelect !== 'undefined' && morphTypeSelect.val()) {
                    let modelName = morphTypeSelect.val().toLowerCase();
                    handleAjaxSelect2Init(element, modelName);
                }
                if (typeof morphTypeSelect !== 'undefined') {
                    morphTypeSelect.on('change', function(e) {
                        element.find('option').remove();
                        element.data('current-value', '');
                        let modelName = e.target.value.toLowerCase();

                        handleAjaxSelect2Init(element, modelName);
                    });
                }
            }
        </script>
    @endBassetBlock
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
