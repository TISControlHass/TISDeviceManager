{{-- Tiny MCE --}}
@php
$tinymceIdentifier = uniqid('tinymce_');
$defaultOptions = [
    'file_picker_callback' => 'elFinderBrowser',
    'selector' => 'textarea.'.$tinymceIdentifier,
    'plugins' => 'image,link,media,anchor',
    //these two options allow tinymce to save the path of images "/upload/image.jpg" instead of the relative server path "../../../uploads/image.jpg"
    'relative_urls' =>  false,
    'remove_script_host' => true,
];

$field['options'] = array_merge($defaultOptions, $field['options'] ?? []);
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    <textarea
        name="{{ $field['name'] }}"
        data-init-function="bpFieldInitTinyMceElement"
        data-options='{!! trim(json_encode($field['options'])) !!}'
        bp-field-main-input
        @include('crud::fields.inc.attributes', ['default_class' =>  'form-control tinymce '.$tinymceIdentifier])
        >{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}</textarea>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    {{-- include tinymce js --}}
    @bassetArchive('https://github.com/tinymce/tinymce-dist/archive/refs/tags/6.3.2.tar.gz', 'tinymce-6.3.2')
    @basset('tinymce-6.3.2/tinymce-dist-6.3.2/tinymce.min.js')

    @bassetBlock('backpack/pro/fields/tinymce-field.js')
    <script type="text/javascript">
    function bpFieldInitTinyMceElement(element) {
        // grab the configuration defined in PHP
        let configuration = element.data('options');

        // disable promotion button
        configuration['promotion'] = false;

        // the target should be the element the function has been called on
        configuration['target'] = element;
        configuration['file_picker_callback'] = eval(configuration['file_picker_callback']);


        // automatically update the textarea value on editor change
        configuration['setup'] = function (editor) {
             editor.on('change', function(e) {
                let hasOriginalEvent = typeof e.originalEvent !== 'undefined';
                // in case there is an original event, we make sure it's NOT our own `save()` event
                // avoiding re-triggering the change event twice.
                if(hasOriginalEvent && e.originalEvent.type !== 'savecontent') {
                    // save only the current editor.
                    editor.save();
                    element.trigger('change');
                }

                // in case there is no original event, it means the change migth have already 
                // ocurred, for example, when adding an image or a link from the toolbar.
                // we just make sure that the process is complete and we have content
                if(!hasOriginalEvent && typeof e.level.content !== 'undefined') {
                    editor.save();
                    element.trigger('change');
                }
            });

            editor.on('input', function(e) {
                // only update the textarea in case the input is a text insertion,
                // other types of inputs like paste etc are handled in the
                // change event. 
                if(e.inputType === 'insertText') {
                    editor.save();
                    element.trigger('change');
                }
            });

            editor.on('Undo Redo', function(e) {
                editor.save();
                element.trigger('change');
            });

            editor.on('init', function() {
                setTinyMceBackgroundColor(editor);
            });
        };

        function isTinyMceEditorInDarkMode() {
            return typeof colorMode !== 'undefined' && colorMode.result === 'dark';
        }

        function setTinyMceColorMode() {
            configuration['skin'] = isTinyMceEditorInDarkMode() ? 'oxide-dark' : 'oxide';
            configuration['content_css'] = isTinyMceEditorInDarkMode() ? 'dark' : '';
        }

        function setTinyMceBackgroundColor(editor) {
            let iframeDocument = editor.contentDocument || editor.contentWindow.document;
            let body = iframeDocument.getElementsByTagName('body')[0];
            body.style.cssText = isTinyMceEditorInDarkMode() ? 'background-color: #221e26; color: #c9c1d6;' : 'background-color: #fff;';
        }

        function getTinyMceEditorId()
        {
            return configuration['target'][0].getAttribute('id');
        }

        // register a listener for color change that will update the tinymce skin 
        // and re-initialize the editor instances
        if(typeof colorMode !== 'undefined') {
            colorMode.onChange(function() {
                let editorId = getTinyMceEditorId();
                let editorInstance = tinymce.get(editorId);

                editorInstance.remove();

                setTinyMceColorMode();
                tinymce.init(configuration);                
            });
        }

        //set the color mode before initialization:
        setTinyMceColorMode();

        // initialize the TinyMCE editor
        tinymce.init(configuration);

        element.on('CrudField:disable', function(e) {
            let editorId = getTinyMceEditorId();
            let editorInstance = tinymce.get(editorId);
            editorInstance.focus();
            tinymce.activeEditor.mode.set('readonly');
        });

        element.on('CrudField:enable', function(e) {
            let editorId = getTinyMceEditorId();
            let editorInstance = tinymce.get(editorId);
            editorInstance.focus();
            tinymce.activeEditor.mode.set('design');
        });
    }

    function elFinderBrowser (callback, value, meta) {
        tinymce.activeEditor.windowManager.openUrl({
            title: 'elFinder 2.0',
            url: '{{ backpack_url('elfinder/tinymce5') }}',
            width: 900,
            height: 460,
            onMessage: function (dialogApi, details) {
                if (details.mceAction === 'fileSelected') {
                    const file = details.data.file;

                    // Make file info
                    const info = file.name;

                    // Provide file and text for the link dialog
                    if (meta.filetype === 'file') {
                        callback(file.url, {text: info, title: info});
                    }

                    // Provide image and alt text for the image dialog
                    if (meta.filetype === 'image') {
                        callback(file.url, {alt: info});
                    }

                    // Provide alternative source and posted for the media dialog
                    if (meta.filetype === 'media') {
                        callback(file.url);
                    }

                    dialogApi.close();
                }
            }
        });
    }
    </script>
    @endBassetBlock
@endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
