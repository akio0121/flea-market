{{-- headerファイルを読み込む --}}
@extends($header)

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<ul>
    <a href="{{ url('/') }}">
        <button>おすすめ</button>
    </a>
    @auth
    <a href="{{ url('/') }}?tab=mylist">
        <button>マイリスト</button>
    </a>
    @endauth
    <div class="image-grid">
        @foreach($products as $product)
        <li>
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
    </div>
</ul>
@endsection