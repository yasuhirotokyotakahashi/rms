<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
    @stack('styles')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__logo" href="/">Rese</a>
                @if (Request::path() === '/' || Request::is('shops/search'))
                    <!-- 店舗一覧画面のパスに応じて条件分岐 -->
                    <form class="search-form" action="{{ route('shops.search') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="area">エリア:</label>
                            <input type="text" name="area" id="area" value="{{ request('area') }}">
                        </div>
                        <div class="form-group">
                            <label for="genre">ジャンル:</label>
                            <input type="text" name="genre" id="genre" value="{{ request('genre') }}">
                        </div>
                        <div class="form-group">
                            <label for="name">店名:</label>
                            <input type="text" name="name" id="name" value="{{ request('name') }}">
                        </div>
                        <button type="submit">検索</button>
                    </form>
                @endif
                <nav>
                    <ul class="header-nav">
                        @auth <!-- ユーザーがログインしている場合 -->
                            <li class="header-nav__item">
                                <a class="header-nav__link" href="/">HOME</a>
                            </li>
                            <li class="header-nav__item">
                                <form class="form" action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button class="header-nav__button">LOGOUT</button>
                                </form>
                            </li>
                            <li class="header-nav__item">
                                <a class="header-nav__link" href="/mypage">MYPAGE</a>
                            </li>
                        @else
                            <!-- ユーザーがログインしていない場合 -->
                            <li class="header-nav__item">
                                <a class="header-nav__link" href="/">HOME</a>
                            </li>
                            <li class="header-nav__item">
                                <a class="header-nav__link" href="{{ route('register') }}">登録</a>
                            </li>
                            <li class="header-nav__item">
                                <a class="header-nav__link" href="{{ route('login') }}">ログイン</a>
                            </li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>
