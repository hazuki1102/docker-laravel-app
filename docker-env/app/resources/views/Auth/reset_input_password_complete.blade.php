@extends('layouts.layout')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col col-md-offset-3 col-md-6">
        <nav class="card mt-5">
          <div class="card-header">
            <div class="text-center">パスワード変更完了</div>
          </div>
          <div class="card-body text-center">
            <p>パスワードの変更が完了しました</p>
            <p>新しいパスワードにて再ログインしてください</p>

            <div class="mt-4">
              <a href="{{ route('login') }}" class="btn btn-primary">ログイン画面へ</a>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
@endsection
