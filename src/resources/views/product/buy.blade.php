@extends('layouts.login_app')

@section('css')
<link rel="stylesheet" href="{{ asset('buy.css') }}">
@endsection

@section('content')

<div class="product-info">
    <h2>{{ $product->name }}</h2>
    <img src="{{ asset($product->image) }}" alt="商品画像" style="width:200px;">
    <p>¥{{ number_format($product->price) }}</p>
</div>

<form method="GET" action="{{ route('product.buy', $product->id) }}">
    <label for="payment_id">支払い方法</label>
    <select name="payment_id" id="payment_id" class="form-control" onchange="this.form.submit()" required>
        <option value="">選択してください</option>
        @foreach ($payments as $payment)
        <option value="{{ $payment->id }}" {{ request('payment_id') == $payment->id ? 'selected' : '' }}>
            {{ $payment->name }}
        </option>
        @endforeach
    </select>
    <div class="form__error">
        @error('payment_id')
        {{ $message }}
        @enderror
    </div>
</form>

@if ($selectedPayment)
<p style="margin-top: 1rem;"><strong>{{ $selectedPayment->name }}</strong></p>
@endif

<div class="address-info">
    <p>〒{{ session('recipient_post', $product->user->post) }}</p>
    <p>{{ session('recipient_address', $product->user->address) }}</p>
    <p>{{ session('recipient_building', $product->user->building) }}</p>
</div>

<a href="{{ route('product.address.edit', $product->id) }}" class="btn btn-secondary">
    変更する
</a>

{{--購入ボタンフォーム --}}
<form action="{{ route('product.purchase', $product->id) }}" method="POST">
    @csrf
    <input type="hidden" name="payment_id" value="{{ request('payment_id') }}">

    <button type="submit" class="btn btn-primary mt-3">
        この商品を購入する
    </button>
</form>

@endsection