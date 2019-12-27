<template>
    <!-- categories list -->
    <nav
        :class="`${addClass ? addClass : ''}`"
        id="sidebar"
        v-if="categories && categories.length > 0">

        <ul type="none">
            <li
                :key="index"
                :id="`category-${category.id}`"
                v-for="(category, index) in categories">

                <a
                    class="category unset"
                    :class="(category.children.length > 0) ? 'fw6' : ''"
                    :href="`${url}/categories/${category['translations'][0].slug}`"
                    @mouseover="hover(index, 'mouseover')"
                    @mouseleave="hover(index, 'mouseleave')">

                    <span>{{ category['name'] }}</span>
                    <i
                        class="angle-right-icon text-down-3"
                        v-if="category.children.length > 0"
                        @click="toggleSubCategory(index)">
                    </i>
                </a>

                <div
                    class="sub-categories"
                    v-if="category.children.length && category.children.length > 0">

                    <sidebar-component
                        :url="url"
                        :categories="category.children">
                    </sidebar-component>
                </div>
            </li>
        </ul>
    </nav>
</template>

<script type="text/javascript">
    export default {
        props: ['categories', 'url', 'addClass', 'mainSidebar'],

        mounted: function () {
            if (
                (window.location.href !== `${this.url}/` && this.mainSidebar)
                || (this.categories && this.categories.length == 0)
            ) {
                this.toggleSidebar();
            }
        },

        methods: {
            hover: function (index, actionType) {
                let category = this.categories[index];

                if (category.children.length > 0) {
                    let categoryElement = document.getElementById(`category-${category.id}`);
                    let subCategories = categoryElement.querySelector('.sub-categories');

                    if (subCategories.style.display == "" || subCategories.style.display == "none") {
                        subCategories.style.display = "block";
                    } else {
                        subCategories.style.display = "none";
                    }

                }
            },

            toggleSubCategory: function (index) {
                // let category = this.categories[index];

                // if (category.children.length > 0) {
                //     let categoryElement = document.getElementById(`category-${category.id}`);
                //     let subCategories = categoryElement.querySelector('.sub-categories');

                //     if (subCategories.style.display == "" || subCategories.style.display == "none") {
                //         subCategories.style.display = "block";
                //     } else {
                //         subCategories.style.display = "none";
                //     }

                // }
            }
        }
    }
</script>