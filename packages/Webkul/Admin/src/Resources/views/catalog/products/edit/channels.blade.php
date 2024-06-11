@if (! $product->parent_id)
    @if ($channels->count() == 1)
        <input type="hidden" name="channels[]" value="{{ $channels->first()->id }}">
    @else
        {!! view_render_event('bagisto.admin.catalog.product.edit.form.channels.before', ['product' => $product]) !!}

        <!-- Panel -->
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <!-- Panel Header -->
            <p class="mb-4 flex justify-between text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.catalog.products.edit.channels.title')
            </p>

            {!! view_render_event('bagisto.admin.catalog.product.edit.form.channels.controls.before', ['product' => $product]) !!}

            <!-- Panel Content -->
            <div class="text-sm text-gray-600 dark:text-gray-300">
                @php $selectedChannelsId = old('channels') ?? $product->channels->pluck('id')->toArray() @endphp
                
                @foreach (core()->getAllChannels() as $channel)
                    <x-admin::form.control-group class="!mb-2 flex items-center gap-2.5 last:!mb-0">
                        <x-admin::form.control-group.control
                            type="checkbox"
                            :id="'channels_' . $channel->id" 
                            name="channels[]"
                            rules="required"
                            :value="$channel->id"
                            :for="'channels_' . $channel->id" 
                            :label="trans('admin::app.catalog.products.edit.channels.title')"
                            :checked="in_array($channel->id, $selectedChannelsId)"
                        />

                        <label
                            class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                            for="channels_{{ $channel->id }}"
                        >
                            {{ $channel->name }} 
                        </label>
                    </x-admin::form.control-group>
                @endforeach

                <x-admin::form.control-group.error control-name="channels[]" />
            </div>

            {!! view_render_event('bagisto.admin.catalog.product.edit.form.channels.controls.after', ['product' => $product]) !!}
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit.form.channels.after', ['product' => $product]) !!}
    @endif
@endif