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

            {{-- ★ 今編集中のメッセージIDを取得 --}}
            @php $editId = request('edit_id'); @endphp

            {{-- チャット履歴 --}}
            <div class="chat-box">
                @forelse($messages as $msg)
                <div class="chat-message {{ $msg->user_id == Auth::id() ? 'my-message' : 'other-message' }}">
                    <strong>{{ $msg->user->name }}</strong>

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
                <textarea name="name" rows="3" placeholder="メッセージを入力してください...">{{ old('name') }}</textarea>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>

                {{-- 画像アップロード --}}
                <input type="file" name="image" accept="image/*">

                <button type="submit" name="action" value="send" class="chat-send-btn">送信</button>
            </form>

            @php
            // 購入者判定（orders テーブルから取得）
            $buyer = \App\Models\Order::where('product_id', $product->id)->first();
            @endphp

            {{-- 購入者だけ取引完了ボタンを表示 --}}
            @if(Auth::check() && $buyer && Auth::id() === $buyer->user_id)
            <div class="deal-complete-section">
                {{-- モーダルを開くリンク --}}
                <a href="#modal-rating" class="deal-complete-btn">
                    取引を完了
                </a>
            </div>
            @endif

            {{-- ===== 購入者が出品者を評価するモーダル ===== --}}
            <div id="modal-rating" class="modal">
                <div class="modal-content">
                    <h3>出品者を評価してください</h3>

                    <form action="{{ route('deal.complete', $product->id) }}" method="POST">
                        @csrf

                        {{-- 評価者IDも渡す --}}
                        <input type="hidden" name="rater_id" value="{{ Auth::id() }}">

                        <label>
                            評価：
                            <select name="point" required>
                                <option value="">選択してください</option>
                                <option value="5">非常に良い(5点)</option>
                                <option value="4">良い(4点)</option>
                                <option value="3">普通(3点)</option>
                                <option value="2">悪い(2点)</option>
                                <option value="1">非常に悪い(1点)</option>
                            </select>
                        </label>
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

                        <label>
                            評価：
                            <select name="point" required>
                                <option value="">選択してください</option>
                                <option value="5">非常に良い(5点)</option>
                                <option value="4">良い(4点)</option>
                                <option value="3">普通(3点)</option>
                                <option value="2">悪い(2点)</option>
                                <option value="1">非常に悪い(1点)</option>
                            </select>
                        </label>
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
@endsection