@extends('layouts.layout')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col col-md-offset-3 col-md-6">
        <nav class="card mt-5">
          <div class="card-header">
            <div class="text-center">メール送信完了</div>
          </div>
          <div class="card-body text-center">
            <p>パスワード再設定用のメールを送信しました</p>
            <p>メールに記載されているリンクからパスワードの再設定を行ってください</p>
            <div class="mt-4">
              <a href="{{ route('login') }}" class="btn btn-primary">ログイン画面へ</a>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
@endsection
