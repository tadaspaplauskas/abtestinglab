<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddForeignKeysCascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('websites', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
        
        Schema::table('tests', function (Blueprint $table) {
            $table->foreign('website_id')
                ->references('id')->on('websites')
                ->onDelete('cascade');
        });
        
        Schema::table('conversions', function (Blueprint $table) {
            $table->foreign('test_id')
                ->references('id')->on('tests')
                ->onDelete('cascade');
        });
        
        Schema::table('visitors', function (Blueprint $table) {
            $table->foreign('website_id')
                ->references('id')->on('websites')
                ->onDelete('cascade');
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
            $table->dropForeign('websites_user_id_foreign');
        });
        
        Schema::table('tests', function (Blueprint $table) {
            $table->dropForeign('tests_website_id_foreign');
        });
        
        Schema::table('conversions', function (Blueprint $table) {
            $table->dropForeign('conversions_test_id_foreign');
        });
        
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropForeign('visitors_website_id_foreign');
        });
    }
}
