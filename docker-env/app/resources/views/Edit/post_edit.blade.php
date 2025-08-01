@extends('layouts.layout')

@section('content')
<main class="py-4">

    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                投稿編集
            </div>
        </div>
    </div>

    <form action="{{ route('post.edit_conf', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row justify-content-around">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">投稿画像</div>
                    <div class="card-body text-center">
                        <input type="file" name="image" accept="image/*" id="imageInput">
                        <p class="mt-2 mb-1">※画像を変更しない場合はそのままでOK</p>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <img id="imagePreview" src="{{ url($post->image_path) }}" alt="現在の投稿画像" style="max-width:100%;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">

                <div class="card mt-0">
                    <div class="card-header text-center">タイトル</div>
                    <div class="card-body">
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" maxlength="30" required>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">キャプション</div>
                    <div class="card-body">
                        <textarea name="body" id="body" class="form-control" rows="4">{{ old('body', $post->body) }}</textarea>
                        @error('body')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">ハッシュタグ</div>
                    <div class="card-body">
                        <input type="text" name="hashtags" id="hashtags" class="form-control" value="{{ old('hashtags', $post->hashtags) }}">
                        @error('hashtags')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">使用した素材（任意）</div>
                    <div class="card-body">
                        <input type="text" name="materials" id="materials" class="form-control" value="{{ old('materials', $post->materials) }}">
                        @error('materials')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        <a href="{{ route('post_delete_conf', $post->id) }}" class="btn btn-danger btn-block">投稿を削除する</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block">編集内容確認</button>
                    </div>
                </div>

            </div>

        </div>

    </form>

</main>
@endsection
