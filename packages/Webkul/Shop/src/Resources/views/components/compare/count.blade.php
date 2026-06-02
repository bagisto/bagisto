@props([
    'label' => trans('shop::app.components.layouts.header.desktop.bottom.compare'),
    'iconClass' => 'inline-block cursor-pointer text-2xl icon-compare',
])

<v-compare-count
    label="{{ $label }}"
    icon-class="{{ $iconClass }}"
>
    <a
        href="{{ route('shop.compare.index') }}"
        aria-label="{{ $label }}"
    >
        <span class="relative inline-block">
            <span
                class="{{ $iconClass }}"
                role="presentation"
            ></span>
        </span>
    </a>
</v-compare-count>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-compare-count-template"
    >
        <a
            href="{{ route('shop.compare.index') }}"
            :aria-label="label"
        >
            <span class="relative inline-block">
                <span
                    :class="iconClass"
                    role="presentation"
                ></span>

                <span
                    class="absolute -top-4 rounded-[44px] bg-navyBlue px-2 py-1.5 text-xs font-semibold leading-[9px] text-white ltr:left-5 rtl:right-5 max-md:px-2 max-md:py-1.5 max-md:ltr:left-4 max-md:rtl:right-4"
                    v-if="compareItemsCount"
                >
                    @{{ compareItemsCount }}
                </span>
            </span>
        </a>
    </script>

    <script type="module">
        app.component('v-compare-count', {
            template: '#v-compare-count-template',

            props: ['label', 'iconClass'],

            data() {
                return {
                    compareItemsCount: 0,

                    isCustomer: @json(auth()->guard('customer')->check()),
                };
            },

            mounted() {
                this.getCompareItemsCount();

                this.$emitter.on('update-compare-items', this.updateCompareItemsCount);
            },

            methods: {
                updateCompareItemsCount(items) {
                    if (Array.isArray(items)) {
                        this.compareItemsCount = items.length;

                        return;
                    }

                    this.getCompareItemsCount();
                },

                getCompareItemsCount() {
                    if (! this.isCustomer) {
                        this.compareItemsCount = this.getStorageValue().length;

                        return;
                    }

                    this.$axios.get('{{ route('shop.api.compare.index') }}')
                        .then(response => {
                            this.compareItemsCount = response.data.data.length;
                        })
                        .catch(error => {});
                },

                getStorageValue() {
                    let value = localStorage.getItem('compare_items');

                    if (! value) {
                        return [];
                    }

                    try {
                        return JSON.parse(value) || [];
                    } catch (error) {
                        return [];
                    }
                },
            },
        });
    </script>
@endPushOnce
