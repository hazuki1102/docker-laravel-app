@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">

        <h4 class="text-center mb-4">この内容で投稿しますか？</h4>

        <div class="card mx-auto" style="max-width:600px;">
            <div class="card-header text-center"><strong>投稿画像</strong></div>

            <div class="card-body text-center">
                @if (!empty($data['image_base64']))
                    <img src="{{ $data['image_base64'] }}" alt="投稿画像" class="img-fluid">
                @else
                    <div class="text-muted">画像がありません</div>
                @endif
            </div>

            <div class="card-body">
                <p><strong>タイトル：</strong>{{ $data['title'] }}</p>
                <p><strong>キャプション：</strong>{{ $data['body'] }}</p>
                <p><strong>ハッシュタグ：</strong>{{ $data['hashtags'] }}</p>
                <p><strong>使用した素材：</strong>{{ $data['materials'] }}</p>
            </div>

            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <form method="POST" action="{{ route('create.post_back') }}">
                            @csrf
                            <input type="hidden" name="temp_image_path" value="{{ $data['temp_image_path'] }}">
                            <input type="hidden" name="image_base64"    value="{{ $data['image_base64'] ?? '' }}">
                            <input type="hidden" name="title"           value="{{ $data['title'] }}">
                            <input type="hidden" name="body"            value="{{ $data['body'] }}">
                            <input type="hidden" name="hashtags"        value="{{ $data['hashtags'] }}">
                            <input type="hidden" name="materials"       value="{{ $data['materials'] }}">
                            <button type="submit" class="btn btn-secondary w-100">編集に戻る</button>
                        </form>
                    </div>

                    <div class="col-12 col-md-6">
                        <form method="POST" action="{{ route('posts.store') }}">
                            @csrf
                            <input type="hidden" name="temp_image_path" value="{{ $data['temp_image_path'] }}">
                            <input type="hidden" name="title"           value="{{ $data['title'] }}">
                            <input type="hidden" name="body"            value="{{ $data['body'] }}">
                            <input type="hidden" name="hashtags"        value="{{ $data['hashtags'] }}">
                            <input type="hidden" name="materials"       value="{{ $data['materials'] }}">
                            <button type="submit" class="btn btn-primary w-100">投稿</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
