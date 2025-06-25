<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'payment_id' => 1,
            'product_id' => 10
        ];
        DB::table('orders')->insert($param);

        $param = [
            'user_id' => 2,
            'payment_id' => 2,
            'product_id' => 5
        ];
        DB::table('orders')->insert($param);
    }
}
