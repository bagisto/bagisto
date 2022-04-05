<template>
    <div class="">
        <div
            class="dropdown-filters per-page"
            v-if="extraFilters.channels != undefined"
        >
            <channel-filter
                :extra-filters="extraFilters"
                :translations="translations"
                @onFilter="changeExtraFilter($event)"
            ></channel-filter>
        </div>

        <div
            class="dropdown-filters per-page"
            v-if="extraFilters.locales != undefined"
        >
            <locale-filter
                :extra-filters="extraFilters"
                :translations="translations"
                @onFilter="changeExtraFilter($event)"
            ></locale-filter>
        </div>

        <div
            class="dropdown-filters per-page"
            v-if="extraFilters.customer_groups != undefined"
        >
            <customer-group-filter
                :extra-filters="extraFilters"
                :translations="translations"
                @onFilter="changeExtraFilter($event)"
            ></customer-group-filter>
        </div>
    </div>
</template>

<script>
import ChannelFilter from '../filters/channel-filter';
import CustomerGroupFilter from '../filters/customer-group-filter';
import LocaleFilter from '../filters/locale-filter';

export default {
    props: ['extraFilters', 'translations'],

    components: {
        ChannelFilter,
        CustomerGroupFilter,
        LocaleFilter
    },

    methods: {
        changeExtraFilter($event) {
            this.$emit('onFilter', $event);
        }
    }
};
</script>
