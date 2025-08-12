@extends('layouts.layout')

@section('content')
<main class="py-4" style="background:#f8f9fa;">

  <div class="container">

    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">みんなの投稿一覧
          <img src="{{ asset('images/post_image.gif') }}" alt="みんなの投稿一覧" style="width: 100px; height:60px; margin-right:6px;">
        </h5>
        <a href="{{ route('create.create_select') }}" class="text-decoration-none">
          <div class="card mb-3" style="background-color:#fff;color:#000;border:2px solid #000;">
            <div class="card-header d-flex align-items-center justify-content-center" style="background-color:#fff;color:#000;border-bottom:1px solid #000;">
              <img src="{{ asset('images/post_logo.png') }}" alt="新規投稿する" style="width:20px;height:20px;margin-right:6px;">
              <span>新規投稿する</span>
            </div>
          </div>
        </a>
      </div>

      <div class="card-body">
        @php
          $isPaginator = $posts instanceof \Illuminate\Contracts\Pagination\Paginator
                      || $posts instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
          $iterable = $isPaginator ? $posts
                    : ($posts instanceof \Illuminate\Support\Collection ? $posts : collect());
        @endphp

        <div class="d-flex flex-wrap justify-content-center gap-3">
        @forelse ($iterable as $post)
            @php
                $isProduct = ($post->type ?? null) === 'product';
                $isMine    = auth()->check() && auth()->id() === (int) $post->user_id;

                $rawPath = $isProduct
                    ? ($post->image_url ?? $post->image_path ?? $post->file_path ?? null)
                    : ($post->image_url ?? $post->image_path ?? null);

                if ($rawPath && \Illuminate\Support\Str::startsWith($rawPath, ['http://','https://'])) {
                    $imageUrl = $rawPath;
                } else {
                    $path     = $rawPath ? ltrim(preg_replace('#^public/#','', $rawPath), '/') : null;
                    $imageUrl = $path ? \Storage::url($path) : asset('images/noimage.png');
                }

                if ($isProduct) {
                    $link = $isMine ? route('myproduct.show', $post->id)
                                    : route('product.show',  $post->id);
                } else {
                    $link = $isMine ? route('mypost.show', $post->id)
                                    : route('posts.show',  $post->id);
                }

                $isLiked = $isProduct
                    ? in_array($post->id, $likedProductIds ?? [], true)
                    : in_array($post->id, $likedPostIds ?? [], true);

                $likeCount = $post->likes_count
                    ?? (method_exists($post, 'likes') ? $post->likes()->count() : 0);

                $likeEndpoint = $isProduct
                    ? route('products.like', $post->id)
                    : route('posts.like',    $post->id);
            @endphp


          <div style="flex:0 0 18%; max-width:18%;" class="text-center position-relative">
            <button
              type="button"
              class="btn btn-light btn-sm position-absolute"
              style="top:6px; right:6px; border-radius:9999px; padding:4px 8px;"
              data-like
              data-post-id="{{ $post->id }}"
              data-endpoint="{{ $isProduct ? route('products.like', $post->id) : route('posts.like', $post->id) }}"
              data-auth="{{ auth()->check() ? '1' : '0' }}"
              aria-pressed="{{ $isLiked ? 'true' : 'false' }}"
              title="いいね">
              <span data-like-icon>{{ $isLiked ? '❤️' : '🤍' }}</span>
            </button>

            <a href="{{ $link }}">
              <img src="{{ $imageUrl }}" alt="投稿画像" class="img-thumbnail rounded">
            </a>

            <div class="mt-1">
              <small class="d-block text-truncate">{{ $post->title }}</small>
              <small class="text-muted">{{ $post->user->username ?? '不明' }}</small>
              <div>
                <small>いいね <span data-like-count="{{ $post->id }}">{{ $likeCount }}</span></small>
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
            @php
            $isPaginator = $posts instanceof \Illuminate\Contracts\Pagination\Paginator
                        || $posts instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
            @endphp

            @if ($isPaginator && method_exists($posts, 'withQueryString'))
            {{ $posts->withQueryString()->links() }}
            @elseif (method_exists($posts, 'links'))
            {{ $posts->links() }}
            @endif
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
      if (btn.dataset.auth !== '1') { window.location.href = "{{ route('login') }}"; return; }

      const endpoint = btn.dataset.endpoint;
      const postId   = btn.dataset.postId;
      const iconEl   = btn.querySelector('[data-like-icon]');
      const countEl  = document.querySelector(`[data-like-count="${postId}"]`);
      const wasLiked = btn.getAttribute('aria-pressed') === 'true';

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
        if (iconEl)  iconEl.textContent = data.liked ? '❤️' : '🤍';
        if (countEl) countEl.textContent = data.likes_count;
      } catch (err) {
        btn.setAttribute('aria-pressed', wasLiked ? 'true' : 'false');
        if (iconEl)  iconEl.textContent = wasLiked ? '❤️' : '🤍';
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
