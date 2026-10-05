<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Repositories\CoreConfigRepository;

return new class extends Migration
{
    /**
     * The copyright notice, which is now resolved per channel as well as per locale.
     */
    const CODE = 'general.content.footer.copyright_content';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $channels = DB::table('channels')->pluck('code');

        $rows = DB::table('core_config')
            ->where('code', self::CODE)
            ->whereNull('channel_code')
            ->get();

        foreach ($rows as $row) {
            foreach ($channels as $channel) {
                if ($this->has($channel, $row->locale_code)) {
                    continue;
                }

                DB::table('core_config')->insert([
                    'code' => self::CODE,
                    'value' => $row->value,
                    'channel_code' => $channel,
                    'locale_code' => $row->locale_code,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]);
            }

            DB::table('core_config')->where('id', $row->id)->delete();
        }

        CacheGeneration::bump(CoreConfigRepository::class);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $rows = DB::table('core_config')
            ->where('code', self::CODE)
            ->whereNotNull('channel_code')
            ->orderBy('id')
            ->get();

        $kept = [];

        foreach ($rows as $row) {
            $locale = (string) $row->locale_code;

            if (in_array($locale, $kept)) {
                DB::table('core_config')->where('id', $row->id)->delete();

                continue;
            }

            $kept[] = $locale;

            DB::table('core_config')->where('id', $row->id)->update(['channel_code' => null]);
        }

        CacheGeneration::bump(CoreConfigRepository::class);
    }

    /**
     * Whether the copyright notice is already stored for one channel and locale.
     */
    protected function has(string $channel, ?string $locale): bool
    {
        return DB::table('core_config')
            ->where('code', self::CODE)
            ->where('channel_code', $channel)
            ->where(fn (Builder $query) => $this->scopeLocale($query, $locale))
            ->exists();
    }

    /**
     * Scope a query to one locale, treating null as null rather than as a value.
     */
    protected function scopeLocale(Builder $query, ?string $locale): Builder
    {
        return $locale === null
            ? $query->whereNull('locale_code')
            : $query->where('locale_code', $locale);
    }
};
