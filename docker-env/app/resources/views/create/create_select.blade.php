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
                <a href="{{ route('create.create_post') }}" class="text-decoration-none">
                    <div class="card mb-3" style="background-color: #fff; color: #000; border: 1px solid #000;">
                        <div class="card-header d-flex align-items-center justify-content-center" style="background-color: #fff; color: #000; border-bottom: 1px solid #000;">
                            <span>イラスト投稿のみ</span>
                        </div>
                    </div>
                </a>
        </div>




        <div class="col-md-4">
                    <a href="{{ route('create.create_product') }}" class="text-decoration-none">
                        <div class="card mb-3" style="background-color: #fff; color: #000; border: 1px solid #000;">
                            <div class="card-header d-flex align-items-center justify-content-center" style="background-color: #fff; color: #000; border-bottom: 1px solid #000;">
                                <span>素材販売/頒布</span>
                            </div>
                        </div>
                    </a>
        </div>
    </div>
</main>
@endsection