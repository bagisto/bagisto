{!! view_render_event('bagisto.shop.layout.header.category.before') !!}

@php
    $velocityContent = app('Webkul\Velocity\Repositories\ContentRepository')->getAllContents();

    $categories = [];

    foreach (app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $category) {
        if ($category->slug)
            array_push($categories, $category);
    }
@endphp

<div class="nav-container">
    <div class="wrapper">
        <div class="greeting">
            <i class="material-icons">perm_identity</i>
            <span>
                @guest('customer')
                    {{ __('velocity::app.responsive.header.greeting', ['customer' => 'Guest']) }}
                @endguest

                @auth('customer')
                    {{ __('velocity::app.responsive.header.greeting', ['customer' => auth()->guard('customer')->user()->first_name]) }}
                @endauth
                <i class="material-icons close-responsive-header" v-on:click="closeDrawer()">cancel</i>
            </span>
        </div>

        <div class="offers">
            <ul type="none">
                @foreach ($velocityContent as $index => $content)
                    <li>{{ $content->title }}</li>
                @endforeach
            </ul>
        </div>

        <div class="layered-category">
            <category-nav categories='@json($categories)' url="{{url()->to('/')}}"></category-nav>
        </div>
    </div>
</div>

{!! view_render_event('bagisto.shop.layout.header.category.after') !!}


@push('scripts')
    <script type="text/x-template" id="category-nav-template">

        <ul type="none">
            <category-item
                v-for="(item, index) in items"
                :key="index"
                :url="url"
                :item="item"
                :parent="index">
            </category-item>
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
            <img
                class="category-icon"
                src="{{ asset('storage/'.$category->category_icon_path) }}"/>

            <a class="category-name" :href="url+'/'+this.item['translations'][0].url_path">
                <span>@{{ name }}</span>
            </a>

            <!-- <i class="material-icons"
                v-if="haveChildren && item.parent_id != null"
                @click="showOrHide(item.id)">
                    chevron_right
            </i> -->

            <!--
            <div class="sub-category" v-if="haveChildren && show">
                @include('shop::UI.shared.subCategories-header', [
                    'category_id' => 12
                    ])
                -->
            </div>
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
                },
            },

            methods: {
                showOrHide: function(id) {
                    $('.category-name').html("").hide();
                    $("img").attr("src", "").hide();
                    $(".demo").html("").hide();
                    this.item.children = this.item;
                    this.show = true;
                    $('.greeting, .offers').hide();
                }
            }
        });
    </script>

    <script type="text/javascript">
        function closeSubCategory() {
            $('.greeting, .offers, .layered-category').show();
            $('.sub-category, .sub-category').hide();
        }
    </script>
@endpush