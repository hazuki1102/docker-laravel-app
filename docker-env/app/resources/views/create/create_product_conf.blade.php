@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                この内容で投稿しますか？
            </div>
        </div>
    </div>

    <div class="row justify-content-around">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">素材ファイル</div>
                <div class="card-body text-center">
                    @if (!empty($data['file_path']))
                        <a href="{{ asset('storage/' . $data['file_path']) }}" target="_blank">
                            {{ basename($data['file_path']) }} を表示
                        </a>
                    @else
                        <p class="text-muted">ファイル未指定</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card">
                <div class="card-header text-center">タイトル</div>
                <div class="card-body text-center">
                    {{ $data['title'] }}
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header text-center">キャプション</div>
                <div class="card-body text-center">
                    {!! nl2br(e($data['caption'])) !!}
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header text-center">ハッシュタグ</div>
                <div class="card-body text-center">
                    {{ $data['tags'] ?? 'なし' }}
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header text-center">価格</div>
                <div class="card-body text-center">
                    {{ $data['price'] }} 円
                </div>
            </div>

            <form action="{{ route('product.store') }}" method="POST" class="mt-4 mb-4">
                @csrf
                <div class="row">
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
