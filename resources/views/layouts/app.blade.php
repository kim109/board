<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
  @stack('stylesheets')

  @stack('scripts')
</head>
<body>
  <div id="app">
    @yield('content')
  </div>
</body>
</html>
