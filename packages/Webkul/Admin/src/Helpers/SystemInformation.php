<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Webkul\Core\Mail\Transport\DynamicMailTransport;
use Webkul\Product\Enums\SearchEngineStatusEnum;
use Webkul\Product\Services\Search\SearchEngineAvailability;
use Webkul\Product\Services\Search\SearchEngineManager;

class SystemInformation
{
    /**
     * Bagisto's own mailer, which picks its transport when a message is sent.
     */
    const DYNAMIC_MAILER = 'bagisto-dynamic-smtp';

    /**
     * The column each section is read in, and its order within that column.
     */
    const COLUMNS = [
        ['bagisto', 'database', 'search', 'mail', 'storage', 'cache'],
        ['environment', 'drivers'],
    ];

    /**
     * Reported entries dropped because another section carries the same fact.
     */
    const SUPERSEDED = [
        'drivers' => ['database', 'mail'],
    ];

    /**
     * Where the wording for this page is kept.
     */
    const LANG = 'admin::app.configuration.index.about.general.';

    /**
     * Reported keys read under a clearer name.
     */
    const RENAMED = [
        'drivers' => [
            'logs' => 'log',
            'broadcasting' => 'broadcast',
        ],
    ];

    /**
     * The icon each section is headed by.
     */
    const ICONS = [
        'bagisto' => 'icon-store',
        'environment' => 'icon-settings',
        'database' => 'icon-list',
        'search' => 'icon-search',
        'drivers' => 'icon-configuration',
        'mail' => 'icon-mail',
        'storage' => 'icon-folder',
        'cache' => 'icon-repeat',
    ];

    /**
     * How a reported fact stands, keyed by `section.key`, for the few that describe a service
     * rather than a setting. Read from the state itself, never from the words it is shown in.
     *
     * @var array<string, string>
     */
    protected array $health = [];

    /**
     * Create a helper instance.
     */
    public function __construct(
        protected SearchEngineManager $searchEngines,
        protected SearchEngineAvailability $searchAvailability,
    ) {}

    /**
     * The sections dealt into the columns they are read in.
     *
     * @return array<int, array<int, array{icon: string, heading: string, entries: array<string, mixed>}>>
     */
    public function columns(): array
    {
        $sections = $this->sections();

        $columns = array_fill(0, count(self::COLUMNS), []);

        foreach (self::COLUMNS as $index => $keys) {
            foreach ($keys as $key) {
                if (isset($sections[$key])) {
                    $columns[$index][] = $sections[$key];

                    unset($sections[$key]);
                }
            }
        }

        foreach ($sections as $card) {
            $counts = array_map('count', $columns);

            $columns[array_search(min($counts), $counts, true)][] = $card;
        }

        return $columns;
    }

    /**
     * Every section, headed and ordered. A resolved section adds to the reported one of the same
     * name rather than replacing it.
     *
     * @return array<string, array{icon: string, heading: string, entries: array<string, mixed>}>
     */
    protected function sections(): array
    {
        $reported = $this->reported();

        foreach ($this->resolved() as $section => $entries) {
            $reported[$section] = array_merge($reported[$section] ?? [], $entries);
        }

        $ordered = array_filter(
            array_flip(array_merge(...self::COLUMNS)),
            fn ($key) => array_key_exists($key, $reported),
            ARRAY_FILTER_USE_KEY
        );

        $sections = [];

        foreach (array_replace($ordered, $reported) as $key => $entries) {
            if (! $entries = $this->entries($key, $entries)) {
                continue;
            }

            $sections[$key] = [
                'icon' => self::ICONS[$key] ?? 'icon-information',
                'heading' => $this->heading($key),
                'entries' => $entries,
                'health' => $this->health($key),
            ];
        }

        return $sections;
    }

    /**
     * What `php artisan about` says, so framework facts are not detected a second time here.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function reported(): array
    {
        Artisan::call('about', ['--json' => true]);

        return json_decode(Artisan::output(), true) ?: [];
    }

    /**
     * The facts the framework cannot report on Bagisto's behalf.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function resolved(): array
    {
        return [
            'bagisto' => [
                'version' => core()->version(),
            ],

            'database' => $this->database(),

            'search' => $this->search(),

            'mail' => [
                'transport' => $this->mailTransport(),
            ],

            'storage' => [
                'disk' => config('filesystems.default'),
            ],
        ];
    }

    /**
     * What the configured database reports about itself.
     *
     * @return array<string, string>
     */
    protected function database(): array
    {
        try {
            $connection = DB::connection();

            return [
                'engine' => $connection->getDriverTitle(),
                'version' => $connection->getServerVersion(),
            ];
        } catch (\Throwable) {
            return [
                'engine' => config('database.default'),
                'version' => $this->translate('values.not-available'),
            ];
        }
    }

    /**
     * The engine the catalog searches through, read from the last connection test. An engine with
     * no server behind it is reported by name alone.
     *
     * @return array<string, string>
     */
    protected function search(): array
    {
        $engine = $this->searchEngines->getMasterEngine();

        $name = trans("admin::app.configuration.index.search-engines.engines.{$engine->value}");

        if (! $this->searchAvailability->isConnectable($engine)) {
            return ['engine' => $name];
        }

        $probe = $this->searchAvailability->cached($engine) ?: [];

        return [
            'engine' => $name,
            'version' => $probe['version'] ?? $this->translate('values.not-available'),
            'status' => $this->searchStatus($probe),
        ];
    }

    /**
     * How a recorded search verdict reads. A cluster that has never been asked reads as unknown 
     * rather than as failing.
     */
    protected function searchStatus(array $probe): string
    {
        $status = SearchEngineStatusEnum::tryFrom((string) ($probe['status'] ?? ''));

        if (! $status) {
            return $this->translate('values.not-checked');
        }

        $this->health['search.status'] = $status->isUsable() ? 'good' : 'bad';

        return $this->translate('statuses.'.$status->value);
    }

    /**
     * What mail leaves by. Bagisto's own mailer names no transport, so the choice behind it is
     * read instead.
     */
    protected function mailTransport(): string
    {
        $mailer = config('mail.default');

        if ($mailer !== self::DYNAMIC_MAILER) {
            return $mailer;
        }

        try {
            return core()->getConfigData('emails.configure.smtp.driver') === DynamicMailTransport::DRIVER_BREVO_API
                ? 'Brevo API'
                : 'SMTP';
        } catch (\Throwable) {
            return 'SMTP';
        }
    }

    /**
     * A section's rows, less those another section carries or that report an unused feature.
     *
     * @return array<string, mixed>
     */
    protected function entries(string $section, array $entries): array
    {
        $rows = [];

        foreach ($entries as $key => $value) {
            if (
                in_array($key, self::SUPERSEDED[$section] ?? [])
                || $this->idle($section, $key, $value)
            ) {
                continue;
            }

            $rows[$this->label($section, $key)] = $value;
        }

        return $rows;
    }

    /**
     * Whether a row reports a default for something that is not running. Octane names a server
     * whether or not anything is served through it, so only a request it handles counts.
     */
    protected function idle(string $section, string $key, mixed $value): bool
    {
        if ($section !== 'drivers') {
            return false;
        }

        if ($key === 'octane') {
            return ! isset($_SERVER['LARAVEL_OCTANE']);
        }

        return $value === 'null';
    }

    /**
     * What a row is read by. A symbolic link is named after the directory it publishes, not the
     * absolute path Laravel reports it under.
     */
    protected function label(string $section, string $key): string
    {
        if (str_contains($key, DIRECTORY_SEPARATOR)) {
            return $this->translate('labels.link');
        }

        return $this->translate('labels.'.str_replace('_', '-', self::RENAMED[$section][$key] ?? $key), $key);
    }

    /**
     * A section's health, keyed by the label each fact is read under.
     *
     * @return array<string, string>
     */
    protected function health(string $section): array
    {
        $health = [];

        foreach ($this->health as $path => $state) {
            [$owner, $key] = array_pad(explode('.', $path, 2), 2, '');

            if ($owner === $section) {
                $health[$this->label($section, $key)] = $state;
            }
        }

        return $health;
    }

    /**
     * A section's own heading.
     */
    protected function heading(string $section): string
    {
        return $this->translate('sections.'.str_replace('_', '-', $section), $section);
    }

    /**
     * This page's wording, or the key read as words where it has none.
     */
    protected function translate(string $key, ?string $fallback = null): string
    {
        return Lang::has(self::LANG.$key)
            ? trans(self::LANG.$key)
            : str($fallback ?? $key)->headline()->toString();
    }
}
