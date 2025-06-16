<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    //商品一覧画面を表示する
    public function index(Request $request)
    {
        // ログイン状態に応じて適切なヘッダーを指定
        $header = Auth::check() ? 'layouts.login_app' : 'layouts.logout_app';

        $keyword = $request->input('keyword');

        // 検索キーワードがある場合は絞り込み
        if ($keyword) {
            $products = Product::where('name', 'like', "%{$keyword}%")->get();
        } else {
            $products = Product::all();
        }

        return view('index', compact('products', 'header', 'keyword'));
    }
}
