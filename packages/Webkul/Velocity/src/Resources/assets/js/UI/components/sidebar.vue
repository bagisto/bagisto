<template>
    <!-- categories list -->
    <nav
        :id="id"
        @mouseover="remainBar(id)"
        :class="`sidebar ${addClass ? addClass : ''}`"
        v-if="slicedCategories && slicedCategories.length > 0">

        <ul type="none">
            <li
                :key="index"
                :id="`category-${category.id}`"
                class="category-content cursor-pointer"
                v-for="(category, index) in slicedCategories"
                @mouseout="toggleSidebar(id, $event, 'mouseout')"
                @mouseover="toggleSidebar(id, $event, 'mouseover')">

                <a
                    class="category unset"
                    :class="(category.children.length > 0) ? 'fw6' : ''"
                    :href="`${url}/${category['translations'][0].slug}`">

                    <div
                        class="category-icon"
                        @mouseout="toggleSidebar(id, $event, 'mouseout')"
                        @mouseover="toggleSidebar(id, $event, 'mouseover')">

                        <img
                            v-if="category.image"
                            :src="`${url}/storage/${category.image}`" />
                    </div>
                    <span class="category-title">{{ category['name'] }}</span>
                    <i
                        class="rango-arrow-right pr15"
                        @mouseout="toggleSidebar(id, $event, 'mouseout')"
                        @mouseover="toggleSidebar(id, $event, 'mouseover')"
                        v-if="category.children.length && category.children.length > 0">
                    </i>
                </a>

                <div
                    class="sub-category-container"
                    v-if="category.children.length && category.children.length > 0">

                    <div :class="`sub-categories sub-category-${sidebarLevel+index}`">
                        <sidebar-component
                            :url="url"
                            :id="`sidebar-level-${sidebarLevel+index}`"
                            :categories="category.children">
                        </sidebar-component>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</template>

<script type="text/javascript">
    export default {
        props: [
            'id',
            'url',
            'addClass',
            'categories',
            'mainSidebar',
            'categoryCount'
        ],

        data: function () {
            let slicedCategories = this.categories;
            let categoryCount = this.categoryCount ? this.categoryCount : 9;

            if (slicedCategories
                && slicedCategories.length > categoryCount
            ) {
                slicedCategories = this.categories.slice(0, categoryCount);
            }

            return {
                slicedCategories,
                sidebarLevel: Math.floor(Math.random() * 1000),
            }
        },

        methods: {
            remainBar: function (id) {
                let sidebar = $(`#${id}`);
                if (sidebar && sidebar.length > 0) {
                    sidebar.show();

                    let actualId = id.replace('sidebar-level-', '');

                    let sidebarContainer = sidebar.closest(`.sub-category-${actualId}`)
                    if (sidebarContainer && sidebarContainer.length > 0) {
                        sidebarContainer.show();
                    }

                }
            },
        }
    }
</script>