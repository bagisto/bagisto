<v-datagrid-export {{ $attributes }}>
    <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-[6px]">
        <p class="text-gray-600 dark:text-gray-300 font-semibold leading-[24px]">
            @lang('admin::app.export.export')
        </p>
    </div>
</v-datagrid-export>

@pushOnce('scripts')
    <script type="text/x-template" id="v-datagrid-export-template">
        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-[6px]">
            <x-admin::modal ref="exportModal">
                <x-slot:toggle>
                    <p class="text-gray-600 dark:text-gray-300 font-semibold leading-[24px]">
                        @lang('admin::app.export.export')
                    </p>
                </x-slot:toggle>

                <x-slot:header>
                    <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.export.download')
                    </p>
                </x-slot:header>

                <x-slot:content>
                    <div class="p-[16px]">
                        <x-admin::form action="">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="select"
                                    name="format"
                                    v-model="format"
                                >
                                    <option value="xls">@lang('admin::app.export.xls')</option>
                                    <option value="csv">@lang('admin::app.export.csv')</option>
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>
                        </x-admin::form>
                    </div>
                </x-slot:content>

                <x-slot:footer>
                    <button
                        type="button"
                        class="primary-button"
                        @click="download"
                    >
                        @lang('admin::app.export.export')
                    </button>
                </x-slot:footer>
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
