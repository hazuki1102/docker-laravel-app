@extends('layouts.layout')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col col-md-offset-3 col-md-6">
        <nav class="card mt-5">
          <div class="card-header">
            <div class="text-center">パスワード再設定</div>
          </div>
          <div class="card-body">
            @if($errors->any())
              <div class="alert alert-danger">
                @foreach($errors->all() as $message)
                  <p>{{ $message }}</p>
                @endforeach
              </div>
            @endif

            <form method="POST" action="{{ route('reset.password.update') }}">
              @csrf
              <input type="hidden" name="reset_token" value="{{ $userToken->rest_password_access_key }}">

              <div class="form-group">
                <label for="password">新パスワード</label>
                <input type="password" name="password" id="password" class="form-control">
                <span class="text-danger">{{ $errors->first('password') }}</span>
                <span class="text-danger">{{ $errors->first('reset_token') }}</span>
              </div>

              <div class="form-group mt-3">
                <label for="password_confirmation">新パスワード（確認）</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
              </div>

              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">パスワードを再設定する</button>
              </div>
            </form>
          </div>
        </nav>
      </div>
    </div>
  </div>
@endsection
