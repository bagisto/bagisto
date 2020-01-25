{!! view_render_event('bagisto.shop.layout.header.category.before') !!}

<?php
    $categories = [];

    foreach (
        app('Webkul\Category\Repositories\CategoryRepository')
            ->getVisibleCategoryTree(
                core()->getCurrentChannel()->root_category_id
            )
        as $category
    ) {
        if ($category->slug) {
            array_push($categories, $category);
        }
    }
?>

{!! view_render_event('bagisto.shop.layout.header.category.after') !!}

@push('scripts')
    <script type="text/x-template" id="category-item-template">
        <li
            class="category-list"
            v-if="parent < 10"
            @mouseover="isShow = true"
            @mouseleave="isShow = false">

            <a :href="url+'/categories/'+this.item['translations'][0].slug">
                @{{ name }}&emsp;
                <i class="material-icons" v-if="haveChildren && item.parent_id != null">chevron_right</i>
            </a>

            <div class="child-container" v-if="haveChildren && isShow">
                <ul class="child-category" v-for="(child, index) in item.children">
                    <li class="category-list">

                        <a :href="url+'/categories/'+child.translations[0].slug">
                            @{{ child.name }}&emsp;
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="category-list" v-else-if="parent == 10">
            <a :href="url+'/categories/'+this.item['translations'][0].slug">

                {{ __('velocity::app.menu-navbar.text-more') }}&emsp;

                <i class="material-icons">chevron_right</i>
            </a>
        </li>
    </script>

    <script type="text/x-template" id="category-nav-template">
        <ul class="category-dropdown nav navbar-nav">
            <category-item
                v-for="(item, index) in items"
                :key="index"
                :url="url"
                :item="item"
                :parent="index">
            </category-item>
        </ul>
    </script>

    <script type="text/javascript">
        Vue.component('category-nav', {
            template: '#category-nav-template',

            props: {
                categories: {
                    type: [Array, String, Object],
                    required: false,
                    default: (function () {
                        return [];
                    })
                },

                url: String
            },

            data: function() {
                return {
                    items_count:0
                };
            },

            computed: {
                items: function() {
                    return JSON.parse(this.categories)
                }
            },
        });

        Vue.component('category-item', {
            template: '#category-item-template',

            props: {
                item:  Object,
                url: String,
                parent: Number,
            },

            data: function() {
                return {
                    isShow: false,
                    items_count:0,
                };
            },

            computed: {
                haveChildren: function() {
                    if (this.item.children.length) {
                        return true;
                    } else {
                        return false;
                    }
                },

                name: function() {
                    if (this.item.translations && this.item.translations.length) {
                        this.item.translations.forEach(function(translation) {
                            if (translation.locale == document.documentElement.lang)
                                return translation.name;
                        });
                    }

                    return this.item.name;
                }
            },
        });

        //Hide show category dropdown
        $(document).ready(function() {
            $('.category-dropdown').addClass('hide');

            $(".list-icon").on("click",function (){
                var clicks = $(this).closest('.list-icon').data('clicks');

                if (clicks == null) {
                    $('.category-dropdown').removeClass('hide');
                    $('.category-dropdown').addClass('show');

                } else if (clicks) {
                    $('.category-dropdown').removeClass('show');
                    $('.category-dropdown').addClass('hide');

                } else {
                    $('.category-dropdown').removeClass('hide');
                    $('.category-dropdown').addClass('show');

                }

                $(this).closest('.list-icon').data("clicks", !clicks);
            });
        });
    </script>

@endpush
