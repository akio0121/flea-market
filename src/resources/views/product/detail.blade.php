{{-- headerファイルを読み込む --}}
@extends($header)

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')

<div class="product-detail-container">
    <div class="left-section">
        <div class="product-list">
            <div class="product-card">
                <a class="image" href="/item/{{$product->id}}">
                    <img class="product-image"
                        src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                        alt="">
                </a>
                @if($isSold)
                <span class="sold-label">Sold</span>
                @endif
            </div>
        </div>
    </div>

    <div class="right-section">
        <h1>{{ $product->name }}</h1>
        <p>{{ $product->brand }}</p>
        <p>¥{{ number_format($product->price) }}(税込)</p>

        <div class="action-buttons">
            @if(Auth::check())
            <form method="POST" action="{{ route('product.toggleLike', ['product' => $product->id]) }}">
                @csrf
                <button type="submit" style="border: none; background: none; padding: 0;">
                    <img src="{{ asset($hasLiked ? 'images/goodplus.png' : 'images/good.png') }}"
                        alt="いいね"
                        style="width: 50px; height: auto;">
                </button>
            </form>
            @else
            <a href="{{ route('login') }}">
                <button type="submit" style="border: none; background: none; padding: 0;">
                    <img src="{{ asset($hasLiked ? 'images/goodplus.png' : 'images/good.png') }}"
                        alt="いいね"
                        style="width: 50px; height: auto;">
                </button>
            </a>
            @endif
            <p>{{ $product->goods_count }}</p>


            <a href="#comment-form">
                <img src="{{ asset('images/comment.png') }}" alt="コメント" style="width: 50px; height: auto;">
            </a>

            <p>{{ $product->comments_count }}</p>
        </div>

        <a href="{{ url('/purchase/' . $product->id) }}"
            class="primary-button {{ $product->is_sold ? 'disabled' : '' }}"
            @if($isSold) aria-disabled="true" onclick="return false;" @endif>
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
            <textarea name="name" class="comment-textarea"></textarea>
            <br>
            <div class="form__error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
            <button type="submit" class="primary-button">コメントを送信する</button>
        </form>
    </div>
</div>
@endsection