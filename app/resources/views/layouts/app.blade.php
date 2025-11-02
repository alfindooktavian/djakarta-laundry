<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyApp')</title>
    @vite('resources/js/check-auth.js')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-white flex min-h-screen overflow-x-hidden font-inter">

    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col transition-all duration-300 ease-in-out" id="mainWrapper">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Page Content -->
        <div id="mainContent" class="flex-1 p-6 transition-all duration-300 ease-in-out">
            @yield('content')
        </div>
    </div>

    <!-- Sidebar Script -->
    <script type="module">
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebarLogo = document.getElementById('sidebarLogo');
        const userProfile = document.getElementById('userProfile');
        const logoutBtn = document.getElementById('logoutBtn');
        const sidebarFooter = document.getElementById('sidebarFooter');

        // ✅ Cek kondisi awal dari localStorage
        let sidebarState = localStorage.getItem('sidebarState') || 'expanded';
        if (sidebarState === 'collapsed') {
            sidebar.classList.add('sidebar-collapsed', 'w-16');
        } else {
            sidebar.classList.add('sidebar-expanded', 'w-64');
        }

        // ✅ Sinkronisasi elemen sidebar saat awal halaman dimuat
        if (sidebarState === 'collapsed') {
            document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
            userProfile?.classList.add('hidden');
            logoutBtn?.classList.add('mx-auto');
            sidebarFooter?.classList.remove('justify-between');
            sidebarFooter?.classList.add('justify-start');
        } else {
            document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('hidden'));
            userProfile?.classList.remove('hidden');
            logoutBtn?.classList.remove('mx-auto');
            sidebarFooter?.classList.add('justify-between');
            sidebarFooter?.classList.remove('justify-start');
        }

        // Tambahkan animasi transisi
        sidebar.classList.add("transition-all", "duration-1000", "ease-in-out");

        // ✅ Fungsi update logo
        function updateLogo(isCollapsed) {
            sidebarLogo.style.transition = 'opacity 0.3s, transform 0.3s';
            sidebarLogo.style.opacity = 0;
            sidebarLogo.style.transform = 'translateX(-10px)';
            setTimeout(() => {
                sidebarLogo.textContent = isCollapsed ? 'DL' : 'Djakarta Laundry';
                sidebarLogo.style.opacity = 1;
                sidebarLogo.style.transform = 'translateX(0)';
            }, 150);
        }
        updateLogo(sidebarState === 'collapsed');

        // ✅ Event: Toggle Sidebar
        toggleBtn.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.contains('sidebar-collapsed');

            if (isCollapsed) {
                // Expand
                sidebar.classList.remove('sidebar-collapsed', 'w-16');
                sidebar.classList.add('sidebar-expanded', 'w-64');

                document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('hidden'));
                userProfile?.classList.remove('hidden');

                logoutBtn?.classList.remove('mx-auto');
                sidebarFooter?.classList.add('justify-between');
                sidebarFooter?.classList.remove('justify-start');

                localStorage.setItem('sidebarState', 'expanded');
            } else {
                // Collapse
                sidebar.classList.remove('sidebar-expanded', 'w-64');
                sidebar.classList.add('sidebar-collapsed', 'w-16');

                document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
                userProfile?.classList.add('hidden');

                logoutBtn?.classList.add('mx-auto');
                sidebarFooter?.classList.remove('justify-between');
                sidebarFooter?.classList.add('justify-start');

                localStorage.setItem('sidebarState', 'collapsed');
            }

            updateLogo(!isCollapsed);
        });

        // ✅ Fungsi Modal
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
        window.openModal = openModal;
window.closeModal = closeModal;
    </script>

</body>
</html>
