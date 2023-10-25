{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

<v-product-review :product-id="{{ $product->id }}">
    <div class="container max-1180:px-[20px]">
        <x-shop::shimmer.products.reviews/>
    </div>
</v-product-review>

{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

@pushOnce('scripts')
    {{-- Product Review Template --}}
    <script type="text/x-template" id="v-product-review-template">
        <div class="container max-1180:px-[20px]">
            <!-- Create Review Form Container -->
            <div 
                class="w-full" 
                v-if="canReview"
            >
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <!-- Review Form -->
                    <form
                        class="grid grid-cols-[auto_1fr] gap-[40px] justify-center max-md:grid-cols-[1fr]"
                        @submit="handleSubmit($event, store)"
                        enctype="multipart/form-data"
                    >
                        <div class="max-w-[286px]">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.control
                                    type="image"
                                    name="attachments"
                                    class="!p-0 !mb-0"
                                    rules="required"
                                    ref="reviewImages"
                                    :label="trans('shop::app.products.view.reviews.attachments')"
                                    :is-multiple="true"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.error
                                    class="mt-4"
                                    control-name="attachments"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>
                        </div>
                        
                        <div>
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="mt-[0] required">
                                    @lang('shop::app.products.view.reviews.rating')
                                </x-shop::form.control-group.label>

                                <x-shop::products.star-rating
                                    name="rating"
                                    :value="old('rating') ?? 5"
                                    :disabled="false"
                                    rules="required"
                                    :label="trans('shop::app.products.view.reviews.rating')"
                                >
                                </x-shop::products.star-rating>

                                <x-shop::form.control-group.error
                                    control-name="rating"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            @if (
                                core()->getConfigData('catalog.products.review.guest_review')
                                && ! auth()->guard('customer')->user()
                            )
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required">
                                        @lang('shop::app.products.view.reviews.name')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        rules="required"
                                        :label="trans('shop::app.products.view.reviews.name')"
                                        :placeholder="trans('shop::app.products.view.reviews.name')"
                                    >
                                    </x-shop::form.control-group.control>

                                    <x-shop::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
                            @endif

                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="required">
                                    @lang('shop::app.products.view.reviews.title')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="text"
                                    name="title"
                                    :value="old('title')"
                                    rules="required"
                                    :label="trans('shop::app.products.view.reviews.title')"
                                    :placeholder="trans('shop::app.products.view.reviews.title')"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.error
                                    control-name="title"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="required">
                                    @lang('shop::app.products.view.reviews.comment')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="textarea"
                                    rows="12"
                                    name="comment"
                                    :value="old('comment')"
                                    rules="required"
                                    :label="trans('shop::app.products.view.reviews.comment')"
                                    :placeholder="trans('shop::app.products.view.reviews.comment')"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.error
                                    control-name="comment"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>


                            <div class="flex gap-[15px] justify-start max-sm:flex-wrap mt-4 max-sm:justify-center max-sm:mb-[20px] max-xl:mb-[20px]">
                                <button
                                    class="primary-button w-full max-w-[374px] py-[16px] px-[43px] rounded-[18px] text-center"
                                    type='submit'
                                >
                                    @lang('shop::app.products.view.reviews.submit-review')
                                </button>
                                
                                <button
                                    type="button"
                                    class="secondary-button items-center px-[30px] py-[10px] rounded-[18px] max-sm:w-full max-sm:max-w-[374px]"
                                    @click="canReview = false"
                                >
                                    @lang('shop::app.products.view.reviews.cancel')
                                </button>
                            </div>
                        </div>
                    </form>
                </x-shop::form>
            </div>

            <!-- Product Reviews Container -->
            <div v-else>
                <!-- Review Container Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop::shimmer.products.reviews/>
                </template>

                <template v-else>
                    <!-- Review Section Header -->
                    <div class="flex gap-[15px] items-center justify-between  max-sm:flex-wrap">
                        <h3 class="font-dmserif text-[30px] max-sm:text-[22px]">
                            @lang('shop::app.products.view.reviews.customer-review')
                        </h3>
                        
                        @if (
                            core()->getConfigData('catalog.products.review.guest_review')
                            || auth()->guard('customer')->user()
                        )
                            <div
                                class="flex gap-x-[15px] items-center px-[15px] py-[10px] border border-navyBlue rounded-[12px] cursor-pointer"
                                @click="canReview = true"
                            >
                                <span class="icon-pen text-[24px]"></span>

                                @lang('shop::app.products.view.reviews.write-a-review')
                            </div>
                        @endif
                    </div>

                    <template v-if="reviews.length">
                        <!-- Average Rating Section -->
                        <div class="flex gap-[15px] justify-between items-center max-w-[365px] mt-[30px] max-sm:flex-wrap">
                            <p class="text-[30px] font-medium max-sm:text-[16px]">{{ number_format($avgRatings, 1) }}</p>

                            <x-shop::products.star-rating :value="$avgRatings"></x-shop::products.star-rating>

                            <p class="text-[12px] text-[#858585]">
                                (@{{ meta.total }} @lang('shop::app.products.view.reviews.customer-review'))
                            </p>
                        </div>

                        <!-- Ratings By Individual Stars -->
                        <div class="flex gap-x-[20px] items-center">
                            <div class="flex gap-y-[18px] flex-wrap max-w-[365px] mt-[10px]">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                        <div class="text-[16px] font-medium">{{ $i }} Stars</div>
                                        <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                            <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: {{ $percentageRatings[$i] }}%"></div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="grid grid-cols-[1fr_1fr] gap-[20px] mt-[60px] max-1060:grid-cols-[1fr]">
                            <!-- Product Review Item Vue Component -->
                            <v-product-review-item
                                v-for='review in reviews'
                                :review="review"
                            ></v-product-review-item>
                        </div>

                        <button
                            class="block mx-auto w-max mt-[60px] py-[11px] px-[43px] bg-white border border-navyBlue rounded-[18px] text-center text-navyBlue text-base font-medium"
                            v-if="links?.next"
                            @click="get()"
                        >
                            @lang('shop::app.products.view.reviews.load-more')
                        </button>
                    </template>

                    <template v-else>
                        <!-- Empty Review Section -->
                        <div class="grid items-center justify-items-center w-[100%] m-auto h-[476px] place-content-center text-center">
                            <img class="" src="{{ bagisto_asset('images/review.png') }}" alt="" title="">

                            <p class="text-[20px]">
                                @lang('shop::app.products.view.reviews.empty-review')
                            </p>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </script>

    {{-- Product Review Item Template --}}
    <script type="text/x-template" id="v-product-review-item-template">
        <div class="flex gap-[20px] p-[25px] border border-[#e5e5e5] rounded-[12px] max-sm:flex-wrap max-xl:mb-[20px]">
            <div>
                <div
                    class="flex justify-center items-center min-h-[100px] max-h-[100px] min-w-[100px] max-w-[100px] rounded-[12px] bg-[#F5F5F5] max-sm:hidden"
                    :title="review.name"
                >
                    <span
                        class="text-[24px] text-[#6E6E6E] font-semibold"
                        v-text="review.name.split(' ').map(name => name.charAt(0).toUpperCase()).join('')"
                    >
                    </span>
                </div>
            </div>

            <div class="w-full">
                <div class="flex justify-between">
                    <p
                        class="text-[20px] font-medium max-sm:text-[16px]"
                        v-text="review.name"
                    >
                    </p>

                    <div class="flex items-center">
                        <x-shop::products.star-rating 
                            ::name="review.name" 
                            ::value="review.rating"
                        >
                        </x-shop::products.star-rating>
                    </div>
                </div>

                <p
                    class="mt-[10px] text-[14px] font-medium max-sm:text-[12px]"
                    v-text="review.created_at"
                >
                </p>

                <p
                    class="mt-[20px] text-[16px] text-[#6E6E6E] font-semibold max-sm:text-[12px]"
                    v-text="review.title"
                >
                </p>

                <p
                    class="mt-[20px] text-[16px] text-[#6E6E6E] max-sm:text-[12px]"
                    v-text="review.comment"
                >
                </p>

                <div class="flex gap-2 flex-wrap mt-2">
                    <template v-for="file in review.images">
                        <a
                            :href="file.url"
                            class="h-12 w-12 flex"
                            target="_blank"
                            v-if="file.type == 'image'"
                        >
                            <img
                                class="min-w-[50px] max-h-[50px] rounded-[12px] cursor-pointer"
                                :src="file.url"
                                :alt="review.name"
                                :title="review.name"
                            >
                        </a>

                        <a
                            :href="file.url"
                            class="flex h-12 w-12"
                            target="_blank"
                            v-else
                        >
                            <video
                                class="min-w-[50px] max-h-[50px] rounded-[12px] cursor-pointer"
                                :src="file.url"
                                :alt="review.name"
                                :title="review.name"
                            >
                            </video>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-review', {
            template: '#v-product-review-template',

            props: ['productId'],

            data() {
                return {
                    isLoading: true,

                    canReview: false,

                    reviews: [],

                    links: {
                        next: '{{ route('shop.api.products.reviews.index', $product->id) }}',
                    },

                    meta: {},
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    if (this.links?.next) {
                        this.$axios.get(this.links.next)
                            .then(response => {
                                this.isLoading = false;

                                this.reviews = [...this.reviews, ...response.data.data];

                                this.links = response.data.links;

                                this.meta = response.data.meta;
                            })
                            .catch(error => {});
                    }
                },

                store(params, { resetForm, setErrors }) {
                    this.$axios.post('{{ route('shop.api.products.reviews.store', $product->id) }}', params, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                            resetForm();

                            this.canReview = false;
                        })
                        .catch(error => {
                            setErrors({'attachments': ["@lang('shop::app.products.view.reviews.failed-to-upload')"]});

                            this.$refs.reviewImages.uploadedFiles.forEach(element => {
                                setTimeout(() => {
                                    this.$refs.reviewImages.removeFile();
                                }, 0);
                            });
                        });
                },

                selectReviewImage() {
                    this.reviewImage = event.target.files[0];
                },
            },
        });
        
        app.component('v-product-review-item', {
            template: '#v-product-review-item-template',

            props: ['review'],
        });
    </script>
@endPushOnce