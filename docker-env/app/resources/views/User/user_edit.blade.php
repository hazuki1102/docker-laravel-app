@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">
        <h3 class="text-center mb-4">アカウント情報の編集</h3>

        <form action="{{ route('user.edit_conf') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3 text-center">
                <img src="{{ asset('storage/sample_icon.png') }}" alt="現在のアイコン" class="rounded-circle" width="120" height="120">
            </div>

            <div class="mb-3 text-center">
                <label for="icon" class="form-label">アイコンを編集する</label>
                <input type="file" name="icon" id="icon" class="form-control">
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">ユーザー名</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="ユーザー名を入力">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="example@example.com">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="新しいパスワードを入力">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">パスワード確認</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="もう一度入力">
            </div>

            <div class="mb-3">
                <label for="profile" class="form-label">プロフィール</label>
                <textarea name="profile" id="profile" class="form-control" rows="4" placeholder="自己紹介を入力してください"></textarea>
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
