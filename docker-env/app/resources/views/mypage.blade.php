@extends('layouts.layout')

@section('content')
<main class="py-4">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card-body text-center">
                        マイページ
                    </div>
                </div>
            </div>

        <div class="row justify-content-around">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class='text-center'>[ブックマーク一覧]</div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-header">
                        <div class='text-center'>[購入製品一覧]</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-body text-center">
                    プロフィール<br>
                    画像
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class='text-center'>[新規投稿する]</div>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-header">
                        <div class='text-center'>[アカウント設定]</div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <div class="card-body text-center">
                    ユーザー名
                </div>
            </div>
        </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class='text-center'>[プロフィール]</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card-body text-center">
                 ■|■|■|■|■
            </div>
        </div>
    </div>

    <div class='text-center'>
        ＜1.2.3.次のページへ＞
    </div>

</main>
@endsection
