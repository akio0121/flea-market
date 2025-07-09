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
            'product_id' => 10,
            'recipient_post' => '123-4567',
            'recipient_address' => '広島県広島市',
            'recipient_building' => 'ハイツ広島',
        ];
        DB::table('orders')->insert($param);

        $param = [
            'user_id' => 1,
            'payment_id' => 2,
            'product_id' => 6,
            'recipient_post' => '123-4567',
            'recipient_address' => '広島県広島市',
            'recipient_building' => 'ハイツ広島',
        ];
        DB::table('orders')->insert($param);

        $param = [
            'user_id' => 2,
            'payment_id' => 2,
            'product_id' => 5,
            'recipient_post' => '234-5678',
            'recipient_address' => '山口県岩国市',
            'recipient_building' => 'ハイツ岩国',
        ];
        DB::table('orders')->insert($param);

        $param = [
            'user_id' => 2,
            'payment_id' => 1,
            'product_id' => 1,
            'recipient_post' => '234-5678',
            'recipient_address' => '山口県岩国市',
            'recipient_building' => 'ハイツ岩国',
        ];
        DB::table('orders')->insert($param);
    }
}
