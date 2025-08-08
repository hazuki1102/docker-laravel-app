@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">投稿詳細</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>投稿ID:</strong> {{ $post->id }}</p>
            <p><strong>タイトル:</strong> {{ $post->title }}</p>
            <p><strong>本文:</strong> {{ $post->body }}</p>
            <p><strong>投稿者:</strong>
                <a href="{{ route('users.show', $post->user->id) }}">
                    {{ $post->user->username }}
                </a>
            </p>
            <p><strong>ブックマーク数:</strong> {{ $post->bookmarks_count }}</p>
            <p><strong>投稿日時:</strong> {{ $post->created_at->format('Y-m-d H:i') }}</p>

            @if ($post->image_url)
                <div class="mt-3">
                    <img src="{{ $post->image_url }}" class="img-fluid rounded" alt="投稿画像">
                </div>
            @endif
        </div>
    </div>

    <h4 class="mb-3">コメント一覧</h4>
    @if ($post->comments->isEmpty())
        <p>コメントはありません。</p>
    @else
        <ul class="list-group">
            @foreach ($post->comments as $comment)
                <li class="list-group-item">
                    <strong>{{ $comment->user->username }}</strong>：
                    {{ $comment->body }}
                    <span class="text-muted float-end">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('post.list') }}" class="btn btn-secondary mt-3">← 投稿一覧に戻る</a>
</div>
@endsection
