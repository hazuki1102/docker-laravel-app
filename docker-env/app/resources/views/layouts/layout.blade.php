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

                <div class="d-flex align-items-center">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="ロゴ" style="height: 50px;">
                    </a>
                </div>

                <form action="{{ route('search.post_search') }}" method="GET" style="max-width: 700px; flex-grow: 1;" class="d-flex align-items-center ms-4">
                    <input type="text" name="keyword" placeholder="検索ワードを入力" class="form-control me-2" style="flex-grow: 1;">
                    <button class="btn btn-outline-secondary" style="min-width: 80px; white-space: nowrap;">さがす</button>
                </form>


                <div class="my-navber-control ms-4">
                    @if(Auth::check())
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
    @yield('scripts')
</body>
</html>