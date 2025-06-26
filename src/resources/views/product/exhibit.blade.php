@extends('layouts.login_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibit.css') }}">
@endsection

@section('content')
<h1>商品の出品</h1>

<h2>商品画像</h2>

<!-- アップロード済み画像表示 -->
@if(session('imagePath'))
<div>
    <img src="{{ asset('storage/' . session('imagePath')) }}" alt="アップロード画像" style="width: 200px; height: auto;">
</div>
@endif

<!-- 画像アップロード用フォーム -->
<form method="POST" action="{{ route('image.upload') }}" enctype="multipart/form-data">
    @csrf
    <label style="cursor: pointer;">
        画像を選択する
        <input type="file" name="image" accept="image/*" onchange="this.form.submit()" style="display:none">
    </label>
</form>

<div class="form__error">
    @error('image')
    {{ $message }}
    @enderror
</div>

<!-- 出品フォーム（ここでhiddenに画像パスを持たせる） -->
<h2>商品の詳細</h2>
<form method="POST" action="{{ route('products.store') }}">
    @csrf

    <!-- 画像パスを hidden で渡す -->
    <input type="hidden" name="image" value="{{ session('imagePath') }}">

    <label for="name">商品名</label>
    <input type="text" id="name" name="name" value="{{ old('name') }}">
    <div class="form__error">@error('name') {{ $message }} @enderror</div>

    <label for="brand">ブランド名</label>
    <input type="text" id="brand" name="brand" value="{{ old('brand') }}">

    <label for="description">商品の説明</label>
    <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
    <div class="form__error">@error('description') {{ $message }} @enderror</div>

    <label for="price">販売価格</label>
    <input type="text" id="price" name="price" value="{{ old('price') }}">
    <div class="form__error">@error('price') {{ $message }} @enderror</div>



    <h3>カテゴリー</h3>
    @foreach ($categories as $category)
    <label>
        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}">
        {{ $category->name }}
    </label>
    @endforeach
    <div class="form__error">@error('category_ids') {{ $message }} @enderror</div>

    <h3>商品の状態</h3>
    <select name="condition_id">
        <option value="">-- 選択してください --</option>
        @foreach($conditions as $condition)
        <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
            {{ $condition->name }}
        </option>
        @endforeach
    </select>
    <div class="form__error">@error('category_ids') {{ $message }} @enderror</div>

    <button type="submit">出品する</button>
</form>
@endsection