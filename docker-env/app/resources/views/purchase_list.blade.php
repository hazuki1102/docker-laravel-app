@extends('layouts.layout')

@section('content')
<main class="py-5">
    <div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 60vh;">

        <p class="text-muted mb-4" style="font-size: 1.2rem;">
            まだ購入製品はありません
        </p>

        <a href="{{ route('mypage') }}" class="btn btn-outline-primary">
            マイページに戻る
        </a>

    </div>
</main>
@endsection
