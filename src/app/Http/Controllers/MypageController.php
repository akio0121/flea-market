<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MypageController extends Controller
{
    //プロフィール設定画面を表示する
    public function edit()
    {
        return view('mypage.setting');
    }

    //プロフィール設定画面で郵便番号等を入力後、商品一覧画面に遷移する
    public function setting(Request $request)
    {
        $user = $request->user();
        $user->post = $request->input('post');
        $user->address = $request->input('address');
        $user->building = $request->input('building');
        $user->save();
        //return redirect('/');
    }
}
