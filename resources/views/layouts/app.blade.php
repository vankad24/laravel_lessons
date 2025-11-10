<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Добавление Favicon из папки public --}}
    <link rel="icon" href="{{ asset('favicon.svg') }}">

    <title>@yield('title', 'Laravel App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 56px; }
        .footer { background-color: #f8f9fa; padding: 10px 0; position: fixed; bottom: 0; width: 100%; }
    </style>
</head>
<body>

    @include('includes.header')

    <div class="container mt-4 mb-5">
        @yield('content')
    </div>

    @include('includes.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
