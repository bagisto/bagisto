@php
    $productChannels = app(\Webkul\Product\Repositories\ProductFlatRepository::class)->findWhere([
        'product_id' => $product->id
    ])->pluck('channel')->unique()->toArray();
@endphp

<accordian title="{{ __('admin::app.catalog.products.channel') }}" :active="false">
    <div slot="body">
        <div class="control-group multi-select" :class="[errors.has('channels[]') ? 'has-error' : '']">
            <label for="channels" class="required">{{ __('admin::app.catalog.products.channel') }}</label>

            <select class="control" name="channels[]" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.catalog.products.channel') }}&quot;" multiple>
                @foreach (app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                    <option value="{{ $channel->id }}" {{ in_array($channel->code, $productChannels) ? 'selected' : ''}}>
                        {{ core()->getChannelName($channel) }}
                    </option>
                @endforeach
            </select>

            <span class="control-error" v-if="errors.has('channels[]')">
                @{{ errors.first('channels[]') }}
            </span>
        </div>
    </div>
</accordian>