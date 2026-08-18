<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.appearance.sections.index.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.appearance.sections.index.before') !!}

    {{--
        The header lives inside the editor rather than here, because the channel switcher
        and the publish controls read the same state as the section list.
    --}}
    <x-admin::appearance.sections.editor
        :sections="$sections"
        :type-labels="$typeLabels"
        :theme-name="$scopedThemeName"
        :channels="$channels->map(fn ($channel) => [
            'id' => $channel->id,
            'name' => $channel->name,
            'url' => route('admin.appearance.sections.index', [
                'code' => $channel->theme ?: $scopedTheme,
                'channel' => $channel->id,
            ]),
        ])->values()"
        :channel-id="$scopedChannel->id"
        :preview-url="$previewUrl"
        :reorder-url="route('admin.appearance.sections.reorder')"
        :store-url="route('admin.appearance.sections.store', ['code' => $scopedTheme])"
        :urls="$urls"
    />

    {!! view_render_event('bagisto.admin.appearance.sections.index.after') !!}
</x-admin::layouts>
