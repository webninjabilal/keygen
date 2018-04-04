<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/png">
    <title>KeyGen | Your Web Dashboard</title>
    <link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
    @yield('extrastyles')

</head>
<body class="gray-bg">
@yield('content')
@yield('footer')
</body>
</html>
