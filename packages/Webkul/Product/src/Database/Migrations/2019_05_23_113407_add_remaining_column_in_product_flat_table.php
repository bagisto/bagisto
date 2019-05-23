<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemainingColumnInProductFlatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_flat', function (Blueprint $table) {
            if (! Schema::hasColumn('short_description'))
                $table->text('short_description')->nullable();
            }
            if (! Schema::hasColumn('meta_title'))
                $table->text('meta_title')->nullable();
            }
            if (! Schema::hasColumn('meta_keywords'))
                $table->text('meta_keywords')->nullable();
            }
            if (! Schema::hasColumn('meta_description'))
                $table->text('meta_description')->nullable();
            }
            if (! Schema::hasColumn('width'))
                $table->decimal('width', 12, 4)->nullable();
            }
            if (! Schema::hasColumn('height'))
                $table->decimal('height', 12, 4)->nullable();
            }
            if (! Schema::hasColumn('depth'))
                $table->decimal('depth', 12, 4)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_flat', function (Blueprint $table) {

        });
    }
}