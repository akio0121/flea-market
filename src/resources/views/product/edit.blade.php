@extends('layouts.login_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')

<form method="POST" action="{{ route('product.address.store', $product->id) }}">
    @csrf
    <div class="form__group">
        <div class="form__group-title">
            <span class="form__label--item">郵便番号</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--text">
                <input type="text" name="recipient_post" value="{{ old('recipient_post', session('recipient_post')) }}" class="form-control">
            </div>
            <div class="form__error">
                @error('recipient_post')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
    <div class="form__group">
        <div class="form__group-title">
            <span class="form__label--item">住所</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--text">
                <input type="text" name="recipient_address" value="{{ old('recipient_address', session('recipient_address')) }}" class="form-control">
            </div>
            <div class="form__error">
                @error('recipient_address')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
    <div class="form__group">
        <div class="form__group-title">
            <span class="form__label--item">建物名</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--text">
                <input type="text" name="recipient_building" value="{{ old('recipient_building', session('recipient_building')) }}" class="form-control">
            </div>
            <div class="form__error">
                @error('recipient_building')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
    <div class="form__button">
        <button class="form__button-submit" type="submit">更新する</button>
    </div>
</form>
</div>
@endsection