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

                <a href="{{ route('bookmark_list') }}" class="text-decoration-none">
                    <div class="card mb-3" style="background-color: #fff; color: #000; border: 3px solid #000;">
                        <div class="card-header d-flex align-items-center justify-content-center" style="background-color: #fff; color: #000; border-bottom: 1px solid #000;">
                            <img src="{{ asset('images/bookmark_logo.png') }}" alt="ブックマーク一覧" style="width: 20px; height:30px; margin-right:6px;">
                            <span>ブックマーク一覧</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('purchase_list') }}" class="text-decoration-none">
                    <div class="card mb-3" style="background-color: #fff; color: #000; border: 3px solid #000;">
                        <div class="card-header d-flex align-items-center justify-content-center" style="background-color: #fff; color: #000; border-bottom: 1px solid #000;">
                            <img src="{{ asset('images/purchase_logo.png') }}" alt="購入製品一覧" style="width: 30px; height:30px; margin-right:6px;">
                            <span>購入製品一覧</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('cart.list') }}" class="text-decoration-none">
                    <div class="card" style="background-color: #fff; color: #000; border: 3px solid #000;">
                        <div class="card-header d-flex align-items-center justify-content-center" style="background-color: #fff; color: #000; border-bottom: 1px solid #000;">
                            <img src="{{ asset('images/cart_logo.png') }}" alt="カート" style="width: 30px; height:30px; margin-right:6px;">
                            <span>カート</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 text-center">
                <div class="card mb-3 p-3">
                    @php
                        $rawIcon = $user->icon_path ?? null;
                        if ($rawIcon && \Illuminate\Support\Str::startsWith($rawIcon, ['http://','https://'])) {
                            $icon = $rawIcon;
                        } else {
                            $iconPath = $rawIcon ? ltrim(preg_replace('#^public/#','', $rawIcon), '/') : null;
                            $icon = $iconPath ? \Storage::url($iconPath) : asset('images/sample_icon.png');
                        }
                    @endphp
                    <img src="{{ $icon }}" alt="プロフィール画像"
                        class="rounded-circle mx-auto d-block"
                        style="width: 140px; height: 140px; object-fit: cover;">
                </div>


                <div class="card mb-3 p-3">
                    <h4>{{ $user->username }}</h4>
                </div>

                <div class="card p-3">
                    <p>{{ $user->profile }}</p>
                </div>
            </div>

            <div class="col-md-4">

                <a href="{{ route('create.create_select') }}" class="text-decoration-none">
                    <div class="card mb-3" style="background-color: #fff; color: #000; border: 3px solid #000;">
                        <div class="card-header d-flex align-items-center justify-content-center" style="background-color: #fff; color: #000; border-bottom: 1px solid #000;">
                            <img src="{{ asset('images/post_logo.png') }}" alt="新規投稿する" style="width: 30px; height:30px; margin-right:6px;">
                            <span>新規投稿する</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('user.edit', ['id' => $user->id]) }}">
                    <div class="card" style="background-color: #fff; color: #000; border: 3px solid #000;">
                        <div class="card-header d-flex align-items-center justify-content-center" style="background-color: #fff; color: #000; border-bottom: 1px solid #000;">
                            <img src="{{ asset('images/mypage_logo.png') }}" alt="アカウント設定" style="width: 50px; height:50px; margin-right:6px;">
                            <span>アカウント設定</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

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
                                @php
                                    $raw = $post->image_url ?? $post->image_path;
                                    if ($raw && \Illuminate\Support\Str::startsWith($raw, ['http://','https://'])) {
                                        $img = $raw;
                                    } else {
                                        $path = $raw ? ltrim(preg_replace('#^public/#','', $raw), '/') : null;
                                        $img  = $path ? \Storage::url($path) : asset('images/noimage.png');
                                    }
                                @endphp

                                <a href="{{ route('mypost.show', $post->id) }}" class="d-block text-decoration-none">
                                    <img src="{{ $img }}" alt="投稿画像">
                                    <div class="post-title" title="{{ $post->title }}">{{ $post->title }}</div>
                                </a>
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
