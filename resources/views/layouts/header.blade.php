<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container nagoyameshi-container">
        <a class="navbar-brand nagoyameshi-app-name" href="{{ url('/') }}">
            <div class="d-flex align-items-center">
                <img class="nagoyameshi-logo me-1" src ="{{ asset('/images/logo.svg') }}" alt="nagoyameshi">
                NAGOYAMESHI
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @if (Auth::guard('admin')->check())
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            管理者メニュー
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('admin.home') }}">ホーム</a>
                            <a class="dropdown-item" href="{{ route('admin.users.index') }}">会員一覧</a>
                            <a class="dropdown-item" href="{{ route('admin.restaurants.index') }}">店舗一覧</a>
                            <a class="dropdown-item" href="{{ route('admin.categories.index') }}">カテゴリ一覧</a>
                            <a class="dropdown-item" href="{{ route('admin.company.index') }}">会社概要</a>
                            <a class="dropdown-item" href="{{ route('admin.terms.index') }}">利用規約</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('admin.logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                ログアウト
                            </a>

                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @else
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">新規登録</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('user.index') }}">会員情報</a>

                                @if (Auth::user()->subscribed('premium_plan'))
                                    <a class="dropdown-item" href="{{ route('reservations.index') }}">予約一覧</a>
                                    <a class="dropdown-item" href="{{ route('favorites.index') }}">お気に入り一覧</a>
                                    <a class="dropdown-item" href="{{ route('subscription.edit') }}">お支払い方法</a>
                                    <a class="dropdown-item" href="{{ route('subscription.cancel') }}">有料プラン解約</a>
                                @else
                                    <a class="dropdown-item" href="{{ route('subscription.create') }}">有料プラン登録</a>
                                @endif

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    ログアウト
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                @endif
            </ul>
        </div>
    </div>
</nav>