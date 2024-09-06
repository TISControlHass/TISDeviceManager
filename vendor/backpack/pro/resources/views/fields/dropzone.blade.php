@php
    $field['configuration'] ??= [];
    $defaultConfig = [
        'url' => $field['configuration']['url'] ?? url($crud->route . '/dropzone/upload'),
        'headers' => array_merge($field['configuration']['headers'] ?? [], ['X-CSRF-TOKEN' => csrf_token()]),
        'parallelUploads' => $field['configuration']['parallelUploads'] ?? 4,
        'uploadMultiple' => true,
        'maxThumbnailFilesize' => $field['configuration']['maxThumbnailFilesize'] ?? 100
    ];

    $field['configuration'] = array_merge($field['configuration'], $defaultConfig);
    $field['value'] = old_empty_or_null($field['name'], '') ?? $field['value'] ?? $field['default'] ?? '';
   
    if(is_string($field['value']) && !empty($field['value'])) {
        $field['value'] = json_decode($field['value'], true) ?? '';
    }
    $temporaryDisk = CRUD::get('dropzone.temporary_disk');
    $temporaryDirectory = CRUD::get('dropzone.temporary_folder');

    if (is_array($field['value'])) {
        $serverFiles = [];

        foreach ($field['value'] as $key => $path) {
            $disk = strpos($path, $temporaryDirectory) !== false ? $temporaryDisk : $field['disk'];
            try {
                $serverFiles[] = [
                    'name' => basename($path),
                    'size' => \Storage::disk($disk)->size($path),
                    'mime' => \Storage::disk($disk)->mimeType($path),
                    'path' => $path,
                    'url' => Storage::disk($disk)->url($path),
                ];
            } catch (\Exception) {
                $serverFiles[] = [
                    'name' => basename($path),
                    'size' => -1,
                    'mime' => '',
                    'path' => $path,
                    'url' => Storage::disk($disk)->url($path),
                ];
            }
        }

        $field['server_files'] = json_encode($serverFiles, true);

        $field['value'] = json_encode($field['value'], true);    
    }
    
    $readonly = $field['attributes']['readonly'] ?? false;
    $disabled = $field['attributes']['disabled'] ?? false;
@endphp

@include('crud::fields.inc.wrapper_start')
    <input
        type="hidden"
        name="{{ $field['name'] }}"
        bp-field-main-input
        value="{{ $field['value'] }}"
        @include('crud::fields.inc.attributes')
    >

    <label>{!! $field['label'] !!}</label>

    @include('crud::fields.inc.translatable_icon')

    <div
        class="dropzone dropzone-target {{ $disabled ? 'disabled' : '' }} {{ $readonly ? 'readonly' : '' }}"
        data-config="{{json_encode($field['configuration'])}}"
        data-init-function="bpFieldInitDropzoneElement"
        data-name="{{ $field['name'] }}"
        data-server-files="{{ $field['server_files'] ?? '' }}"
        data-form-operation="{{ $crud->get('dropzone.formOperation') }}"
        data-temp-upload-folder-name="{{ $temporaryDirectory }}"
        data-is-dropzone-active="{{ var_export(($disabled || $readonly) ? false : true) }}">
        <div class="dz-message">
            <button class="dz-button" type="button">{!! trans('backpack/pro::dropzone.click_or_drop_files') !!}</button>
        </div>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

    <div class="hidden hidden-container"></div>
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        {{-- include dropzone css --}}
        @basset('https://www.unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone.css')
        @bassetBlock('backpack/pro/fields/dropzone-field.css')
        <style>

        .dropzone {
            border: 1px solid rgba(0, 40, 100, 0.2);
            min-height: 4.5rem;
        }

        .dropzone.disabled, .dropzone.readonly {
            background-color: #f8f9fa;
        }

        .dropzone .dz-preview.dz-image-preview .dz-details {
            cursor: move;
        }
        .dropzone .dz-preview {
            visibility: hidden;
        }
        .dropzone .dz-progress {
            visibility: visible;
        }

        .dropzone .dz-message {
            margin: 0 auto;
        }
        .dropzone.dz-started .dz-message {
            display: block;
        }
        .dropzone.disabled .dz-message, .dropzone.readonly .dz-message {
            display: none;
        }

        .dropzone.disabled .dz-preview, .dropzone.readonly .dz-preview {
            opacity: 0.5;
        }
        .dropzone .dz-preview .dz-image, .dropzone .dz-preview.dz-file-preview .dz-image {
            border-radius: 0.4rem;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; 
        }

        .dropzone.disabled .dz-preview .dz-image, .dropzone.disabled .dz-preview.dz-file-preview .dz-image, .dropzone.readonly .dz-preview .dz-image, .dropzone.readonly .dz-preview.dz-file-preview .dz-image {
            border-radius: 0.4rem;
        }
        .dropzone.dz-clickable {
            cursor: auto;
        }
        .dropzone .dz-preview .dz-remove i {
            cursor: pointer;
        }

        .dropzone.disabled .dz-remove, .dropzone.readonly .dz-remove {
            display: none;
        }

        .dropzone .dz-preview .dz-image img {
            height: 100%;
            object-fit: cover;
        }

        .dropzone .dz-remove i {
            border: 1px solid;
            border-radius: 50%;
        }

        .dropzone .dz-remove i::before {
            padding-top:2px;
        }

        [data-bs-theme=dark] .dropzone .dz-preview.dz-image-preview {
           background: inherit;
        }

        [data-bs-theme=dark] .dropzone {
            border: 1px solid rgba(78, 78, 79, 0.3);
        }

        [data-bs-theme=dark] .dropzone.disabled, [data-bs-theme=dark] .dropzone.readonly {
            background-color: inherit;
            color: inherit;
        }
        
        </style>
        @endBassetBlock
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        {{-- include dropzone js --}}
        @basset('https://www.unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone-min.js')
        @basset('https://www.unpkg.com/sortablejs@1.15.0/Sortable.min.js')

        @bassetBlock('backpack/pro/fields/dropzone-field.js')
        <script>
            function bpFieldInitDropzoneElement(element) {
                const dz = element[0];
                let $dropzoneConfig = JSON.parse(dz.dataset.config);
                let input = dz.parentNode.querySelector('input[type="hidden"]');
                let dropzoneHiddenContainer = dz.parentNode.querySelector('div.hidden-container');
                let formOperation = dz.dataset.formOperation;
                // always ensure that the random id starts with a letter, it will break the selector if it starts
                // with a number. **sad pikachu face**
                let randomId = "abcdefghijklmnopqrstuvwxyz"[Math.floor(Math.random() * 26)]+Math.random().toString(36).substring(2, 10+2);

                let isDropzoneActive = dz.dataset.isDropzoneActive;

                dz.setAttribute('data-name', input.getAttribute('name'));

                dropzoneHiddenContainer.classList.add(randomId + '-bp-dropzone-hidden-input');

                $dropzoneConfig.paramName = input.getAttribute('data-repeatable-input-name') !== null ? dz.parentNode.parentNode.getAttribute('data-repeatable-identifier')+'#'+input.getAttribute('data-repeatable-input-name') : input.getAttribute('name');
                $dropzoneConfig.hiddenInputContainer = 'div.' + randomId + '-bp-dropzone-hidden-input';

                Dropzone.autoDiscover = false;

                if (!$dropzoneConfig.init) {
                    $dropzoneConfig.init = function () {
                        this.on('addedfile', function(file) {
                            var removeButton = Dropzone.createElement('<div class="dz-remove" data-dz-remove=""><i class="la la-remove"></i></div>');
                            var _this = this;

                            removeButton.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();

                                if (isDropzoneActive) {
                                    _this.removeFile(file);
                                }
                            });

                            file.previewElement.appendChild(removeButton);

                            input.dispatchEvent(new Event('change', {bubbles:true}));
                        });

                        const serverFiles = dz.dataset.serverFiles;
                        if (!serverFiles) {
                            return;
                        }

                        let files = JSON.parse(serverFiles);

                        files.forEach((file) => {
                            this.emit('addedfile', file);
                            if (file.url && file.mime?.startsWith('image')) {
                                this.emit('thumbnail', file, file.url);
                            }
                            this.files.push(file);
                            file.accepted = true;
                            this._updateMaxFilesReachedClass();
                            file.previewElement.querySelector('.dz-progress').style.visibility = 'hidden';
                            file.previewElement.style.visibility = 'visible';
                        
                            if(file.size === -1) {
                                file.previewElement.querySelector('.dz-size').innerHTML = 'File Error';
                                file.previewElement.querySelector('.dz-details').classList.add('text-danger');
                            }
                        });
                    };
                }

                if (!$dropzoneConfig.successmultiple) {
                    $dropzoneConfig.successmultiple = function (files, response, request) {
                        let inputFiles = input.value ?? [];

                        if (inputFiles) {
                            inputFiles = JSON.parse(inputFiles);
                            inputFiles = Object.values(inputFiles)
                        }

                        newFiles = response.files;

                        let mergedFiles = [...inputFiles, ...newFiles];
                        input.value = mergedFiles.length > 0 ? JSON.stringify(mergedFiles) : '';

                        dz.parentNode.classList.remove('text-danger');
                        dz.parentNode.querySelector('.invalid-feedback')?.remove();

                        files.forEach(function(file, index) {
                            file.previewElement.style.visibility = 'visible';
                            file.upload.filename = response.files[index];
                            let uploadedFileNameContainer = file.previewElement.querySelector('[data-dz-name]');
                            uploadedFileNameContainer.innerHTML = response.files[index];
                        });
                    };
                }

                if(!$dropzoneConfig.error) {
                    $dropzoneConfig.error = function (file, response, request) {
                        if(response) {
                            let errorBagName = $dropzoneConfig.paramName;
                            // it's a repeatable dropzone container
                            if(errorBagName.includes('#')) {
                                errorBagName = errorBagName.replace('#', '.0.');
                            }
                            let errorMessages = typeof response.errors !== 'undefined' ? response.errors[errorBagName].join('<br/>') : response + '<br/>';
                            let errorNode = dz.querySelector('.dz-error-message span');
                            // remove previous error messages
                            dz.parentNode.querySelector('.invalid-feedback')?.remove();

                            // add the red text classes
                            dz.parentNode.classList.add('text-danger');

                            // create the error message container
                            let errorContainer = document.createElement("div");
                            errorContainer.classList.add('invalid-feedback', 'd-block');
                            errorContainer.innerHTML = errorMessages;
                            dz.parentNode.appendChild(errorContainer);

                            // remove the preview for failed uploads
                            file.previewElement.remove();
                        }
                    };
                }

                $dropzoneConfig.removedfile = function (file, xhr) {
                    let filePath = file;

                    if (file.xhr) {
                        filePath = file.upload.filename;
                    }

                    let tempUploadFolderName = dz.dataset.tempUploadFolderName;
                    let inputFiles = input.value;
                    let files = inputFiles ? JSON.parse(inputFiles) : [];

                    if (!filePath.path && filePath.includes(tempUploadFolderName)) {
                        $.ajax({
                            url: '{{ url($crud->route. '/dropzone/delete') }}',
                            type: 'POST',
                            data: `file=${filePath}`,
                            success: function (data) {
                                if (data.success) {

                                    file.previewElement?.remove();
                                    files.splice(files.findIndex(obj => (obj === filePath)), 1);
                                    input.value = files.length > 0 ? JSON.stringify(files) : '';
                                }
                            }
                        });
                    } else {
                        files = Array.isArray(files) ? files : Object.values(files);

                        let fileToDelete = files.find((obj) => obj === filePath.path);

                        if (fileToDelete) {
                            file.previewElement?.remove();
                            files.splice(files.findIndex(obj => (obj === filePath.path)), 1);
                        }

                        input.value = files.length > 0 ? JSON.stringify(files) : '';
                    }
                    input.dispatchEvent(new Event('change', {bubbles: true}));
                };

                $dropzoneConfig.sending = function (file, xhr, formData) {
                    formData.append('previousUploadedFiles', input.value);
                    formData.append('operation', formOperation);
                    formData.append('fieldName', $dropzoneConfig.paramName);
                };

   
                let dropzone = new Dropzone(dz, $dropzoneConfig);

                let sortable = new Sortable(dz, {
                    handle: '.dz-preview',
                    draggable: '.dz-preview',
                    scroll: false,
                    onEnd: function (evt) {
                        const currentSort = input.value;

                        let files = currentSort ? JSON.parse(currentSort) : [];
                        var newSort = files.splice(evt.oldIndex - 1, 1)[0];
                        files.splice(evt.newIndex - 1, 0, newSort);

                        input.value = files.length > 0 ? JSON.stringify(files) : '';
                    },
                    onChange: function (evt) {
                        $(input).trigger('change');
                    }
                });

                function disableDropzone() {
                    $(dz).addClass('disabled');
                    $(dz).siblings().find('.dz-hidden-input').prop('disabled', true);
                    sortable.options.disabled = true;
                    dropzone.removeEventListeners();
                }

                function enableDropzone() {
                    $(dz).removeClass('disabled');
                    $(dz).siblings().find('.dz-hidden-input').prop('disabled', false);
                    sortable.options.disabled = false;
                    dropzone.setupEventListeners();
                }

                input.addEventListener('CrudField:disable', function(e) {
                    disableDropzone();
                });

                input.addEventListener('CrudField:enable', function(e) {
                    enableDropzone();
                });

                element.find('.dz-filename').on('click', function(e) {
                    const serverFiles = dz.dataset.serverFiles;
                    if (!serverFiles || !e.currentTarget.innerText) {
                        return;
                    }

                    let files = JSON.parse(serverFiles);

                    files.forEach((file) => {
                        if (file.name == e.currentTarget.innerText) {
                            window.open(file.url, '_blank');
                        }
                    });
                });
            
                if (isDropzoneActive !== 'true') {
                    disableDropzone();
                }
            }
        </script>
        @endBassetBlock
    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
