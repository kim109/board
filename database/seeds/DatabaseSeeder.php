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
        DB::table('categories')->insert([
            ['table' => 'questions', 'name' => '보험청구'],
            ['table' => 'questions', 'name' => '상담&진료관련'],
            ['table' => 'questions', 'name' => '재료관련'],
            ['table' => 'questions', 'name' => '임플란트 판별'],
            ['table' => 'questions', 'name' => '이모저모']
        ]);

        DB::table('categories')->insert([
            ['table' => 'columns', 'name' => '보험청구'],
            ['table' => 'columns', 'name' => '재료관련'],
            ['table' => 'columns', 'name' => '장비컬럼'],
            ['table' => 'columns', 'name' => '업체현황'],
            ['table' => 'columns', 'name' => '이달의 인물']
        ]);

        DB::table('categories')->insert([
            ['table' => 'seminars', 'name' => '치위생사'],
            ['table' => 'seminars', 'name' => '치과의사']
        ]);

        DB::table('categories')->insert([
            ['table' => 'notices', 'name' => '공지'],
            ['table' => 'notices', 'name' => '이벤트']
        ]);
    }
}
