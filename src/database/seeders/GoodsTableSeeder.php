<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'product_id' => 6,
            'user_id' => 1
        ];
        DB::table('goods')->insert($param);

        $param = [
            'product_id' => 1,
            'user_id' => 2
        ];
        DB::table('goods')->insert($param);
    }
}
