@extends('layouts.layout')

@section('content')
<main class="py-4">

    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-md-9 text-center">
                <h2>マイページ</h2>
            </div>
        </div>

        <div class="row justify-content-around">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header text-center">[ブックマーク一覧]</div>
                </div>
                <div class="card">
                    <div class="card-header text-center">[購入製品一覧]</div>
                </div>
            </div>

            <div class="col-md-4 text-center">
                <div class="card mb-3 p-3">
                    {{-- プロフィール画像 --}}
                    <img src="{{ asset('storage/' . ($user->icon_path ?? 'sample_icon.png')) }}" alt="プロフィール画像" class="rounded-circle mx-auto d-block" style="width: 140px; height: 140px; object-fit: cover;">

                </div>

                <div class="card mb-3 p-3">
                    {{-- ユーザー名 --}}
                    <h4>{{ $user->username }}</h4>
                </div>

                <div class="card p-3">
                    {{-- プロフィール文 --}}
                    <p>{{ $user->profile }}</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header text-center">
                        <a href="{{ route('create.create_select') }}" class="text-decoration-none">
                            新規投稿する
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header text-center">
                        <a href="{{ route('user.user_edit', ['id' => Auth::id()]) }}" class="text-decoration-none">
                            アカウント設定
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 自分の投稿イラスト一覧 --}}
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card p-3">
                    <h5 class="text-center mb-4">自分の投稿イラスト一覧</h5>

                    <style>
                        .posts-container {
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: center;
                            gap: 12px;
                        }
                        .post-image-wrapper {
                            flex: 0 0 18%;
                            max-width: 120px;
                            text-align: center;
                        }
                        .post-image-wrapper img {
                            width: 100%;
                            aspect-ratio: 1 / 1;
                            object-fit: cover;
                            border-radius: 10px;
                        }
                        .post-title {
                            margin-top: 6px;
                            font-size: 0.9rem;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        }
                    </style>

                    <div class="posts-container">
                        @forelse ($posts as $post)
                            <div class="post-image-wrapper">
                                <img src="{{ url($post->image_path) }}" alt="投稿画像">
                                <div class="post-title" title="{{ $post->title }}">{{ $post->title }}</div>
                            </div>
                        @empty
                            <p class="text-center w-100">投稿がまだありません。</p>
                        @endforelse
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

</main>
@endsection
