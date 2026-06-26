<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The default neutral statuses whose titles are clarified.
     * Keyed by id => [old default title, new title]. Only rows that still
     * hold the old default title are updated, so custom titles are preserved.
     */
    protected array $renames = [
        1 => ['Pending', 'Pending Review'],
        2 => ['Accept', 'Approved'],
        3 => ['Awaiting', 'Awaiting Return'],
        4 => ['Dispatched Package', 'Return In Transit'],
        7 => ['Declined', 'Request Declined'],
        9 => ['Canceled', 'Request Canceled'],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->renames as $id => [$old, $new]) {
            DB::table('rma_statuses')
                ->where('id', $id)
                ->where('title', $old)
                ->update(['title' => $new]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->renames as $id => [$old, $new]) {
            DB::table('rma_statuses')
                ->where('id', $id)
                ->where('title', $new)
                ->update(['title' => $old]);
        }
    }
};
