<template>
    <div
        id="main-category"
        :class="
            `main-category fs16 unselectable fw6 ${
                $root.sharedRootCategories.length > 0
                    ? 'cursor-pointer'
                    : 'cursor-not-allowed'
            } left`
        "
        @mouseout="toggleSidebar('0', $event, 'mouseout')"
        @mouseover="toggleSidebar('0', $event, 'mouseover')"
    >
        <i class="rango-view-list align-vertical-top fs18"> </i>

        <span
            class="pl5"
            v-text="heading"
            @mouseover="toggleSidebar('0', $event, 'mouseover')"
        >
        </span>
    </div>
</template>

<script>
export default {
    props: ['heading']
};
</script>
