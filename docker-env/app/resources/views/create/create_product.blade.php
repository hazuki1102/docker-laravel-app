@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                新規素材投稿
            </div>
        </div>
    </div>

    <form action="{{ route('create.product_conf') }}" method="POST" enctype="multipart/form-data">
    @csrf

        <div class="row justify-content-around">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">素材ファイル</div>
                    <div class="card-body text-center">
                        <input type="file" name="file" accept=".png,.jpg,.jpeg,.zip,.psd,.ai,.pdf" id="fileInput" required>
                        @error('file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <span id="fileName"></span>
                                <!-- 画像プレビューを表示 -->
                                <div id="imagePreview" class="mt-2">
                                    <img id="previewImg" src="#" alt="画像プレビュー" style="max-width: 100%; display: none;">
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">

                <div class="card mt-0">
                    <div class="card-header text-center">タイトル</div>
                    <div class="card-body">
                        <input type="text" name="title" class="form-control" maxlength="30" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">キャプション</div>
                    <div class="card-body">
                        <textarea name="caption" class="form-control" rows="4">{{ old('caption') }}</textarea>
                        @error('caption')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">ハッシュタグ</div>
                    <div class="card-body">
                        <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags') }}">
                        @error('tags')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">価格（円）</div>
                    <div class="card-body">
                        <input type="number" name="price" class="form-control" min="0" value="{{ old('price', 0) }}" required>
                        @error('price')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        <a href="{{ route('mypage') }}" class="btn btn-secondary btn-block">戻る</a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block">投稿内容確認</button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</main>
@endsection

@section('scripts')
<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileNameDisplay = document.getElementById('fileName');
    const previewImg = document.getElementById('previewImg');

    fileNameDisplay.textContent = file ? file.name : '';

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(event) {
            previewImg.src = event.target.result;
            previewImg.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewImg.style.display = 'none';
        previewImg.src = '#';
    }
});

document.getElementById('tags').addEventListener('input', function() {
    let val = this.value.trim();
    if (val && val[0] !== '#') {
        this.value = '#' + val;
    }
});
</script>
@endsection
