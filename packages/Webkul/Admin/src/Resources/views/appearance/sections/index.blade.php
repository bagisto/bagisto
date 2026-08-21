<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.appearance.sections.index.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.appearance.sections.index.before') !!}

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
        :locales="$locales->sortBy('name')->map(fn ($locale) => [
            'code' => $locale->code,
            'name' => $locale->name,
            'url' => route('admin.appearance.sections.index', [
                'code' => $scopedTheme,
                'channel' => $scopedChannel->id,
                'locale' => $locale->code,
            ]),
        ])->values()"
        :locale-code="$scopedLocale->code"
        :preview-url="$previewUrl"
        :reorder-url="route('admin.appearance.sections.reorder')"
        :store-url="route('admin.appearance.sections.store', [
            'code' => $scopedTheme,
            'channel' => $scopedChannel->id,
        ])"
        :publish-url="$publishUrl"
        :discard-url="$discardUrl"
        :urls="$urls"
    />

    {!! view_render_event('bagisto.admin.appearance.sections.index.after') !!}
</x-admin::layouts>
