<template>
    <!-- categories list -->
    <nav
        id="sidebar"
        :class="`${addClass ? addClass : ''}`"
        v-if="slicedCategories && slicedCategories.length > 0">

        <ul type="none">
            <li
                :key="index"
                class="category-content"
                :id="`category-${category.id}`"
                v-for="(category, index) in slicedCategories">

                <a
                    class="category unset"
                    @mouseover="hover(index, 'mouseover')"
                    @mouseleave="hover(index, 'mouseleave')"
                    :class="(category.children.length > 0) ? 'fw6' : ''"
                    :href="`${url}/${category['translations'][0].slug}`">

                    <span class="category-title">{{ category['name'] }}</span>
                    <i
                        class="angle-right-icon"
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
        props: [
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
                slicedCategories
            }
        },

        mounted: function () {
            let checkLocale = window.location.href.slice(0, `${this.url}/?locale=`.length);
            let checkCurrency = window.location.href.slice(0, `${this.url}/?currency=`.length);

            if (
                checkLocale == `${this.url}/?locale=`
                || checkCurrency == `${this.url}/?currency=`
            ) {
            } else if (
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