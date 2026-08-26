<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Search settings moved out of the catalog into a section of their own.
     */
    const RELOCATED = [
        'catalog.products.search.engine' => 'search_engines.general.settings.engine',
        'catalog.products.search.admin_mode' => 'search_engines.general.products.admin_mode',
        'catalog.products.search.storefront_mode' => 'search_engines.general.products.storefront_mode',
        'catalog.products.search.min_query_length' => 'search_engines.elastic.settings.min_query_length',
        'catalog.products.search.max_query_length' => 'search_engines.elastic.settings.max_query_length',
    ];

    /**
     * The switch that now gates every external engine.
     */
    const ENABLED = 'search_engines.general.settings.enabled';

    /**
     * The engine whose presence means the switch was already on.
     */
    const ENGINE = 'search_engines.general.settings.engine';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::RELOCATED as $from => $to) {
            $this->relocate($from, $to);
        }

        $this->enableWhereAnExternalEngineWasChosen();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('core_config')->where('code', self::ENABLED)->delete();

        foreach (self::RELOCATED as $from => $to) {
            $this->relocate($to, $from);
        }
    }

    /**
     * Turn the switch on wherever an external engine was already selected.
     */
    protected function enableWhereAnExternalEngineWasChosen(): void
    {
        $rows = DB::table('core_config')
            ->where('code', self::ENGINE)
            ->where('value', '!=', 'database')
            ->get(['channel_code', 'locale_code']);

        foreach ($rows as $row) {
            $exists = DB::table('core_config')
                ->where('code', self::ENABLED)
                ->where(fn ($query) => $this->scope($query, $row->channel_code, $row->locale_code))
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('core_config')->insert([
                'code' => self::ENABLED,
                'value' => 1,
                'channel_code' => $row->channel_code,
                'locale_code' => $row->locale_code,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Rename a stored configuration code, dropping rows a destination already holds.
     */
    protected function relocate(string $from, string $to): void
    {
        $existing = DB::table('core_config')
            ->where('code', $to)
            ->get(['channel_code', 'locale_code']);

        foreach ($existing as $row) {
            DB::table('core_config')
                ->where('code', $from)
                ->where(fn ($query) => $this->scope($query, $row->channel_code, $row->locale_code))
                ->delete();
        }

        DB::table('core_config')->where('code', $from)->update(['code' => $to]);
    }

    /**
     * Scope a query to one channel and locale, treating null as null rather than as a value.
     */
    protected function scope(Builder $query, ?string $channel, ?string $locale): Builder
    {
        $channel === null
            ? $query->whereNull('channel_code')
            : $query->where('channel_code', $channel);

        return $locale === null
            ? $query->whereNull('locale_code')
            : $query->where('locale_code', $locale);
    }
};
