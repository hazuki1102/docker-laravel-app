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
                        <div style="flex: 0 0 18%; max-width: 120px;" class="text-center">
                            <a href="
                                @auth
                                    {{ Auth::id() === $post->user_id ? route('mypost.show', $post->id) : route('posts.show', $post->id) }}
                                @else
                                    {{ route('posts.show', $post->id) }}
                                @endauth
                            ">
                                <img src="{{ url($post->image_path) }}" alt="投稿画像"
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

        <div class="text-center mt-4">
            {{ $posts ?? ''->links() }}
            <div class="mt-2">＜1.2.3.次のページへ＞</div>
        </div>
    </div>
</main>
@endsection
