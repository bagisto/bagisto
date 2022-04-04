<template>
    <div class="control-group">
        <select
            class="control"
            id="customer-group-switcher"
            name="customer_group"
            @change="changeCustomerGroup($event)"
        >
            <option
                value="all"
                :selected="extraFilters.current.customer_group == 'all'"
                v-text="translations.allCustomerGroups"
            ></option>

            <option
                :key="customerGroupKey"
                v-for="(customerGroup,
                customerGroupKey) in extraFilters.customer_groups"
                v-text="customerGroup.name"
                :value="customerGroup.id"
                :selected="
                    customerGroup.id == extraFilters.current.customer_group
                "
            ></option>
        </select>
    </div>
</template>

<script>
export default {
    props: ['extraFilters', 'translations'],

    data() {
        return {};
    },

    methods: {
        changeCustomerGroup({ target }) {
            this.$emit('onFilter', {
                data: { type: 'customer_group', value: target.value }
            });
        }
    }
};
</script>
