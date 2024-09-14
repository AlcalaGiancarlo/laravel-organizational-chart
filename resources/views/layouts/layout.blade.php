<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - My Laravel App</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav>
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ url('positions') }}">Positions</a></li>
            <li><a href="{{ url('positions/create') }}">Create Position</a></li>
        </ul>
    </nav>
    
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
