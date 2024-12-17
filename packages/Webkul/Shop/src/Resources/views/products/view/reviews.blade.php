{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

<v-product-reviews>
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
        <div class="container max-1180:mt-3.5 max-1180:px-5 max-md:px-4 max-sm:px-3.5">
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
                        class="grid grid-cols-[auto_1fr] justify-center gap-10 max-md:grid-cols-[1fr] max-md:gap-0"
                        @submit="handleSubmit($event, store)"
                        enctype="multipart/form-data"
                    >
                        <div class="max-w-[286px]">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.control
                                    type="image"
                                    class="!mb-0 !p-0 max-md:gap-1.5"
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

                                <span
                                    class="icon-star-fill cursor-pointer text-2xl"
                                    role="presentation"
                                    v-for="rating in [1,2,3,4,5]"
                                    :class="appliedRatings >= rating ? 'text-amber-500' : 'text-zinc-500'"
                                    @click="appliedRatings = rating"
                                >
                                </span>

                                <v-field
                                    type="hidden"
                                    name="rating"
                                    v-model="appliedRatings"
                                ></v-field>

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


                            <div class="mt-4 flex justify-start gap-4 max-xl:mb-5 max-sm:mb-5 max-sm:flex-wrap max-sm:justify-normal max-sm:gap-x-0">
                                <button
                                    class="primary-button w-full max-w-[374px] rounded-2xl px-11 py-4 text-center max-md:max-w-full max-md:rounded-lg max-md:py-3 max-sm:py-1.5"
                                    type='submit'
                                >
                                    @lang('shop::app.products.view.reviews.submit-review')
                                </button>
                                
                                <button
                                    type="button"
                                    class="secondary-button items-center rounded-2xl px-8 py-2.5 max-md:w-full max-md:max-w-full max-md:rounded-lg max-md:py-1.5"
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

                <!-- Reviews Cards Container -->
                <template v-else>
                    <template v-if="reviews.length">
                        <h3 class="mb-8 font-dmserif text-3xl max-md:mb-2.5 max-md:text-2xl max-sm:text-xl">
                            @lang('shop::app.products.view.reviews.customer-review')

                            ({{ $reviewHelper->getTotalReviews($product) }})
                        </h3>
                        
                        <div class="flex gap-16 max-lg:flex-wrap max-sm:gap-5 max-sm:gap-x-0">
                            <!-- Left Section -->
                            <div class="sticky top-24 flex h-max flex-col gap-6 max-lg:relative max-lg:top-auto max-md:w-full">
                                
                                <div class="flex flex-col items-center gap-2 max-md:mt-3 max-md:gap-0 max-md:border-b max-md:border-zinc-200 max-md:pb-3">
                                    <p class="text-5xl max-md:text-3xl">
                                        {{ $avgRatings }}
                                    </p>
                                    
                                    <div class="flex items-center gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="icon-star-fill text-3xl {{ $avgRatings >= $i ? 'text-amber-500' : 'text-zinc-500' }}"></span>
                                        @endfor
                                    </div>

                                    <p class="text-base text-zinc-500 max-sm:text-sm">
                                        {{ $reviewHelper->getTotalFeedback($product) }}

                                        @lang('shop::app.products.view.reviews.ratings')
                                    </p>
                                </div>

                                <!-- Ratings By Individual Stars -->
                                <div class="grid max-w-[365px] flex-wrap gap-y-3 max-md:max-w-full">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <div class="row grid grid-cols-[1fr_2fr] items-center gap-4 max-md:grid-cols-[0.5fr_2fr] max-sm:flex-wrap max-sm:gap-0">
                                            <div class="whitespace-nowrap text-base font-medium max-sm:text-sm">{{ $i }} Stars</div>

                                            <div class="h-4 w-[275px] max-w-full rounded-sm bg-neutral-200 max-sm:h-3.5 max-sm:w-full">
                                                <div
                                                    class="h-4 rounded-sm bg-amber-500 max-sm:h-3.5"
                                                    style="width: {{ $percentageRatings[$i] }}%"
                                                ></div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                <!-- Create Button -->
                                @if(core()->getConfigData('catalog.products.review.customer_review'))
                                    @if (
                                        core()->getConfigData('catalog.products.review.guest_review')
                                        || auth()->guard('customer')->user()
                                    )
                                        <div
                                            class="flex cursor-pointer items-center justify-center gap-x-4 rounded-xl border border-navyBlue px-4 py-3 max-sm:rounded-lg max-sm:py-1.5"
                                            @click="canReview = true"
                                        >
                                            <span class="icon-pen text-2xl"></span>

                                            @lang('shop::app.products.view.reviews.write-a-review')
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <!-- Right Section -->
                            <div class="flex w-full flex-col gap-5">
                                <!-- Product Review Item Vue Component -->
                                <v-product-review-item
                                    v-for='review in reviews'
                                    :review="review"
                                ></v-product-review-item>

                                <button
                                    class="mx-auto block w-max rounded-2xl border border-navyBlue bg-white px-11 py-3 text-center text-base font-medium text-navyBlue"
                                    v-if="links?.next"
                                    @click="get()"
                                >
                                    @lang('shop::app.products.view.reviews.load-more')
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Empty Review Section -->
                    <template v-else>
                        <div class="m-auto grid h-[476px] w-full place-content-center items-center justify-items-center text-center max-md:h-60">
                            <img
                                class="max-md:h-32 max-md:w-32 max-sm:h-[100px] max-sm:w-[100px]"
                                src="{{ bagisto_asset('images/review.png') }}"
                                alt=""
                                title=""
                            >

                            <p class="text-xl max-md:text-sm max-sm:text-xs">
                                @lang('shop::app.products.view.reviews.empty-review')
                            </p>
                        
                            @if(core()->getConfigData('catalog.products.review.customer_review'))
                                @if (
                                    core()->getConfigData('catalog.products.review.guest_review')
                                    || auth()->guard('customer')->user()
                                )
                                    <div
                                        class="mt-8 flex cursor-pointer items-center gap-x-4 rounded-xl border border-navyBlue px-4 py-2.5 max-sm:mt-5 max-sm:gap-x-1.5 max-sm:rounded-lg max-sm:py-1.5 max-sm:text-sm"
                                        @click="canReview = true"
                                    >
                                        <span class="icon-pen text-2xl max-sm:text-lg"></span>

                                        @lang('shop::app.products.view.reviews.write-a-review')
                                    </div>
                                @endif
                            @endif
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </script>

    <!-- Product Review Item Template -->
    <script
        type="text/x-template"
        id="v-product-review-item-template"
    >
        <div class="rounded-xl border border-zinc-200 p-6 max-md:hidden">
            <div class="flex gap-5">
                <template v-if="review.profile">
                    <img
                        class="flex max-h-[100px] min-h-[100px] min-w-[100px] max-w-[100px] items-center justify-center rounded-xl"
                        :src="review.profile"
                        :alt="review.name"
                        :title="review.name"
                    >
                </template>

                <template v-else>
                    <div
                        class="flex max-h-[100px] min-h-[100px] min-w-[100px] max-w-[100px] items-center justify-center rounded-xl bg-zinc-100"
                        :title="review.name"
                    >
                        <span class="text-2xl font-semibold text-zinc-500">
                            @{{ review.name.split(' ').map(name => name.charAt(0).toUpperCase()).join('') }}
                        </span>
                    </div>
                </template>
            
                <div class="flex flex-col">
                    <p class="font x-md:text-lg text-xl">
                        @{{ review.name }}
                    </p>
                    
                    <p class="mb-2 text-sm font-medium text-neutral-500">
                        @{{ review.created_at }}
                    </p>

                    <div class="flex items-center gap-0.5">
                        <span
                            class="icon-star-fill text-3xl"
                            v-for="rating in [1,2,3,4,5]"
                            :class="review.rating >= rating ? 'text-amber-500' : 'text-zinc-500'"
                        ></span>
                    </div>
                </div>
            </div>

            <div class="mt-3 flex flex-col gap-4">
                <p class="text-base max-sm:text-xs">
                    @{{ review.title }}
                </p>

                <p class="text-base leading-relaxed text-neutral-500 max-sm:text-xs">
                    @{{ review.comment }}
                </p>

                @if ((bool) core()->getConfigData('general.magic_ai.review_translation.enabled'))
                    <button
                        class="secondary-button min-h-[34px] rounded-lg px-2 py-1 text-sm max-md:rounded-lg"
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
                @endif
                
                <!-- Review Attachments -->
                <div
                    class="mt-3 flex flex-wrap gap-2"
                    v-if="review.images.length"
                >
                    <template v-for="(file, index) in review.images">
                        <div
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
                                @click="isImageZooming = !isImageZooming; activeIndex = index"
                            >
                        </div>
                        
                        <div
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
                                @click="isImageZooming = !isImageZooming; activeIndex = index"
                            >
                            </video>
                        </div>
                    </template>
                </div>

                <!-- Review Images zoomer -->
                <x-shop::image-zoomer 
                    ::attachments="attachments" 
                    ::is-image-zooming="isImageZooming" 
                    ::initial-index="'file_'+activeIndex"
                />
            </div>
        </div>

        <!-- For Mobile View -->
        <div class="md:hidden">
            <div class="grid gap-1.5 rounded-xl border border-zinc-200 p-4 max-md:mb-0">
                <div class="flex items-center gap-2.5">
                    <img
                        v-if="review.profile"
                        class="flex max-h-10 min-h-10 min-w-10 max-w-10 items-center justify-center rounded-full"
                        :src="review.profile"
                        :alt="review.name"
                        :title="review.name"
                    >
    
                    <div
                        v-else
                        class="flex max-h-10 min-h-10 min-w-10 max-w-10 items-center justify-center rounded-full bg-zinc-100"
                        :title="review.name"
                    >
                        <span class="text-xs font-semibold text-zinc-500">
                            @{{ review.name.split(' ').map(name => name.charAt(0).toUpperCase()).join('') }}
                        </span>
                    </div>
    
                    <div class="grid grid-cols-1">
                        <p class="text-base font-medium">
                            @{{ review.name }}
                        </p>
                        
                        <p class="text-xs text-zinc-500">
                            @{{ review.created_at }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="icon-star-fill text-xl {{ $avgRatings >= $i ? 'text-amber-500' : 'text-zinc-500' }}"></span>
                    @endfor
                </div>
    
                <div class="w-full">
                    <p class="text-sm font-semibold">
                        @{{ review.title }}
                    </p>
    
                    <p class="mt-1.5 text-sm text-zinc-500">
                        @{{ review.comment }}
                    </p>

                    @if ((bool) core()->getConfigData('general.magic_ai.review_translation.enabled'))
                        <button
                            class="secondary-button mt-2.5 min-h-[34px] rounded-lg px-4 py-2.5 text-base max-md:rounded-lg max-sm:px-3 max-sm:py-1 max-sm:text-xs"
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
                    @endif
                </div>
    
                <!-- Review Attachments -->
                <div
                    class="journal-scroll scrollbar-width-hidden mt-3 flex gap-2 overflow-auto"
                    v-if="review.images.length"
                >
                    <template v-for="file in review.images">
                        <a
                            :href="file.url"
                            class="flex h-20 w-20"
                            target="_blank"
                            v-if="file.type == 'image'"
                        >
                            <img
                                class="max-h-20 min-w-20 cursor-pointer rounded-xl"
                                :src="file.url"
                                :alt="review.name"
                                :title="review.name"
                            >
                        </a>
    
                        <a
                            :href="file.url"
                            class="flex h-20 w-20"
                            target="_blank"
                            v-else
                        >
                            <video
                                class="max-h-20 min-w-20 cursor-pointer rounded-xl"
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

            data() {
                return {
                    isLoading: true,
                    
                    appliedRatings: 5,

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
                    if (! this.links?.next) {
                        return;
                    }
                    
                    this.$axios.get(this.links.next)
                        .then(response => {
                            this.isLoading = false;

                            this.reviews = [...this.reviews, ...response.data.data];

                            this.links = response.data.links;

                            this.meta = response.data.meta;
                        })
                        .catch(error => {});
                },

                store(params, { resetForm, setErrors }) {
                    let selectedFiles = this.$refs.reviewImages.uploadedFiles
                        .filter(obj => obj.file instanceof File)
                        .map(obj => obj.file);

                    params.attachments = selectedFiles;

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
            },
        });
        
        app.component('v-product-review-item', {
            template: '#v-product-review-item-template',

            props: ['review'],

            data() {
                return {
                    isLoading: false,

                    isImageZooming: false,

                    activeIndex: 0,
                }
            },

            computed: {
                attachments() {
                    let data = [...this.review.images].map((file) => {
                        return {
                            url: file.url,
                            type: file.type,
                        }
                    });

                    return data;
                },
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