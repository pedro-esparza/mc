<!DOCTYPE html>
<html lang="en">

<head>
  @include('layout.partials.head')
</head>

<body class="{{ $bodyClass }}">
  @include('layout.partials.header')
  <main>
    @yield('aboveContent')
    @include('layout.partials.scripts')
    @yield('belowContent')
    @include('layout.partials.adsense')
  </main>
</body>

</html>