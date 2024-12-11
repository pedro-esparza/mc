@php
    $siteTitle = '';
    if (isset($headTitle)) {
        $siteTitle = $headTitle;
    } elseif (url()->current() == url('/login')) {
        $siteTitle = 'Login';
    } elseif (url()->current() == url('/register')) {
        $siteTitle = 'Register';
    } elseif (url()->current() == url('/password/reset')) {
        $siteTitle = 'Forgot password';
    } elseif (url()->current() == url('/password/create')) {
        $siteTitle = 'Create password';
    } elseif (url()->current() == url('/search')) {
        if (isset($_GET['query']) && $_GET['query'] != '') {
            $siteTitle = 'Search results for "'.$_GET['query'].'"';
        } else {
            $siteTitle = 'Search for players';
        }
    } elseif (str_contains(url()->current(), url('/password/reset').'/')) {
        $siteTitle = 'Password reset';
    } else {
        $siteTitle = 'Tournament';
    }
@endphp
<title>{{ $siteTitle }} - Aplasta Cráneos 2000</title>
<meta property="og:title" content="{{ $siteTitle }} - Aplasta Cráneos 2000" />
@include('layout.partials.common.head')