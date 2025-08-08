@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">ユーザー詳細</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $user->username }}</h5>
            <p class="card-text"><strong>ユーザーID:</strong> {{ $user->id }}</p>
            <p class="card-text"><strong>メールアドレス:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>投稿数:</strong> {{ $user->posts_count }}</p>
            <p class="card-text"><strong>ブックマーク数:</strong> {{ $user->bookmarks_count }}</p>
            <p class="card-text"><strong>管理者権限:</strong> {{ $user->is_admin ? 'あり' : 'なし' }}</p>
        </div>
    </div>

    <h4 class="mb-3">投稿一覧</h4>
    @if ($user->posts->isEmpty())
        <p>投稿はありません。</p>
    @else
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>投稿ID</th>
                    <th>タイトル</th>
                    <th>投稿日時</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ url('/post_detail?post_id=' . $post->id) }}" class="btn btn-sm btn-outline-secondary">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('user.list') }}" class="btn btn-secondary mt-3">← ユーザー一覧に戻る</a>
</div>
@endsection
