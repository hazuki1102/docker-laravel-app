<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DrawStock') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('stylesheet')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container d-flex justify-content-between align-items-center">

                {{-- 左：ロゴ --}}
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40">
                </a>

                {{-- 中央：検索フォーム --}}
                <div style="flex: 1; max-width: 500px;" class="mx-3">
                    <input type="text" placeholder="検索ワードを入力" class="form-control" style="display: inline-block; width: 70%;">
                    <button class="btn btn-outline-secondary" style="width: 28%;">さがす</button>
                </div>

                {{-- 右：ログイン/ログアウト/登録 --}}
                <div class="my-navber-control">
                    @if(Auth::check())
                        {{-- ユーザー名をリンクにしてmypageへ飛ばす --}}
                        <a href="{{ route('mypage') }}" class="my-navber-item">{{ Auth::user()->username }}</a>
                        /
                        <a href="#" id="logout" class="my-navber-item">ログアウト</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <script>
                            document.getElementById('logout').addEventListener('click', function(event){
                                event.preventDefault();
                                document.getElementById('logout-form').submit();
                            });
                        </script>
                    @else
                        <a class="my-navber-item" href="{{ route('login') }}">ログイン</a>
                        /
                        <a class="my-navber-item" href="{{ route('register') }}">会員登録</a>
                    @endif
                </div>
            </div>
        </nav>

        @yield('content')
    </div>
</body>
</html>