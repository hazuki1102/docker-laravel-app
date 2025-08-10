@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container d-flex justify-content-center">
        <div class="card shadow-sm" style="max-width: 480px; width: 100%;">
            <div class="card-body p-4">
                <h4 class="text-center mb-4">このアカウントを削除しますか？</h4>

                <p class="text-center mb-4">本当にアカウントを削除してよろしいですか？<br>削除すると復元できません。</p>

                <form action="{{ route('user.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4 text-center">
                        @if (!empty($user->icon_path))
                            <img src="{{ asset('storage/' . $user->icon_path) }}" alt="アイコン" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                        @else
                            <img src="{{ asset('storage/sample_icon.png') }}" alt="アイコン" class="rounded-circle" width="120" height="120">
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>ユーザー名：</strong> {{ $user->username }}
                    </div>

                    <div class="mb-3">
                        <strong>メールアドレス：</strong> {{ $user->email }}
                    </div>

                    <div class="mb-3">
                        <strong>プロフィール：</strong><br>
                        {!! nl2br(e($user->profile ?? '（未入力）')) !!}
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('mypage') }}" class="btn btn-secondary">マイページに戻る</a>
                        <button type="submit" class="btn btn-danger">削除する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
