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

    <style>
        .title {line-height:40px; padding-left:10px; background-color:#dfdfdf; font-weight:bold; border-bottom:2px solid #ccc;border-top:1px solid #ccc}
        /*.contents {height:250px;}*/

        .thumnail {width:40%; height:20%; border:1px solid #000; float:left; margin:10px;}
        .textlist {clear:both; margin-top:10px;}
    </style>


@push('scripts')
  <script src="{{ mix('js/summary/news.js') }}"></script>
@endpush
</head>
<body>
<div id="summary">
    <div class="section">
        <div class="title">치카톡 뉴스</div>
        <div class="contents">
            <media-list writable="{{ Auth::check() }}"></media-list>            
        </div>
    </div>
    <div class="section">
       <div class="title">치카지식인</div>
       <div class="contents">
            <jisik-list writable="{{ Auth::check() }}"></jisik-list>
       </div>
    </div>    
    <div class="section">
        <div class="title">치카칼럼</div>
        <div class="contents"></div>
    </div>
    <div class="section">
        <div class="title">덴티마켓</div>
        <div class="contents"></div>
    </div>

 <!--    <nav class="navbar navbar-expand-md subnav">
        <div class="container">
           <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link @if (Request::is('/')) active @endif" href="{{ url('/') }}">치카플러스 홈</a>
                    <a class="nav-item nav-link @if (Request::is('insurances*')) active @endif" href="/insurances">치카지식인</a>
                    <a class="nav-item nav-link @if (Request::is('abc*')) active @endif" href="#">치카칼럼</a>
                    <a class="nav-item nav-link @if (Request::is('seminars*')) active @endif" href="/seminars">세미나소식</a>
                    <a class="nav-item nav-link @if (Request::is('market*')) active @endif" href="/market">덴티마켓</a>
                    <a class="nav-item nav-link @if (Request::is('notices*')) active @endif" href="/notices">공지사항</a>
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
    </nav>-->


</div>

@stack('scripts')
</body>
</html>
