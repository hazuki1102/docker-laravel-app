@extends('layouts.layout')

@section('content')
<main class="py-4">
  <div class="container">

    <form action="{{ route('search.post_search') }}" method="GET" class="card mb-3">
      <div class="card-body row g-2">
        <div class="col-md-3">
          <label class="form-label mb-1">タイトル</label>
          <input type="text" name="title" class="form-control"
                 value="{{ old('title', $filters['title'] ?? '') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label mb-1">ユーザー名</label>
          <input type="text" name="username" class="form-control"
                 value="{{ old('username', $filters['username'] ?? '') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label mb-1">本文</label>
          <input type="text" name="body" class="form-control"
                 value="{{ old('body', $filters['body'] ?? '') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label mb-1">対象</label>
          @php $type = $filters['type'] ?? 'all'; @endphp
          <select name="type" class="form-control">
            <option value="all"     {{ $type==='all'?'selected':'' }}>すべて</option>
            <option value="post"    {{ $type==='post'?'selected':'' }}>投稿(Post)</option>
            <option value="product" {{ $type==='product'?'selected':'' }}>素材(Product)</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label mb-1">投稿日（自）</label>
          <input type="date" name="date_from" class="form-control"
                 value="{{ old('date_from', $filters['date_from'] ?? '') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label mb-1">投稿日（至）</label>
          <input type="date" name="date_to" class="form-control"
                 value="{{ old('date_to', $filters['date_to'] ?? '') }}">
        </div>
        <div class="col-12 mt-2">
          <button class="btn btn-primary">検索</button>
          <a href="{{ route('search.post_search') }}" class="btn btn-outline-secondary">クリア</a>
        </div>
      </div>
    </form>

    @if(!empty($keyword))
      <div class="card shadow-sm mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">キーワード検索結果：「{{ $keyword }}」</h5>
        </div>

        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-center gap-3">
            @forelse ($kwPosts as $post)
              @php
                $img = \Illuminate\Support\Str::startsWith($post->image_path, 'http')
                      ? $post->image_path : asset($post->image_path);
                $isProduct = ($post->type ?? null) === 'product' || $post instanceof \App\Models\Product;
                $detailUrl = $isProduct ? route('product.show', $post->id) : route('posts.show', $post->id);
              @endphp

              <div style="flex:0 0 18%; max-width:18%;" class="text-center">
                <a href="{{ $detailUrl }}">
                  <img src="{{ $img }}" alt="画像" class="img-thumbnail rounded"
                      style="aspect-ratio:1/1; object-fit:cover;">
                </a>
                <div class="mt-1">
                  <small class="d-block text-truncate">{{ $post->title }}</small>
                  <small class="text-muted">{{ $post->user->username ?? '' }}</small>
                </div>
              </div>
            @empty
              <p class="text-center w-100">「{{ $keyword }}」に一致する結果はありませんでした。</p>
            @endforelse
          </div>
        </div>

        <div class="text-center mt-3">
          {{ $kwPosts->appends(request()->query())->links() }}
        </div>
      </div>
    @endif


  </div>
</main>
@endsection
