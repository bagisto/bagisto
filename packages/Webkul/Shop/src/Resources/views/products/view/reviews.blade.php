{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

<v-product-reviews :product-id="{{ $product->id }}">
    <div class="container max-1180:px-5">
        <x-shop::shimmer.products.reviews />
    </div>
</v-product-reviews>

{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <!-- Product Review Template -->
    <script
        type="text/x-template"
        id="v-product-reviews-template"
    >
        <div class="container max-1180:px-5">
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
                        class="grid grid-cols-[auto_1fr] justify-center gap-10 max-md:grid-cols-[1fr]"
                        @submit="handleSubmit($event, store)"
                        enctype="multipart/form-data"
                    >
                        <div class="max-w-[286px]">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.control
                                    type="image"
                                    class="!mb-0 !p-0"
                                    name="attachments"
                                    :label="trans('shop::app.products.view.reviews.attachments')"
                                    :is-multiple="true"
                                    ref="reviewImages"
                                />

                                <x-shop::form.control-group.error
                                    class="mt-4"
                                    control-name="attachments"
                                />
                            </x-shop::form.control-group>
                        </div>
                        
                        <div>
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="required mt-0">
                                    @lang('shop::app.products.view.reviews.rating')
                                </x-shop::form.control-group.label>

                                <x-shop::products.star-rating
                                    name="rating"
                                    rules="required"
                                    :value="old('rating') ?? 5"
                                    :label="trans('shop::app.products.view.reviews.rating')"
                                    :disabled="false"
                                />

                                <x-shop::form.control-group.error control-name="rating" />
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
                                        rules="required"
                                        :value="old('name')"
                                        :label="trans('shop::app.products.view.reviews.name')"
                                        :placeholder="trans('shop::app.products.view.reviews.name')"
                                    />

                                    <x-shop::form.control-group.error control-name="name" />
                                </x-shop::form.control-group>
                            @endif

                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="required">
                                    @lang('shop::app.products.view.reviews.title')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="text"
                                    name="title"
                                    rules="required"
                                    :value="old('title')"
                                    :label="trans('shop::app.products.view.reviews.title')"
                                    :placeholder="trans('shop::app.products.view.reviews.title')"
                                />

                                <x-shop::form.control-group.error control-name="title" />
                            </x-shop::form.control-group>

                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="required">
                                    @lang('shop::app.products.view.reviews.comment')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="textarea"
                                    name="comment"
                                    rules="required"
                                    :value="old('comment')"
                                    :label="trans('shop::app.products.view.reviews.comment')"
                                    :placeholder="trans('shop::app.products.view.reviews.comment')"
                                    rows="12"
                                />

                                <x-shop::form.control-group.error control-name="comment" />
                            </x-shop::form.control-group>


                            <div class="mt-4 flex justify-start gap-4 max-xl:mb-5 max-sm:mb-5 max-sm:flex-wrap max-sm:justify-center">
                                <button
                                    class="primary-button w-full max-w-[374px] rounded-2xl px-11 py-4 text-center"
                                    type='submit'
                                >
                                    @lang('shop::app.products.view.reviews.submit-review')
                                </button>
                                
                                <button
                                    type="button"
                                    class="secondary-button items-center rounded-2xl px-8 py-2.5 max-sm:w-full max-sm:max-w-[374px]"
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
                    <x-shop::shimmer.products.reviews />
                </template>

                <template v-else>
                    <!-- Review Section Header -->
                    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                        <h3 class="font-dmserif text-3xl max-sm:text-xl">
                            @lang('shop::app.products.view.reviews.customer-review')
                        </h3>
                        
                        @if (
                            core()->getConfigData('catalog.products.review.guest_review')
                            || auth()->guard('customer')->user()
                        )
                            <div
                                class="flex cursor-pointer items-center gap-x-4 rounded-xl border border-navyBlue px-4 py-2.5"
                                @click="canReview = true"
                            >
                                <span class="icon-pen text-2xl"></span>

                                @lang('shop::app.products.view.reviews.write-a-review')
                            </div>
                        @endif
                    </div>

                    <template v-if="reviews.length">
                        <!-- Average Rating Section -->
                        <div class="mt-8 flex max-w-[365px] items-center justify-between gap-4 max-sm:flex-wrap">
                            <p class="text-3xl font-medium max-sm:text-base">{{ number_format($avgRatings, 1) }}</p>

                            <x-shop::products.star-rating :value="$avgRatings" />

                            <p class="text-xs text-[#858585]">
                                (@{{ meta.total }} @lang('shop::app.products.view.reviews.customer-review'))
                            </p>
                        </div>

                        <!-- Ratings By Individual Stars -->
                        <div class="flex items-center gap-x-5">
                            <div class="mt-2.5 grid max-w-[365px] flex-wrap gap-y-5">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="row grid grid-cols-[1fr_2fr] items-center gap-2.5 max-sm:flex-wrap">
                                        <div class="text-base font-medium">{{ $i }} Stars</div>

                                        <div class="h-4 w-[275px] max-w-full rounded-sm bg-[#E5E5E5]">
                                            <div class="h-4 rounded-sm bg-[#FEA82B]" style="width: {{ $percentageRatings[$i] }}%"></div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="mt-14 grid grid-cols-[1fr_1fr] gap-5 max-1060:grid-cols-[1fr]">
                            <!-- Product Review Item Vue Component -->
                            <v-product-review-item
                                v-for='review in reviews'
                                :review="review"
                            ></v-product-review-item>
                        </div>

                        <button
                            class="mx-auto mt-14 block w-max rounded-2xl border border-navyBlue bg-white px-11 py-3 text-center text-base font-medium text-navyBlue"
                            v-if="links?.next"
                            @click="get()"
                        >
                            @lang('shop::app.products.view.reviews.load-more')
                        </button>
                    </template>

                    <template v-else>
                        <!-- Empty Review Section -->
                        <div class="m-auto grid h-[476px] w-full place-content-center items-center justify-items-center text-center">
                            <img class="" src="{{ bagisto_asset('images/review.png') }}" alt="" title="">

                            <p class="text-xl">
                                @lang('shop::app.products.view.reviews.empty-review')
                            </p>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </script>

    <!-- Product Review Item Template -->
    <script type="text/x-template" id="v-product-review-item-template">
        <div class="flex gap-5 rounded-xl border border-[#e5e5e5] p-6 max-xl:mb-5 max-sm:flex-wrap">
            <div>
                <img
                    v-if="review.profile"
                    class="flex max-h-[100px] min-h-[100px] min-w-[100px] max-w-[100px] items-center justify-center rounded-xl max-sm:hidden"
                    :src="review.profile"
                    :alt="review.name"
                    :title="review.name"
                >

                <div
                    v-else
                    class="flex max-h-[100px] min-h-[100px] min-w-[100px] max-w-[100px] items-center justify-center rounded-xl bg-[#F5F5F5] max-sm:hidden"
                    :title="review.name"
                >
                    <span
                        class="text-2xl font-semibold text-[#6E6E6E]"
                        v-text="review.name.split(' ').map(name => name.charAt(0).toUpperCase()).join('')"
                    >
                    </span>
                </div>
            </div>

            <div class="w-full">
                <div class="flex justify-between">
                    <p
                        class="text-xl font-medium max-sm:text-base"
                        v-text="review.name"
                    >
                    </p>

                    <div class="flex items-center">
                        <x-shop::products.star-rating 
                            ::name="review.name" 
                            ::value="review.rating"
                        />
                    </div>
                </div>

                <p
                    class="mt-2.5 text-sm font-medium max-sm:text-xs"
                    v-text="review.created_at"
                >
                </p>

                <p
                    class="mt-5 text-base font-semibold text-[#6E6E6E] max-sm:text-xs"
                    v-text="review.title"
                >
                </p>

                <p
                    class="mt-5 text-base text-[#6E6E6E] max-sm:text-xs"
                    v-text="review.comment"
                >
                </p>

                <button
                    class="secondary-button mt-2.5 min-h-[34px] rounded-lg px-2 py-1 text-sm"
                    @click="translate"
                >
                    <!-- Spinner -->
                    <template v-if="isLoading">
                        <img
                            class="h-5 w-5 animate-spin text-blue-600"
                            src="{{ bagisto_asset('images/spinner.svg') }}"
                        />

                        @lang('shop::app.products.view.reviews.translating')
                    </template>

                    <template v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" role="presentation"> <g clip-path="url(#clip0_3148_2242)"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.1484 9.31989L9.31995 12.1483L19.9265 22.7549L22.755 19.9265L12.1484 9.31989ZM12.1484 10.7341L10.7342 12.1483L13.5626 14.9767L14.9768 13.5625L12.1484 10.7341Z" fill="#060C3B"/> <path d="M11.0877 3.30949L13.5625 4.44748L16.0374 3.30949L14.8994 5.78436L16.0374 8.25924L13.5625 7.12124L11.0877 8.25924L12.2257 5.78436L11.0877 3.30949Z" fill="#060C3B"/> <path d="M2.39219 2.39217L5.78438 3.95197L9.17656 2.39217L7.61677 5.78436L9.17656 9.17655L5.78438 7.61676L2.39219 9.17655L3.95198 5.78436L2.39219 2.39217Z" fill="#060C3B"/> <path d="M3.30947 11.0877L5.78434 12.2257L8.25922 11.0877L7.12122 13.5626L8.25922 16.0374L5.78434 14.8994L3.30947 16.0374L4.44746 13.5626L3.30947 11.0877Z" fill="#060C3B"/> </g> <defs> <clipPath id="clip0_3148_2242"> <rect width="24" height="24" fill="white"/> </clipPath> </defs> </svg>
                        
                        @lang('shop::app.products.view.reviews.translate')
                    </template>
                </button>

                <!-- Review Attachments -->
                <div
                    class="mt-3 flex flex-wrap gap-2"
                    v-if="review.images.length"
                >
                    <template v-for="file in review.images">
                        <a
                            :href="file.url"
                            class="flex h-12 w-12"
                            target="_blank"
                            v-if="file.type == 'image'"
                        >
                            <img
                                class="max-h-[50px] min-w-[50px] cursor-pointer rounded-xl"
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
                                class="max-h-[50px] min-w-[50px] cursor-pointer rounded-xl"
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
        app.component('v-product-reviews', {
            template: '#v-product-reviews-template',

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
                    let selectedFiles = this.$refs.reviewImages.uploadedFiles.filter(obj => obj.file instanceof File).map(obj => obj.file);

                    params.attachments = { ...params.attachments, ...selectedFiles };
                    
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

            data() {
                return {
                    isLoading: false,
                }
            },

            methods: {
                translate() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('shop.api.products.reviews.translate', ['id' => $product->id, 'review_id' => ':reviewId']) }}".replace(':reviewId', this.review.id))
                        .then(response => {
                            this.isLoading = false;

                            this.review.comment = response.data.content;
                        })
                        .catch(error => {
                            this.isLoading = false;

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },
            },
        });
    </script>
@endPushOnce