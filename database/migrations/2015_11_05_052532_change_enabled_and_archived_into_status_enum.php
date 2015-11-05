<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEnabledAndArchivedIntoStatusEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('enabled');
            $table->dropColumn('archived');
            
            $table->enum('status', ['enabled', 'disabled', 'archived'])->default('enabled');
        });
        
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('enabled');
            
            $table->enum('status', ['enabled', 'disabled', 'archived'])->default('enabled');
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
            $table->dropColumn('status');
            $table->tinyinteger('archived')->default(0);
            $table->tinyinteger('enabled')->default(0);
        });
        
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->tinyinteger('enabled')->default(0);
        });
    }
}
