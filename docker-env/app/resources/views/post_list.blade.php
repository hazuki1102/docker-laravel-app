@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3 text-center">投稿検索</h2>

    <form method="GET" action="{{ route('post.list') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="タイトルで検索" value="{{ request('keyword') }}">
            <button class="btn btn-primary">検索</button>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>投稿ID</th>
                <th>ユーザー名</th>
                <th>タイトル</th>
                <th>ブックマーク数</th>
                <th>投稿日時</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->user->name }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->bookmarks_count }}</td>
                <td>{{ $post->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ url('/post_detail?post_id=' . $post->id) }}" class="btn btn-sm btn-outline-secondary">詳細</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $posts->appends(request()->query())->links() }}
</div>
@endsection
