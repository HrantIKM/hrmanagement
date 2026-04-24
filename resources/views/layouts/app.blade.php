<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $meta->getTitle() }}</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">

    <!-- Meta Data -->
    <meta name="description" content="{{$meta->getDescription()}}">
    <meta name="keywords" content="{{$meta->getKeywords()}}">

    <meta property="og:title" content="{{$meta->getTitle()}}"/>
    <meta property="og:description" content="{{$meta->getDescription()}}"/>
    <meta property="og:image" content="{{$meta->getOgImage()}}"/>
    <meta property="og:url" content="{{$meta->getOgUrl()}}"/>
    <meta property="og:type" content="{{$meta->getOgType()}}"/>

    @foreach(getSupportedLocales() as $locale)
        <link rel="alternate" hreflang="{{$locale}}" href="{{getCurrentAlternateHref($locale)}}"/>
    @endforeach

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="front-shell">
        <nav class="navbar navbar-expand-md navbar-light front-navbar py-3">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2 fw-semibold" href="{{ url('/') }}">
                    <span class="brand-dot"></span>
                    {{ config('app.name', 'Manage Studio') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-md-center gap-md-2">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item"><a class="nav-link px-3" href="{{ route('careers.index') }}">{{ __('front.nav.careers') }}</a></li>
                            <li class="nav-item"><a class="btn btn-sm btn-front-primary ms-md-2" href="{{ route('login') }}">{{ __('front.nav.login') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                        <li class="nav-item ms-md-3">
                            <div class="locale-switch d-flex gap-1">
                                @foreach(getSupportedLocales() as $locale)
                                    <a href="{{ getCurrentAlternateHref($locale) }}"
                                       class="locale-pill {{ app()->getLocale() === $locale ? 'active' : '' }}">
                                        {{ strtoupper($locale) }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 front-main">
            @yield('content')
        </main>
    </div>
</body>
</html>
