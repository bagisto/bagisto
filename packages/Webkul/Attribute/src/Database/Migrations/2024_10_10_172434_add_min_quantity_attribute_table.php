<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('attributes')
            ->insert([
                'code'                => 'min_sellable_qty',
                'admin_name'          => trans('installer::app.seeders.attribute.attributes.minimum-sellable-qty', [], config('app.locale')),
                'type'                => 'text',
                'validation'          => 'numeric',
                'position'            => 1,
                'is_required'         => 0,
                'is_unique'           => 0,
                'value_per_locale'    => 0,
                'value_per_channel'   => 0,
                'default_value'       => '1',
                'is_filterable'       => 0,
                'is_configurable'     => 0,
                'is_user_defined'     => 0,
                'is_visible_on_front' => 0,
                'is_comparable'       => 0,
                'enable_wysiwyg'      => 0,
                'created_at'          => Carbon::now(),
                'updated_at'          => Carbon::now(),
            ]);

        Schema::table('attribute_groups', function (Blueprint $table) {
            $families = DB::table('attribute_families')->get();

            foreach ($families as $family) {
                $minSellableQtyAttribute = DB::table('attributes')
                    ->where('code', 'min_sellable_qty')
                    ->first();

                $inventoryGroup = DB::table('attribute_groups')
                    ->where('name', 'Inventories')
                    ->where('attribute_family_id', $family->id)
                    ->first();

                DB::table('attribute_group_mappings')
                    ->insert([
                        'attribute_group_id' => $inventoryGroup->id,
                        'attribute_id'       => $minSellableQtyAttribute->id,
                        'position'           => 1,
                    ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
