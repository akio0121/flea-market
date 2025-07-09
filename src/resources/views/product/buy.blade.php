@extends('layouts.login_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/buy.css') }}">
@endsection

@section('content')

<div class="product-details-container">
    <div class="product-image">
        <img src="{{ asset($product->image) }}" alt="商品画像">
    </div>
    <div class="product-info">
        <h2>{{ $product->name }}</h2>
        <p>¥{{ number_format($product->price) }}</p>
    </div>
    <div class="payment-summary-box">
        <p class="summary-price">
            <span class="label">商品代金</span>
            <span class="amount">¥{{ number_format($product->price) }}</span>
        </p>
        <p class="summary-price">
            <span class="label">支払い方法</span>
            @if ($selectedPayment)
            <span>{{ $selectedPayment->name }}</span>
            @endif
        </p>
    </div>

    {{--購入ボタンフォーム --}}
    <form action="{{ route('product.purchase', $product->id) }}" method="POST" class="purchase-form">
        @csrf
        <input type="hidden" name="payment_id" value="{{ request('payment_id') }}">
        <button type="submit" class="btn custom-purchase-button">
            購入する
        </button>
    </form>
</div>
<hr class="section-divider">

<form method="GET" action="{{ route('product.buy', $product->id) }}">
    <div class="payment-select">
        <label for="payment_id">支払い方法</label>
        <select name="payment_id" id="payment_id" class="form-control" onchange="this.form.submit()" required>
            <option value="">選択してください</option>
            @foreach ($payments as $payment)
            <option value="{{ $payment->id }}" {{ request('payment_id') == $payment->id ? 'selected' : '' }}>
                {{ $payment->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="form__error">
        @error('payment_id')
        {{ $message }}
        @enderror
    </div>
</form>

<hr class="section-divider">

@auth
<div class="address-info">
    <p>〒{{ session('recipient_post', Auth::user()->post) }}</p>
    <p>{{ session('recipient_address', Auth::user()->address) }}</p>
    <p>{{ session('recipient_building', Auth::user()->building) }}</p>
</div>
@endauth

<a href="{{ route('product.address.edit', $product->id) }}" class="btn btn-secondary">
    変更する
</a>

<hr class="section-divider">


@endsection