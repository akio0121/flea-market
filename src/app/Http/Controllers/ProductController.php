<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    //商品詳細画面を表示する
    public function detail($id)
    {
        $product = Product::with([
            'productCategories.category',
            'comments.user'
        ])
            ->withCount(['goods', 'comments'])
            ->findOrFail($id);

        $header = Auth::check() ? 'layouts.login_app' : 'layouts.logout_app';
        return view('product.detail', compact('product', 'header'));
    }

    //商品詳細画面でコメントを入力する
    public function store(Request $request, $product_id)
    {
        //commentsテーブルにコメント内容を保存する
        Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $product_id,
            'name' => $request->input('name'),
        ]);

        return back()->with('success', 'コメントを投稿しました。');
    }

    //商品詳細画面で「いいね」する
    public function toggleLike(Product $product)
    {
        $user = Auth::user();

        if ($product->likedUsers()->where('user_id', $user->id)->exists()) {
            // 既にいいねしている → 削除
            $product->likedUsers()->detach($user->id);
        } else {
            // いいねしていない → 追加
            $product->likedUsers()->attach($user->id);
        }

        return back();
    }


    //商品出品画面を表示する
    public function exhibit(Product $product)
    {
        return view('product.exhibit');
    }
}
