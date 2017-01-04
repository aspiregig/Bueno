<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/favicon.png" type="image/x-icon" rel="icon">
    <link rel="canonical" href="{{ request()->url() }}" />
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    @include('partials/css_includes')
    @if(env('SHOW_ANALYTICS') == 'true')
        @include('partials.analytics')
    @endif
</head>

<body>

    @include('partials/header')

    @yield('content')

    @include('partials/footer')

</body>
</html>