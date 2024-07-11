<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>{{ $config['title'] }}</title>
    <base href="{{ url('') }}" />
    @include('admin.partials.metadata')
    @include('admin.partials.importhead')
</head>

<body>
    <div class="preloader-wrapper">
        <div class="preloader">
            <img src="images/logomsonly.png" alt="Logo">
        </div>
    </div>
    <main>
        <section class="body-admin">
            @include('admin.layouts.head')
            @include('admin.layouts.menu')
            <div class="body">
                @yield('content')
            </div>
            @include('admin.layouts.foot')
        </section>
    </main>
    @include('admin.partials.importfoot')
</body>

</html>
