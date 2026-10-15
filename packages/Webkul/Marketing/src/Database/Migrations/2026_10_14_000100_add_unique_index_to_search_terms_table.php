<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The index that makes a term unique within a channel and locale.
     */
    protected const INDEX = 'search_terms_term_channel_locale_unique';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->mergeDuplicates();

        Schema::table('search_terms', function (Blueprint $table) {
            if (! Schema::hasIndex('search_terms', self::INDEX)) {
                $table->unique(['term', 'channel_id', 'locale'], self::INDEX);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_terms', function (Blueprint $table) {
            if (Schema::hasIndex('search_terms', self::INDEX)) {
                $table->dropUnique(self::INDEX);
            }
        });
    }

    /**
     * Fold the rows a term accumulated before it was unique into one, so the index can be added.
     * The counts are summed rather than discarded, and the surviving row keeps the earliest id.
     */
    protected function mergeDuplicates(): void
    {
        $groups = DB::table('search_terms')
            ->select('term', 'channel_id', 'locale')
            ->groupBy('term', 'channel_id', 'locale')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($groups as $group) {
            $rows = DB::table('search_terms')
                ->where('term', $group->term)
                ->where('channel_id', $group->channel_id)
                ->where('locale', $group->locale)
                ->orderBy('id')
                ->get();

            $survivor = $rows->first();

            $latest = $rows->sortByDesc(fn ($row) => [$row->updated_at, $row->id])->first();

            DB::table('search_terms')
                ->where('id', $survivor->id)
                ->update([
                    'uses' => $rows->sum('uses'),
                    'results' => $latest->results,
                    'redirect_url' => $rows->firstWhere(fn ($row) => ! empty($row->redirect_url))?->redirect_url,
                    'display_in_suggested_terms' => $rows->max('display_in_suggested_terms'),
                ]);

            DB::table('search_terms')
                ->whereIn('id', $rows->skip(1)->pluck('id'))
                ->delete();
        }
    }
};
