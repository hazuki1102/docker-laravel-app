@extends('layouts.layout')

@section('content')
<main class="py-4">

    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                素材編集
            </div>
        </div>
    </div>

    <form action="{{ route('product.edit_conf', $product->id) }}" method="POST" enctype="multipart/form-data" id="productEditForm">
        @csrf

        <div class="row justify-content-around">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">素材ファイル</div>
                        <div class="card-body text-center">
                            <input type="file" name="file" id="fileInput">
                            <p class="mt-2 mb-1">※変更しない場合はそのままでOK</p>
                            @error('file')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror

                            @php
                                $raw = $product->file_path ?? null;

                                if ($raw && \Illuminate\Support\Str::startsWith($raw, ['http://','https://'])) {
                                    $fileUrl = $raw;
                                } elseif ($raw && \Illuminate\Support\Str::startsWith($raw, ['/storage/'])) {
                                    $fileUrl = $raw;
                                } else {
                                    $path = $raw ? ltrim(preg_replace('#^(public/|storage/)#', '', $raw), '/') : null;
                                    $fileUrl = $path ? \Storage::url($path) : asset('images/noimage.png');
                                }
                            @endphp

                            <div class="mt-3">
                                <img id="filePreview"
                                    src="{{ $fileUrl }}"
                                    alt="現在の素材画像"
                                    style="max-width:100%; border-radius:8px;">
                                @if(!$raw)
                                    <p class="text-muted mt-2">現在のファイルが見つかりません</p>
                                @endif
                            </div>
                        </div>
                </div>
            </div>

            <div class="col-md-4">

                <div class="card mt-0">
                    <div class="card-header text-center">タイトル</div>
                    <div class="card-body">
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $product->title) }}" maxlength="30" required>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">説明文</div>
                    <div class="card-body">
                        <textarea name="caption" class="form-control" rows="4">{{ old('caption', $product->caption) }}</textarea>
                        @error('caption')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">タグ</div>
                    <div class="card-body">
                        <input type="text" name="tags" class="form-control"
                               value="{{ old('tags', $product->tags) }}">
                        @error('tags')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">価格（円）</div>
                    <div class="card-body">
                        <input type="number" name="price" class="form-control"
                               value="{{ old('price', $product->price) }}" min="0" required>
                        @error('price')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ボタン類 --}}
                <div class="row mt-4 mb-4">
                    <div class="col">
                        <a href="{{ route('product_delete_conf', $product->id) }}" class="btn btn-danger btn-block">素材を削除する</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block" id="submitBtn">編集内容確認</button>
                    </div>
                </div>

            </div>

        </div>

    </form>

</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('fileInput');
    const preview = document.getElementById('filePreview');
    if (input && preview) {
        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                preview.src = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; };
            reader.readAsDataURL(file);
        });
    }

    const form = document.getElementById('productEditForm');
    const btn  = document.getElementById('submitBtn');
    if (form && btn) {
        form.addEventListener('submit', () => {
            btn.disabled = true;
            btn.textContent = '送信中…';
        });
    }
});
</script>
@endpush
