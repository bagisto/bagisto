<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
        });

        $attributeGroups = DB::table('attribute_groups')->get();

        foreach ($attributeGroups as $attributeGroup) {
            DB::table('attribute_groups')
                ->where('id', $attributeGroup->id)
                ->update([
                    'code' => Str::of($attributeGroup->name)->snake(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
