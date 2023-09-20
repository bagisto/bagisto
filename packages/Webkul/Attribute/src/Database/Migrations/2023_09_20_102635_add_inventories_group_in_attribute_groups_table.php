<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('attributes')
            ->insert([
                'code'              => 'manage_stock',
                'admin_name'        => 'Manage Stock',
                'type'              => 'boolean',
                'position'          => 1,
                'value_per_channel' => 1,
                'default_value'     => 1,
                'is_user_defined'   => 0,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);

        Schema::table('attribute_groups', function (Blueprint $table) {
            $families = DB::table('attribute_families')->get();

            foreach ($families as $family) {
                DB::table('attribute_groups')->insert([
                    'name'                => 'Inventories',
                    'column'              => 2,
                    'is_user_defined'     => 0,
                    'position'            => 4,
                    'attribute_family_id' => $family->id,
                ]);

                $manageStockAttribute = DB::table('attributes')
                    ->where('code', 'manage_stock')
                    ->first();

                $inventoryGroup = DB::table('attribute_groups')
                    ->where('name', 'Inventories')
                    ->where('attribute_family_id', $family->id)
                    ->first();

                DB::table('attribute_group_mappings')
                    ->insert([
                        'attribute_group_id' => $inventoryGroup->id,
                        'attribute_id'       => $manageStockAttribute->id,
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
        Schema::table('attribute_groups', function (Blueprint $table) {
            //
        });
    }
};
