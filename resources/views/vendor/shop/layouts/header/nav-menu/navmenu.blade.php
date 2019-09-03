{!! view_render_event('bagisto.shop.layout.header.category.before') !!}

<?php

$categories = [];

foreach (app('Webkul\CustomerGroupCatalog\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $category) {
    if ($category->slug)
        array_push($categories, $category);
}

$cmsPages = app('Webkul\CMS\Repositories\CMSRepository')->findWhere([
    'channel_id' => core()->getCurrentChannel()->id,
    'locale_id' => core()->getCurrentLocale()->id
]);

?>

<category-nav categories='@json($categories)' url="{{url()->to('/')}}"></category-nav>

{!! view_render_event('bagisto.shop.layout.header.category.after') !!}


@push('scripts')


<script type="text/x-template" id="category-nav-template">

    <ul class="nav">
        <li>
            <a href="{{ route('shop.home.index') }}">Home</a>
        </li>

        <li>
            <a>Catalog</a>

            <category-item
                v-for="(item, index) in items"
                :key="index"
                :url="url"
                :item="item"
                :parent="index">
            </category-item>
        </li>

        @foreach($cmsPages as $cmsPage)
            <li>
                <a href="{{ route('shop.cms.page', $cmsPage->url_key) }}">{{ $cmsPage->page_title }}</a>
            </li>
        @endforeach
    </ul>

</script>

<script>
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

        data: function(){
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
</script>

<script type="text/x-template" id="category-item-template">
    <li>
        <a :href="url+'/categories/'+this.item['translations'][0].slug">
            @{{ name }}&emsp;
            <i class="icon dropdown-right-icon" v-if="haveChildren && item.parent_id != null"></i>
        </a>

        <i :class="[show ? 'icon icon-arrow-down mt-15' : 'icon dropdown-right-icon left mt-15']"
        v-if="haveChildren"  @click="showOrHide"></i>

        <ul v-if="haveChildren && show">
            <category-item
                v-for="(child, index) in item.children"
                :key="index"
                :url="url"
                :item="child">
            </category-item>
        </ul>
    </li>
</script>

<script>
    Vue.component('category-item', {

        template: '#category-item-template',

        props: {
            item:  Object,
            url: String,
        },

        data: function() {
            return {
                items_count:0,
                show: false,
            };
        },

        mounted: function() {
            if(window.innerWidth > 770){
                this.show = true;
            }
        },

        computed: {
            haveChildren: function() {
                return this.item.children.length ? true : false;
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

        methods: {
            showOrHide: function() {
                this.show = !this.show;
            }
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('.nav > li > li').css('display', 'none');

        $('.nav > li').mouseenter(function () {
            $('.nav > li > li').css('display', 'block');
        });

        $('.nav > li').mouseleave(function () {
            $('.nav > li > li').css('display', 'none');
        });

        $('ul > li li').css('background', 'white');

        $('ul > li li').css('border', '1px solid #c7c7c7');

        $('ul > li li').css('border-right', '1px solid #c7c7c7');

        $('.header .header-bottom .nav > li').css('z-index', '1');
    });
</script>

@endpush