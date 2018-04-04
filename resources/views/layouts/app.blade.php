<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/png">
    <title>Key Gen | Your Web Dashboard</title>

    <link href="{{ url(mix('/css/app.css')) }}" rel="stylesheet">
    <!-- page dependent styles -->
    @yield('extrastyles')
    <script>base_url = '{{ url('') }}'</script>
    <script>csrf_token = '{{ csrf_token() }}'</script>
    <script>per_page_show = 25</script>
</head>
<body class="left-navigation">
<div id="wrapper">
    @include('_partials.sidebar')
    <div id="page-wrapper" class="gray-bg">

        <div class="row border-bottom">
            @include('_partials.header')
        </div>
        <div class="main_container">
            @yield('content')
        </div>
        @include('_partials.footer')
    </div>
    <!-- Scripts -->
    <script src="{{ url(mix('/js/app.js')) }}"></script>
    @yield('footer')
    <script>
        var windowsize = $(window).width();
        $(window).resize(function() {
            windowsize = $(window).width();
            if (windowsize < 900) {
                $('body').addClass('mini-navbar');
            } else {
                $('body').removeClass('mini-navbar');
            }
        });
    </script>
</div>
</body>
</html>
