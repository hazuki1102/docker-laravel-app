@extends('layouts.layout')

@section('content')
<main class="py-4">

    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                新規イラスト投稿
            </div>
        </div>
    </div>

    <form action="{{ route('create.post_conf') }}" method="POST" enctype="multipart/form-data">
    @csrf

        <div class="row justify-content-around">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">投稿画像</div>
                    <div class="card-body text-center">
                        <input type="file" name="image" accept="image/*" id="imageInput" required>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <img id="imagePreview" src="#" alt="画像プレビュー" style="max-width:100%; display:none;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">

                <div class="card mt-0">
                    <div class="card-header text-center">タイトル</div>
                    <div class="card-body">
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" maxlength="30" required>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">キャプション</div>
                    <div class="card-body">
                        <textarea name="body" id="body" class="form-control" rows="4">{{ old('body') }}</textarea>
                        @error('body')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">ハッシュタグ</div>
                    <div class="card-body">
                        <input type="text" name="hashtags" id="hashtags" class="form-control" value="{{ old('hashtags') }}">
                        @error('hashtags')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">使用した素材（任意）</div>
                    <div class="card-body">
                        <input type="text" name="materials" id="materials" class="form-control" value="{{ old('materials') }}">
                        @error('materials')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block">戻る</a>
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
document.getElementById('imageInput').addEventListener('change', function(e) {
    const [file] = e.target.files;
    const preview = document.getElementById('imagePreview');
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
});
document.getElementById('hashtags').addEventListener('input', function() {
    let val = this.value.trim();
    if (val && val[0] !== '#') {
        this.value = '#' + val;
    }
});
</script>
@endsection
