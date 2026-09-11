<?php

namespace Webkul\Admin\Http\Controllers\Appearance;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Contracts\Channel;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Theme\ThemeCatalog;

class ThemeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ThemeCatalog $themeCatalog,
        protected ChannelRepository $channelRepository
    ) {}

    /**
     * Display the theme gallery.
     *
     * @return View
     */
    public function index()
    {
        $themes = $this->themeCatalog->all();

        $channels = $this->channelRepository->all();

        return view('admin::appearance.themes.index', compact('themes', 'channels'));
    }

    /**
     * Report what switching the given channels over to this theme would leave behind.
     *
     * @return JsonResponse
     */
    public function impact(string $code)
    {
        $this->validate(request(), $this->channelRules());

        $themes = $this->themeCatalog->all()->keyBy('code');

        $impact = $this->channelRepository
            ->findWhereIn('id', request()->input('channel_ids'))
            ->map(fn ($channel) => [
                'channel_id' => $channel->id,
                'channel' => $channel->name,
                'current_theme' => $channel->theme ? ($themes->get($channel->theme)['name'] ?? $channel->theme) : null,
                'customizations' => $channel->theme && $channel->theme !== $code
                    ? $this->themeCatalog->sectionCount($channel->id, $channel->theme)
                    : 0,
            ])
            ->filter(fn ($row) => $row['customizations'] > 0)
            ->values();

        return new JsonResponse(['impact' => $impact]);
    }

    /**
     * Activate a theme on one or more channels.
     *
     * @return JsonResponse
     */
    public function activate(string $code)
    {
        $this->validate(request(), $this->channelRules());

        if (! $this->themeCatalog->isInstalled($code)) {
            return new JsonResponse([
                'message' => trans('admin::app.appearance.themes.index.not-installed'),
            ], 404);
        }

        $theme = $this->themeCatalog->find($code);

        $channels = $this->channelRepository->findWhereIn('id', request()->input('channel_ids'));

        foreach ($channels as $channel) {
            $this->activateOn($channel, $code);
        }

        session()->flash('success', trans('admin::app.appearance.themes.index.activate-success', [
            'theme' => $theme['name'],
            'channel' => $channels->pluck('name')->implode(', '),
        ]));

        return new JsonResponse([
            'redirect_url' => route('admin.appearance.themes.index'),
        ]);
    }

    /**
     * Point a single channel at the theme.
     *
     * @param  Channel  $channel
     */
    protected function activateOn($channel, string $code): void
    {
        Event::dispatch('appearance.theme.activate.before', $channel->id);

        Event::dispatch('core.channel.update.before', $channel->id);

        $channel->theme = $code;

        $channel->save();

        Event::dispatch(new RepositoryEntityUpdated($this->channelRepository, $channel));

        Event::dispatch('core.channel.update.after', $channel);

        Event::dispatch('appearance.theme.activate.after', $channel);
    }

    /**
     * Validation rules for the channels a theme is being applied to.
     */
    protected function channelRules(): array
    {
        return [
            'channel_ids' => 'required|array|min:1',
            'channel_ids.*' => 'required|in:'.implode(',', $this->channelRepository->pluck('id')->toArray()),
        ];
    }
}
