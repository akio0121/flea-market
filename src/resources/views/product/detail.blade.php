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

<p>いいね：{{ $product->goods_count }}</p>
<p>コメント：{{ $product->comments_count }}</p>
<h3>商品説明</h3>
<p>{{ $product->description }}</p>
<h3>商品の情報</h3>
<p>カテゴリー</p>
<ul>
    @foreach ($product->productCategories as $productCategory)
    <li>{{ $productCategory->category->name }}</li>
    @endforeach
</ul>
<p>商品の状態：{{ $product->condition->name }}</p>

<p>コメント({{ $product->comments_count }})</p>

<ul>
    @foreach ($product->comments as $comment)
    <li>
        <strong>{{ $comment->user->name }}</strong>：
        <span>{{ $comment->name }}</span>
    </li>
    @endforeach
</ul>

@endsection