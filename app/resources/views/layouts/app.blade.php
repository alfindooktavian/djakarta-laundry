<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyApp')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

    <!-- Toggle Script -->
    <script>
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebarToggle');
const sidebarLogo = document.getElementById('sidebarLogo');

// Tambahkan class transisi ke sidebar & text
sidebar.classList.add("transition-all", "duration-1000", "ease-in-out");

// Fungsi update logo dengan animasi
function updateLogo(isCollapsed) {
    sidebarLogo.style.transition = 'opacity 0.3s, transform 0.3s';
    sidebarLogo.style.opacity = 0;
    sidebarLogo.style.transform = 'translateX(-10px)';

    setTimeout(() => {
        sidebarLogo.textContent = isCollapsed ? 'DL' : 'Djakarta Laundry';
        sidebarLogo.style.opacity = 1;
        sidebarLogo.style.transform = 'translateX(0)';
    }, 150); // delay supaya fade-out dulu
}

// Inisialisasi logo saat halaman load
updateLogo(sidebar.classList.contains('sidebar-collapsed'));

toggleBtn.addEventListener('click', () => {
    const isCollapsed = sidebar.classList.contains('sidebar-collapsed');

    if (isCollapsed) {
        // Expand
        sidebar.classList.remove('sidebar-collapsed', 'w-16');
        sidebar.classList.add('sidebar-expanded', 'w-64');

        document.querySelectorAll('.sidebar-text').forEach(el => {
            el.classList.remove('hidden');
            el.classList.add('opacity-0', '-translate-x-2', 'transition-all', 'duration-300');
            setTimeout(() => {
                el.classList.remove('opacity-0', '-translate-x-2');
                el.classList.add('opacity-100', 'translate-x-0');
            }, 10);
        });

    } else {
        // Collapse
        sidebar.classList.remove('sidebar-expanded', 'w-64');
        sidebar.classList.add('sidebar-collapsed', 'w-16');

        document.querySelectorAll('.sidebar-text').forEach(el => {
            el.classList.remove('opacity-100', 'translate-x-0');
            el.classList.add('opacity-0', '-translate-x-2');
            setTimeout(() => {
                el.classList.add('hidden');
            }, 250); // delay biar fade-out dulu baru hilang
        });
    }

    // Update logo dengan animasi
    updateLogo(!isCollapsed);
});

function openModal(id) {
    const modal = document.getElementById(id);
    if(modal){
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if(modal){
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>

</body>
</html>
