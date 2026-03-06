<!-- Blade Template -->
<v-seo-helper {{ $attributes }}></v-seo-helper>

@pushOnce('scripts')
    <!-- SEO Vue Component Template -->
    <script
        type="text/x-template"
        id="v-seo-helper-template"
    >
        <div class="mb-8 flex flex-col gap-1">
            <p 
                class="text-[#161B9D] dark:text-white"
                v-text="metaTitle"
            >
            </p>

            <!-- SEO Meta Title -->
            <p 
                class="text-[#135F29]"
                v-text="urlPreview"
            >
            </p>

            <!-- SEO Meta Description -->
            <p 
                class="text-gray-600 dark:text-gray-300"
                v-text="metaDescription"
            >
            </p>
        </div>
    </script>

    <script type="module">
        const BASE_URL = '{{ rtrim(URL::to('/'), '/') }}';

        app.component('v-seo-helper', {
            template: '#v-seo-helper-template',

            props: {
                metaTitleField: {
                    type: String,
                    default: 'meta_title',
                },

                urlKeyField: {
                    type: String,
                    default: 'url_key',
                },

                metaDescriptionField: {
                    type: String,
                    default: 'meta_description',
                },

                slug: {
                    type: String,
                    default: '',
                },

                urlType: {
                    type: String,
                    default: 'path',
                },
            },

            data() {
                return {
                    metaTitle: '',
                    urlKey: '',
                    metaDescription: ''
                };
            },

            computed: {
                urlPreview() {
                    const rawUrlValue = (this.urlKey || '').trim();

                    if (this.urlType === 'host') {
                        if (! rawUrlValue) {
                            return `${BASE_URL}/`;
                        }

                        if (/^https?:\/\//i.test(rawUrlValue)) {
                            return rawUrlValue.replace(/\/+$/, '');
                        }

                        return `https://${rawUrlValue.replace(/^\/+|\/+$/g, '')}`;
                    }

                    const path = [
                        (this.slug || '').trim().replace(/^\/+|\/+$/g, ''),
                        rawUrlValue.toLowerCase().replace(/\s+/g, '-')
                    ].filter(Boolean).join('/');

                    return path ? `${BASE_URL}/${path}` : `${BASE_URL}/`;
                }
            },

            mounted() {
                this.bindField('metaTitle', this.metaTitleField);

                this.bindField('urlKey', this.urlKeyField);
                
                this.bindField('metaDescription', this.metaDescriptionField);
            },

            methods: {
                escapeSelector(value) {
                    return window.CSS?.escape ? window.CSS.escape(value) : value.replace(/"/g, '\\"');
                },

                findElement(fieldIdentifier) {
                    if (! fieldIdentifier) return null;
                    
                    const escaped = this.escapeSelector(fieldIdentifier);
                    
                    return document.querySelector(`#${escaped}, [name="${escaped}"]`);
                },

                bindField(stateKey, fieldIdentifier) {
                    const element = this.findElement(fieldIdentifier);
                    
                    if (! element) {
                        this[stateKey] = '';
                        return;
                    }

                    this[stateKey] = element.value || '';
                    
                    element.addEventListener('input', (e) => {
                        this[stateKey] = e.target?.value || '';
                    });
                }
            }
        });
    </script>
@endPushOnce