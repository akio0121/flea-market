<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'product_id' => 1,
            'user_id' => 1,
            'name' => '高性能の時計です。'
        ];
        DB::table('comments')->insert($param);

        $param = [
            'product_id' => 6,
            'user_id' => 2,
            'name' => '高音質のマイクです。'
        ];
        DB::table('comments')->insert($param);
    }
}
