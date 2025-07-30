@extends('layouts.layout')
@section('content')
<main class="py-4">

    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                どちらを投稿しますか？
            </div>
        </div>
    </div>

    <div class="row justify-content-around">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('create.create_post') }}" class="text-center">[イラスト投稿のみ]</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('create.create_product') }}" class="text-center">[素材販売/頒布]</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection