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
                <img class="product-image" src="{{$product->image}}" alt="">
            </a>
            <p class="name">{{$product->name}}</p>
        </li>
        @endforeach
    </div>
</ul>
@endsection