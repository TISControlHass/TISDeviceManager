@php
    $value = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));
    if (!empty($value)) {
        session()->put('morphTypeFieldValue', $value);
    }
@endphp
@include('crud::fields.inc.wrapper_start')
@include('crud::fields.inc.translatable_icon')
<select name="{{ $field['name'] }}" data-init-function="bpFieldInitMorphTypeSelectElement"
    data-field-is-inline="{{ var_export($inlineCreate ?? false) }}" @include('crud::fields.inc.attributes', ['default_class' => 'form-control'])>
    @if (count($field['options']))
        @foreach ($field['options'] as $key => $optionValue)
            @if ($key == $value || (is_array($value) && in_array($key, $value)))
                <option value="{{ $key }}" selected>{{ $optionValue }}</option>
            @else
                <option value="{{ $key }}" @if ($loop->first && empty($value)) selected @endif>{{ $optionValue }}
                </option>
            @endif
        @endforeach
    @endif
</select>

{{-- HINT --}}
@if (isset($hint))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')

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
    @bassetBlock('backpack/pro/fields/relationship-morphto-type-select-field.js')
        <script>
            function bpFieldInitMorphTypeSelectElement(element) {
                if (!element.hasClass("select2-hidden-accessible")) {
                    let $isFieldInline = element.data('field-is-inline');
                    element.select2({
                        theme: "bootstrap",
                        allowClear: false,
                        multiple: false,
                        dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : $(document.body)
                    });
                }
            }
        </script>
    @endBassetBlock
@endpush
