<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Deal;
use App\Models\Assessment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DealRequest;
use Illuminate\Support\Facades\Storage;
use App\Mail\DealCompletedMail;
use Illuminate\Support\Facades\Mail;

class DealController extends Controller
{
    // 取引用ページを表示する
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

        // 各商品ごとの最新メッセージ日時を取得
        $latestMessageTimes = \App\Models\Deal::whereIn('product_id', $dealProducts->pluck('id'))
            ->selectRaw('product_id, MAX(created_at) as latest_message_time')
            ->groupBy('product_id')
            ->pluck('latest_message_time', 'product_id');

        // 最新メッセージが新しい順にソート（メッセージなしは最下位）
        $dealProducts = $dealProducts->sortByDesc(function ($p) use ($latestMessageTimes) {
            return $latestMessageTimes[$p->id] ?? '1970-01-01 00:00:00';
        });

        // チャット履歴を取得（古い順）
        $messages = $product->deals()->with('user')->orderBy('created_at')->get();

        // レイアウト判定
        $header = Auth::check() ? 'layouts.login_app' : 'layouts.app';

        // 商品ごとの未読件数をまとめる
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

        // この商品の評価があるか判定（購入者→出品者）
        $hasAssessment = \App\Models\Assessment::where('product_id', $product->id)->exists();

        /* ▼▼ ここからモーダル表示のための追加ロジック ▼▼ */

        // 購入者情報を取得
        $buyerOrder = \App\Models\Order::where('product_id', $product->id)->first();
        $buyerId = $buyerOrder ? $buyerOrder->user_id : null;
        $sellerId = $product->user_id;

        // 購入者 → 出品者 の評価が済んでいるか？
        $buyerRatedSeller = \App\Models\Assessment::where('product_id', $product->id)
            ->where('rater_id', $buyerId)
            ->where('user_id', $sellerId)
            ->exists();

        // 出品者 → 購入者 の評価が済んでいるか？
        $sellerRatedBuyer = \App\Models\Assessment::where('product_id', $product->id)
            ->where('rater_id', $sellerId)
            ->where('user_id', $buyerId)
            ->exists();

        // モーダル表示判定
        $showBuyerRatingModal = false;
        if (
            Auth::id() === $sellerId &&   // ログインしているのが出品者
            $buyerRatedSeller &&         // 購入者→出品者の評価は済んでる
            !$sellerRatedBuyer           // でもまだ出品者→購入者の評価はしてない
        ) {
            $showBuyerRatingModal = true;
        }

        /* ▲▲ モーダル判定ここまで ▲▲ */

        return view('product.deal', [
            'product'             => $product,        // 今表示中の商品
            'dealProducts'        => $dealProducts,   // サイドバー用の取引中商品一覧
            'messages'            => $messages,       // チャット履歴
            'header'              => $header,
            'unreadCounts'        => $unreadCounts,   // 商品ごとの未読件数
            'hasAssessment'       => $hasAssessment,
            'showBuyerRatingModal' => $showBuyerRatingModal, // モーダル表示フラグ
        ]);
    }


    // 取引用ページで、取引チャットを行う
    public function sendDealMessage(DealRequest $request, Product $product)
    {
        $imagePath = null;

        // 画像が送信された場合、アップロード処理
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('deal_images', 'public');
        }

        Deal::create([
            'product_id' => $product->id,
            'user_id'    => Auth::id(),
            'name'       => $request->name,   // テキストメッセージ
            'image'      => $imagePath,       // 画像パス
            'read_flg'   => 0,
        ]);

        return redirect()->route('products.deal', ['product' => $product->id]);
    }

    // メッセージ更新（編集）
    public function updateDealMessage(Request $request, Deal $deal)
    {
        // 編集できるのは自分のメッセージのみ
        if ($deal->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->hasFile('image')) {
            // 古い画像を削除（あれば）
            if ($deal->image) {
                Storage::disk('public')->delete($deal->image);
            }
            $deal->image = $request->file('image')->store('deal_images', 'public');
        }

        $deal->name = $request->input('name');
        $deal->save();

        return redirect()->route('products.deal', ['product' => $deal->product_id]);
    }

    // メッセージ削除
    public function destroyDealMessage(Deal $deal)
    {
        // 削除できるのは自分のメッセージのみ
        if ($deal->user_id !== Auth::id()) {
            abort(403);
        }

        // 画像もあれば削除
        if ($deal->image) {
            Storage::disk('public')->delete($deal->image);
        }

        $deal->delete();

        return redirect()->route('products.deal', ['product' => $deal->product_id]);
    }

    // 取引完了ボタンを押して、出品者を評価する
    public function complete(Request $request, Product $product)
    {
        $validated = $request->validate([
            'point' => 'required|integer|min:1|max:5',
        ]);

        // 評価を保存（assessmentsテーブルを作る）
        Assessment::create([
            'product_id' => $product->id,
            'user_id'    => $product->user_id,  // 評価対象（出品者）
            'rater_id'   => Auth::id(),          // 評価した人（購入者）
            'point'      => (int) $validated['point'], // 数値として保存
        ]);

        // 出品者情報
        $seller = $product->user;
        // 購入者情報
        $user = auth()->user();

        // メール送信
        Mail::to($seller->email)->send(new DealCompletedMail($product, $user));

        return redirect('/');
    }

    // 購入者を評価する
    public function completeBuyerRating(Request $request, Product $product)
    {
        $request->validate([
            'point' => 'required|integer|min:1|max:5',
        ]);

        // 購入者を取得
        $buyerOrder = \App\Models\Order::where('product_id', $product->id)->first();

        $buyerId = $buyerOrder->user_id;
        $sellerId = $product->user_id;

        // すでに出品者が購入者を評価済みか確認
        $already = \App\Models\Assessment::where('product_id', $product->id)
            ->where('rater_id', $sellerId)  // 出品者が評価した履歴
            ->where('user_id', $buyerId)    // 評価対象が購入者
            ->exists();

        if (!$already) {
            // 登録（出品者が購入者を評価）
            \App\Models\Assessment::create([
                'rater_id'   => $sellerId,        // 評価したのは出品者
                'user_id'    => $buyerId,         // 評価対象は購入者
                'product_id' => $product->id,
                'point'      => $request->point,
            ]);
        }

        return redirect('/');
    }
}
