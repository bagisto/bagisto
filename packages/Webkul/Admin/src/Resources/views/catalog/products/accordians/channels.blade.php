<accordian :title="'{{ __('admin::app.catalog.products.channel') }}'" :active="true">
    <div slot="body">
        <div class="control-group">
            <label for="sku">{{ __('admin::app.catalog.products.channel') }}</label>
            <select class="control" name="channels[]" multiple>
                @foreach (app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                    <option value="{{ $channel->id }}">
                        {{ $channel->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</accordian>