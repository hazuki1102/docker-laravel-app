@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                投稿詳細
            </div>
        </div>
    </div>

    <div class="row justify-content-around">

        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">投稿画像</div>
                <div class="card-body text-center">
                    <img src="{{ url($post->image_path) }}" alt="投稿画像" class="img-fluid rounded">
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card mt-0">
                <div class="card-header text-center">タイトル</div>
                <div class="card-body">
                    <p class="mb-0">{{ $post->title }}</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header text-center">キャプション</div>
                <div class="card-body">
                    <p class="mb-0">{{ $post->body }}</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header text-center">ハッシュタグ</div>
                <div class="card-body">
                    <p class="mb-0">{{ $post->hashtags }}</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header text-center">使用した素材</div>
                <div class="card-body">
                    <p class="mb-0">{{ $post->materials ?? '（未入力）' }}</p>
                </div>
            </div>

            <div class="row mt-4 mb-4">
                <div class="col">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block">戻る</a>
                </div>
                <div class="col">
                    <form action="{{ route('bookmark.store', $post->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-block">ブックマークする</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div class="row justify-content-center mt-5">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">コメントする</div>
            <div class="card-body">
                <form action="{{ route('comments.store', $post->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="comment" rows="3" class="form-control" placeholder="コメントを入力">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">送信</button>
                </form>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">コメント一覧</div>
            <div class="card-body">
                @forelse ($post->comments as $comment)
                    <div class="mb-3">
                        <strong>{{ $comment->user->username }}</strong>
                        <small class="text-muted">（{{ $comment->created_at->format('Y/m/d H:i') }}）</small>
                        <p class="mb-0">{{ $comment->content }}</p>
                        <hr>
                    </div>
                @empty
                    <p>コメントはまだありません。</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

</main>
@endsection
