<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('weekly_reports')->default(true);
            $table->boolean('test_notifications')->default(true);
            $table->boolean('newsletter')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('weekly_reports');
            $table->dropColumn('test_notifications');
            $table->dropColumn('newsletter');
        });
    }
}
