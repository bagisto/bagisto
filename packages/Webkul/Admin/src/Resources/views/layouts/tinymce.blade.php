<script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

<script>
    let tinyMCEHelper = {
        initTinyMCE: function (extraConfiguration) {
            let self = this;

            let config = {
                relative_urls : false,
                remove_script_host : false,
                document_base_url : '{{ asset('/') }}',
                uploadRoute: '{{ route('admin.tinymce.upload') }}',
                csrfToken: '{{ csrf_token() }}',
                ...extraConfiguration,
            };

            tinymce.init({
                ...config,

                file_picker_callback: function(cb, value, meta) {
                    self.filePickerCallback(config, cb, value, meta);
                },

                images_upload_handler: function (blobInfo, success, failure, progress) {
                    self.uploadImageHandler(config, blobInfo, success, failure, progress);
                },
            });
        },

        filePickerCallback: function(config, cb, value, meta) {
            let input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.onchange = function() {
                let file = this.files[0];

                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    let id = 'blobid' + (new Date()).getTime();
                    let blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    let base64 = reader.result.split(',')[1];
                    let blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), {title: file.name});
                };
            };
            input.click();
        },

        uploadImageHandler: function(config, blobInfo, success, failure, progress) {
            let xhr, formData;

            xhr = new XMLHttpRequest();

            xhr.withCredentials = false;

            xhr.open('POST', config.uploadRoute);

            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = function() {
                let json;

                if (xhr.status === 403) {
                    failure('{{ __('admin::app.error.tinymce.http-error') }}', { remove: true });
                    return;
                }

                if (xhr.status < 200 || xhr.status >= 300) {
                    failure('{{ __('admin::app.error.tinymce.http-error') }}');
                    return;
                }

                json = JSON.parse(xhr.responseText);

                if (! json || typeof json.location != 'string') {
                    failure('{{ __('admin::app.error.tinymce.invalid-json') }} ' + xhr.responseText);
                    return;
                }

                success(json.location);
            };

            xhr.onerror = function () {
                failure('{{ __('admin::app.error.tinymce.upload-failed') }}');
            };

            formData = new FormData();
            formData.append('_token', config.csrfToken);
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        }
    };
</script>