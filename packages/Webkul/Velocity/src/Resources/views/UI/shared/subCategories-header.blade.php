@php
    $category = app('Webkul\Category\Repositories\CategoryRepository');
    $categoryData = $category->find($category_id);
    $categoryNestedData = $category->getVisibleCategoryTree($category_id);
@endphp

    <child-category></child-category>

    <script type="text/x-template" id="child-category-template">
        <i class="material-icons">chevron_right</i>

        <h1>{{ $categoryData->name }}</h1>

        @foreach($categoryNestedData as $index => $childrens)
            <li>
                {{ $childrens->name }}

                @if($childrens->children && sizeof($childrens->children) > 0)
                    <i class="material-icons"
                        onclick="showOrHide()">
                            chevron_right
                    </i>
                @endif

            </li>
        @endforeach
    </script>

   @push('scripts')
        <script>
            Vue.component('child-category', {

                template: '#child-category-template',

                data: function() {
                    return {
                        categoryName: ''
                    };
                },

                mounted: function() {
                    this.categoryName = "menswear";
                },

                created: function() {

                },

                methods: {
                    showOrHide: function(id) {
                        alert(id);
                    }
                }
            });
        </script>

        <script type=text/javascript>
            function showOrHide() {
                alert("showOrHide");
            }
        </script>
   @endpush