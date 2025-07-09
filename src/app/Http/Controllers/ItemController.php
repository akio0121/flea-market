<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Good;
use App\Models\Order;
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

        if ($tab === 'mylist') {
            if ($currentUserId) {
                // ログイン済み → いいねした商品を取得
                $productIds = Good::where('user_id', $currentUserId)->pluck('product_id');
                $query->whereIn('id', $productIds);
            } else {
                // 未ログイン → 空の商品リストを渡す
                $products = collect(); // 空のコレクション
                $soldProductIds = Order::pluck('product_id')->unique()->toArray();

                return view('index', compact('products', 'header', 'keyword', 'tab', 'soldProductIds'));
            }
        } elseif ($currentUserId) {
            // ログイン中 → 自分以外の商品を表示
            $query->where('user_id', '!=', $currentUserId);
        }

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $products = $query->get();

        // 売れた商品ID一覧を取得（ordersテーブルにある product_id）
        $soldProductIds = Order::pluck('product_id')->unique()->toArray();

        return view('index', compact('products', 'header', 'keyword', 'tab', 'soldProductIds'));
    }
}