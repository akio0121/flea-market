<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\DealController;

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



//プロフィール設定画面で画像を変更する
Route::post('/profile/preview', [MypageController::class, 'preview'])->name('profile.preview');

//商品一覧画面を表示する
Route::get('/', [ItemController::class, 'index'])->name('product.index');

//商品詳細画面を表示する
Route::get('/item/{product}', [ProductController::class, 'detail'])->name('products.detail');


Route::middleware('auth')->group(function () {

    // 商品詳細画面でコメントを入力
    Route::post('/item/{product}', [ProductController::class, 'store'])->name('comment.store');

    //商品詳細画面で「いいね」する
    Route::post('/item/{product}/like', [ProductController::class, 'toggleLike'])->name('product.toggleLike');

    //プロフィール画面を表示する
    Route::get('/mypage', [MypageController::class, 'showProfile']);

    // 商品出品画面を表示
    Route::get('/sell', [ProductController::class, 'exhibit'])->name('sell');

    // 出品処理（カテゴリ・商品情報まとめて送信）
    Route::post('/sell', [ProductController::class, 'exhibitStore'])->name('products.store');

    // 出品処理（画像アップロード用）
    Route::post('/image-upload', [ProductController::class, 'uploadImage'])->name('image.upload');

    //商品購入画面を表示する
    Route::get('/purchase/{product}', [ProductController::class, 'showBuy'])->name('product.buy');

    //商品購入画面で、購入処理を行う
    Route::post('/purchase/{product}', [ProductController::class, 'purchase'])->name('product.purchase');

    //送付先住所変更画面を表示する
    Route::get('/purchase/address/{product}', [ProductController::class, 'editAddress'])->name('product.address.edit');

    //送付先住所変更画面で、変更した住所をセッションに保存
    Route::post('/purchase/address/{product}', [ProductController::class, 'storeAddress'])->name('product.address.store');

    //プロフィール設定画面で郵便番号等を入力する
    Route::post('/mypage/profile', [MypageController::class, 'setting']);

    // 取引用ページを表示する
    Route::get('/deal/{product}', [DealController::class, 'showDeal'])
        ->name('products.deal');

    //取引用ページで取引チャットを行う
    Route::post('/deal/{product}/message', [DealController::class, 'sendDealMessage'])->name('deal.message.send');

    // 取引メッセージを編集する
    Route::put('/deal/message/{deal}', [DealController::class, 'updateDealMessage'])->name('deal.message.update');

    //取引メッセージ削除する
    Route::delete('/deal/message/{deal}', [DealController::class, 'destroyDealMessage'])->name('deal.message.destroy');

    //取引完了ボタンを押して、出品者を評価する（購入者＞出品者）
    Route::post('/deal/{product}/complete', [DealController::class, 'complete'])->name('deal.complete');

    //取引完了ボタンを押して、出品者を評価する（出品者＞購入者）
    Route::post('/deal/{product}/complete-buyer', [DealController::class, 'completeBuyerRating'])
        ->name('deal.complete.buyer');
});
