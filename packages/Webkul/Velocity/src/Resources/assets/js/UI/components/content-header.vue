<template>
    <div class="row velocity-divide-page remove-padding-margin vc-header">
        <div class="vc-small-screen container">
            <div class="row">
                <div class="col-8">
                    <div class="row col-12">
                        <div class="hamburger-wrapper">
                            <i class="rango-toggle hamburger"></i>
                        </div>
                        <logo-component add-class="pl10"></logo-component>
                    </div>
                </div>
                <div class="row col-4 right-vc-header">
                    <i class="fs16 fw6 rango-search"></i>
                    <i class="material-icons text-down-3">shopping_cart</i>
                </div>
            </div>
        </div>

        <div
            @mouseout="toggleSidebar('0', $event, 'mouseout')"
            @mouseover="toggleSidebar('0', $event, 'mouseover')"
            class="main-category fs16 unselectable fw6 cursor-pointer left">

            <i class="rango-view-list text-down-4 align-vertical-top fs18">
            </i>
            <span
                class="pl5"
                v-text="heading"
                @mouseover="toggleSidebar('0', $event, 'mouseover')">
            </span>
        </div>

        <div class="content-list right">
            <ul type="none" class="no-margin ul-shadow">
                <li v-for="(content, index) in headerContent" :key="index">
                    <a
                        v-text="content.title"
                        :href="content['page_link']"
                        v-if="(content['content_type'] == 'link')"
                        :target="content['link_target'] ? '_blank' : '_self'">
                    </a>

                    <a href="#" v-else v-text="content.title"></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['heading', 'headerContent', 'isEnabled'],
    }
</script>
