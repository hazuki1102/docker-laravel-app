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

            @php use Illuminate\Support\Str; @endphp

            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @forelse ($posts ?? '' as $post)
                        @php
                            $isProduct = $post->type === 'product';
                            $imageUrl = Str::startsWith($post->image_path, 'http') ? $post->image_path : asset($post->image_path);
                            if ($isProduct) {
                                $link = Auth::check() && Auth::id() === $post->user_id
                                    ? route('myproduct.show', $post->id)
                                    : route('product.show', $post->id);
                            } else {
                                $link = Auth::check() && Auth::id() === $post->user_id
                                    ? route('mypost.show', $post->id)
                                    : route('posts.show', $post->id);
                            }
                            $liked = isset($likedIds) ? in_array($post->id, $likedIds) : false;
                        @endphp

                        <div style="flex: 0 0 18%; max-width: 18%;" class="text-center position-relative">

                            <button
                                type="button"
                                class="btn btn-light btn-sm position-absolute"
                                style="top:6px; right:6px; border-radius:9999px; padding:4px 8px;"
                                data-like
                                data-post-id="{{ $post->id }}"
                                data-endpoint="{{ route('posts.like', $post->id) }}"
                                data-auth="{{ auth()->check() ? '1' : '0' }}"
                                aria-pressed="{{ $liked ? 'true' : 'false' }}"
                                title="いいね"
                            >
                                <span data-like-icon>{{ $liked ? '❤️' : '🤍' }}</span>
                            </button>

                            <a href="{{ $link }}">
                                <img src="{{ $imageUrl }}" alt="投稿画像"
                                    class="img-thumbnail rounded"
                                    style="aspect-ratio: 1/1; object-fit: cover;">
                            </a>

                            <div class="mt-1">
                                <small class="d-block text-truncate">{{ $post->title }}</small>
                                <small class="text-muted">{{ $post->user->username }}</small>
                                <div>
                                <small>
                                    いいね
                                    @if ($post instanceof \App\Models\Post)
                                    <span data-like-count="{{ $post->id }}">{{ $post->likes_count ?? $post->likes()->count() }}</span>
                                    @else
                                    <span>-</span>
                                    @endif
                                </small>
                                </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.querySelectorAll('[data-like]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();

            if (btn.dataset.auth !== '1') {
                window.location.href = "{{ route('login') }}";
                return;
            }

            const endpoint = btn.dataset.endpoint;
            const postId = btn.dataset.postId;
            const iconEl = btn.querySelector('[data-like-icon]');
            const countEl = document.querySelector(`[data-like-count="${postId}"]`);


            const currentlyLiked = btn.getAttribute('aria-pressed') === 'true';
            btn.disabled = true;

            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!res.ok) throw new Error('Network response was not ok');

                const data = await res.json();

                btn.setAttribute('aria-pressed', data.liked ? 'true' : 'false');
                if (iconEl) iconEl.textContent = data.liked ? '❤️' : '🤍';
                if (countEl) countEl.textContent = data.likes_count;

            } catch (err) {
                btn.setAttribute('aria-pressed', currentlyLiked ? 'true' : 'false');
                if (iconEl) iconEl.textContent = currentlyLiked ? '❤️' : '🤍';
                console.error(err);
                alert('いいねの更新に失敗しました。時間をおいて再度お試しください。');
            } finally {
                btn.disabled = false;
            }
        });
    });
});
</script>
@endpush

@endsection
