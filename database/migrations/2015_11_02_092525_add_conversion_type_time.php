<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConversionTypeTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('conversion_type');
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->enum('conversion_type', ['click', 'time'])->default('click');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('conversion_type');
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->enum('conversion_type', ['click'])->default('click');
        });
    }
}
