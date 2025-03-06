<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'コンビニ支払い'
        ];
        DB::table('payments')->insert($param);

        $param = [
            'name' => 'カード支払い'
        ];
        DB::table('payments')->insert($param);
    }
}
