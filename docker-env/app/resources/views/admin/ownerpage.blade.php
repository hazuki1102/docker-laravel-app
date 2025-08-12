@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container text-center">
        <h2 class="mb-5">管理者ページ</h2>

        <div class="d-grid gap-3 col-6 mx-auto">
            <a href="{{ route('user_list') }}" class="btn btn-primary btn-lg">ユーザー検索</a>
            <a href="{{ route('post_list') }}" class="btn btn-secondary btn-lg">投稿検索</a>
        </div>
    </div>
</main>
@endsection
