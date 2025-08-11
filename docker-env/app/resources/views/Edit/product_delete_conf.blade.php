@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">

        <div class="card mx-auto" style="max-width:600px;">
            <div class="card-header text-center">
                素材削除の確認
            </div>

            <div class="card-body">

                <div class="text-center mb-4">
                    @php
                        $isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $product->file_path ?? '');
                        $fileUrl = \Illuminate\Support\Str::startsWith($product->file_path, 'http')
                            ? $product->file_path
                            : asset($product->file_path);
                    @endphp

                    @if($isImage)
                        <img src="{{ $fileUrl }}" alt="素材画像" class="img-fluid rounded" style="max-height:300px;">
                    @else
                        <p class="text-muted">現在のファイル: {{ basename($product->file_path) }}</p>
                    @endif
                </div>

                <div>
                    <p><strong>タイトル：</strong>{{ $product->title }}</p>
                    <p><strong>説明文：</strong>{{ $product->caption ?? '（未入力）' }}</p>
                    <p><strong>タグ：</strong>{{ $product->tags ?? '（未入力）' }}</p>
                    <p><strong>価格：</strong>{{ number_format($product->price) }} 円</p>
                </div>

                <form action="{{ route('product.delete', $product->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')

                    <div class="row">
                        <div class="col">
                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-secondary btn-block">編集画面に戻る</a>
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
