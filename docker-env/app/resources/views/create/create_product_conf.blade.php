@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">

        <div class="row justify-content-center mb-4">
            <div class="col-md-9 text-center">
                <h4>この内容で投稿しますか？</h4>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">

                    <div class="card-header text-center">
                        <strong>投稿画像</strong>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/sample_image.jpg') }}" alt="投稿画像" class="img-fluid">
                    </div>

                    <div class="card-body">
                        <p><strong>タイトル：</strong>サンプルタイトル</p>

                        <p><strong>キャプション：</strong>これはキャプションのサンプルです。</p>

                        <p><strong>ハッシュタグ：</strong>#sample #example</p>

                        <p><strong>価格：</strong>00000000￥</p>
                    </div>

                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="card">
                                    <div class="card-header text-center">
                                        編集に戻る
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <div class="card-header text-center">投稿</div>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
        </div>

    </div>
</main>
@endsection
