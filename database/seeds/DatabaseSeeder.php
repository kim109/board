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
            'table' => 'notices',
            'name' => '공지'
        ], [
            'table' => 'notices',
            'name' => '이벤트'
        ], [
            'table' => 'supports',
            'name' => '버그리포트'
        ], [
            'table' => 'supports',
            'name' => '원격 요청'
        ], [
            'table' => 'supports',
            'name' => '바란다'
        ]]);
    }
}
