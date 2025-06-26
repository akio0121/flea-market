<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Category;
use App\Models\Condition;
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
        // 「購入済み」かどうかを判定
        $isSold = Order::where('product_id', $product->id)->exists();

        return view('product.detail', compact('product', 'header', 'isSold'));
    }

    //商品詳細画面でコメントを入力する
    public function store(CommentRequest $request, $product_id)
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

    //商品出品画面を表示
    public function exhibit()
    {
        $categories = Category::all(); // 全カテゴリを取得
        $conditions = Condition::all();  // 商品状態リスト

        return view('product.exhibit', compact('categories', 'conditions'));
    }

    // 出品処理（カテゴリ・商品情報まとめて送信）
    public function exhibitStore(ExhibitionRequest $request)
    {
        // 商品登録
        $product = new Product();
        $product->user_id = Auth::id();
        $product->image = $request->input('image'); // hidden から取得
        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->condition_id = $request->condition_id;
        $product->save();

        // カテゴリを紐づけ（多対多）
        $product->categories()->attach($request->category_ids);

        return redirect()->route('sell')->with('success', '商品を出品しました。');
    }

    // 画像アップロード処理
    public function uploadImage(Request $request)
    {

        $path = $request->file('image')->store('images', 'public');

        // セッションに画像パスを保存してリダイレクト
        return back()->with('imagePath', $path);
    }
}
