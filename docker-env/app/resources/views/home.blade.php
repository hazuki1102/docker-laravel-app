@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">みんなの投稿一覧</h5>
                <a
                    @auth
                        href="{{ route('create.create_select') }}"
                    @else
                        href="{{ route('login') }}"
                    @endauth
                    class="btn btn-outline-primary btn-sm"
                >
                    新規投稿する
                </a>
            </div>

            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @forelse ($posts ?? '' as $post)
                        @php
                            $isProduct = $post->type === 'product';
                            $imageUrl = Str::startsWith($post->image_path, 'http')
                                ? $post->image_path
                                : asset($post->image_path);

                            if ($isProduct) {
                                $link = Auth::check() && Auth::id() === $post->user_id
                                    ? route('myproduct.show', $post->id)
                                    : route('product.show', $post->id);
                            } else {
                                $link = Auth::check() && Auth::id() === $post->user_id
                                    ? route('mypost.show', $post->id)
                                    : route('posts.show', $post->id);
                            }
                        @endphp

                        <div style="flex: 0 0 18%; max-width: 18%;" class="text-center">
                            <a href="{{ $link }}">
                                <img src="{{ $imageUrl }}" alt="投稿画像"
                                    class="img-thumbnail rounded"
                                    style="aspect-ratio: 1/1; object-fit: cover;">
                            </a>

                            <div class="mt-1">
                                <small class="d-block text-truncate">{{ $post->title }}</small>
                                <small class="text-muted">{{ $post->user->username }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center w-100">投稿がまだありません。</p>
                    @endforelse
                </div>
            </div>

            <div class="row mt-4">
                <div class="col d-flex justify-content-center">
                    {{ $posts->links() }}
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
