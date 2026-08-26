<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Configuration fields that moved to a different group, old code => new code.
     */
    const RELOCATED = [
        'catalog.products.storefront.buy_now_button_display' => 'catalog.products.product_view_page.buy_now_button_display',
        'sales.checkout.my_cart.summary' => 'sales.checkout.mini_cart.summary',
        'customer.settings.social_login.enable_linkedin-openid' => 'customer.settings.social_login.enable_linkedin',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::RELOCATED as $from => $to) {
            $this->relocate($from, $to);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::RELOCATED as $from => $to) {
            $this->relocate($to, $from);
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
