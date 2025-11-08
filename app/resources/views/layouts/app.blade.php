<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Title --}}
    <title>@yield('title') | Djakarta Laundry</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    {{-- Load CSS & JS via Vite --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/check-auth.js',
        'resources/js/components/sidebar.js',
        'resources/js/components/modal.js'
    ])

    {{-- External Dependencies --}}
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-white font-inter min-h-screen flex overflow-x-hidden">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main Content Wrapper --}}
    <div class="flex-1 flex flex-col ml-20 lg:ml-64 transition-all duration-300 ease-in-out" id="mainWrapper">

        {{-- Navbar --}}
        @include('components.navbar')

        {{-- Page Content --}}
        <main id="mainContent" class="flex-1 p-6 transition-all duration-300 ease-in-out bg-gray-50 min-h-screen">
            @yield('content')
        </main>
    </div>

</body>
</html>
