<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Deal;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DealRequest;

class DealController extends Controller
{

    //取引用ページを表示する
    public function showDeal(Request $request, Product $product)
    {
        $user = Auth::user();

        // 取引中の商品一覧（マイページの deal タブと同じロジック）
        $boughtOrders = $user->orders()->with('product')->get()->pluck('product');
        $soldOrders = \App\Models\Order::whereIn(
            'product_id',
            \App\Models\Product::where('user_id', $user->id)->pluck('id')
        )->with('product')->get()->pluck('product');

        // 取引中の商品をマージ
        $dealProducts = $boughtOrders->merge($soldOrders);

        //各商品ごとの最新メッセージ日時を取得
        $latestMessageTimes = \App\Models\Deal::whereIn('product_id', $dealProducts->pluck('id'))
            ->selectRaw('product_id, MAX(created_at) as latest_message_time')
            ->groupBy('product_id')
            ->pluck('latest_message_time', 'product_id');

        //最新メッセージが新しい順にソート（メッセージなしは最下位）
        $dealProducts = $dealProducts->sortByDesc(function ($p) use ($latestMessageTimes) {
            return $latestMessageTimes[$p->id] ?? '1970-01-01 00:00:00';
        });

        // チャット履歴を取得（古い順）
        $messages = $product->deals()->with('user')->orderBy('created_at')->get();

        // レイアウト判定
        $header = Auth::check() ? 'layouts.login_app' : 'layouts.app';


        //商品ごとの未読件数をまとめる
        $unreadCounts = Deal::select('product_id', \DB::raw('COUNT(*) as unread_count'))
            ->where('user_id', '!=', $user->id)   // 自分以外が送ったメッセージ
            ->where('read_flg', 0)               // 未読のみ
            ->whereIn('product_id', $dealProducts->pluck('id')) // 取引中の商品だけ
            ->groupBy('product_id')
            ->pluck('unread_count', 'product_id'); // [product_id => 件数]

        // 自分以外の未読メッセージを既読に更新
        Deal::where('product_id', $product->id)
            ->where('user_id', '!=', $user->id)
            ->where('read_flg', 0)
            ->update(['read_flg' => 1]);

        return view('product.deal', [
            'product'       => $product,        // 今表示中の商品
            'dealProducts'  => $dealProducts,   // サイドバー用の取引中商品一覧
            'messages'      => $messages,       // チャット履歴
            'header'        => $header,
            'unreadCounts'  => $unreadCounts, // ← 商品ごとの未読件数
        ]);
    }

    //取引用ページで、取引チャットを行う
    public function sendDealMessage(DealRequest $request, Product $product)
    {
        
        $imagePath = null;

        // 画像が送信された場合、アップロード処理
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('deal_images', 'public');
        }

        \App\Models\Deal::create([
            'product_id' => $product->id,
            'user_id'    => Auth::id(),
            'name'       => $request->name,     // テキストメッセージ
            'image'      => $imagePath,         // 画像パス
            'read_flg'   => 0,
        ]);

        return redirect()->route('products.deal', ['product' => $product->id]);
    }
}

