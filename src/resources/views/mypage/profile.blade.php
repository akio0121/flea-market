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

    <div>
        <h2>取引評価点数</h2>
    </div>

    <div>
        <h2>新規取引メッセージ件数</h2>
        {{ $unreadCount }}
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

    <a href="{{ url('/mypage?tab=deal') }}" class="tab-link {{ $currentTab === 'deal' ? 'active' : '' }}">
        取引中の商品
    </a>
    @endauth
</div>

<hr class="tab-divider">

<div class="product-list">
    @foreach($products as $product)
    <div class="product-card">
        <a href="{{ route($productLinkRoute, $product->id) }}">
            {{-- 商品画像 --}}
            <img class="product-image"
                src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}">

            {{-- 未読メッセージ件数バッジ（取引タブのみ表示）--}}
            @if(request('tab') === 'deal' && isset($unreadCounts[$product->id]) && $unreadCounts[$product->id] > 0)
            <span class="badge">{{ $unreadCounts[$product->id] }}</span>
            @endif
        </a>

        {{-- SOLD 表示は $soldProductIds がある場合だけ --}}
        @if(in_array($product->id, $soldProductIds))
        <span class="sold-label">Sold</span>
        @endif

        <p class="product-name">{{ $product->name }}</p>
    </div>
    @endforeach
</div>
@endsection