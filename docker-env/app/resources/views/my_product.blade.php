@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                自分の素材投稿詳細
            </div>
        </div>
    </div>

    <div class="row justify-content-around">

        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">素材ファイル</div>
                <div class="card-body text-center">
                    @php
                        $ext = pathinfo($product->file_path, PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                        $fileUrl = asset($product->file_path);
                    @endphp

                    @if ($isImage)
                        <img src="{{ $fileUrl }}" alt="素材画像" class="img-fluid rounded">
                    @else
                        <a href="{{ $fileUrl }}" target="_blank">{{ basename($product->file_path) }} を開く</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card mt-0">
                <div class="card-header text-center">タイトル</div>
                <div class="card-body">
                    <p class="mb-0">{{ $product->title }}</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header text-center">キャプション</div>
                <div class="card-body">
                    <p class="mb-0">{{ $product->caption ?? '（未入力）' }}</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header text-center">タグ</div>
                <div class="card-body">
                    <p class="mb-0">{{ $product->tags ?? '（未入力）' }}</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header text-center">価格</div>
                <div class="card-body">
                    <p class="mb-0">{{ number_format($product->price) }} 円</p>
                </div>
            </div>

            <div class="row mt-4 mb-4">
                <div class="col">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block">戻る</a>
                </div>
                {{-- 編集機能を後で作る場合はこちらを有効化 --}}
                {{-- <div class="col">
                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary btn-block">編集する</a>
                </div> --}}
            </div>

        </div>
    </div>
</main>
@endsection
