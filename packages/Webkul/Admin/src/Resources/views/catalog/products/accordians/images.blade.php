{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.images.before', ['product' => $product]) !!}

<accordian :title="'{{ __('admin::app.catalog.products.images') }}'" :active="false">
    <div slot="body">

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.images.controls.before', ['product' => $product]) !!}

        <div class="control-group {!! $errors->has('images.*') ? 'has-error' : '' !!}">
            <label>{{ __('admin::app.catalog.categories.image') }}</label>

            <product-image></product-image>

            <span class="control-error" v-if="{!! $errors->has('images.*') !!}">
                @php $count=1 @endphp
                @foreach ($errors->get('images.*') as $key => $message)
                    @php echo str_replace($key, 'Image'.$count, $message[0]); $count++ @endphp
                @endforeach
            </span>
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.images.controls.after', ['product' => $product]) !!}

    </div>
</accordian>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.images.after', ['product' => $product]) !!}

@push('scripts')
    @parent

    <script type="text/x-template" id="product-image-template">
        <div>
            <div class="image-wrapper">
                <product-image-item
                    v-for='(image, index) in items'
                    :key='image.id'
                    :image="image"
                    @onRemoveImage="removeImage($event)"
                    @onImageSelected="imageSelected($event)"
                ></product-image-item>
            </div>

            <label class="btn btn-lg btn-primary" style="display: inline-block; width: auto" @click="createFileType">
                {{ __('admin::app.catalog.products.add-image-btn-title') }}
            </label>
        </div>
    </script>

    <script type="text/x-template" id="product-image-item-template">
        <label class="image-item" v-bind:class="{ 'has-image': imageData.length > 0 }">
            <input type="hidden" :name="'images[' + image.id + ']'" v-if="! new_image"/>

            <input type="file" v-validate="'mimes:image/*'" accept="image/*" :name="'images[]'" ref="imageInput" :id="_uid" @change="addImageView($event)" multiple="multiple"/>

            <img class="preview" :src="imageData" v-if="imageData.length > 0">

            <label class="remove-image" @click="removeImage()">
                {{ __('admin::app.catalog.products.remove-image-btn-title') }}
            </label>
        </label>
    </script>

    <script>
        Vue.component('product-image', {

            template: '#product-image-template',
            
            data: function() {
                return {
                    images: @json($product->images),

                    imageCount: 0,

                    items: []
                }
            },

            computed: {
                finalInputName: function() {
                    return 'images[' + this.image.id + ']';
                }
            },

            created: function() {
                var this_this = this;

                this.images.forEach(function(image) {
                    this_this.items.push(image)

                    this_this.imageCount++;
                });
            },

            methods: {
                createFileType: function() {
                    var this_this = this;

                    this.imageCount++;

                    this.items.push({'id': 'image_' + this.imageCount});
                },

                removeImage (image) {
                    let index = this.items.indexOf(image)

                    Vue.delete(this.items, index);
                },

                imageSelected: function(event) {
                    var this_this = this;

                    Array.from(event.files).forEach(function(image, index) {
                        if (index) {
                            this_this.imageCount++;

                            this_this.items.push({'id': 'image_' + this_this.imageCount, file: image});
                        }
                    });
                }
            }
        });

        Vue.component('product-image-item', {

            template: '#product-image-item-template',

            props: {
                image: {
                    type: Object,
                    required: false,
                    default: null
                },
            },

            data: function() {
                return {
                    imageData: '',

                    new_image: 0
                }
            },

            mounted () {
                if (this.image.id && this.image.url) {
                    this.imageData = this.image.url;
                } else if (this.image.id && this.image.file) {
                    this.readFile(this.image.file);
                }
            },

            computed: {
                finalInputName: function() {
                    return this.inputName + '[' + this.image.id + ']';
                }
            },

            methods: {
                addImageView: function() {
                    var imageInput = this.$refs.imageInput;

                    if (imageInput.files && imageInput.files[0]) {
                        if (imageInput.files[0].type.includes('image/')) {
                            this.readFile(imageInput.files[0])

                            if (imageInput.files.length > 1) {
                                this.$emit('onImageSelected', imageInput)
                            }
                        } else {
                            imageInput.value = "";

                            alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                        }
                    }
                },

                readFile: function(image) {
                    var reader = new FileReader();

                    reader.onload = (e) => {
                        this.imageData = e.target.result;
                    }

                    reader.readAsDataURL(image);

                    this.new_image = 1;
                },

                removeImage: function() {
                    this.$emit('onRemoveImage', this.image)
                }
            }
        });
    </script>

@endpush