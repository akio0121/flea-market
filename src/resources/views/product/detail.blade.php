@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')


<h1>{{ $product->name }}</h1>
<a class="image" href="/item/{{$product->id}}">
    <img class="product-image" src="{{$product->image}}" alt="">
</a>
<h5>ブランド名</h5>
<p>¥{{ number_format($product->price) }}(税込)</p>
<h3>商品説明</h3>
<p>{{ $product->description }}</p>
<h3>商品の情報</h3>
<p>商品の状態：{{ $product->condition->name }}</p>
@endsection