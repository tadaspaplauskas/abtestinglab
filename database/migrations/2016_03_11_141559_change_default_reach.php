<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeDefaultReach extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `users` CHANGE `total_available_reach` `total_available_reach` INT(10) UNSIGNED NOT NULL DEFAULT 3000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `users` CHANGE `total_available_reach` `total_available_reach` INT(10) UNSIGNED NOT NULL DEFAULT 5000;');
    }
}
