@extends('layouts.layout')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col col-md-offset-3 col-md-6">
        <nav class="card mt-5">
          <div class="card-header">
            <div class='text-center'>パスワード再設定</div>
          </div>
          <div class="card-body">
            @if($errors->any())
              <div class="alert alert-danger">
                @foreach($errors->all() as $message)
                  <p>{{ $message }}</p>
                @endforeach
              </div>
            @endif

            <p class="text-center">ご利用中のメールアドレスを入力してください</p>
            <p class="text-center">パスワード再設定のためのURLをお送りします</p>

            <form method="POST" action="{{ route('reset.send') }}">
              @csrf
              <div class="form-group">
                <label for="email">メールアドレス</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  value="{{ old('email') }}"
                  class="form-control"
                  required
                >
              </div>
              <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">再設定メールを送信</button>
              </div>
            </form>

            <div class="text-center mt-3">
              <a href="{{ route('login') }}">戻る</a>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
@endsection
