@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<ul>
    @foreach($products as $product)
    <li>
        <a href="/item/{{$product->id}}">
            <img src="{{$product->image}}" alt="">
        </a>
        <p>{{$product->name}}</p>
    </li>
    @endforeach
</ul>
@endsection