<main class="py-4">
    <div class="container">
        <h3 class="text-center mb-4">アカウント情報の確認</h3>

        @if (!empty($data['icon_path']))
            <div class="mb-3 text-center">
                <img src="{{ asset('storage/' . $data['icon_path']) }}" alt="新しいアイコン" class="rounded-circle" width="120" height="120">
            </div>
        @else
            <div class="mb-3 text-center">
                <img src="{{ asset('storage/sample_icon.png') }}" alt="現在のアイコン" class="rounded-circle" width="120" height="120">
            </div>
        @endif

        <div class="mb-3">
            <strong>ユーザー名：</strong> {{ $data['name'] }}
        </div>

        <div class="mb-3">
            <strong>メールアドレス：</strong> {{ $data['email'] }}
        </div>

        <div class="mb-3">
            <strong>パスワード：</strong> ********（非表示）
        </div>

        <div class="mb-3">
            <strong>プロフィール：</strong><br>
            {!! nl2br(e($data['profile'] ?? '（未入力）')) !!}
        </div>

        <form action="{{ route('user.update') }}" method="POST">
            @csrf
            <div class="d-flex justify-content-between">
                <div class="btn btn-secondary">編集画面に戻る</a>
                <button type="submit" class="btn btn-primary">登録する</button>
            </div>
        </form>
    </div>
</main>
