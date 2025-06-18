<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    //商品一覧画面を表示する
    public function index(Request $request)
    {
        $header = Auth::check() ? 'layouts.login_app' : 'layouts.logout_app';
        $keyword = $request->input('keyword');
        $currentUserId = Auth::id();
        $tab = $request->input('tab');

        $query = Product::query();

        if ($tab === 'mylist' && $currentUserId) {
            // Goodモデルからuser_idがログインユーザーのものだけ取得し、product_idだけ抜き出す
            $productIds = Good::where('user_id', $currentUserId)->pluck('product_id');

            // そのproduct_idのものだけ絞り込む
            $query->whereIn('id', $productIds);
        } elseif ($currentUserId) {
            $query->where('user_id', '!=', $currentUserId);
        }

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $products = $query->get();

        return view('index', compact('products', 'header', 'keyword', 'tab'));
    }
}
