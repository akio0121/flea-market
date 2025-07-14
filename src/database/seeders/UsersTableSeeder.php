<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'testA',
            'email' => 'aaa@bbb.com',
            'password' => Hash::make('aaaaaaaa'),
            'post' => '123-4567',
            'address' => '広島県広島市',
            'building' => 'ハイツ広島'

        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'testB',
            'email' => 'bbb@ccc.com',
            'password' => Hash::make('bbbbbbbb'),
            'post' => '234-5678',
            'address' => '山口県岩国市',
            'building' => 'ハイツ岩国'
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'testC',
            'email' => 'ccc@ddd.com',
            'password' => Hash::make('cccccccc'),
            'post' => '345-6789',
            'address' => '島根県松江市',
            'building' => 'ハイツ松江'
        ];
        DB::table('users')->insert($param);
    }
}
