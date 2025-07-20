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

    <div class="deal-main">
        @if($partnerUser)
        <div class="deal-partner-header">
            <img src="{{ asset('images/default_profile.png') }}"
                alt="{{ $partnerUser->name }}"
                class="partner-avatar">
            <div class="partner-info">
                <span class="partner-name">{{ $partnerUser->name }}さんとの取引画面</span>
            </div>
            @php
            // 購入者判定（orders テーブルから取得）
            $buyer = \App\Models\Order::where('product_id', $product->id)->first();
            @endphp

            {{-- 購入者だけ取引完了ボタンを表示 --}}
            @if(Auth::check() && $buyer && Auth::id() === $buyer->user_id)
            <div class="deal-complete-section">
                {{-- モーダルを開くリンク --}}
                <a href="#modal-rating" class="deal-complete-btn">
                    取引を完了する
                </a>
            </div>
            @endif
        </div>
        @endif
        <hr class="section-divider">

        {{-- 右側メイン --}}
        <div class="product-info-container">
            <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="" style="max-width:300px;">

            <div class="product-details">
                <h2>{{ $product->name }}</h2>
                <p>¥{{ number_format($product->price) }}</p>
            </div>
        </div>
        <hr class="section-divider">

        {{-- 取引チャット --}}
        <div class="chat-section">
            <h3>取引チャット</h3>

            {{-- ★ 今編集中のメッセージIDを取得 --}}
            @php $editId = request('edit_id'); @endphp

            {{-- チャット履歴 --}}
            <div class="chat-box">
                @forelse($messages as $msg)
                <div class="chat-message {{ $msg->user_id == Auth::id() ? 'my-message' : 'other-message' }}">
                    <div class="chat-message-header">
                        <img src="{{ $msg->user->profile_image
                ? asset('storage/' . $msg->user->profile_image)
                : asset('images/default_profile.png') }}"
                            alt="{{ $msg->user->name }}"
                            class="chat-user-avatar">
                        <strong>{{ $msg->user->name }}</strong>
                    </div>
                    {{-- ★ 編集対象ならフォーム表示 --}}
                    @if($editId == $msg->id)
                    <form action="{{ route('deal.message.update', $msg->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <textarea name="name" class="edit-textarea">{{ old('name', $msg->name) }}</textarea>
                        <div class="edit-actions-row">
                            <input type="file" name="image" accept="image/*">
                            <div class="chat-message-actions">
                                <button type="submit">更新</button>
                                {{-- キャンセルは edit_id を外して戻る --}}
                                <a href="{{ route('products.deal', $product->id) }}">キャンセル</a>
                            </div>
                        </div>
                    </form>
                    @else
                    {{-- 通常表示 --}}
                    <p>{!! nl2br(e($msg->name)) !!}</p>
                    @if($msg->image)
                    <img src="{{ asset('storage/' . $msg->image) }}" alt="添付画像" style="max-width: 150px;">
                    @endif
                    <small>{{ $msg->created_at->format('Y/m/d H:i') }}</small>

                    {{-- 自分のメッセージだけ編集/削除可能 --}}
                    @if($msg->user_id == Auth::id())
                    <div class="chat-message-actions">
                        {{-- ★ 編集リンクに必ず ?edit_id=xx をつける --}}
                        <a href="{{ route('products.deal', ['product' => $product->id]) }}?edit_id={{ $msg->id }}">編集</a>

                        <form action="{{ route('deal.message.destroy', $msg->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">削除</button>
                        </form>
                    </div>
                    @endif
                    @endif
                </div>
                @empty
                <p class="no-message">まだメッセージはありません</p>
                @endforelse
            </div>

            {{-- チャット送信フォーム --}}
            <form action="{{ route('deal.message.send', ['product' => $product->id]) }}" method="POST" class="chat-form" enctype="multipart/form-data">
                @csrf
                <div class="chat-form-row">
                    <textarea name="name" placeholder="取引メッセージを記入してください">{{ old('name') }}</textarea>
                    <div class="form__error">
                        @error('name')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="chat-form-actions">
                        <label class="upload-btn">
                            画像を追加
                            <input type="file" name="image" accept="image/*" style="display:none;">
                        </label>

                        <button type="submit" class="chat-send-btn">
                            <img src="/images/send.png" alt="送信" class="send-icon">
                        </button>
                    </div>
                </div>
            </form>

            {{-- ===== 購入者が出品者を評価するモーダル ===== --}}
            <div id="modal-rating" class="modal">
                <div class="modal-content">
                    <h3>出品者を評価してください</h3>

                    <form action="{{ route('deal.complete', $product->id) }}" method="POST">
                        @csrf

                        {{-- 評価者IDも渡す --}}
                        <input type="hidden" name="rater_id" value="{{ Auth::id() }}">

                        <div class="star-rating">
                            <input type="radio" id="star5" name="point" value="5" required>
                            <label for="star5" title="5点">★</label>

                            <input type="radio" id="star4" name="point" value="4">
                            <label for="star4" title="4点">★</label>

                            <input type="radio" id="star3" name="point" value="3">
                            <label for="star3" title="3点">★</label>

                            <input type="radio" id="star2" name="point" value="2">
                            <label for="star2" title="2点">★</label>

                            <input type="radio" id="star1" name="point" value="1">
                            <label for="star1" title="1点">★</label>
                        </div>

                        <div style="margin-top:10px;">
                            <button type="submit" style="padding:8px 15px; background:#007bff; color:#fff; border:none; border-radius:5px;">
                                評価を送信
                            </button>

                            {{-- モーダルを閉じるリンク --}}
                            <a href="#" style="margin-left:10px; text-decoration:none; color:#555;">
                                キャンセル
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ===== 出品者が購入者を評価するモーダル（自動表示用） ===== --}}
            @if($showBuyerRatingModal)
            <div id="modal-buyer-rating" class="modal show" style="display:block;">
                <div class="modal-content">
                    <h3>購入者を評価してください</h3>

                    <form action="{{ route('deal.complete.buyer', $product->id) }}" method="POST">
                        @csrf

                        {{-- 星評価 --}}
                        <div class="star-rating">
                            <input type="radio" id="buyer-star5" name="point" value="5" required>
                            <label for="buyer-star5" title="非常に良い">★</label>

                            <input type="radio" id="buyer-star4" name="point" value="4">
                            <label for="buyer-star4" title="良い">★</label>

                            <input type="radio" id="buyer-star3" name="point" value="3">
                            <label for="buyer-star3" title="普通">★</label>

                            <input type="radio" id="buyer-star2" name="point" value="2">
                            <label for="buyer-star2" title="悪い">★</label>

                            <input type="radio" id="buyer-star1" name="point" value="1">
                            <label for="buyer-star1" title="非常に悪い">★</label>
                        </div>
                        <div style="margin-top:10px;">
                            <button type="submit" style="padding:8px 15px; background:#007bff; color:#fff; border:none; border-radius:5px;">
                                評価を送信
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
</div>
@endsection