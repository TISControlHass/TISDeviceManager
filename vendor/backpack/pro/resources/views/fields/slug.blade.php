@include('crud::fields.text')

@if(isset($field['target']) && $field['target'] != null && $field['target'] != '')
  @push('after_scripts')
    <script>
        crud.field('{{ $field['target'] }}').onChange(field => {
          let slug = field.value.toString().toLowerCase().trim()
              .normalize('NFD')                // separate accent from letter
              .replace(/[\u0300-\u036f]/g, '') // remove all separated accents
              .replace(/\s+/g, '-')            // replace spaces with -
              .replace(/[^\w\-]+/g, '')        // remove all non-word chars
              .replace(/\-\-+/g, '-')          // replace multiple '-' with single '-'

          crud.field('{{ $field['name'] }}').input.value = slug;
        });
    </script>
  @endpush
@endif
