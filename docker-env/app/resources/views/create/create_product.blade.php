@extends('layouts.layout')
@section('content')
<main class="py-4">

    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-body text-center">
                新規素材投稿
            </div>
        </div>
    </div>

    <div class="row justify-content-around">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class='text-center'>[投稿画像表示]</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class='text-center'>タイトル</div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <div class='text-center'>キャプション</div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <div class='text-center'>ハッシュタグ</div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <div class='text-center'>価格</div>
                </div>
            </div>

            <div class="row mt-4 mb-4">
                <div class="col">
                    <div class="card">
                        <div class="card-header text-center">
                            戻る
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <a href="{{ route('create.product_conf') }}" class="text-center">投稿内容確認</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection