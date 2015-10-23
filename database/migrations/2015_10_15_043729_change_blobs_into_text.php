<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class ChangeBlobsIntoText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tests', function (Blueprint $table) {
            DB::statement('ALTER TABLE tests MODIFY COLUMN test_element TEXT');
            DB::statement('ALTER TABLE tests MODIFY COLUMN test_variation TEXT');
            DB::statement('ALTER TABLE tests MODIFY COLUMN conversion_element TEXT');
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
            DB::statement('ALTER TABLE tests MODIFY COLUMN test_element BLOB');
            DB::statement('ALTER TABLE tests MODIFY COLUMN test_variation BLOB');
            DB::statement('ALTER TABLE tests MODIFY COLUMN conversion_element BLOB');
        });
    }
}
