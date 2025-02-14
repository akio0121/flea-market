<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ItemController extends Controller
{
    //商品一覧画面を表示する
    public function index()
    {
        return view('top');
    }
}
