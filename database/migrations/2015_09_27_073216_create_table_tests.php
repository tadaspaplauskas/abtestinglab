<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('website_id')->unsigned();
            $table->boolean('enabled')->default(true);
            $table->string('title');
            $table->binary('test_element');
            $table->enum('element_type', ['image', 'text']);
            $table->binary('test_variation');
            $table->binary('conversion_element');
            $table->enum('conversion_type', ['click'])->default('click');
            $table->integer('original_conversion_count')->unsigned();
            $table->integer('variation_conversion_count')->unsigned();
            $table->integer('original_pageviews')->unsigned();
            $table->integer('variation_pageviews')->unsigned();
            $table->boolean('adaptive')->default(false);
            $table->enum('goal_type', ['conversions', 'views']);
            $table->integer('goal')->unsigned();
            $table->timestamp('start');
            $table->timestamp('end');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tests');
    }
}
