@extends('layouts.layout')

@if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif


@section('content')
<main class="py-4">
    <div class="container">
        <h3 class="text-center mb-4">アカウント情報の編集</h3>

        <form action="{{ route('user.edit_conf') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3 text-center">
                <img
                    src="{{ asset('storage/' . ($data['icon_path'] ?? 'sample_icon.png')) }}"
                    alt="現在のアイコン"
                    class="rounded-circle"
                    width="120"
                    height="120"
                    style="object-fit: cover;"
                >
            </div>

            <div class="mb-3 text-center">
                <label for="icon" class="form-label">アイコンを編集する</label>
                <input type="file" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" accept="image/*">
                @error('icon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">ユーザー名</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-control @error('username') is-invalid @enderror"
                    placeholder="ユーザー名を入力"
                    value="{{ old('username', $data['username'] ?? '') }}"
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="example@example.com"
                    value="{{ old('email', $data['email'] ?? '') }}"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">パスワード（変更する場合のみ入力）</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="新しいパスワードを入力"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">パスワード確認</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="form-control"
                    placeholder="もう一度入力"
                >
            </div>

            <div class="mb-3">
                <label for="profile" class="form-label">プロフィール</label>
                <textarea
                    name="profile"
                    id="profile"
                    class="form-control @error('profile') is-invalid @enderror"
                    rows="4"
                    placeholder="自己紹介を入力してください"
                >{{ old('profile', $data['profile'] ?? '') }}</textarea>
                @error('profile')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('mypage') }}" class="btn btn-secondary">変更せずマイページに戻る</a>
                <button type="submit" class="btn btn-primary">編集内容確認</button>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('user.delete_conf') }}" class="btn btn-danger">アカウントを削除する</a>
            </div>

        </form>
    </div>
</main>
@endsection
