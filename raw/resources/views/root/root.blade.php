@section('head-title')
    <title>Title</title>
@endsection

@section('head-description')
    <meta name="description" content="Root">
@endsection

@section('body-property')
    <body>
    @endsection

    <!doctype html>
    <html class="no-js" lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @yield('head-meta')
        @yield('head-title')
        @yield('head-description')
        @yield('head-property')
        @yield('head-css-pre')
        @yield('head-css-post')
        @yield('head-js-pre')
        @yield('head-js-post')
    </head>
    @yield('body-property')
    @yield('body-css-upper-pre')
    @yield('body-css-upper-post')
    @yield('body-js-upper-pre')
    @yield('body-js-upper-post')
    @yield('body-upgrade-browser')
    @yield('body-content')
    @yield('body-css-lower-pre')
    @yield('body-css-lower-post')
    @yield('body-js-lower-pre')
    @yield('body-js-lower-post')
    </body>
    </html>
