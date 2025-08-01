@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">

        <div class="card mx-auto" style="max-width:600px;">
            <div class="card-header text-center">
                投稿削除の確認
            </div>

            <div class="card-body">

                <div class="text-center mb-4">
                    <img src="{{ url($post->image_path) }}" alt="投稿画像" class="img-fluid rounded" style="max-height:300px;">
                </div>

                <div>
                    <p><strong>タイトル：</strong>{{ $post->title }}</p>
                    <p><strong>キャプション：</strong>{{ $post->body }}</p>
                    <p><strong>ハッシュタグ：</strong>{{ $post->hashtags }}</p>
                    <p><strong>使用した素材：</strong>{{ $post->materials ?? '（未入力）' }}</p>
                </div>

                <form action="{{ route('post.delete', $post->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')

                    <div class="row">
                        <div class="col">
                            <a href="{{ route('post.edit', $post->id) }}" class="btn btn-secondary btn-block">編集画面に戻る</a>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-danger btn-block">削除する</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</main>
@endsection
