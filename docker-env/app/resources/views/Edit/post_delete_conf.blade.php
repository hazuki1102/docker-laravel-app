@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">

        <div class="card mx-auto" style="max-width:600px;">
            <div class="card-header text-center">
                投稿削除の確認
            </div>

            <div class="card-body">

            <div class="card-body text-center">
                @if (!empty($data['image_base64']))
                    <img src="{{ $data['image_base64'] }}" alt="新しい投稿画像" style="max-width:100%;">
                @else
                    @php
                        $raw = $post->image_url ?? $post->image_path;
                        if ($raw && \Illuminate\Support\Str::startsWith($raw, ['http://','https://'])) {
                            $img = $raw;
                        } else {
                            $path = $raw ? ltrim(preg_replace('#^public/#','', $raw), '/') : null;
                            $img  = $path ? \Storage::url($path) : asset('images/noimage.png');
                        }
                    @endphp
                    <img src="{{ $img }}" alt="現在の投稿画像" style="max-width:100%;">
                @endif
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
                            <form action="{{ route('post.delete', $post->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">削除する</button>
                            </form>

                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</main>
@endsection
