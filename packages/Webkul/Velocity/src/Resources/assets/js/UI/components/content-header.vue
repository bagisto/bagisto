<template>
    <div class="row">
        <div class="main-category fs20 pt10 col-2 pl30">
            <i class="rango-view-list cursor-pointer align-vertical-top" @click="toggleSidebar"></i>
            <span class="text-up" v-text="heading"></span>
        </div>

        <div class="content-list row no-margin col-10">
            <ul type="none" class="no-margin">
                <li v-for="(content, index) in headerContent" :key="index">
                    <a
                        v-if="(content['content_type'] == 'link')"
                        href="content['page_link']"
                        :target="content['link_target'] ? '_blank' : '_self'"
                        v-text="content.title"
                    ></a>

                    <a href="#" v-else v-text="content.title"></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['heading', 'headerContent'],

        methods: {
            // toggleSidebar: function (event) {
            //     let rightBarContainer = document.getElementById('home-right-bar-container');
            //     let categoryListContainer = document.getElementById('sidebar');

            //     categoryListContainer.classList.toggle('hide');

            //     if (rightBarContainer.className.search('col-10') > -1) {
            //         rightBarContainer.className = rightBarContainer.className.replace('col-10', 'col-12');
            //     } else {
            //         rightBarContainer.className = rightBarContainer.className.replace('col-12', 'col-10');
            //     }
            // }
        }
    }
</script>
