@php
    $isCollapsible = core()->getConfigData('catalog.products.category_view_page.collapsible_description');
@endphp

{!! view_render_event('bagisto.shop.categories.view.description.before') !!}

@if (in_array($category->display_mode, [null, 'description_only', 'products_and_description']))
    @if ($category->description)
        @if ($isCollapsible)
            <div class="container mt-8.5 px-15 max-lg:px-8 max-md:mt-4 max-md:px-4 max-md:text-sm max-sm:text-xs">
                <v-category-description>
                    <div class="rich-text">
                        {!! $category->description !!}
                    </div>
                </v-category-description>
            </div>
        @else
            <div class="rich-text container mt-8.5 px-15 max-lg:px-8 max-md:mt-4 max-md:px-4 max-md:text-sm max-sm:text-xs">
                {!! $category->description !!}
            </div>
        @endif
    @endif
@endif

{!! view_render_event('bagisto.shop.categories.view.description.after') !!}

@if ($isCollapsible)
    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-category-description-template"
        >
            <div>
                <div
                    class="relative overflow-hidden"
                    :style="isCollapsed ? { maxHeight: collapsedHeight + 'px' } : {}"
                >
                    <div ref="content">
                        <slot></slot>
                    </div>

                    <div
                        class="pointer-events-none absolute inset-x-0 bottom-0 h-16 bg-linear-to-t from-white"
                        v-if="isCollapsed"
                    ></div>
                </div>

                <button
                    type="button"
                    class="mt-3 cursor-pointer text-base font-medium text-navyBlue hover:underline max-md:text-sm max-sm:text-xs"
                    :aria-expanded="isExpanded ? 'true' : 'false'"
                    v-if="isOverflowing"
                    @click="isExpanded = ! isExpanded"
                >
                    <template v-if="isExpanded">
                        @lang('shop::app.categories.view.read-less')
                    </template>

                    <template v-else>
                        @lang('shop::app.categories.view.read-more')
                    </template>
                </button>
            </div>
        </script>

        <script type="module">
            app.component('v-category-description', {
                template: '#v-category-description-template',

                data() {
                    return {
                        collapsedHeight: 150,

                        isExpanded: false,

                        isOverflowing: false,

                        resizeObserver: null,
                    };
                },

                computed: {
                    isCollapsed() {
                        return this.isOverflowing && ! this.isExpanded;
                    },
                },

                mounted() {
                    this.measure();

                    this.resizeObserver = new ResizeObserver(() => this.measure());

                    this.resizeObserver.observe(this.$refs.content);
                },

                beforeUnmount() {
                    this.resizeObserver?.disconnect();
                },

                methods: {
                    measure() {
                        this.isOverflowing = this.$refs.content.offsetHeight > this.collapsedHeight;
                    },
                },
            });
        </script>
    @endPushOnce
@endif
