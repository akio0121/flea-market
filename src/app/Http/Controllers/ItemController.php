<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class ItemController extends Controller
{
    //商品一覧画面を表示する
    public function index()
    {
        $products = Product::all();
        return view('index', ["products" => $products]);
    }
}
