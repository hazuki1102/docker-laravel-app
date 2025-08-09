@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container d-flex justify-content-center">
        <div class="card shadow-sm" style="max-width: 480px; width: 100%;">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">編集内容の確認</h3>

                <form action="{{ route('user.update') }}" method="POST">
                    @csrf

                    <div class="mb-4 text-center">
                        @php
                            use Illuminate\Support\Facades\Storage;

                            $relPath = $data['icon_path']
                                ?? $data['current_icon_path']
                                ?? null;

                            $iconUrl = $relPath ? Storage::url($relPath) : asset('images/sample_icon.png');
                        @endphp

                        <img src="{{ $iconUrl }}" alt="アイコン"
                            class="rounded-circle" width="120" height="120" style="object-fit: cover;">

                        <input type="hidden" name="icon_path" value="{{ $data['icon_path'] ?? '' }}">

                    </div>

                    <div class="mb-3">
                        <strong>ユーザー名：</strong> {{ $data['username'] }}
                        <input type="hidden" name="username" value="{{ $data['username'] }}">
                    </div>

                    <div class="mb-3">
                        <strong>メールアドレス：</strong> {{ $data['email'] }}
                        <input type="hidden" name="email" value="{{ $data['email'] }}">
                    </div>

                    <input type="hidden" name="password" value="{{ $data['password'] ?? '' }}">
                    <input type="hidden" name="password_confirmation" value="{{ $data['password_confirmation'] ?? '' }}">

                    <div class="mb-3">
                        <strong>プロフィール：</strong><br>
                        {!! nl2br(e($data['profile'] ?? '（未入力）')) !!}
                        <input type="hidden" name="profile" value="{{ $data['profile'] }}">
                    </div>

                    <input type="hidden" name="icon_path" value="{{ $data['icon_path'] ?? '' }}">

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">編集画面に戻る</a>
                        <button type="submit" class="btn btn-primary">登録する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
