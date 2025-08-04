@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">
        <h3 class="text-center mb-4">ブックマーク一覧</h3>

        @if ($bookmarks->count() > 0)
            <div class="row">
                @foreach ($bookmarks as $post)
                    <div class="col-md-2 mb-4 text-center">
                        <a href="{{ route('posts.show', $post->id) }}">
                            <img src="{{ url($post->image_path) }}" class="img-thumbnail" style="aspect-ratio: 1/1; object-fit: cover;">
                        </a>
                        <div class="mt-1">
                            <small class="d-block text-truncate">{{ $post->title }}</small>
                            <small class="text-muted">{{ $post->user->username }}</small>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                {{ $bookmarks->links() }}
            </div>
        @else
            <p class="text-center">ブックマークはまだありません。</p>
        @endif

    </div>
</main>
@endsection
