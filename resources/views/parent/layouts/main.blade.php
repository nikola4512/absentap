<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>{{ $config['title'] }}</title>
    <base href="{{ url('') }}" />
    @include('parent.partials.metadata')
    @include('parent.partials.importhead')
</head>

<body>
    <div class="preloader-wrapper">
        <div class="preloader">
            <img src="images/logomsonly.png" alt="Logo">
        </div>
    </div>
    <main>
        <section class="body-admin">
            @include('parent.layouts.head')
            @include('parent.layouts.menu')
            <div class="body">
                @yield('content')
            </div>
            @include('parent.layouts.foot')
        </section>
    </main>
    @include('parent.partials.importfoot')
</body>

</html>
