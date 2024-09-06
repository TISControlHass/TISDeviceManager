@php
    $loadedAssets = json_decode($parentLoadedAssets ?? '[]', true);

    //mark parent crud assets as loaded.
    foreach($loadedAssets as $asset) {
        Basset::markAsLoaded($asset);
    }
@endphp

<div class="modal modal-blur fade" id="inline-create-dialog" tabindex="0" data-backdrop="static" data-bs-backdrop="static" role="dialog" aria-labelledby="{{$entity}}-inline-create-dialog-label" aria-hidden="true">
    <div class="{{ $modalClass }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $entity }}-inline-create-dialog-label">
                    {!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}
                </h5>
                <button type="button" class="btn-close close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <form method="post"
                      id="{{$entity}}-inline-create-form"
                      action="#"
                      onsubmit="return false"
                      @if ($crud->hasUploadFields('create'))
                      enctype="multipart/form-data"
                        @endif
                >
                    {!! csrf_field() !!}

                    @include($crud->getFirstFieldView('relationship.inc.form_content'), [ 'fields' => $fields, 'action' => $action])

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelButton">{{trans('backpack::crud.cancel')}}</button>
                <button type="button" class="btn btn-primary" id="saveButton">{{trans('backpack::crud.save')}}</button>
            </div>
        </div>
    </div>
</div>

{{-- This is where modal fields assets are pushed.
We bind the modal content including what fields pushed to this stacks to the end of the body tag,
so we make sure all backpack assets are previously loaded, like jquery etc.
We can give the same stack name because backpack `crud_fields_scripts` is already rendered and
this is the only available when rendering the modal html. --}}

@stack('crud_fields_scripts')
<script>
    // Focus on first focusable field when modal is shown
    $('#inline-create-dialog').on('shown.bs.modal', function () {
        $(this).find('form').find('input, select, textarea, button').not('[readonly]').not('[disabled]').filter(':visible:first').focus();
    });
</script>

@stack('crud_fields_styles')

@stack('after_scripts')

@stack('after_styles')