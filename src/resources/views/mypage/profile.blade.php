@extends('layouts.login_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

<div class="profile-container">
    @php
    $imagePath = $user->image && file_exists(storage_path('app/public/' . $user->image))
    ? asset('storage/' . $user->image)
    : asset('images/default_profile.png');
    @endphp

    {{-- ユーザー画像（なければデフォルト画像） --}}
    <div class="profile-image">
        <img src="{{ asset($user->image ? 'storage/' . $user->image : 'images/default_profile.png') }}" alt="プロフィール画像" class="profile-icon">
    </div>

    <div class="profile-info">
        <h2 class="profile-name">{{ $user->name }}</h2>

        <a href="{{ url('/mypage/profile') }}">
            <button type="button" class="edit-profile-button">プロフィールを編集</button>
        </a>
    </div>
</div>

@php
$currentTab = request()->query('tab');
@endphp

<div class="tab-buttons">
    <a href="{{ url('/mypage?tab=sell') }}" class="tab-link {{ $currentTab === 'sell' ? 'active' : '' }}">
        出品した商品
    </a>

    @auth
    <a href="{{ url('/mypage?tab=buy') }}" class="tab-link {{ $currentTab === 'buy' ? 'active' : '' }}">
        購入した商品
    </a>
    @endauth
</div>

<hr class="tab-divider">

<div class="product-list">
    @foreach ($products as $product)
    <div class="product-card">
        <a class="image" href="/item/{{$product->id}}">
            <img class="product-image"
                src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                alt="">
        </a>
        <p class="product-name">{{ $product->name }}</p>

        {{-- SOLD ラベル（出品商品のときだけ表示） --}}
        @if(isset($soldProductIds) && in_array($product->id, $soldProductIds))
        <span class="sold-label">SOLD</span>
        @endif

    </div>
    @endforeach
</div>
@endsection