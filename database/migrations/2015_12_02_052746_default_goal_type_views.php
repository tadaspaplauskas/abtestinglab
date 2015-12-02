<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultGoalTypeViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('goal_type');
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->enum('goal_type', ['views', 'conversions'])->default('views');
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
            $table->dropColumn('goal_type');
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->enum('goal_type', ['views', 'conversions'])->default('views');
        });
    }
}
