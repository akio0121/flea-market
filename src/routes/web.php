<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MypageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//ログイン画面を表示する
Route::get('/login', [UserController::class, 'login'])->name('login');

//ログイン画面でログインする
Route::post('/login', [UserController::class, 'startLogin']);

//会員登録画面を表示する
Route::get('/register', [UserController::class, 'register']);

//会員登録画面でユーザー名等を入力する
Route::post('/register', [UserController::class, 'create']);

//プロフィール設定画面を表示する
Route::get('/mypage/profile', [MypageController::class, 'edit']);

//プロフィール設定画面で郵便番号等を入力する
Route::post('/mypage/profile', [MypageController::class, 'setting']);

//プロフィール設定画面で画像を変更する
Route::post('/profile/preview', [MypageController::class, 'preview'])->name('profile.preview');

//商品一覧画面を表示する
Route::get('/', [ItemController::class, 'index'])->name('product.index');

//商品詳細画面を表示する
Route::get('/item/{product}', [ProductController::class, 'detail']);

//商品出品画面を表示する
Route::get('/sell', [ProductController::class, 'exhibit'])
    ->name('sell')
    ->middleware('auth');
