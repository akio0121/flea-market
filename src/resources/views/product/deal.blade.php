{{-- headerファイルを読み込む --}}
@extends($header)

@section('css')
<link rel="stylesheet" href="{{ asset('css/deal.css') }}">
@endsection

@section('content')
<div class="deal-container">

    {{-- 左側サイドバー --}}
    <div class="sidebar">
        <h4>取引中の商品</h4>
        @foreach($dealProducts as $dealProduct)
        <div class="product-thumb">
            <a href="{{ route('products.deal', ['product' => $dealProduct->id]) }}">
                <img src="{{ Str::startsWith($dealProduct->image, 'http') ? $dealProduct->image : asset('storage/' . $dealProduct->image) }}" alt="">
            </a>
            <p>{{ $dealProduct->name }}</p>

            {{-- 未読件数があればバッジを表示 --}}
            @if(isset($unreadCounts[$dealProduct->id]) && $unreadCounts[$dealProduct->id] > 0)
            <span class="unread-badge">
                {{ $unreadCounts[$dealProduct->id] }}
            </span>
            @endif

        </div>
        @endforeach
    </div>

    {{-- 右側メイン --}}
    <div class="deal-main">
        <h2>{{ $product->name }}</h2>
        <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="" style="max-width:300px;">
        <p>価格: ¥{{ number_format($product->price) }}</p>

        {{-- 取引チャット --}}
        <div class="chat-section">
            <h3>取引チャット</h3>

            {{-- チャット履歴 --}}
            <div class="chat-box">
                @forelse($messages as $msg)
                <div class="chat-message {{ $msg->user_id == Auth::id() ? 'my-message' : 'other-message' }}">
                    <strong>{{ $msg->user->name }}</strong>

                    {{-- テキストメッセージ --}}
                    @if($msg->name)
                    <p>{{ $msg->name }}</p>
                    @endif

                    {{-- 画像メッセージ --}}
                    @if($msg->image)
                    <div class="chat-image">
                        <img src="{{ asset('storage/' . $msg->image) }}"
                            alt="送信画像"
                            style="max-width:200px; border:1px solid #ccc;">
                    </div>
                    @endif

                    <small>{{ $msg->created_at->format('Y/m/d H:i') }}</small>
                </div>
                @empty
                <p class="no-message">まだメッセージはありません</p>
                @endforelse
            </div>


            {{-- チャット送信フォーム --}}
            <form action="{{ route('deal.message.send', ['product' => $product->id]) }}"
                method="POST"
                class="chat-form"
                enctype="multipart/form-data"> {{-- ← 画像送信用に必須 --}}
                @csrf

                {{-- テキスト入力 --}}
                <textarea name="name" rows="5" placeholder="メッセージを入力してください..."></textarea>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
                {{-- 画像アップロード --}}
                <input type="file" name="image" accept="image/*">

                <button type="submit" class="chat-send-btn">送信</button>
            </form>
        </div>
    </div>
</div>
@endsection