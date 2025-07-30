@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">

        <h4 class="text-center mb-4">この内容で投稿しますか？</h4>

        <div class="card mx-auto" style="max-width:600px;">
            <div class="card-header text-center"><strong>投稿画像</strong></div>
            <div class="card-body text-center">
                <img src="{{ Storage::url($data['temp_image_path']) }}" alt="投稿画像" class="img-fluid">
            </div>
            <div class="card-body">
                <p><strong>タイトル：</strong>{{ $data['title'] }}</p>
                <p><strong>キャプション：</strong>{{ $data['body'] }}</p>
                <p><strong>ハッシュタグ：</strong>{{ $data['hashtags'] }}</p>
                <p><strong>使用した素材：</strong>{{ $data['materials'] }}</p>
            </div>

            <form method="POST" action="{{ route('posts.store') }}">
                @csrf
                <input type="hidden" name="temp_image_path" value="{{ $data['temp_image_path'] }}">
                <input type="hidden" name="title" value="{{ $data['title'] }}">
                <input type="hidden" name="body" value="{{ $data['body'] }}">
                <input type="hidden" name="hashtags" value="{{ $data['hashtags'] }}">
                <input type="hidden" name="materials" value="{{ $data['materials'] }}">

                <div class="row mt-4 mb-4">
                    <div class="col">
                        <a href="{{ route('posts.create') }}" class="btn btn-secondary btn-block">編集に戻る</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block">投稿</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</main>
@endsection
