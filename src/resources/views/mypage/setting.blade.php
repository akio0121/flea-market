@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/setting.css') }}">
@endsection

@section('content')

<div class="register__content">
    <div class="register-form__heading">
        <h2>プロフィール設定</h2>
    </div>

    <form action="{{ route('profile.preview') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="profile-container">
            {{-- ユーザー画像（なければデフォルト画像） --}}
            <img src="{{ asset($user->image ?? 'images/default_profile.png') }}"
                alt="プロフィール画像"
                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">

            {{-- 画像選択ボタン（ラベルで囲ってデフォルト表示を非表示に） --}}
            <label>
                画像を選択
                <input type="file" name="image" accept="image/*" required onchange="this.form.submit()" style="display:none">
            </label>
            <div class="form__error">
                @error('image')
                {{ $message }}
                @enderror
            </div>
        </div>
    </form>

    <form class="form" action="/mypage/profile" method="post">
        @csrf
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ old('name') }}" />
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">郵便番号</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="post" value="{{ old('post') }}" />
                </div>
                <div class="form__error">
                    @error('post')
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
                    <input type="text" name="address" />
                </div>
                <div class="form__error">
                    @error('address')
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
                    <input type="text" name="building" />
                </div>
                <div class="form__error">
                    @error('building')
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