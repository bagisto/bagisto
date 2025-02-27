<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGiftcardInfoToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('giftcard_number', 191)->nullable()->after('grand_total'); // Choose the appropriate position for the column
            $table->decimal('giftcard_amount', 12, 4)->nullable()->after('giftcard_number'); // Using 'nullable' because not all invoices may use a giftcard
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['giftcard_number', 'giftcard_amount']);
        });
    }
}
