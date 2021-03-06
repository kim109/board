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
    @stack('styles')
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-dark topnav">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="/images/bi.jpg" width="168" height="46" alt="치카톡">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">로그인</a></li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                로그아웃
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-expand-md subnav">
        <div class="container">
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link @if (Request::is('/')) active @endif"           href="{{ url('/') }}">치카플러스 홈</a>
                    <a class="nav-item nav-link @if (Request::is('qna*')) active @endif"        href="{{ route('qna.index') }}">치카지식인</a>
                    <a class="nav-item nav-link @if (Request::is('columns*')) active @endif"    href="{{ route('columns.index') }}">치카칼럼</a>
                    <a class="nav-item nav-link @if (Request::is('seminars*')) active @endif"   href="{{ route('seminars.index') }}">세미나소식</a>
                    {{--  <a class="nav-item nav-link @if (Request::is('market*')) active @endif"     href="/market">덴티마켓</a>  --}}
                    <a class="nav-item nav-link @if (Request::is('notices*')) active @endif"    href="{{ route('notices.index') }}">공지사항</a>
                  @if (Auth::guest())
                    <a class="nav-item nav-link d-block d-sm-none" href="{{ route('login') }}">로그인</a>
                  @else
                    <a class="nav-item nav-link d-block d-sm-none" href="{{ route('logout') }}"
                      onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        로그아웃
                    </a>
                  @endif
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <div class="footer pt-3">
        <div class="container">
            <div class="row">
                <div class="col-5">
                    <div class="mb-3">
                        <img class="align-bottom" src="/images/denit_ci.png" width="104" height="56" alt="DENIT">
                        <img class="align-bottom" src="/images/jplelab_ci.png" width="152" height="32" alt="JPLELAB">
                    </div>
                    <p class="small mb-3">
                        서울시 강남구 역삼로 542(대치동) 신사S&amp;G빌딩 1층  <span class="ml-4">T. 02-6204-7575</span><br>
                        (주) 대닛 대표 : 주지훈 <span class="ml-4">사업자등록번호 : 598-81-00812</span>
                    </p>
                    <p class="small">Copyright(c) DenIT & JPLELAB. All rights reserved.</p>
                </div>
                <div class="col-3 pt-4">
                    <h4>QUICK LINK</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="https://www.chikatalk.co.kr/privacy/">개인정보처리방침</a></li>
                        <li class="mb-2"><a href="https://www.chikatalk.co.kr/term/">이용약관</a></li>
                        <li class="mb-2"><a href="https://www.chikatalk.co.kr/service/">서비스 전체보기</a></li>
                        <li><a href="https://www.chikatalk.co.kr/support/">원격지원 신청</a></li>
                    </ul>
                </div>
                <div class="col-3 pt-4">
                    <h4>CONTACT US</h4>
                </div>
            </div>
        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>
