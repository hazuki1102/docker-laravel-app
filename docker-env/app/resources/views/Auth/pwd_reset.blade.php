@extends('layouts.layout')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <div class="container" style="max-width: 400px;">
        <h2 class="text-center mb-2">パスワード再設定</h2>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group mb-3 border rounded p-3">
                <p class="text-center mb-5">パスワード再設定のためのメールを送信します</p>
                <label for="email mb-5">メールアドレス</label>
                <input id="email" type="email" name="email" required autofocus class="form-control" value="{{ old('email') }}">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100">メールを送信</button>
        </form>
    </div>
</div>
@endsection
