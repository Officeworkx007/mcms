<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — HCLSC Samadhan Admin</title>
    <meta name="robots" content="noindex, nofollow">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-cream min-h-screen font-sans">

    @include('admin.layouts.header')

    <div class="flex">
        @include('admin.layouts.sidebar')

        <main class="flex-1 min-h-[calc(100vh-64px)] p-6">
            @yield('content')
        </main>
    </div>

    @include('admin.layouts.footer')

    @stack('scripts')

</body>

</html>
