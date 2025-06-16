<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class UserController extends Controller
{

    //ログイン画面を表示する
    public function login()
    {
        return view('auth.login');
    }

    //ログイン画面でログインする
    public function startLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ])->onlyInput('email');
    }

    //会員登録画面を表示する
    public function register()
    {
        return view('auth.register');
    }

    //会員登録画面でユーザー名等を入力後、プロフィール設定画面に遷移する
    public function create(RegisterRequest $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();
        Auth::login($user);
        return redirect('/mypage/profile');
    }
}
