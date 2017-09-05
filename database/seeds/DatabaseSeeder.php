<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('categories')->insert([[
            'title' => '자유게시판',
            'prefix' => '/freeboard',
            'open' => true
        ], [
            'title' => '중고장터',
            'prefix' => '/market',
            'open' => true
        ]]);
    }
}
