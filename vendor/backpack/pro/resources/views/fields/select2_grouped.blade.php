{{-- select2 --}}
@php
    $current_value = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));
    $field['allows_null'] = $field['allows_null'] ?? $field['model']::isColumnNullable($field['name']);
    $field['placeholder'] = $field['placeholder'] ?? trans('backpack::crud.select_entry');
@endphp

@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')
@php
    $related_model = $field['model'];
    $group_by_model = (new $related_model())->{$field['group_by']}()->getRelated();
    $categories = $group_by_model::with($field['group_by_relationship_back'])->get();

    if (isset($field['model'])) {
        $categorylessEntries = $related_model::doesnthave($field['group_by'])->get();
    }
@endphp
<select name="{{ $field['name'] }}" style="width: 100%" data-init-function="bpFieldInitSelect2GroupedElement"
    data-field-is-inline="{{ var_export($inlineCreate ?? false) }}"
    data-language="{{ str_replace('_', '-', app()->getLocale()) }}" data-field-placeholder="{{ $field['placeholder'] }}"
    data-field-allow-clear="{{ var_export($field['allows_null']) }}" @include('crud::fields.inc.attributes', ['default_class' => 'form-control select2_field'])>

    @if ($field['allows_null'])
        <option value="">-</option>
    @endif

    @if (isset($field['model']) && isset($field['group_by']))
        @foreach ($categories as $category)
            <optgroup label="{{ $category->{$field['group_by_attribute']} }}">
                @foreach ($category->{$field['group_by_relationship_back']} as $subEntry)
                    <option value="{{ $subEntry->getKey() }}" @if (
                        (old($field['name']) && old($field['name']) == $subEntry->getKey()) ||
                            (isset($field['value']) && $subEntry->getKey() == $field['value'])) selected @endif>
                        {{ $subEntry->{$field['attribute']} }}</option>
                @endforeach
            </optgroup>
        @endforeach

        @if ($categorylessEntries->count())
            <optgroup label="-">
                @foreach ($categorylessEntries as $subEntry)
                    @if ($current_value == $subEntry->getKey())
                        <option value="{{ $subEntry->getKey() }}" selected>{{ $subEntry->{$field['attribute']} }}
                        </option>
                    @else
                        <option value="{{ $subEntry->getKey() }}">{{ $subEntry->{$field['attribute']} }}</option>
                    @endif
                @endforeach
            </optgroup>
        @endif
    @endif
</select>

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}

{{-- FIELD CSS - will be loaded in the after_styles section --}}
@push('crud_fields_styles')
    {{-- select2_grouped field type css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap.min.css') }}">
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    {{-- select2_grouped field type js --}}
    <script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
    @if (app()->getLocale() !== 'en')
        @basset('https://unpkg.com/select2@4.0.13/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
    @endif
    @bassetBlock('backpack/pro/fields/select2-grouped-field.js')
        <script>
            function bpFieldInitSelect2GroupedElement(element) {
                if (!element.hasClass("select2-hidden-accessible")) {
                    let isFieldInline = element.data('field-is-inline');
                    let placeholder = element.data('field-placeholder');
                    let allowClear = element.data('field-allow-clear');

                    element.select2({
                        theme: "bootstrap",
                        placeholder: placeholder,
                        allowClear: allowClear,
                        dropdownParent: isFieldInline ? $('#inline-create-dialog .modal-content') : $(document.body)
                    });
                }
            }
        </script>
    @endBassetBlock
@endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
