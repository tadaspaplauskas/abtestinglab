<?php

use Illuminate\Database\Seeder;

class WebsiteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('websites')->insert([
            'user_id' => 1,
            'url' => 'http://blog.paplauskas.lt',
            'title' => 'Tadas\' blog',
            
        ]);
    }
}
