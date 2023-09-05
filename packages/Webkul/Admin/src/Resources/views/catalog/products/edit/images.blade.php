{!! view_render_event('bagisto.admin.catalog.product.edit.form.images.before', ['product' => $product]) !!}

<div class="relative p-[16px] bg-white rounded-[4px] box-shadow">

    <v-product-images :uploaded-images='@json($product->images)'></v-product-images>

</div>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.images.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-images-template">
        <div>
            <!-- Panel Header -->
            <div class="flex gap-[20px] justify-between mb-[16px]">
                <div class="flex flex-col gap-[8px]">
                    <p class="text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.products.edit.images.title')
                    </p>

                    <p class="text-[12px] text-gray-500 font-medium">
                        @lang('admin::app.catalog.products.edit.images.info')
                    </p>
                </div>
            </div>

            <!-- Panel Content -->
            <div class="grid">
                <div class="flex flex-wrap gap-[4px]">
                    <!-- Upload Image Button -->
                    <label
                        class="grid justify-items-center items-center w-full h-[120px] max-w-[120px] min-w-[120px] max-h-[120px] border border-dashed border-gray-300 rounded-[4px] cursor-pointer transition-all hover:border-gray-400"
                        :for="$.uid + '_imageInput'"
                    >
                        <div class="flex flex-col items-center">
                            <span class="icon-image text-[24px]"></span>

                            <p class="grid text-[14px] text-gray-600 font-semibold text-center">
                                @lang('admin::app.catalog.products.edit.images.add-image-btn')
                                
                                <span class="text-[12px]">
                                    @lang('admin::app.catalog.products.edit.images.allowed-types')
                                </span>
                            </p>

                            <input
                                type="file"
                                class="hidden"
                                :id="$.uid + '_imageInput'"
                                accept="image/*"
                                multiple="multiple"
                                :ref="$.uid + '_imageInput'"
                                @change="add"
                            />
                        </div>
                    </label>

                    <!-- Uploaded Images -->
                    <draggable
                        class="flex gap-[4px]"
                        ghost-class="draggable-ghost"
                        v-bind="{animation: 200}"
                        :list="images"
                        item-key="id"
                    >
                        <template #item="{ element, index }">
                            <v-product-image-item
                                :index="index"
                                :image="element"
                                @onRemove="remove($event)"
                            ></v-product-image-item>
                        </template>
                    </draggable>

                    <!-- Placeholders -->
                    <template v-if="! images.length">
                        <!-- Front Placeholder -->
                        <div
                            class="w-full h-[120px] max-w-[120px] min-w-[120px] max-h-[120px] relative border border-dashed border-gray-300 rounded-[4px]"
                            v-for="placeholder in placeholders"
                        >
                            <img :src="placeholder.image">

                            <p class="w-full absolute bottom-[15px] text-[12px] text-gray-400 text-center font-semibold">
                                @{{ placeholder.label }}
                            </p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="v-product-image-item-template">
        <div class="grid justify-items-center max-w-[120px] min-w-[120px] max-h-[120px] relative rounded-[4px] overflow-hidden transition-all hover:border-gray-400 group">
            <!-- Image Preview -->
            <img :src="image.url"/>

            <div class="flex flex-col justify-between invisible w-full p-[11px] bg-white absolute top-0 bottom-0 opacity-80 transition-all group-hover:visible">
                <!-- Image Name -->
                <p class="text-[12px] text-gray-600 font-semibold break-all"></p>

                <!-- Actions -->
                <div class="flex justify-between">
                    <span
                        class="icon-delete text-[24px] p-[6px]  rounded-[6px] cursor-pointer hover:bg-gray-200"
                        @click="remove"
                    ></span>

                    <label
                        class="icon-edit text-[24px] p-[6px]  rounded-[6px] cursor-pointer hover:bg-gray-200"
                        :for="$.uid + '_imageInput_' + index"
                    ></label>

                    <input type="hidden" :name="'images[files][' + image.id + ']'" v-if="! image.is_new"/>

                    <input type="hidden" :name="'images[positions][' + image.id + ']'"/>

                    <input
                        type="file"
                        name="images[files][]"
                        class="hidden"
                        accept="image/*"
                        :id="$.uid + '_imageInput_' + index"
                        :ref="$.uid + '_imageInput_' + index"
                        @change="edit"
                    />
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-images', {
            template: '#v-product-images-template',

            props: ['uploadedImages'],

            data() {
                return {
                    images: [],

                    placeholders: [
                        {
                            label: "@lang('admin::app.catalog.products.edit.images.placeholders.front')",
                            image: "{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                        }, {
                            label: "@lang('admin::app.catalog.products.edit.images.placeholders.next')",
                            image: "{{ bagisto_asset('images/product-placeholders/next-1.svg') }}"
                        }, {
                            label: "@lang('admin::app.catalog.products.edit.images.placeholders.next')",
                            image: "{{ bagisto_asset('images/product-placeholders/next-2.svg') }}"
                        }, {
                            label: "@lang('admin::app.catalog.products.edit.images.placeholders.zoom')",
                            image: "{{ bagisto_asset('images/product-placeholders/zoom.svg') }}"
                        }, {
                            label: "@lang('admin::app.catalog.products.edit.images.placeholders.use-cases')",
                            image: "{{ bagisto_asset('images/product-placeholders/use-cases.svg') }}"
                        }, {
                            label: "@lang('admin::app.catalog.products.edit.images.placeholders.size')",
                            image: "{{ bagisto_asset('images/product-placeholders/size.svg') }}"
                        }
                    ]
                }
            },

            mounted() {
                this.images = this.uploadedImages;
            },

            methods: {
                add() {
                    let imageInput = this.$refs[this.$.uid + '_imageInput'];

                    if (imageInput.files == undefined) {
                        return;
                    }

                    const validFiles = Array.from(imageInput.files).every(file => file.type.includes('image/'));

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "{{ trans('admin::app.catalog.products.edit.images.not-allowed-error') }}"
                        });

                        return;
                    }

                    imageInput.files.forEach((file, index) => {
                        this.images.push({
                            id: 'image_' + this.images.length,
                            url: '',
                            file: file
                        });
                    });
                },

                remove(image) {
                    let index = this.images.indexOf(image);

                    this.images.splice(index, 1);
                }
            }
        });

        app.component('v-product-image-item', {
            template: '#v-product-image-item-template',

            props: ['index', 'image'],

            mounted() {
                if (this.image.file instanceof File) {
                    this.setFile(this.image.file);

                    this.readFile(this.image.file);
                }
            },

            methods: {
                edit() {
                    let imageInput = this.$refs[this.$.uid + '_imageInput_' + this.index];

                    if (imageInput.files == undefined) {
                        return;
                    }

                    const validFiles = Array.from(imageInput.files).every(file => file.type.includes('image/'));

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "{{ trans('admin::app.catalog.products.edit.images.not-allowed-error') }}"
                        });

                        return;
                    }

                    this.setFile(imageInput.files[0]);

                    this.readFile(imageInput.files[0]);
                },

                remove() {
                    this.$emit('onRemove', this.image)
                },

                setFile(file) {
                    this.image.is_new = 1;

                    const dataTransfer = new DataTransfer();

                    dataTransfer.items.add(file);

                    this.$refs[this.$.uid + '_imageInput_' + this.index].files = dataTransfer.files;
                },

                readFile(file) {
                    let reader = new FileReader();

                    reader.onload = (e) => {
                        this.image.url = e.target.result;
                    }

                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
@endPushOnce