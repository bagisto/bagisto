@pushOnce('scripts')
    <script type="module">
        app.config.globalProperties.$mediaUpload = {
            messages: {
                invalidType: @js(trans('admin::app.components.media.validation.invalid-type')),

                maxSize: @js(trans('admin::app.components.media.validation.max-size')),

                sizeHint: @js(trans('admin::app.components.media.validation.size-hint')),
            },

            /**
             * Turn a comma-separated list or an array of extensions into lower-cased extensions without dots.
             */
            parseExtensions(types) {
                const list = Array.isArray(types) ? types : String(types ?? '').split(',');

                return list
                    .map(type => String(type).trim().toLowerCase().replace(/^\./, ''))
                    .filter(Boolean);
            },

            /**
             * Build the accept attribute of a file input out of the allowed extensions.
             */
            accept(extensions) {
                return extensions.map(extension => '.' + extension).join(',');
            },

            /**
             * Format a size in kilobytes the way a person reads it.
             */
            formatSize(kilobytes) {
                return kilobytes >= 1024
                    ? +(kilobytes / 1024).toFixed(1) + ' MB'
                    : kilobytes + ' KB';
            },

            /**
             * Describe the allowed extensions and size under an upload button.
             */
            hint(extensions, maxSize) {
                const parts = [extensions.join(', ').toUpperCase()];

                if (maxSize) {
                    parts.push(this.messages.sizeHint.replace(':size', this.formatSize(maxSize)));
                }

                return parts.filter(Boolean).join(' · ');
            },

            /**
             * Return why a file cannot be uploaded, or null when its extension, type and size are allowed.
             */
            validate(file, { extensions, maxSize, mimePrefix }) {
                const extension = file.name.includes('.')
                    ? file.name.split('.').pop().toLowerCase()
                    : '';

                if (
                    ! extensions.includes(extension)
                    || (
                        mimePrefix
                        && file.type
                        && ! file.type.startsWith(mimePrefix)
                    )
                ) {
                    return this.messages.invalidType.replace(':types', extensions.join(', '));
                }

                if (
                    maxSize
                    && file.size > maxSize * 1024
                ) {
                    return this.messages.maxSize.replace(':size', this.formatSize(maxSize));
                }

                return null;
            },

            /**
             * Take the files out of a file input and clear it, so the same file can be picked again.
             */
            takeFiles(input) {
                const files = Array.from(input?.files ?? []);

                if (input) {
                    input.value = '';
                }

                return files;
            },

            /**
             * Find the first error the server reported for one of a field's files, in dot or bracket form.
             */
            fieldError(errors, name) {
                const toPath = key => key.replace(/\[(.*?)\]/g, '.$1');

                const field = toPath(name);

                const message = Object.entries(errors ?? {}).find(([key]) => {
                    const path = toPath(key);

                    return path === field || path.startsWith(field + '.');
                })?.[1];

                return Array.isArray(message) ? message[0] : (message ?? null);
            },
        };
    </script>
@endPushOnce
