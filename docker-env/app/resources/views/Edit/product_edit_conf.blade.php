@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">
        <h3 class="text-center mb-4">素材編集内容の確認</h3>

        <div class="card mx-auto" style="max-width:600px;">
            <div class="card-body text-center">
                @if (!empty($validated['tmp_file_path']))
                    <p>新しいファイルが選択されました</p>
                    @if (isset($validated['tmp_file_path']) && Str::endsWith($validated['tmp_file_path'], ['.jpg','.jpeg','.png','.gif']))
                        <img src="{{ asset('storage/'.$validated['tmp_file_path']) }}" alt="新しいファイル" style="max-width:100%;">
                    @endif
                @else
                    <p>ファイルは変更されていません</p>
                @endif
            </div>

            <div class="card-body">
                <p><strong>タイトル:</strong> {{ $validated['title'] }}</p>
                <p><strong>キャプション:</strong> {{ $validated['caption'] }}</p>
                <p><strong>タグ:</strong> {{ $validated['tags'] }}</p>
                <p><strong>価格:</strong> {{ $validated['price'] }}円</p>
            </div>

            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="title" value="{{ $validated['title'] }}">
                <input type="hidden" name="caption" value="{{ $validated['caption'] }}">
                <input type="hidden" name="tags" value="{{ $validated['tags'] }}">
                <input type="hidden" name="price" value="{{ $validated['price'] }}">
                @if (!empty($validated['tmp_file_path']))
                    <input type="hidden" name="tmp_file_path" value="{{ $validated['tmp_file_path'] }}">
                @endif

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary">更新する</button>
                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-secondary">戻る</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
