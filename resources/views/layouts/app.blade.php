<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '치카플러스') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/chikaplus.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-dark topnav">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="/images/BI.jpg" width="168" height="46" alt="치카톡">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    @if (Auth::guest())
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">로그인</a></li>
                        <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">회원가입</a></li>
                    @else
                        <li class="nav-item"><a href="#" class="nav-link">정보수정</a></li>
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                로그아웃
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    @endif
                </ul>
            </div>

        </div>
    </nav>
    <nav class="navbar navbar-expand-md subnav">
        <div class="container">
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link active" href="{{ url('/') }}">치카플러스 홈</a>
                    <a class="nav-item nav-link" href="#">보험청구 이모저모</a>
                    <a class="nav-item nav-link" href="#">세미나소식</a>
                    <a class="nav-item nav-link" href="/notices">공지사항</a>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')
</div>

@stack('scripts')
</body>
</html>
