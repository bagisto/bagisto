@if ($categories->count())
<accordian :title="'{{ __($accordian['name']) }}'" :active="true">
    <div slot="body">
        
        <tree-view behavior="normal" value-field="id" name-field="categories" input-type="checkbox" items='@json($categories)' value='@json($product->categories->pluck("id"))'></tree-view>

    </div>
</accordian>
@endif