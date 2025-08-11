@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">
        <h3 class="text-center mb-4">投稿編集内容の確認</h3>

        <div class="card mx-auto" style="max-width:600px;">
            <div class="card-body text-center">
                @if (!empty($data['image_base64']))
                    <img src="{{ $data['image_base64'] }}" alt="新しい投稿画像" style="max-width:100%;">
                @else
                    <img src="{{ asset($post->image_path) }}" alt="現在の投稿画像" style="max-width:100%;">
                @endif
            </div>

            <div class="card-body">
                <p><strong>タイトル:</strong> {{ $data['title'] }}</p>
                <p><strong>キャプション:</strong> {{ $data['body'] }}</p>
                <p><strong>ハッシュタグ:</strong> {{ $data['hashtags'] }}</p>
                <p><strong>使用した素材:</strong> {{ $data['materials'] }}</p>
            </div>

            <form action="{{ route('post.update', $post->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="title" value="{{ $data['title'] }}">
                <input type="hidden" name="body" value="{{ $data['body'] }}">
                <input type="hidden" name="hashtags" value="{{ $data['hashtags'] }}">
                <input type="hidden" name="materials" value="{{ $data['materials'] }}">
                @if (!empty($data['temp_image_path']))
                    <input type="hidden" name="temp_image_path" value="{{ $data['temp_image_path'] }}">
                @endif

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary">更新する</button>
                    <a href="{{ route('post.edit', $post->id) }}" class="btn btn-secondary">戻る</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
