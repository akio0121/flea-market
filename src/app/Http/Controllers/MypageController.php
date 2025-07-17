<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;


class MypageController extends Controller
{
    //プロフィール設定画面を表示する
    public function edit()
    {
        $user = auth()->user(); // ログインユーザー取得
        return view('mypage.setting', compact('user'));
    }

    //プロフィール設定画面で郵便番号等を入力後、商品一覧画面に遷移する
    public function setting(AddressRequest $request)
    {
        $user = $request->user();
        $user->name = $request->input('name');
        $user->post = $request->input('post');
        $user->address = $request->input('address');
        $user->building = $request->input('building');
        $user->save();
        return redirect('/');
    }

    //プロフィール設定画面で、ユーザー画像を選択する
    public function preview(ProfileRequest $request)
    {
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $filename = 'user_' . $user->id . '.' . $request->file('image')->extension();

            $path = $request->file('image')->storeAs('profile_images', $filename, 'public');

            // DBに画像パスを保存
            $user->image = $path;
            $user->save();
        }

        return redirect()->back();
    }

    //プロフィール画面で「出品した商品」、「購入した商品」、「取引中の商品」を切り替えて表示する。購入した商品はsold表示する。
    public function showProfile()
    {
        $user = Auth::user();
        $tab = request('tab');
        $keyword = request('keyword'); // 検索キーワード取得

        //ログインユーザーの評価の平均値を取得
        $averageRating = \App\Models\Assessment::where('user_id', $user->id)->avg('point');
        // 四捨五入して整数にする
        $averageRating = $averageRating ? round($averageRating) : null;

        if ($tab === 'buy') {
            // 購入した商品
            $orders = $user->orders()->with(['product' => function ($query) use ($keyword) {
                if ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                }
            }])->get();

            $products = $orders->pluck('product');
            $soldProductIds = []; // 購入商品には SOLD ラベル不要
            $productLinkRoute = 'products.detail'; // ← 購入タブは detail.blade.php

        } elseif ($tab === 'deal') {
            // 取引中の商品

            // 1. 自分が購入した商品
            $boughtOrders = $user->orders()->with(['product' => function ($query) use ($keyword) {
                if ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                }
            }])->get();
            $boughtProducts = $boughtOrders->pluck('product');

            // 2. 自分が出品した商品のうち、購入されたもの
            $soldOrdersQuery = Order::whereIn(
                'product_id',
                Product::where('user_id', $user->id)->pluck('id')
            )->with(['product' => function ($query) use ($keyword) {
                if ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                }
            }]);
            $soldOrders = $soldOrdersQuery->get();
            $soldProducts = $soldOrders->pluck('product');

            // 両方マージ
            $products = $boughtProducts->merge($soldProducts);

            //各商品ごとの最新メッセージ日時を取得
            $latestMessageTimes = \App\Models\Deal::whereIn('product_id', $products->pluck('id'))
                ->selectRaw('product_id, MAX(created_at) as latest_message_time')
                ->groupBy('product_id')
                ->pluck('latest_message_time', 'product_id');

            //最新メッセージが新しい順にソート
            $products = $products->sortByDesc(function ($product) use ($latestMessageTimes) {
                return $latestMessageTimes[$product->id] ?? '1970-01-01 00:00:00';
            });


            // SOLD ラベルは全て非表示
            $soldProductIds = [];
            $productLinkRoute = 'products.deal'; // ← 取引タブは deal.blade.php

        } else {
            // 出品した商品
            $query = Product::where('user_id', $user->id);

            if ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            }

            $products = $query->get();

            // 売れたものの ID を取得
            $soldProductIds = Order::whereIn('product_id', $products->pluck('id'))
                ->pluck('product_id')->toArray();

            $productLinkRoute = 'products.detail'; // ← 出品タブは detail.blade.php
        }

        //未読件数（自分以外が送ったメッセージ かつ 未読）
        $unreadCount = Deal::where('user_id', '!=', $user->id)
            ->where('read_flg', 0)
            ->count();

        //商品ごとの未読件数
        $unreadCounts = Deal::selectRaw('product_id, COUNT(*) as cnt')
            ->where('user_id', '!=', $user->id)
            ->where('read_flg', 0)
            ->groupBy('product_id')
            ->pluck('cnt', 'product_id');

        return view('mypage.profile', compact(
            'user',
            'products',
            'tab',
            'soldProductIds',
            'keyword',
            'productLinkRoute',
            'unreadCount',   // 全体の未読件数
            'unreadCounts',   // 商品ごとの未読件数
            'averageRating' //評価の平均値
        ));
    }
}
