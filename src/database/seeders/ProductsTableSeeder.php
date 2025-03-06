<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '腕時計',
            'price' => 15000,
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'condition_id' => 1,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'purchase_flg' => false,
            'user_id' => 1
        ];
        DB::table('products')->insert($param);

        $param = [
            'name' => 'HDD',
            'price' => 5000,
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'condition_id' => 2,
            'description' => '高速で信頼性の高いハードディスク',
            'purchase_flg' => false,
            'user_id' => 1
        ];
        DB::table('products')->insert($param);

        $param = [
            'name' => '玉ねぎ3束',
            'price' => 300,
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'condition_id' => 3,
            'description' => '新鮮な玉ねぎ3束のセット',
            'purchase_flg' => false,
            'user_id' => 1
        ];
        DB::table('products')->insert($param);

        $param = [
            'name' => '革靴',
            'price' => 4000,
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'condition_id' => 4,
            'description' => 'クラシックなデザインの革靴',
            'purchase_flg' => false,
            'user_id' => 1
        ];
        DB::table('products')->insert($param);

        $param = [
            'name' => 'ノートPC',
            'price' => 45000,
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'condition_id' => 1,
            'description' => '高性能なノートパソコン',
            'purchase_flg' => false,
            'user_id' => 1
        ];
        DB::table('products')->insert($param);

        $param = [
            'name' => 'マイク',
            'price' => 8000,
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            'condition_id' => 2,
            'description' => '高音質のレコーディング用マイク',
            'purchase_flg' => false,
            'user_id' => 2
        ];
        DB::table('products')->insert($param);

        $param = [
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            'condition_id' => 3,
            'description' => 'おしゃれなショルダーバッグ',
            'purchase_flg' => false,
            'user_id' => 2
        ];
        DB::table('products')->insert($param);

        $param = [
            'name' => 'タンブラー',
            'price' => 500,
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            'condition_id' => 4,
            'description' => '使いやすいタンブラー',
            'purchase_flg' => false,
            'user_id' => 2
        ];
        DB::table('products')->insert($param);

        $param = [
            'name' => 'コーヒーミル',
            'price' => 4000,
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            'condition_id' => 1,
            'description' => '手動のコーヒーミル',
            'purchase_flg' => false,
            'user_id' => 2
        ];
        DB::table('products')->insert($param);

        $param = [
            'name' => 'メイクセット',
            'price' => 2500,
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            'condition_id' => 2,
            'description' => '便利なメイクアップセット',
            'purchase_flg' => false,
            'user_id' => 2
        ];
        DB::table('products')->insert($param);

    }
}
