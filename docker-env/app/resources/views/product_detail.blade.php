@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                素材詳細
            </div>
        </div>
    </div>

    <div class="row justify-content-around">

        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">素材画像</div>
                <div class="card-body text-center">
                    <img src="{{ url($product->file_path) }}" alt="素材画像" class="img-fluid rounded">
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
                    <p class="mb-0">{{ $product->price }} 円</p>
                </div>
            </div>

            <div class="row mt-4 mb-4">
                <div class="col">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block">戻る</a>
                </div>
                <div class="col">
                    <form action="{{ route('bookmark.store', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-block">ブックマークする</button>
                    </form>
                </div>
            </div>

            {{-- カートに入れるボタン --}}
            <div class="row mb-4">
                <div class="col">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">カートに入れる</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
