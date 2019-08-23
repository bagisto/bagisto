@php
    $link = app('Webkul\Custom\Helpers\CollectURLKeys');

    $categoryKeys = $link->getCategoryKeys();

    $productKeys = $link->getProductKeys();
@endphp

<div class="control-group">
    <label for="select-link">Select Link</label>

    <select class="control" name="url_key" v-validate="'required'">
        @if (count($categoryKeys))
            <optgroup label="categories">
                @foreach ($categoryKeys as $categoryKey)
                    @php
                        $value = 'categories/'.$categoryKey['url_key'];
                    @endphp

                    <option value="{{ $value }}"
                    @if(isset($slider->url_key) && $slider->url_key == $value)
                        selected="selected"
                    @endif>

                        {{ $categoryKey['name'] }}

                    </option>
                @endforeach
            </optgroup>
        @endif

        @if (count($productKeys))
            <optgroup label="products">
                @foreach ($productKeys as $productKey)
                    @php
                        $value = 'products/'.$productKey['url_key'];
                    @endphp

                    <option value="{{ $value }}"
                    @if(isset($slider->url_key) && $slider->url_key == $value)
                        selected="selected"
                    @endif>

                        {{ $productKey['name'] }}

                    </option>
                @endforeach
            </optgroup>
        @endif
    </select>
</div>