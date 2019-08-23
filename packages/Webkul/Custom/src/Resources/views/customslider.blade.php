@php
    $link = app('Webkul\Custom\Helpers\CollectURLKeys');

    $categoryKeys = $link->getCategoryKeys();

    $productKeys = $link->getProductKeys();
@endphp

<div class="control-group">
    <label for="select-link">Select Link</label>

    <select class="control" name="url_key">
        @if (count($categoryKeys))
            <optgroup label="categories">
                @foreach ($categoryKeys as $categoryKey)
                    <option value="categories/{{ $categoryKey['url_key'] }}">{{ $categoryKey['name'] }}</option>
                @endforeach
            </optgroup>
        @endif

        @if (count($productKeys))
            <optgroup label="products">
                @foreach ($productKeys as $productKey)
                    <option value="products/{{ $productKey['url_key'] }}">{{ $productKey['name'] }}</option>
                @endforeach
            </optgroup>
        @endif
    </select>
</div>