<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
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
        return view('mypage.setting');
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

    //プロフィール画面で「出品した商品」、「購入した商品」を切り替えて表示する
    public function showProfile()
    {
        $user = Auth::user();
        $tab = request('tab'); // ?tab=sell or ?tab=buy

        if ($tab === 'buy') {
            // 購入した商品を取得（orders 経由）
            $orders = $user->orders()->with('product')->get();
            $products = $orders->pluck('product'); // 購入した products を抽出
        } else {
            // 出品した商品（products テーブル）
            $products = Product::where('user_id', $user->id)->get();
        }

        return view('mypage.profile', compact('user', 'products', 'tab'));
    }
}
