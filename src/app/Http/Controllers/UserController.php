<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    //ログイン画面を表示する
    public function login()
    {
        return view('auth.login');
    }

    //会員登録画面を表示する
    public function register()
    {
        return view('auth.register');
    }

    //会員登録画面でユーザー名等を入力後、プロフィール設定画面に遷移する
    public function create(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();
        dd($user);
        //Auth::login($user);
        
    }

}
