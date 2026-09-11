<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The configuration field was previously named after the OpenID protocol.
     */
    const FROM = 'customer.settings.social_login.enable_linkedin-openid';

    /**
     * The configuration field is now named after the provider.
     */
    const TO = 'customer.settings.social_login.enable_linkedin';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->relocate(self::FROM, self::TO);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->relocate(self::TO, self::FROM);
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
