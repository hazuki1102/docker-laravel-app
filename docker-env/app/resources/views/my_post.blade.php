@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                自分の投稿詳細
            </div>
        </div>
    </div>

    <div class="row justify-content-around">

        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">投稿画像</div>
                    <div class="card-body text-center">
                        @php
                            $raw = $post->image_url ?? $post->image_path; // image_url があれば優先
                            if ($raw && \Illuminate\Support\Str::startsWith($raw, ['http://','https://'])) {
                                $img = $raw; // すでにフルURL
                            } else {
                                $path = $raw ? ltrim(preg_replace('#^public/#','',$raw), '/') : null;
                                $img  = $path ? \Storage::url($path) : asset('images/noimage.png');
                            }
                        @endphp

                        <img src="{{ $img }}" alt="投稿画像" class="img-fluid rounded">
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
                    <a href="{{ route('post.edit', $post->id) }}" class="btn btn-primary btn-block">編集する</a>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
