{{-- headerファイルを読み込む --}}
@extends($header)

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="tab-buttons">
    <a href="{{ url('/') }}" class="tab-link {{ request('tab') !== 'mylist' ? 'active' : '' }}">
        おすすめ
    </a>
    <a href="{{ url('/') }}?tab=mylist&keyword={{ request('keyword') }}"
        class="tab-link {{ request('tab') === 'mylist' ? 'active' : '' }}">
        マイリスト
    </a>
</div>

<hr class="tab-divider">

<ul class="product-list">
    @foreach($products as $product)
    <li class="product-card">
        <a class="image" href="/item/{{$product->id}}">
            <img class="product-image"
                src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                alt="">
        </a>
        <p class="name">{{$product->name}}</p>
        @if(in_array($product->id, $soldProductIds))
        <span class="sold-label">SOLD</span>
        @endif
    </li>


    @endforeach
</ul>
@endsection