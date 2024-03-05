<v-datagrid-export {{ $attributes }}>
    <div class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white">
        @lang('admin::app.export.export')
    </div>
</v-datagrid-export>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-export-template"
    >
        <div>
            <!-- Modal Component -->
            <x-admin::modal ref="exportModal">
                <!-- Modal Toggle -->
                <x-slot:toggle>
                    <button class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white">
                        <span class="icon-export text-xl text-gray-600"></span>
                                        
                        @lang('admin::app.export.export')
                    </button>
                </x-slot>

                <!-- Modal Header -->
                <x-slot:header>
                    <p class="text-lg text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.export.download')
                    </p>
                </x-slot>

                <!-- Modal Content -->
                <x-slot:content>
                    <x-admin::form action="">
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.control
                                type="select"
                                name="format"
                                v-model="format"
                            >
                                <option value="csv">
                                    @lang('admin::app.export.csv')
                                </option>

                                <option value="xls">
                                    @lang('admin::app.export.xls')
                                </option>

                                <option value="xlsx">
                                    @lang('admin::app.export.xlsx')
                                </option>
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </x-admin::form>
                </x-slot>

                <!-- Modal Footer -->
                <x-slot:footer>
                    <button
                        type="button"
                        class="primary-button"
                        @click="download"
                    >
                        @lang('admin::app.export.export')
                    </button>
                </x-slot>
            </x-admin::modal>
        </div>
    </script>

    <script type="module">
        app.component('v-datagrid-export', {
            template: '#v-datagrid-export-template',

            props: ['src'],

            data() {
                return {
                    format: 'xls',

                    available: null,

                    applied: null,
                };
            },

            mounted() {
                this.registerEvents();
            },

            methods: {
                registerEvents() {
                    this.$emitter.on('change-datagrid', this.updateProperties);
                },

                updateProperties({available, applied }) {
                    this.available = available;

                    this.applied = applied;
                },

                download() {
                    if (! this.available?.records?.length) {                        
                        this.$emitter.emit('add-flash', { type: 'warning', message: '@lang('admin::app.export.no-records')' });

                        this.$refs.exportModal.toggle();
                    } else {
                        let params = {
                            export: 1,
    
                            format: this.format,
    
                            sort: {},
    
                            filters: {},
                        };
    
                        if (
                            this.applied.sort.column &&
                            this.applied.sort.order
                        ) {
                            params.sort = this.applied.sort;
                        }
    
                        this.applied.filters.columns.forEach(column => {
                            params.filters[column.index] = column.value;
                        });
    
                        this.$axios
                            .get(this.src, {
                                params,
                                responseType: 'blob',
                            })
                            .then((response) => {
                                const url = window.URL.createObjectURL(new Blob([response.data]));

                                /**
                                 * Link generation.
                                 */
                                const link = document.createElement('a');
                                link.href = url;
                                link.setAttribute('download', `${(Math.random() + 1).toString(36).substring(7)}.${this.format}`);

                                /**
                                 * Adding a link to a document, clicking on the link, and then removing the link.
                                 */
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);

                                this.$refs.exportModal.toggle();
                            });
                    }
                },
            },
        });
    </script>
@endPushOnce
