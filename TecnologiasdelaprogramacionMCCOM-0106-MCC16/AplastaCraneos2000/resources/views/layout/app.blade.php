<!doctype html>
<html lang="en">
<head>
  @include('layout.partials.app.head')
</head>
@if (isset($bodyClass))
<body class="{{ $bodyClass }}">
@elseif (url()->current() == url('/login'))
<body class="login">
@elseif (url()->current() == url('/register'))
<body class="register">
@elseif (url()->current() == url('/password/reset'))
<body class="login">
@elseif (url()->current() == url('/password/create'))
<body class="login">
@elseif (str_contains(url()->current(), url('/password/reset').'/'))
<body class="login">
@elseif (url()->current() == url('/tournament') || url()->current() == url('/ranking') || url()->current() == url('/history') || url()->current() == url('/rooms') || url()->current() == url('/puzzle') || url()->current() == url('/search'))
<body class="dashboard">
@else
<body>
@endif
  @include('layout.partials.header')
  <main class="py-5">
    @include('layout.partials.app.scripts')
    @yield('content')
  </main>
  @include('layout.partials.footer')
</body>
</html>
