<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLeaveBestVariationToWebsites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->boolean('keep_best_variation')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('keep_best_variation');
        });
    }
}
