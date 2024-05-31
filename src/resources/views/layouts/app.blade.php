<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMS</title>
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
                <form class="form-group" action="{{ route('shops.index') }}" method="GET">
                    <label for="sort">並べ替え</label>
                    <select name="sort" id="sort">
                        <option value="default">デフォルト</option>
                        <option value="random">ランダム</option>
                        <option value="rating_desc">評価が高い順</option>
                        <option value="rating_asc">評価が低い順</option>
                    </select>
                </form>
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
            </div>
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
                        @if (auth()->check() && auth()->user()->roles()->where('role_id', 1)->exists())
                            <li class="header-nav__item">
                                <a class="header-nav__link" href="/admin">管理者専用画面へ</a>
                            </li>
                        @endif
                        @if (auth()->check() && auth()->user()->roles()->where('role_id', 2)->exists())
                            <li class="header-nav__item">
                                <a class="header-nav__link" href="/representative">店舗代表者専用画面へ</a>
                            </li>
                        @endif
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
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>

<script>
    document.getElementById('sort').addEventListener('change', function() {
        var selectedValue = this.value;
        window.location.href = "{{ route('shops.index') }}?sort=" + selectedValue;
    });
</script>
