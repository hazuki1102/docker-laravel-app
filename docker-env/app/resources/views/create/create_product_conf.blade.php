@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">

        <h4 class="text-center mb-4">この内容で投稿しますか？</h4>

        <div class="card mx-auto" style="max-width:600px;">
            <div class="card-header text-center"><strong>素材プレビュー</strong></div>
            <div class="card-body text-center">
                @php
                    $extension = strtolower(pathinfo($data['file_path'] ?? '', PATHINFO_EXTENSION));
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                @endphp

                @if (!empty($data['image_base64']) && $isImage)
                    <img src="{{ $data['image_base64'] }}" alt="素材画像" class="img-fluid">
                @else
                    <p class="text-muted">画像形式ではないファイル（{{ basename($data['file_path']) }}）が選択されています。</p>
                @endif
            </div>

            <div class="card-body">
                <p><strong>タイトル：</strong>{{ $data['title'] }}</p>
                <p><strong>キャプション：</strong>{{ $data['caption'] ?? '（なし）' }}</p>
                <p><strong>ハッシュタグ：</strong>{{ $data['tags'] ?? '（なし）' }}</p>
                <p><strong>価格：</strong>{{ $data['price'] }} 円</p>
            </div>

            <form method="POST" action="{{ route('product.store') }}">
                @csrf
                <input type="hidden" name="file_path" value="{{ $data['file_path'] }}">
                <input type="hidden" name="title" value="{{ $data['title'] }}">
                <input type="hidden" name="caption" value="{{ $data['caption'] }}">
                <input type="hidden" name="tags" value="{{ $data['tags'] }}">
                <input type="hidden" name="price" value="{{ $data['price'] }}">

                <div class="row mt-4 mb-4">
                    <div class="col">
                        <a href="{{ route('create.create_product') }}" class="btn btn-secondary btn-block">編集に戻る</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block">投稿する</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</main>
@endsection
