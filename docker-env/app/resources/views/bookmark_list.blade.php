@extends('layouts.layout')

@section('content')
<main class="py-4">
  <div class="container">
    <h4 class="mb-3">ブックマーク一覧</h4>

    <div class="d-flex flex-wrap gap-3">
      @forelse($bookmarks as $bm)
        @php
          $isProduct = !is_null($bm->product_id);

          $targetId   = $isProduct ? $bm->product_id      : $bm->post_id;
          $title      = $isProduct ? $bm->product_title   : $bm->post_title;
          $rawPath    = $isProduct ? $bm->product_file    : $bm->post_image;
          $ownerId    = $isProduct ? $bm->product_user_id : $bm->post_user_id;

          if ($rawPath && \Illuminate\Support\Str::startsWith($rawPath, ['http://','https://'])) {
              $img = $rawPath;
          } else {
              $path = $rawPath ? ltrim(preg_replace('#^(public/|storage/)#','', $rawPath), '/') : null;
              $img  = $path ? \Storage::url($path) : asset('images/noimage.png');
          }

          if ($isProduct) {
              $link = (auth()->check() && auth()->id() === (int)$ownerId)
                      ? route('myproduct.show', $targetId)
                      : route('product.show', $targetId);
          } else {
              $link = (auth()->check() && auth()->id() === (int)$ownerId)
                      ? route('mypost.show', $targetId)
                      : route('posts.show', $targetId);
          }
        @endphp

        <a href="{{ $link }}" class="text-decoration-none" style="width:160px">
          <img src="{{ $img }}" class="img-fluid rounded mb-1" alt="">
          <div class="small text-truncate">{{ $title }}</div>
          <div class="text-muted small">{{ $isProduct ? '素材' : '投稿' }}</div>
        </a>
      @empty
        <p>ブックマークはありません。</p>
      @endforelse
    </div>

    <div class="mt-3 d-flex justify-content-center">
      {{ $bookmarks->links() }}
    </div>
  </div>
</main>
@endsection
