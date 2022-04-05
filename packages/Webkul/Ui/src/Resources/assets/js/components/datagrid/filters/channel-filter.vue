<template>
    <div class="control-group">
        <select class="control" name="channel" @change="changeChannel($event)">
            <option
                value="all"
                :selected="extraFilters.current.channel == 'all'"
                v-text="translations.allChannels"
            ></option>

            <option
                :key="channelKey"
                v-for="(channel, channelKey) in extraFilters.channels"
                v-text="channel.name"
                :value="channel.code"
                :selected="channel.code == extraFilters.current.channel"
            ></option>
        </select>
    </div>
</template>

<script>
export default {
    props: ['translations', 'extraFilters'],

    data() {
        return {};
    },

    methods: {
        changeChannel({ target }) {
            this.$emit('onFilter', {
                data: { type: 'channel', value: target.value }
            });
        }
    }
};
</script>
