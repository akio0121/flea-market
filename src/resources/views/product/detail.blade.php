{{-- headerファイルを読み込む --}}
@extends($header)

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')


<h1>{{ $product->name }}</h1>
<a class="image" href="/item/{{$product->id}}">
    <img class="product-image"
        src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
        alt="">
</a>
@if($isSold)
<span class="sold-label">Sold</span>
@endif

<p>ブランド：{{ $product->brand }}</p>
<p>¥{{ number_format($product->price) }}(税込)</p>

<p>いいね：{{ $product->goods_count }}</p>
@if(Auth::check())
<form method="POST" action="{{ route('product.toggleLike', ['product' => $product->id]) }}">
    @csrf
    <button type="submit">いいね</button>
</form>
@else
<a href="{{ route('login') }}">
    <button type="button">いいね</button>
</a>
@endif
<p>コメント：{{ $product->comments_count }}</p>
<a href="#comment-form">
    <button type="button">コメントする</button>
</a>
<a href="{{ url('/purchase/' . $product->id) }}" class="btn btn-primary">
    購入手続きへ
</a>
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
        <img src="{{ asset($comment->user->image ?? 'images/default_profile.png') }}"
            alt="ユーザー画像"
            style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
        <strong>{{ $comment->user->name }}</strong>：
        <span>{{ $comment->name }}</span>
    </li>
    @endforeach
</ul>
<p>商品へのコメント</p>
<form action="{{ route('comment.store', ['product' => $product->id]) }}" method="POST" id="comment-form">
    @csrf
    <textarea name="name" rows="5" cols="50"></textarea>
    <br>
    @error('name')
    {{ $message }}
    @enderror
    <button type="submit">コメントを送信する</button>
</form>

@endsection