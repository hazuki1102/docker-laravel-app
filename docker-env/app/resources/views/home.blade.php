@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="row justify-content-around">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class='text-center'>[みんなの投稿一覧]</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <a
                    @auth
                        href="{{ route('create.create_select') }}"
                    @else
                        href="{{ route('login') }}"
                    @endauth
                    class="text-decoration-none text-dark"
                >
                    <div class="card-header">
                        <div class='text-center'>[新規投稿する]</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card-body text-center">
                 ■|■|■|■|■<br>
                 ■|■|■|■|■
            </div>
        </div>
    </div>

    <div class='text-center'>
        ＜1.2.3.次のページへ＞
    </div>
</main>
@endsection
