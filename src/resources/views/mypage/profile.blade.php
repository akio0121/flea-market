@extends('layouts.login_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

<div class="profile">
    {{-- ユーザー画像（なければデフォルト画像） --}}
    <img src="{{ asset($user->image ?? 'images/default_profile.png') }}"
        alt="プロフィール画像"
        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
    {{-- ユーザー名の表示 --}}
    <h2>{{ $user->name }}</h2>
</div>
<a href="{{ url('/mypage/profile') }}">
    <button type="button">プロフィールを編集</button>
</a>
<ul>
    {{-- タブ切り替えボタン --}}
    <li>
        <a href="{{ url('/mypage?tab=sell') }}">
            <button>出品した商品</button>
        </a>
    </li>
    @auth
    <li>
        <a href="{{ url('/mypage?tab=buy') }}">
            <button>購入した商品</button>
        </a>
    </li>
    @endauth
</ul>
<div class="product-list">
    @foreach ($products as $product)
    <li>
        <a class="image" href="/item/{{$product->id}}">
            <img class="product-image"
                src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                alt="">
        </a>
        <p>{{ $product->name }}</p>
    </li>

    {{-- SOLD ラベル（出品商品のときだけ表示） --}}
    @if(isset($soldProductIds) && in_array($product->id, $soldProductIds))
    <span class="sold-label">SOLD</span>
    @endif

</div>
@endforeach
</div>
@endsection