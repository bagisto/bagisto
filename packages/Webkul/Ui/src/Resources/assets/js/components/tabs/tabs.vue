<template>
    <div>
        <div class="tabs">
            <ul>
                <li v-for="tab in tabs" :class="{ 'active': tab.isActive }" @click="selectTab(tab)">
                    <a>{{ tab.name }}</a>
                </li>
            </ul>
        </div>

        <div class="tabs-content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                tabs: []
            }
        },

        created() {
            this.tabs = this.$children;
        },

        methods: {
            selectTab(selectedTab) {
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.name == selectedTab.name);
                });
            }
        }
    }
</script>