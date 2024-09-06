@if ($hidden ?? false)
<div class="d-none">
@endif

<div class="col-md-12 well repeatable-element row m-1 p-2" data-repeatable-identifier="{{ $field['name'] }}">
    @if (isset($field['subfields']) && is_array($field['subfields']) && count($field['subfields']))
    <div class="controls">
        <button type="button" class="close delete-element"><span aria-hidden="true">×</span></button>
        @if ($field['reorder'])
        <button type="button" class="close move-element-up">
            <svg viewBox="0 0 64 80"><path d="M46.8,36.7c-4.3-4.3-8.7-8.7-13-13c-1-1-2.6-1-3.5,0c-4.3,4.3-8.7,8.7-13,13c-2.3,2.3,1.3,5.8,3.5,3.5c4.3-4.3,8.7-8.7,13-13c-1.2,0-2.4,0-3.5,0c4.3,4.3,8.7,8.7,13,13C45.5,42.5,49,39,46.8,36.7L46.8,36.7z"/></svg>
        </button>
        <button type="button" class="close move-element-down">
            <svg viewBox="0 0 64 80"><path d="M17.2,30.3c4.3,4.3,8.7,8.7,13,13c1,1,2.6,1,3.5,0c4.3-4.3,8.7-8.7,13-13c2.3-2.3-1.3-5.8-3.5-3.5c-4.3,4.3-8.7,8.7-13,13c1.2,0,2.4,0,3.5,0c-4.3-4.3-8.7-8.7-13-13C18.5,24.5,15,28,17.2,30.3L17.2,30.3z"/></svg>
        </button>
        @endif
    </div>
    @foreach($field['subfields'] as $subfield)
        @php
            $subfieldView = $crud->getFirstFieldView($subfield['type'], $subfield['view_namespace'] ?? false);

            foreach((array)$subfield['name'] as $subfieldName) {
                if($crud->isRequired($field['name'].'.'.$subfieldName)) {
                    $subfield['showAsterisk'] = $field['showAsterisk'] ?? true;
                }
            }

            if(isset($row)) {
                // Cast objects to array (could be a translation object, or a developer provided collection).
                if (is_object($row)) {
                    $row = collect($row)->toArray();
                }
                if(!is_array($subfield['name'])) {
                    if(!Str::contains($subfield['name'], '.')) {
                        // this is a fix for 4.1 repeatable names that when the field was multiple, saved the keys with `[]` in the end. Eg: `tags[]` instead of `tags`
                        if(isset($row[$subfield['name']]) || isset($row[$subfield['name'].'[]'])) {
                            $subfield['value'] = $row[$subfield['name']] ?? $row[$subfield['name'].'[]'];
                        }
                        $subfield['name'] = $field['name'].'['.$repeatable_row_key.']['.$subfield['name'].']';
                    }else{
                        $subfield['value'] = \Arr::get($row, $subfield['name']);
                        $subfield['name'] = $field['name'].'['.$repeatable_row_key.']['.Str::replace('.', '][', $subfield['name']).']';
                    }
                }else{
                    foreach ($subfield['name'] as $k => $item) {
                        $subfield['name'][$k] = $field['name'].'['.$repeatable_row_key.']['.$item.']';
                        $subfield['value'][$subfield['name'][$k]] = \Arr::get($row, $item);
                    }
                }
            } else {
                // use an un-matchable field name to avoid field initialization problems
                // this would prevent the field from EVER get value in old() and triggering errors.
                if(!is_array($subfield['name'])) {
                    $subfield['name'] = $field['name'].'[-1]['.$subfield['name'].']';
                }else{
                    foreach($subfield['name'] as $k => $subfieldName) {
                        $subfield['name'][$k] = $field['name'].'[-1]['.$subfieldName.']';
                    }
                }
            }

           
        @endphp

        @include($subfieldView, ['field' => $subfield])
    @endforeach

    @endif
</div>


@if ($hidden ?? false)
</div>
@endif
