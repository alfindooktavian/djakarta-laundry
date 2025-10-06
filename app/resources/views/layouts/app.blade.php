<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyApp')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-white flex min-h-screen overflow-x-hidden font-inter">

    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col transition-all duration-300" id="mainWrapper">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Page Content -->
        <div id="mainContent" class="flex-1 p-6 transition-all duration-300">
            @yield('content')
        </div>
    </div>

    <!-- Toggle Script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.contains('sidebar-collapsed');

            if(isCollapsed){
                // Expand
                sidebar.classList.remove('sidebar-collapsed', 'w-16');
                sidebar.classList.add('sidebar-expanded', 'w-64');
                document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('hidden'));
            } else {
                // Collapse
                sidebar.classList.remove('sidebar-expanded', 'w-64');
                sidebar.classList.add('sidebar-collapsed', 'w-16');
                document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
            }
        });
    </script>
</body>
</html>
