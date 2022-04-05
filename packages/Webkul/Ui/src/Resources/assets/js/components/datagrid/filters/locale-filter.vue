<template>
    <div class="control-group">
        <select class="control" name="locale" @change="changeLocale($event)">
            <option
                value="all"
                :selected="extraFilters.current.locale == 'all'"
                v-text="translations.allLocales"
            ></option>

            <option
                :key="localeKey"
                v-for="(locale, localeKey) in extraFilters.locales"
                v-text="locale.name"
                :value="locale.code"
                :selected="locale.code == extraFilters.current.locale"
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
        changeLocale({ target }) {
            this.$emit('onFilter', {
                data: { type: 'locale', value: target.value }
            });
        }
    }
};
</script>
