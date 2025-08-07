@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">管理ユーザーページ</h2>

    <div class="row justify-content-center">
        <div class="col-md-4 text-center">
            <a href="{{ url('/user_list') }}" class="btn btn-primary btn-block mb-3">
                ユーザー検索
            </a>
        </div>
        <div class="col-md-4 text-center">
            <a href="{{ url('/post_list') }}" class="btn btn-secondary btn-block mb-3">
                投稿検索
            </a>
        </div>
    </div>
</div>
@endsection
